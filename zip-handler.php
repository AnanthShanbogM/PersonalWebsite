<?php
header('Content-Type: application/json');

$zipFile = __DIR__ . '/images/images_2.zip';
$extractDir = __DIR__ . '/temp_images';

// Create temp directory if it doesn't exist
if (!file_exists($extractDir)) {
    if (!mkdir($extractDir, 0777, true)) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to create temporary directory'
        ]);
        exit;
    }
}

// Clear old files
$files = glob($extractDir . '/*');
foreach ($files as $file) {
    if (is_file($file)) {
        if (!unlink($file)) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Failed to clear temporary files'
            ]);
            exit;
        }
    }
}

// Check if ZIP file exists
if (!file_exists($zipFile)) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'ZIP file not found'
    ]);
    exit;
}

// Extract ZIP file
$zip = new ZipArchive;
if ($zip->open($zipFile) !== true) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to open ZIP file: ' . $zip->getStatusString()
    ]);
    exit;
}

// Extract files
if ($zip->extractTo($extractDir) !== true) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to extract ZIP file'
    ]);
    exit;
}

$zip->close();

// Get list of valid images
$images = [];
$validExtensions = ['jpg', 'jpeg', 'png', 'gif'];

foreach (glob($extractDir . '/*') as $file) {
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (in_array($extension, $validExtensions)) {
        $images[] = basename($file);
    }
}

if (empty($images)) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'No valid images found in ZIP file'
    ]);
    exit;
}

// Return image paths with additional information
echo json_encode([
    'success' => true,
    'images' => $images,
    'totalImages' => count($images),
    'extractedAt' => date('Y-m-d H:i:s')
]);
?>
