<?php
/**
 * Comprehensive PDF generation test with background images
 * This simulates the exact process used in GeneratePdfReportJob
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "=== PDF Generation Debug Test ===\n\n";

// Test 1: Check if we can load Laravel functions
echo "1. Testing Laravel environment...\n";
try {
    if (file_exists(__DIR__ . '/bootstrap/app.php')) {
        $app = require_once __DIR__ . '/bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        echo "✅ Laravel environment loaded\n";
        
        // Test public_path function
        $publicPath = public_path();
        echo "✅ public_path(): $publicPath\n";
        
        // Test storage_path function
        $storagePath = storage_path();
        echo "✅ storage_path(): $storagePath\n";
    } else {
        echo "❌ Laravel bootstrap not found\n";
        // Fallback paths
        $publicPath = __DIR__ . '/public';
        $storagePath = __DIR__ . '/storage';
    }
} catch (Exception $e) {
    echo "❌ Laravel environment error: " . $e->getMessage() . "\n";
    $publicPath = __DIR__ . '/public';
    $storagePath = __DIR__ . '/storage';
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// Test 2: Check background images with exact paths used in job
echo "2. Testing background image access...\n";
$backgroundImages = [
    'page1' => $publicPath . '/Image/page1_optimized.jpg',
    'page2' => $publicPath . '/Image/page2_optimized.jpg', 
    'page6' => $publicPath . '/Image/page6_optimized.jpg'
];

foreach ($backgroundImages as $name => $path) {
    echo "Testing $name: $path\n";
    $exists = file_exists($path);
    $readable = $exists ? is_readable($path) : false;
    $size = $exists ? filesize($path) : 0;
    
    echo "  Exists: " . ($exists ? "✅ Yes" : "❌ No") . "\n";
    echo "  Readable: " . ($readable ? "✅ Yes" : "❌ No") . "\n";
    echo "  Size: " . number_format($size) . " bytes\n";
    
    if ($exists && $readable) {
        $imageInfo = getimagesize($path);
        if ($imageInfo) {
            echo "  ✅ Valid image: {$imageInfo[0]}x{$imageInfo[1]}, MIME: {$imageInfo['mime']}\n";
        } else {
            echo "  ❌ Invalid image format\n";
        }
    }
    echo "\n";
}

echo str_repeat("-", 50) . "\n\n";

// Test 3: Test Dompdf configuration
echo "3. Testing Dompdf configuration...\n";
try {
    $options = new \Dompdf\Options();
    $options->setChroot([$publicPath, $storagePath . '/app/public']);
    $options->setIsPhpEnabled(true);
    $options->setIsRemoteEnabled(true);
    $options->setDefaultFont('Arial');
    
    echo "✅ Dompdf options configured\n";
    echo "  Chroot paths: " . implode(', ', [$publicPath, $storagePath . '/app/public']) . "\n";
    echo "  PHP enabled: Yes\n";
    echo "  Remote enabled: Yes\n";
    
    $pdf = new \Dompdf\Dompdf($options);
    echo "✅ Dompdf instance created\n";
    
} catch (Exception $e) {
    echo "❌ Dompdf error: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// Test 4: Test CSS background image paths
echo "4. Testing CSS background image paths...\n";

$testCssPatterns = [
    "background-image: url('" . $publicPath . "/Image/page1_optimized.jpg')",
    "background-image: url('" . $publicPath . "/Image/page2_optimized.jpg')", 
    "background-image: url('" . $publicPath . "/Image/page6_optimized.jpg')",
    "background-image: url('Image/page1_optimized.jpg')",
    "background-image: url('Image/page2_optimized.jpg')",
    "background-image: url('Image/page6_optimized.jpg')"
];

foreach ($testCssPatterns as $i => $pattern) {
    echo "Pattern " . ($i + 1) . ": $pattern\n";
    
    // Extract path from CSS
    if (preg_match("/url\('([^']+)'\)/", $pattern, $matches)) {
        $cssPath = $matches[1];
        
        // If relative path, make it absolute
        if (!str_starts_with($cssPath, '/')) {
            $fullPath = $publicPath . '/' . $cssPath;
        } else {
            $fullPath = $cssPath;
        }
        
        echo "  Resolved path: $fullPath\n";
        echo "  Accessible: " . (file_exists($fullPath) ? "✅ Yes" : "❌ No") . "\n";
    }
    echo "\n";
}

echo str_repeat("-", 50) . "\n\n";

// Test 5: Create a minimal PDF with background image
echo "5. Testing minimal PDF generation...\n";
try {
    $options = new \Dompdf\Options();
    $options->setChroot([$publicPath, $storagePath . '/app/public']);
    $options->setIsPhpEnabled(true);
    $options->setIsRemoteEnabled(true);
    $options->setDefaultFont('Arial');
    
    $pdf = new \Dompdf\Dompdf($options);
    
    // Create minimal HTML with background image
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            .page {
                width: 100%;
                height: 100vh;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
            }
            .page-1-bg {
                background-image: url(\'' . $publicPath . '/Image/page1_optimized.jpg\');
            }
        </style>
    </head>
    <body>
        <div class="page page-1-bg">
            <h1>Test PDF with Background Image</h1>
            <p>If you can see this text with a background image, the fix is working!</p>
        </div>
    </body>
    </html>';
    
    echo "✅ HTML template created\n";
    
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();
    
    echo "✅ PDF rendered successfully\n";
    
    $output = $pdf->output();
    $testFile = __DIR__ . '/test_background_debug.pdf';
    file_put_contents($testFile, $output);
    
    echo "✅ PDF saved: $testFile\n";
    echo "  File size: " . number_format(filesize($testFile)) . " bytes\n";
    
} catch (Exception $e) {
    echo "❌ PDF generation failed: " . $e->getMessage() . "\n";
    echo "  Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Debug Test Complete ===\n";
echo "\nNext steps:\n";
echo "1. Check the generated PDF: test_background_debug.pdf\n";
echo "2. If background images are missing, check Laravel logs\n";
echo "3. Verify the updated GeneratePdfReportJob.php is deployed on your server\n";
echo "4. Clear Laravel caches: php artisan cache:clear\n";
echo "5. Restart queue workers if using them\n";