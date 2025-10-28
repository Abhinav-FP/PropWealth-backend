<?php

namespace App\Http\Controllers;

use App\Mail\ReportReady;
use App\Jobs\SendReportEmailJob;
use App\Jobs\GeneratePdfReportJob;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Report;
use App\Models\UserDownloadLimit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class PdfReportController extends Controller
{

    public function index()
    {
        $roldId = Auth::user()->role_id;

        if ($roldId !== 1) {
            $reports = Report::with('user')->where('user_id', Auth::id())->latest()->paginate(20);
        } else {
            $reports = Report::with('user')->latest()->paginate(20);
        }

        return view('backend.user.reports.index', compact('reports'));
    }

    public function allReports(Request $request)
    {
        // Admin-only method to view all reports with filtering and sorting
        $reports = $this->buildReportQuery($request)->paginate(20);
        return view('backend.user.reports.all-reports', compact('reports'));
    }

    /**
     * Build a filtered and sorted query for reports
     * This method is reused by both listing and export functions
     */
    private function buildReportQuery(Request $request)
    {
        $query = Report::with('user');
        
        // Define sortable columns whitelist for security
        $sortableColumns = ['id', 'created_at', 'suburb_name', 'user.first_name', 'user.last_name', 'user.email', 'user.mobile_number'];
        
        // Apply search filter (name, email, mobile, suburb)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('first_name', 'LIKE', "%{$search}%")
                              ->orWhere('last_name', 'LIKE', "%{$search}%")
                              ->orWhere('email', 'LIKE', "%{$search}%")
                              ->orWhere('mobile_number', 'LIKE', "%{$search}%");
                })
                ->orWhere('suburb_name', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply date range filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }
        
        
        // Apply suburb/location filter
        if ($request->filled('location')) {
            $query->where('suburb_name', 'LIKE', "%{$request->input('location')}%");
        }
        
        // Apply email filter specifically
        if ($request->filled('email')) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('email', 'LIKE', "%{$request->input('email')}%");
            });
        }
        
        // Apply mobile filter specifically  
        if ($request->filled('mobile')) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->where('mobile_number', 'LIKE', "%{$request->input('mobile')}%");
            });
        }
        
        // Apply sorting with whitelist validation
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        
        // Validate sort direction
        if (!in_array(strtolower($sortDir), ['asc', 'desc'])) {
            $sortDir = 'desc';
        }
        
        // Apply sorting based on column type
        if (in_array($sortBy, $sortableColumns)) {
            if (strpos($sortBy, 'user.') === 0) {
                // Sort by user relationship fields
                $userField = str_replace('user.', '', $sortBy);
                $query->join('users', 'reports.user_id', '=', 'users.id')
                      ->select('reports.*')
                      ->orderBy("users.{$userField}", $sortDir);
            } else {
                // Sort by report fields
                $query->orderBy($sortBy, $sortDir);
            }
        } else {
            // Default sorting if invalid sort column
            $query->latest('created_at');
        }
        
        return $query;
    }


    public function userReport($userId)
    {
        $user = User::findOrFail($userId);
        $reports = Report::where('user_id', $userId)->latest()->paginate(20);

        return view('backend.user.reports.index', compact('user', 'reports'));
    }


    public function generate(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Fetch the data you need for the report (e.g., charts, maps, user info)
        $reportData = [
            'user' => $user,
            'charts' => [/* your chart data */],
            'maps' => [/* your map data */],
        ];

        // Generate the PDF from a Blade view
        $pdf = Pdf::loadView('reports.user_report', $reportData);

        // Download the PDF file
        return $pdf->download('report-for-' . $user->first_name . '.pdf');
    }

    public function generatePdfFromData(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
            ]);

            // Debug: log incoming request keys relevant to email dispatch
            \Log::info('generatePdfFromData received', [
                'has_recipient_email' => $request->has('recipient_email'),
                'recipient_email' => $request->input('recipient_email'),
                'recipient_name' => $request->input('recipient_name'),
                'suburb' => $request->input('name'),
                'user_id' => $request->input('user_id'),
            ]);
            
            // Get authenticated user ID from request
            $userId = $request->input('user_id');
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User authentication required. Please verify your OTP first.',
                ], 401);
            }

            // No authentication required - removed user dependency and download limits

            // --- Handle Mapbox base64 charts ---
            $charts = [
                'houseInventoryChart' => $request->houseInventoryChart,
                'houseListingsChart' => $request->houseListingsChart,
                'housePriceChart' => $request->housePriceChart,
                'unitInventoryChart' => $request->unitInventoryChart,
                'unitListingsChart' => $request->unitListingsChart,
                'unitPriceChart' => $request->unitPriceChart,
                'houseRentsChart' => $request->houseRentsChart,
                'unitRentsChart' => $request->unitRentsChart,
                'vacancyRatesChart' => $request->vacancyRatesChart,
                'housePriceSegments' => $request->housePriceSegments,
                'elevation' => $request->elevation,
                'seifa' => $request->seifa,
            ];

            // Add performance settings - increased to prevent timeout
            ini_set('max_execution_time', 600); // Increased from 300 to 600 seconds
            ini_set('memory_limit', '1024M'); // Increased from 512M to 1024M
            set_time_limit(600); // Additional safeguard

            $tempFiles = [];
            $tempChartPaths = [];

            $publicChartsPath = public_path('charts');

            // Ensure charts directory exists
            if (!file_exists($publicChartsPath)) {
                mkdir($publicChartsPath, 0755, true);
            }

            foreach ($charts as $key => $base64) {
                if ($base64) {
                    try {
                        $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
                        $imageData = base64_decode($base64);

                        if ($imageData === false || strlen($imageData) < 100) {
                            \Log::warning("Invalid image data for: " . $key);
                            continue;
                        }

                        // Skip very large base64 strings (2MB+ raw data)
                        if (strlen($imageData) > 2 * 1024 * 1024) {
                            \Log::warning("Skipping large image: " . $key);
                            continue;
                        }

                        // Resize large images before saving (max width = 1200px)
                        $image = imagecreatefromstring($imageData);
                        if ($image !== false) {
                            $maxWidth = 1200;
                            $width = imagesx($image);
                            $height = imagesy($image);

                            if ($width > $maxWidth) {
                                $newHeight = intval($height * ($maxWidth / $width));
                                $resized = imagecreatetruecolor($maxWidth, $newHeight);

                                // White background (fixes black background issue)
                                $white = imagecolorallocate($resized, 255, 255, 255);
                                imagefill($resized, 0, 0, $white);

                                imagecopyresampled($resized, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
                                imagedestroy($image);
                                $image = $resized;
                            }

                            ob_start();
                            imagepng($image, null, 6); // quality 0-9 (6 = good balance)
                            $imageData = ob_get_clean();
                            imagedestroy($image);
                        }

                        $filename = $key . '-' . time() . '-' . rand(1000, 9999) . '.png';
                        $filePath = $publicChartsPath . '/' . $filename;

                        if (file_put_contents($filePath, $imageData) !== false) {
                            // Convert to base64 data URI for direct embedding in PDF
                            $base64Data = base64_encode($imageData);
                            $tempChartPaths[$key] = 'data:image/png;base64,' . $base64Data;

                            // Track for cleanup AFTER PDF generation
                            $tempFiles[] = $filePath;
                        }
                    } catch (\Exception $e) {
                        \Log::error("Error processing chart {$key}: " . $e->getMessage());
                    }
                }
            }

            // --- Prepare report data ---
            $reportData = [
                'user' => null, // No user required
                'charts' => $tempChartPaths,
                'note' => $request->note,
                'date' => date('j-F-Y'),
                'year' => date('Y'),
                'suburb' => $request->name,
                'houseText' => $request->houseText,
                'unitText' => $request->unitText,
            ];
            
            // Check if we should generate PDF asynchronously
            $recipientEmail = trim($request->input('recipient_email', ''));
            $recipientName = trim($request->input('recipient_name', ''));
            
            if (!empty($recipientEmail) && filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                // Dispatch async job to generate PDF and email it
                \Log::info('PdfReportController: Dispatching async PDF generation job', [
                    'email' => $recipientEmail,
                    'name' => $recipientName,
                    'suburb' => $request->name,
                    'user_id' => $userId,
                    'queue_connection' => config('queue.default'),
                    'temp_files_count' => count($tempFiles),
                    'charts_count' => count($tempChartPaths),
                    'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
                    'php_version' => PHP_VERSION,
                    'timestamp' => now()->toISOString()
                ]);
                
                try {
                    GeneratePdfReportJob::dispatch($reportData, $recipientEmail, $recipientName, $request->name, $userId, $tempFiles);
                    
                    \Log::info('PdfReportController: GeneratePdfReportJob dispatched successfully', [
                        'email' => $recipientEmail,
                        'suburb' => $request->name,
                        'dispatch_time' => now()->toISOString()
                    ]);
                } catch (\Throwable $e) {
                    \Log::error('PdfReportController: Failed to dispatch GeneratePdfReportJob', [
                        'email' => $recipientEmail,
                        'suburb' => $request->name,
                        'error' => $e->getMessage(),
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
                
                // NOTE: Temp files cleanup will happen AFTER PDF generation in the job
                
                // Return immediately
                return response()->json([
                    'success' => true,
                    'message' => 'Your report is being generated and will be emailed to you shortly!',
                    'async' => true
                ]);
            }

            // --- Generate PDF with Enhanced Configuration ---
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('defaultFont', 'Arial');
            $options->set('dpi', 72);
            $options->set('fontHeightRatio', 1.1);
            $options->set('chroot', [public_path(), storage_path('app/public')]);
            $options->set('logOutputFile', storage_path('logs/dompdf.log'));
            $options->set('debugKeepTemp', config('app.debug'));
            $options->set('debugPng', false);
            $options->set('debugLayout', false);
            $options->set('debugLayoutLines', false);
            $options->set('debugLayoutBlocks', false);
            $options->set('debugLayoutInline', false);
            $options->set('debugLayoutPaddingBox', false);

            $pdf = new \Dompdf\Dompdf($options);
            
            try {
                $html = view('reports.user_report', $reportData)->render();
                \Log::info('Generated PDF HTML length: ' . strlen($html));
            } catch (\Exception $e) {
                \Log::error('Error rendering PDF view: ' . $e->getMessage());
                throw $e;
            }
            
            \Log::info('TRACE 1: About to load HTML into PDF');
            $pdf->loadHtml($html);
            \Log::info('TRACE 2: HTML loaded, about to set paper');
            $pdf->setPaper('A4', 'portrait');
            // --- Check email recipient BEFORE rendering to avoid timeout issues ---
            $recipientEmail = trim($request->input('recipient_email', ''));
            $recipientName = trim($request->input('recipient_name', ''));
            
            \Log::info('CHECKING EMAIL BEFORE PDF RENDER', [
                'has_email' => !empty($recipientEmail),
                'email' => $recipientEmail,
            ]);

            \Log::info('TRACE 3: Paper set, about to render - THIS MAY TAKE TIME');
            $startTime = microtime(true);
            $pdf->render();
            $renderTime = round(microtime(true) - $startTime, 2);
            \Log::info('TRACE 4: PDF rendered successfully', ['render_time_seconds' => $renderTime]);

            // --- Save PDF ---
            $filename = 'report-' . Str::uuid() . '.pdf';
            $storagePath = 'reports/' . $filename;
            \Log::info('TRACE 5: About to save PDF to storage', ['path' => $storagePath]);
            Storage::disk('public')->put($storagePath, $pdf->output());
            $fileUrl = Storage::url($storagePath);
            \Log::info('TRACE 6: PDF saved, about to store in database');

            // --- Store report in database ---
            $report = new Report();
            $report->user_id = null; // No user required
            $report->suburb_id = 111000;
            $report->file_name = $filename;
            $report->file_path = $fileUrl;
            $report->save();
            \Log::info('TRACE 7: Report saved to database, now dispatching email');

            \Log::info('Checking recipient_email for report dispatch', [
                'has_recipient_email' => !empty($recipientEmail),
                'recipient_email' => $recipientEmail,
                'recipient_name' => $recipientName,
            ]);

            if ($recipientEmail !== '' && $recipientEmail !== null) {
                // Validate email format before attempting to send
                if (filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                    try {
                        $fileLocalPath = storage_path('app/public/' . $storagePath);
                        
                        \Log::info('PdfReportController: About to dispatch SendReportEmailJob (sync path)', [
                            'email' => $recipientEmail,
                            'name' => $recipientName,
                            'suburb' => $request->name,
                            'file_path' => $fileLocalPath,
                            'filename' => $filename,
                            'file_exists' => file_exists($fileLocalPath),
                            'file_size_bytes' => @filesize($fileLocalPath) ?: null,
                            'queue_connection' => config('queue.default'),
                            'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
                            'timestamp' => now()->toISOString()
                        ]);
                        
                        // Queue email dispatch to avoid blocking the request (corrected parameter order)
                        SendReportEmailJob::dispatch(
                            $recipientEmail,
                            $recipientName,
                            $request->name,
                            $fileLocalPath,
                            $filename
                        );

                        \Log::info('PdfReportController: SendReportEmailJob dispatched successfully (sync path)', [
                            'email' => $recipientEmail,
                            'suburb' => $request->name,
                            'dispatch_time' => now()->toISOString()
                        ]);
                    } catch (\Throwable $e) {
                        \Log::error('Failed to dispatch report email job', [
                            'email' => $recipientEmail,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);
                    }
                } else {
                    \Log::warning('Invalid recipient_email format; skipping report email dispatch', [
                        'recipient_email' => $recipientEmail,
                    ]);
                }
            } else {
                \Log::warning('No recipient_email provided; skipping report email dispatch', [
                    'request_data' => $request->only(['recipient_email', 'recipient_name']),
                ]);
            }


            // --- Clean up temporary chart images ---
            foreach ($tempFiles as $filePath) {
                try {
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                } catch (\Exception $e) {
                    \Log::warning("Failed to delete temp file: " . $filePath);
                }
            }


            // --- Success Response ---
            return response()->json([
                'success' => true,
                'message' => 'Your report has been generated successfully and is ready for download!',
                'download_url' => url($fileUrl),
                'filename' => $filename
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please check your input data and try again.',
                'errors' => $e->errors(),
                'error_type' => 'validation_failed'
            ], 422);
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later or contact support.',
                'error_type' => 'unexpected_error',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }



    public function download(Report $report)
    {

        $relativePath = Str::after($report->file_path, '/storage/');

        // Check if the file exists on the 'public' disk
        if (!Storage::disk('public')->exists($relativePath)) {
            return redirect()->route('report.index')->with('error', 'The file you requested does not exist.');
        }

        // Download the file from the 'public' disk with the original file name
        return Storage::disk('public')->download($relativePath, $report->file_name);
    }


    public function destroy(Report $report)
    {
        // Extract the path from the URL
        $path = str_replace(url('storage') . '/', '', $report->file_path);

        // Delete the file from the storage disk
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // Delete the report record from the database
        $report->delete();

        return redirect()->route('report.index')->with('success', 'Report deleted successfully.');
    }

    public function export($userId)
    {
        // Export reports for a specific user
        $user = User::findOrFail($userId);
        $reports = Report::where('user_id', $userId)->latest()->get();
        
        // You can implement Excel export logic here
        // For now, return a simple response
        return response()->json([
            'message' => 'Export functionality for user reports',
            'user' => $user->name,
            'reports_count' => $reports->count()
        ]);
    }

    public function exportFiltered(Request $request)
    {
        try {
            // Use the same query builder as the listing page
            $reports = $this->buildReportQuery($request)->get();
            
            if ($reports->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No reports found with the applied filters.'
                ], 404);
            }
            
            // Transform data for Excel export
            $exportData = $reports->map(function ($report) {
                return [
                    'ID' => $report->id,
                    'First Name' => $report->user ? $report->user->first_name : 'N/A',
                    'Last Name' => $report->user ? $report->user->last_name : 'N/A', 
                    'Email' => $report->user ? $report->user->email : 'N/A',
                    'Mobile' => $report->user ? $report->user->mobile_number : 'N/A',
                    'Location' => $report->suburb_name ?? 'N/A',
                    'Downloaded At' => $report->created_at->format('Y-m-d H:i:s'),
                    'File Name' => $report->file_name ?? 'N/A'
                ];
            });
            
            // Create CSV content
            $csvContent = $this->arrayToCsv($exportData->toArray());
            
            // Generate filename with timestamp
            $filename = 'reports_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            // Return CSV download response
            return response($csvContent)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
                
        } catch (\Exception $e) {
            \Log::error('Export Error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while exporting data. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Convert array data to CSV format
     */
    private function arrayToCsv(array $data)
    {
        if (empty($data)) {
            return '';
        }
        
        $output = fopen('php://temp', 'r+');
        
        // Add headers
        fputcsv($output, array_keys($data[0]));
        
        // Add data rows
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);
        
        return $csvContent;
    }
}
