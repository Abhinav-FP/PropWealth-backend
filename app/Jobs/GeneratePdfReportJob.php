<?php

namespace App\Jobs;

use App\Mail\ReportReady;
use App\Jobs\SendReportEmailJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Report;

class GeneratePdfReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 600; // 10 minutes
    public $backoff = 60; // Wait 60 seconds between retries

    protected $reportData;
    protected $reportDataPath;
    protected $recipientEmail;
    protected $recipientName;
    protected $suburbName;
    protected $userId;
    protected $tempFiles;

    public function __construct(?array $reportData, string $recipientEmail, string $recipientName, string $suburbName, ?int $userId = null, ?string $reportDataPath = null, array $tempFiles = [])
    {
        $this->reportData = $reportData;
        $this->recipientEmail = $recipientEmail;
        $this->recipientName = $recipientName;
        $this->suburbName = $suburbName;
        $this->userId = $userId;
        $this->reportDataPath = $reportDataPath;
        $this->tempFiles = $tempFiles;
    }

    public function handle(): void
    {
        $startTime = microtime(true);
        
        try {
            Log::info('GeneratePdfReportJob started', [
                'email' => $this->recipientEmail,
                'suburb' => $this->suburbName,
                'user_id' => $this->userId,
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'php_version' => PHP_VERSION,
                'temp_files_count' => count($this->tempFiles ?? [])
            ]);

            // Generate PDF with increased limits
            Log::info('Setting PHP limits for PDF generation');
            ini_set('max_execution_time', 300);
            ini_set('memory_limit', '512M');
            set_time_limit(300);
            
            Log::info('PHP limits set', [
                'new_memory_limit' => ini_get('memory_limit'),
                'new_max_execution_time' => ini_get('max_execution_time')
            ]);

            Log::info('Creating Dompdf options');
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
            
            Log::info('Dompdf options configured', [
                'dpi' => 72,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial',
                'chroot_paths' => [public_path(), storage_path('app/public')],
                'log_output_file' => storage_path('logs/dompdf.log')
            ]);

            Log::info('Creating Dompdf instance');
            $pdf = new \Dompdf\Dompdf($options);
            
            // Check background image files before rendering
            $backgroundImages = [
                'page1' => public_path('Image/page1_optimized.jpg'),
                'page2' => public_path('Image/page2_optimized.jpg'),
                'page6' => public_path('Image/page6_optimized.jpg')
            ];
            
            Log::info('Checking background image files', [
                'public_path' => public_path(),
                'image_directory' => public_path('Image')
            ]);
            
            foreach ($backgroundImages as $name => $path) {
                $exists = file_exists($path);
                $readable = $exists ? is_readable($path) : false;
                $size = $exists ? filesize($path) : 0;
                
                Log::info("Background image check: {$name}", [
                    'path' => $path,
                    'exists' => $exists,
                    'readable' => $readable,
                    'size_bytes' => $size
                ]);
            }

            // Load heavy report data from JSON on disk if a path was provided
            if (!$this->reportData && $this->reportDataPath && file_exists($this->reportDataPath)) {
                try {
                    $loaded = json_decode(file_get_contents($this->reportDataPath), true);
                    if (is_array($loaded)) {
                        $this->reportData = $loaded;
                        Log::info('Loaded reportData from JSON file', [
                            'path' => $this->reportDataPath,
                            'charts_count' => isset($loaded['charts']) && is_array($loaded['charts']) ? count($loaded['charts']) : null,
                        ]);
                    } else {
                        Log::warning('JSON file did not decode to array', ['path' => $this->reportDataPath]);
                    }
                } catch (\Throwable $e) {
                    Log::error('Failed to load reportData from path', [
                        'path' => $this->reportDataPath,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $startViewTime = microtime(true);
            $html = view('reports.user_report', $this->reportData)->render();
            $viewRenderTime = round(microtime(true) - $startViewTime, 2);
            
            Log::info('Blade view rendered successfully', [
                'html_length' => strlen($html),
                'render_time_seconds' => $viewRenderTime,
                'contains_charts' => strpos($html, 'data:image') !== false,
                'contains_background_images' => strpos($html, 'page1_optimized.jpg') !== false
            ]);

            Log::info('Loading HTML into Dompdf');
            $pdf->loadHtml($html);
            
            Log::info('Setting paper size');
            $pdf->setPaper('A4', 'portrait');
            
            Log::info('Starting PDF render process - this may take time');
            $startRenderTime = microtime(true);
            $pdf->render();
            $renderTime = round(microtime(true) - $startRenderTime, 2);
            
            Log::info('PDF rendered successfully', [
                'render_time_seconds' => $renderTime,
                'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
                'peak_memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2)
            ]);

            // Save PDF
            Log::info('Generating filename and saving PDF');
            $filename = 'report-' . Str::uuid() . '.pdf';
            $storagePath = 'reports/' . $filename;
            
            Log::info('Saving PDF to storage', [
                'filename' => $filename,
                'storage_path' => $storagePath
            ]);
            
            $pdfOutput = $pdf->output();
            $pdfSize = strlen($pdfOutput);
            
            Log::info('PDF output generated', [
                'pdf_size_bytes' => $pdfSize,
                'pdf_size_mb' => round($pdfSize / 1024 / 1024, 2)
            ]);
            
            Storage::disk('public')->put($storagePath, $pdfOutput);
            $fileLocalPath = storage_path('app/public/' . $storagePath);
            
            Log::info('PDF saved to disk', [
                'local_path' => $fileLocalPath,
                'file_exists' => file_exists($fileLocalPath),
                'file_size_bytes' => file_exists($fileLocalPath) ? filesize($fileLocalPath) : null
            ]);

            // Save to database
            Log::info('Saving report to database');
            $report = new Report();
            $report->user_id = $this->userId; // Link to authenticated user
            $report->suburb_id = 111000; // Default suburb ID
            $report->suburb_name = $this->suburbName;
            $report->file_name = $filename;
            $report->file_path = Storage::url($storagePath);
            $report->save();
            
            Log::info('Report saved to database', [
                'report_id' => $report->id,
                'file_name' => $filename,
                'file_path' => $report->file_path,
                'user_id' => $this->userId
            ]);

            // Clean up temp files
            Log::info('Cleaning up temporary files', [
                'temp_files_count' => count($this->tempFiles ?? [])
            ]);
            
            foreach ($this->tempFiles ?? [] as $tempFile) {
                try {
                    if (file_exists($tempFile)) {
                        unlink($tempFile);
                        Log::info('Deleted temp file', ['file' => $tempFile]);
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to delete temp file', [
                        'file' => $tempFile,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Clean up persisted JSON if present
            if ($this->reportDataPath && file_exists($this->reportDataPath)) {
                try {
                    unlink($this->reportDataPath);
                    Log::info('Deleted reportData JSON file', ['file' => $this->reportDataPath]);
                } catch (\Exception $e) {
                    Log::warning('Failed to delete reportData JSON file', [
                        'file' => $this->reportDataPath,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('Dispatching email job', [
                'email' => $this->recipientEmail,
                'file' => $filename,
                'file_exists' => file_exists($fileLocalPath),
                'queue_connection' => config('queue.default'),
                'mail_driver' => config('mail.default')
            ]);

            // Dispatch email job
            SendReportEmailJob::dispatch(
                $this->recipientEmail,
                $this->recipientName,
                $this->suburbName,
                $fileLocalPath,
                $filename
            );

            Log::info('GeneratePdfReportJob completed successfully', [
                'email' => $this->recipientEmail,
                'suburb' => $this->suburbName,
                'filename' => $filename,
                'total_execution_time' => round(microtime(true) - $startTime, 2)
            ]);

        } catch (\Throwable $e) {
            Log::error('GeneratePdfReportJob failed', [
                'email' => $this->recipientEmail,
                'suburb' => $this->suburbName,
                'error' => $e->getMessage(),
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
                'peak_memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2)
            ]);
            
            // Clean up temp files even on failure
            foreach ($this->tempFiles ?? [] as $tempFile) {
                try {
                    if (file_exists($tempFile)) {
                        unlink($tempFile);
                    }
                } catch (\Exception $cleanupError) {
                    Log::warning('Failed to cleanup temp file after job failure', [
                        'file' => $tempFile,
                        'cleanup_error' => $cleanupError->getMessage()
                    ]);
                }
            }
            
            throw $e; // Re-throw to mark job as failed
        }
    }

    public function failed(\Throwable $exception): void
{
    Log::error('GeneratePdfReportJob failed completely', [
        'email' => $this->recipientEmail,
        'suburb' => $this->suburbName,
        'error' => $exception->getMessage(),
        'exception_class' => get_class($exception),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);
}
}
