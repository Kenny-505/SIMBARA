<?php
// File: test_image_direct.php
// Letakkan file ini di root directory Laravel (sejajar dengan artisan)
// Akses via: http://127.0.0.1:8000/test_image_direct.php?id=1

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        die('ID parameter required. Usage: test_image_direct.php?id=1');
    }
    
    // Direct PDO query to get image
    $pdo = DB::connection()->getPdo();
    $stmt = $pdo->prepare("SELECT foto_1, LENGTH(foto_1) as size FROM barang WHERE id_barang = ? AND foto_1 IS NOT NULL");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && $result['foto_1']) {
        $imageData = $result['foto_1'];
        
        // Set appropriate headers
        header('Content-Type: image/jpeg');
        header('Content-Length: ' . strlen($imageData));
        header('Cache-Control: public, max-age=31536000');
        
        // Output the image
        echo $imageData;
        exit;
    } else {
        // If no image found, return error info
        header('Content-Type: text/html');
        echo "<h3>No image found for ID: {$id}</h3>";
        echo "<p>Query result:</p>";
        echo "<pre>" . print_r($result, true) . "</pre>";
        
        // Show what's available
        $available = $pdo->query("SELECT id_barang, nama_barang, LENGTH(foto_1) as size FROM barang WHERE foto_1 IS NOT NULL LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
        echo "<h4>Available images:</h4>";
        echo "<ul>";
        foreach ($available as $item) {
            echo "<li>ID: {$item['id_barang']} - {$item['nama_barang']} - Size: {$item['size']} bytes</li>";
        }
        echo "</ul>";
    }
    
} catch (Exception $e) {
    header('Content-Type: text/html');
    echo "<div style='color: red;'>";
    echo "<h3>Error loading image:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}
?> 