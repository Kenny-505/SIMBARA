<?php
// File: test_blob.php
// Letakkan file ini di root directory Laravel (sejajar dengan artisan)
// Akses via: http://127.0.0.1:8000/test_blob.php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "<h2>SIMBARA - Test BLOB Images</h2>";
    echo "<hr>";
    
    // Test 1: Direct PDO Connection
    echo "<h3>Test 1: Direct PDO Connection</h3>";
    $pdo = DB::connection()->getPdo();
    $stmt = $pdo->prepare("SELECT id_barang, nama_barang, LENGTH(foto_1) as foto_1_size FROM barang WHERE foto_1 IS NOT NULL LIMIT 5");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Nama Barang</th><th>Foto 1 Size (bytes)</th><th>Test Image</th></tr>";
    
    foreach ($results as $row) {
        echo "<tr>";
        echo "<td>{$row['id_barang']}</td>";
        echo "<td>{$row['nama_barang']}</td>";
        echo "<td>{$row['foto_1_size']}</td>";
        echo "<td><a href='test_image_direct.php?id={$row['id_barang']}' target='_blank'>View Image</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test 2: Laravel Eloquent
    echo "<h3>Test 2: Laravel Eloquent</h3>";
    $barangs = App\Models\Barang::select('id_barang', 'nama_barang', 'foto_1')->limit(5)->get();
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Nama Barang</th><th>Foto 1 Exists</th><th>Foto 1 Length</th><th>Data Type</th></tr>";
    
    foreach ($barangs as $barang) {
        echo "<tr>";
        echo "<td>{$barang->id_barang}</td>";
        echo "<td>{$barang->nama_barang}</td>";
        echo "<td>" . (!empty($barang->foto_1) ? 'YES' : 'NO') . "</td>";
        echo "<td>" . ($barang->foto_1 ? strlen($barang->foto_1) : 0) . "</td>";
        echo "<td>" . gettype($barang->foto_1) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test 3: Database Configuration
    echo "<h3>Test 3: Database Configuration</h3>";
    $config = [
        'driver' => config('database.default'),
        'host' => config('database.connections.mysql.host'),
        'database' => config('database.connections.mysql.database'),
        'charset' => config('database.connections.mysql.charset'),
    ];
    
    echo "<pre>" . print_r($config, true) . "</pre>";
    
    // Test 4: MySQL Variables
    echo "<h3>Test 4: MySQL Variables (Important for BLOB)</h3>";
    $mysqlVars = DB::select("SHOW VARIABLES WHERE Variable_name IN ('max_allowed_packet', 'group_concat_max_len')");
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Variable Name</th><th>Value</th></tr>";
    foreach ($mysqlVars as $var) {
        echo "<tr><td>{$var->Variable_name}</td><td>{$var->Value}</td></tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>";
    echo "<h3>Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}
?> 