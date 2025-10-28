<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;


class UserController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function index()
    {
        $users = User::latest()->where('role_id', 2)->paginate(20);
        return view('backend.user.index', compact('users'));
    }


    public function export(Request $request)
    {
        // Use the same filtering logic as the index method
        $users = User::query();

        if ($request->filled('email')) {
            $users->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->filled('mobile')) {
            $users->where('mobile_number', 'like', '%' . $request->input('mobile') . '%');
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $users->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date') . ' 23:59:59']);
        }

        $users = $users->get();

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the column headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Mobile');
        $sheet->setCellValue('E1', 'Registered At');

        // Apply bold to header row
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        // Set column widths to auto-size
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Populate the spreadsheet with user data
        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->id);
            $sheet->setCellValue('B' . $row, $user->first_name . ' ' . $user->last_name);
            $sheet->setCellValue('C' . $row, $user->email);
            $sheet->setCellValue('D' . $row, $user->mobile_number);
            $sheet->setCellValue('E' . $row, $user->created_at);
            $row++;
        }

        // Add a filter to the header row
        $sheet->setAutoFilter('A1:E' . ($row - 1));

        // Create an Xlsx writer and save the file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'users-export-' . now()->format('Y-m-d') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }



    public function store(Request $request)
    {

        // dd($request->all());
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'mobile_number' => [
                'required',
                'string',
                'regex:/^\+\d{1,4}\d{6,15}$/', // E.164 format: + followed by country code and number
            ],
            'email' => 'required|string|email|max:255',
        ]);
     
        // Custom validation messages
        $validator->setCustomMessages([
            'mobile_number.regex' => 'Mobile number must be in international format (e.g., +919876543210 for India, +15551234567 for US).',
            'mobile_number.required' => 'Mobile number is required.',
        ]);
        
        // Additional validation for Indian numbers (must have exactly 10 digits after +91)
        if ($validator->passes()) {
            $mobileNumber = $request->mobile_number;
            if (str_starts_with($mobileNumber, '+91')) {
                // Extract digits after +91
                $digitsAfter91 = substr($mobileNumber, 3);
                // Must be exactly 10 digits and start with 6-9
                if (strlen($digitsAfter91) !== 10 || !preg_match('/^[6-9]\d{9}$/', $digitsAfter91)) {
                    $validator->errors()->add('mobile_number', 'Indian mobile numbers must have exactly 10 digits after +91 and start with 6, 7, 8, or 9.');
                }
            }
        }
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please check your input and try again.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $plainTextPassword = Str::random(10);
            $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

            $userId = $request->user;
            $user = User::where('id', $userId)->first();

            if(isset($user)){
                $user->mobile_number = $request->mobile_number;
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->password = Hash::make($plainTextPassword);
                $user->otp = $otp;
                $user->otp_expires_at = now()->addMinutes(10);
                $user->mobile_verified_at = null; // Reset verification status
                $user->email_verified_at = null; // Reset email verification status
                $user->role_id = 2;
                $user->save();
            }else {
                $user = User::updateOrCreate(
                    ['mobile_number' => $request->mobile_number],
                    [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($plainTextPassword), // Hash the password
                    'otp' => $otp,
                    'otp_expires_at' => now()->addMinutes(10),
                    'mobile_verified_at' => null, // Will be set after OTP verification
                    'email_verified_at' => null, // Will be set after OTP verification
                    'role_id' => 2,
                    ]
                );
            }

            // Send OTP via SMS
            $twilioService = app(TwilioService::class);
            $smsSent = $twilioService->sendOtp($user->mobile_number, $otp);

            // Also attempt to send OTP via email as a fallback
            $emailSent = false;
            try {
                if (!empty($user->email)) {
                    Mail::raw("Your verification code is: {$otp}. This code will expire in 10 minutes.", function ($message) use ($user) {
                        $message->to($user->email)
                                ->subject('Your PropWealth verification code');
                    });
                    $emailSent = true;
                    Log::info('OTP email queued/sent', ['email' => $user->email]);
                }
            } catch (\Exception $mailEx) {
                Log::error('Failed to send OTP email', ['email' => $user->email, 'error' => $mailEx->getMessage()]);
            }

            if (!$smsSent && !$emailSent) {
                Log::error('Failed to send OTP via both SMS and email', ['mobile_number' => $user->mobile_number, 'email' => $user->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send verification code. Please try again.',
                ], 500);
            }

            Log::info('User registered and OTP sent successfully:', [
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'otp' => $otp // Remove this in production
            ]);

            return response()->json([
                'success' => true,
                'message' => $smsSent && $emailSent
                    ? 'Verification code sent to your mobile number and email!'
                    : ($smsSent ? 'Verification code sent to your mobile number!' : 'Verification code sent to your email!'),
                'user_id' => $user->id,
                'mobile_number' => $user->mobile_number,
                'email' => $user->email,
                'requires_verification' => true
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error saving report:', ['error' => $e->getMessage(), 'data' => $request->all()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit report.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function verifyOtp(Request $request)
    {
        Log::info('OTP verification request:', $request->all());
        
        try {
            // Use validator to avoid ValidationException and provide consistent response
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'otp' => 'required|string|size:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid input data.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::where('id', $request->user_id)
                ->where('otp', $request->otp)
                ->where('otp_expires_at', '>', now())
                ->first();

            Log::info('OTP verification user found:', ['user_id' => $user?->id ?? 'not found']);

            if ($user) {
                $user->otp = null;
                $user->otp_expires_at = null;
                $user->mobile_verified_at = now();
                $user->save();

                // Generate authentication token for the user
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'success' => true, 
                    'message' => 'OTP verified successfully!',
                    'user' => $user,
                    'token' => $token
                ], 200);
            }

            return response()->json([
                'success' => false, 
                'message' => 'Invalid or expired OTP. Please try again.'
            ], 400);
            
        } catch (\Exception $e) {
            Log::error('OTP verification error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during verification. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function resendOtp(Request $request)
    {
        try {
            // Use validator to avoid ValidationException and provide consistent response
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid input data.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::where('id', $request->user_id)->first();

            if (!$user) {
                return response()->json([
                    'success' => false, 
                    'message' => 'User not found.'
                ], 404);
            }

            // Generate a new OTP and update the user's record
            $newOtp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $otpExpiresAt = now()->addMinutes(10);

            $user->otp = $newOtp;
            $user->otp_expires_at = $otpExpiresAt;
            $user->save();

            // Send the new OTP via SMS
            $smsResult = $this->twilioService->sendOtp($user->mobile_number, $newOtp);

            // Also attempt to send OTP via email as a fallback
            $emailResult = false;
            try {
                if (!empty($user->email)) {
                    Mail::raw("Your new verification code is: {$newOtp}. This code will expire in 10 minutes.", function ($message) use ($user) {
                        $message->to($user->email)
                                ->subject('Your PropWealth verification code');
                    });
                    $emailResult = true;
                    Log::info('Resend OTP email queued/sent', ['email' => $user->email]);
                }
            } catch (\Exception $mailEx) {
                Log::error('Resend OTP email failed', ['email' => $user->email, 'error' => $mailEx->getMessage()]);
            }

            Log::info('New OTP sent to user:', [
                'email' => $user->email,
                'mobile_number' => $user->mobile_number,
                'otp' => $newOtp, // Remove this in production
                'sms_sent' => $smsResult
            ]);

            if ($smsResult || $emailResult) {
                return response()->json([
                    'success' => true, 
                    'message' => $smsResult && $emailResult
                        ? 'New OTP sent to your mobile number and email successfully.'
                        : ($smsResult ? 'New OTP sent to your mobile number successfully.' : 'New OTP sent to your email successfully.')
                ], 200);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => 'Failed to send OTP. Please try again.'
                ], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('Resend OTP error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while resending OTP. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid email or password.',
            ], 401);
        }
    }

    public function edit(User $user)
    {
        return view('backend.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ];

        $request->validate($rules);

        try {
            $user->name = $request->input('name');
            $user->email = $request->input('email');

            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            $user->save();

            return redirect()->route('user.edit', $user->id)->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the user: ' . $e->getMessage())->withInput();
        }
    }
}
