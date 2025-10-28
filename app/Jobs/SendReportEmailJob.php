<?php

namespace App\Jobs;

use App\Mail\ReportReady;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReportEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    protected string $recipientEmail;
    protected string $recipientName;
    protected string $fileLocalPath;
    protected string $filename;
    protected string $suburbName;

    /**
     * Create a new job instance.
     */
    public function __construct(string $recipientEmail, string $recipientName, string $suburbName, string $fileLocalPath, string $filename)
    {
        $this->recipientEmail = $recipientEmail;
        $this->recipientName = $recipientName;
        $this->fileLocalPath = $fileLocalPath;
        $this->filename = $filename;
        $this->suburbName = $suburbName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startTime = microtime(true);
        
        try {
            Log::info('SendReportEmailJob started', [
                'email' => $this->recipientEmail,
                'name' => $this->recipientName,
                'suburb' => $this->suburbName,
                'file_exists' => file_exists($this->fileLocalPath),
                'file_size_bytes' => @filesize($this->fileLocalPath) ?: null,
                'filename' => $this->filename,
                'queue_connection' => config('queue.default'),
                'mail_driver' => config('mail.default'),
                'mail_from' => config('mail.from.address'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_port' => config('mail.mailers.smtp.port'),
                'attempt' => isset($this->job) ? $this->job->attempts() : null,
                'max_tries' => $this->tries,
                'timeout' => $this->timeout,
                'php_version' => PHP_VERSION,
                'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2)
            ]);

            // Validate file exists and is readable
            if (!file_exists($this->fileLocalPath)) {
                throw new \Exception("PDF file does not exist: {$this->fileLocalPath}");
            }
            
            if (!is_readable($this->fileLocalPath)) {
                throw new \Exception("PDF file is not readable: {$this->fileLocalPath}");
            }
            
            $fileSize = filesize($this->fileLocalPath);
            if ($fileSize === false || $fileSize === 0) {
                throw new \Exception("PDF file is empty or unreadable: {$this->fileLocalPath}");
            }
            
            Log::info('File validation passed', [
                'file_path' => $this->fileLocalPath,
                'file_size_bytes' => $fileSize,
                'file_size_mb' => round($fileSize / 1024 / 1024, 2),
                'is_readable' => is_readable($this->fileLocalPath)
            ]);

            // Validate email configuration
            $mailConfig = [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name')
            ];
            
            Log::info('Mail configuration', [
                'driver' => $mailConfig['driver'],
                'host' => $mailConfig['host'],
                'port' => $mailConfig['port'],
                'has_username' => !empty($mailConfig['username']),
                'encryption' => $mailConfig['encryption'],
                'from_address' => $mailConfig['from_address'],
                'from_name' => $mailConfig['from_name']
            ]);

            // Build mailable
            Log::info('Creating ReportReady mailable');
            $mailable = new ReportReady(
                (object)['first_name' => $this->recipientName],
                $this->suburbName,
                $this->fileLocalPath,
                $this->filename
            );
            
            Log::info('Mailable created successfully', [
                'mailable_class' => get_class($mailable),
                'recipient_name' => $this->recipientName,
                'suburb' => $this->suburbName
            ]);

            // Send email
            Log::info('Attempting to send email via Mail facade');
            $sendStartTime = microtime(true);
            
            Mail::to($this->recipientEmail)->send($mailable);
            
            $sendTime = round(microtime(true) - $sendStartTime, 2);
            
            Log::info('Mail::send() completed', [
                'send_time_seconds' => $sendTime,
                'memory_after_send_mb' => round(memory_get_usage(true) / 1024 / 1024, 2)
            ]);

            // Inspect failures if available
            $failures = method_exists(Mail::class, 'failures') ? Mail::failures() : [];
            Log::info('SendReportEmailJob mail send result', [
                'failure_count' => is_array($failures) ? count($failures) : null,
                'failures' => $failures,
                'has_failures' => !empty($failures)
            ]);
            
            if (!empty($failures)) {
                throw new \Exception('Mail sending failed with failures: ' . json_encode($failures));
            }

            Log::info('Queued report email sent successfully', [
                'email' => $this->recipientEmail,
                'name' => $this->recipientName,
                'suburb' => $this->suburbName,
                'file' => $this->fileLocalPath,
                'filename' => $this->filename,
                'total_execution_time' => round(microtime(true) - $startTime, 2),
                'final_memory_mb' => round(memory_get_usage(true) / 1024 / 1024, 2)
            ]);
            
        } catch (\Throwable $e) {
            Log::error('Queued report email failed to send', [
                'email' => $this->recipientEmail,
                'name' => $this->recipientName,
                'suburb' => $this->suburbName,
                'file_path' => $this->fileLocalPath,
                'filename' => $this->filename,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'attempt' => isset($this->job) ? $this->job->attempts() : null,
                'max_tries' => $this->tries,
                'execution_time' => round(microtime(true) - $startTime, 2),
                'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
                'peak_memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2)
            ]);
            throw $e; // allow retries
        }
    }
}