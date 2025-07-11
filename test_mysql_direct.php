<?php
// File: test_mysql_direct.php
// Direct MySQL connection test (bypass Laravel completely)

// Database config - adjust these to match your .env settings
$host = '127.0.0.1';
$port = '3306';
$database = 'simbarafixbanget'; // Sesuai yang Anda sebutkan di awal
$username = 'root';
$password = ''; // Biasanya kosong di Laragon

try {
    echo "<h2>Direct MySQL Connection Test</h2>";
    echo "<hr>";
    
    // Test 1: Basic connection
    echo "<h3>Test 1: Basic Connection</h3>";
    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    echo "✅ Connection successful!<br>";
    
    // Test 2: Check MySQL version and variables
    echo "<h3>Test 2: MySQL Configuration</h3>";
    $version = $pdo->query("SELECT VERSION() as version")->fetch(PDO::FETCH_ASSOC);
    echo "MySQL Version: " . $version['version'] . "<br>";
    
    $vars = $pdo->query("SHOW VARIABLES WHERE Variable_name IN ('max_allowed_packet', 'group_concat_max_len')")->fetchAll(PDO::FETCH_ASSOC);
    foreach($vars as $var) {
        echo $var['Variable_name'] . ": " . $var['Value'] . "<br>";
    }
    
    // Test 3: List tables to make sure we're in right database
    echo "<h3>Test 3: Tables in Database</h3>";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . implode(', ', $tables) . "<br>";
    
    // Test 4: Check barang table structure
    echo "<h3>Test 4: Barang Table Structure</h3>";
    $columns = $pdo->query("DESCRIBE barang")->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    foreach($columns as $col) {
        echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td><td>{$col['Null']}</td><td>{$col['Key']}</td></tr>";
    }
    echo "</table>";
    
    // Test 5: Count records with BLOB data
    echo "<h3>Test 5: BLOB Data Count</h3>";
    $counts = $pdo->query("SELECT 
        COUNT(*) as total_records,
        COUNT(foto_1) as foto_1_not_null,
        COUNT(foto_2) as foto_2_not_null,
        COUNT(foto_3) as foto_3_not_null
        FROM barang")->fetch(PDO::FETCH_ASSOC);
    
    foreach($counts as $key => $value) {
        echo "{$key}: {$value}<br>";
    }
    
    // Test 6: Get BLOB sizes
    echo "<h3>Test 6: BLOB Sizes (Top 5)</h3>";
    $sizes = $pdo->query("SELECT id_barang, nama_barang, 
        COALESCE(LENGTH(foto_1), 0) as foto_1_size,
        COALESCE(LENGTH(foto_2), 0) as foto_2_size,
        COALESCE(LENGTH(foto_3), 0) as foto_3_size
        FROM barang 
        ORDER BY foto_1_size DESC 
        LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nama</th><th>Foto 1 Size</th><th>Foto 2 Size</th><th>Foto 3 Size</th></tr>";
    foreach($sizes as $row) {
        echo "<tr>";
        echo "<td>{$row['id_barang']}</td>";
        echo "<td>{$row['nama_barang']}</td>";
        echo "<td>{$row['foto_1_size']}</td>";
        echo "<td>{$row['foto_2_size']}</td>";
        echo "<td>{$row['foto_3_size']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test 7: Try to fetch actual BLOB data
    echo "<h3>Test 7: Fetch BLOB Data</h3>";
    $stmt = $pdo->prepare("SELECT foto_1 FROM barang WHERE id_barang = 1 LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && $result['foto_1']) {
        echo "✅ BLOB data fetched successfully!<br>";
        echo "Data length: " . strlen($result['foto_1']) . " bytes<br>";
        echo "Data type: " . gettype($result['foto_1']) . "<br>";
        echo "First 50 bytes (hex): " . bin2hex(substr($result['foto_1'], 0, 50)) . "<br>";
    } else {
        echo "❌ No BLOB data fetched<br>";
        echo "Result: " . print_r($result, true) . "<br>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>";
    echo "<h3>Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
?> 