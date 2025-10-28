<?php

namespace App\Jobs;

use App\Mail\ReportReady;
use App\Models\Report;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateReportJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected $userId;
    protected $requestData;
    protected $tries;
    protected $timeout;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId, array $requestData)
    {
        $this->userId = $userId;
        $this->requestData = $requestData;

        $this->tries = 3;
        $this->timeout = 120;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find($this->userId);

        if (!$user) {
            Log::error("GenerateReportJob failed: User ID {$this->userId} not found.");
            return;
        }

        // Define which base64 keys to process
        $chartKeys = [
            'houseInventoryChart', 'houseListingsChart', 'housePriceChart',
            'unitInventoryChart', 'unitListingsChart', 'unitPriceChart',
            'houseRentsChart', 'unitRentsChart', 'vacancyRatesChart',
            'housePriceSegments', 'elevation', 'seifa',
        ];

        $inlineChartUris = [];

        // --- Process and Resize Charts (into Base64 Data URIs) ---
        foreach ($chartKeys as $key) {
            $base64 = $this->requestData[$key] ?? null;

            if ($base64) {
                try {
                    // 1. Clean the base64 prefix
                    $base64Data = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
                    $imageData = base64_decode($base64Data);

                    if ($imageData === false || strlen($imageData) < 100) {
                        Log::warning("Invalid image data for: " . $key);
                        continue;
                    }

                    // 2. Resize large images before rendering (max width = 1200px)
                    $image = imagecreatefromstring($imageData);
                    if ($image !== false) {
                        $maxWidth = 1200;
                        $width = imagesx($image);
                        $height = imagesy($image);

                        if ($width > $maxWidth) {
                            $newHeight = intval($height * ($maxWidth / $width));
                            $resized = imagecreatetruecolor($maxWidth, $newHeight);

                            // White background (fixes common GD black background issue)
                            $white = imagecolorallocate($resized, 255, 255, 255);
                            imagefill($resized, 0, 0, $white);

                            imagecopyresampled($resized, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
                            imagedestroy($image);
                            $image = $resized;
                        }

                        // 3. Get the resized image data as Base64 encoded string
                        ob_start();
                        imagepng($image, null, 6); // Output PNG to buffer
                        $resizedImageData = ob_get_clean();
                        imagedestroy($image);

                        // 4. Store as an inline data URI
                        $inlineChartUris[$key] = 'data:image/png;base64,' . base64_encode($resizedImageData);

                    }
                } catch (\Exception $e) {
                    Log::error("Job Error processing chart {$key}: " . $e->getMessage());
                }
            }
        }

          // --- Prepare report data ---
        $reportData = [
            'user' => $user,
            // Pass the INLINE Base64 URIs to the view
            'charts' => $inlineChartUris, 
            'note' => $this->requestData['note'] ?? null,
            'date' => date('j-F-Y'),
            'year' => date('Y'),
            'suburb' => $this->requestData['name'],
            'houseText' => $this->requestData['houseText'] ?? null,
            'unitText' => $this->requestData['unitText'] ?? null,
        ];

        // Log::info('*****************************/////////////////////**********************');
        // Log::info($reportData);

         // --- Generate PDF ---
        try {
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', false); // IMPORTANT: Set to false since we use inline data
            $options->set('defaultFont', 'sans-serif');

            $pdf = new Dompdf($options);
            // Render the Blade view, which should use the charts array for <img src="data:image/png;base64,...">
            $html = view('reports.user_report', $reportData)->render(); 
            $pdf->loadHtml($html);
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();

            // --- Save PDF ---
            $filename = 'report-' . Str::uuid() . '.pdf';
            $storagePath = 'reports/' . $filename;
            Storage::disk('public')->put($storagePath, $pdf->output());
            $fileUrl = Storage::url($storagePath); // Public URL for download

            // --- Store report in database ---
            $report = new Report();
            $report->user_id = $user->id;
            $report->suburb_id = 111000; // Assuming a static placeholder or you'd get this from request
            $report->file_name = $filename;
            $report->file_path = $fileUrl;
            $report->save();

            // --- Send email with PDF attachment ---
            $filePath = Storage::disk('public')->path($storagePath);

            Mail::to($user->email)->send(new ReportReady($user, $this->requestData['name'], $filePath, $filename));

        } catch (\Exception $e) {
            Log::error('Job PDF Generation Error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);

        }


    }
}
