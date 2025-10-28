<?php
/**
 * Test script to verify image file access on server
 * Run this on your server to test if PHP can access the background images
 */

echo "=== Testing Image File Access on Server ===\n\n";

// Define the image files we need
$imageFiles = [
    'page1_optimized.jpg',
    'page2_optimized.jpg', 
    'page6_optimized.jpg'
];

$publicImagePath = __DIR__ . '/public/Image/';

foreach ($imageFiles as $filename) {
    $fullPath = $publicImagePath . $filename;
    
    echo "Testing: $filename\n";
    echo "Full path: $fullPath\n";
    
    // Check if file exists
    if (file_exists($fullPath)) {
        echo "✅ File exists\n";
        
        // Check if readable
        if (is_readable($fullPath)) {
            echo "✅ File is readable\n";
            
            // Get file size
            $size = filesize($fullPath);
            echo "✅ File size: " . number_format($size) . " bytes\n";
            
            // Test reading first few bytes
            $handle = fopen($fullPath, 'rb');
            if ($handle) {
                $firstBytes = fread($handle, 10);
                fclose($handle);
                echo "✅ Can read file content\n";
                
                // Check if it's a valid image
                $imageInfo = getimagesize($fullPath);
                if ($imageInfo) {
                    echo "✅ Valid image: {$imageInfo[0]}x{$imageInfo[1]}, MIME: {$imageInfo['mime']}\n";
                } else {
                    echo "❌ Invalid image format\n";
                }
            } else {
                echo "❌ Cannot open file for reading\n";
            }
        } else {
            echo "❌ File is not readable\n";
        }
    } else {
        echo "❌ File does not exist\n";
    }
    
    echo "\n" . str_repeat("-", 50) . "\n\n";
}

echo "=== Testing public_path() function ===\n";
if (function_exists('public_path')) {
    echo "✅ public_path() function is available\n";
    foreach ($imageFiles as $filename) {
        $publicPath = public_path("Image/$filename");
        echo "public_path('Image/$filename') = $publicPath\n";
        echo "Exists: " . (file_exists($publicPath) ? "Yes" : "No") . "\n\n";
    }
} else {
    echo "❌ public_path() function not available (not in Laravel context)\n";
}

echo "=== Test Complete ===\n";