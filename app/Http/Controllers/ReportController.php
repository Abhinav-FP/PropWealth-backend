<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:11|unique:reports,mobile_number,NULL,id,mobile_verified_at,NULL',
            'email' => 'required|string|email|max:255|unique:reports,email,NULL,id,email_verified_at,NULL',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }


        try {
            $otp = random_int(100000, 999999);
            $otpExpiresAt = now()->addMinutes(10);
            $plainTextPassword = Str::random(10);


            // Check if a verified user with the same mobile or email already exists
            $existingReport = Report::where(function ($query) use ($request) {
                $query->where('email', $request->email)->whereNotNull('email_verified_at');
            })->orWhere(function ($query) use ($request) {
                $query->where('mobile_number', $request->mobile_number)->whereNotNull('mobile_verified_at');
            })->first();

            if ($existingReport) {
                return response()->json([
                    'message' => 'User already verified.',
                    'errors' => ['email' => ['A verified user already exists with this email or mobile number.']]
                ], 422);
            }

            $report = Report::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'password' => Hash::make($plainTextPassword), // Hash the password
                'otp' => $otp,
                'otp_expires_at' => $otpExpiresAt,
            ]);

            Log::info('Report Submission with OTP:', ['mobile_number' => $request->mobile_number, 'otp' => $otp]);

            return response()->json([
                'message' => 'Report submitted successfully!',
                'data' => $report
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error saving report:', ['error' => $e->getMessage(), 'data' => $request->all()]);

            return response()->json([
                'message' => 'Failed to submit report.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $report = Report::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>', now())
            ->whereNull('mobile_verified_at')
            ->first();

        if ($report) {
            $report->otp = null;
            $report->otp_expires_at = null;
            $report->mobile_verified_at = now();
            $report->save();

            return response()->json(['success' => true, 'message' => 'OTP verified successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 400);
    }
}
