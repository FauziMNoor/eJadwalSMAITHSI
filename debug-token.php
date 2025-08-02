<?php
// Debug script for token testing on Hostinger
// WARNING: Remove this file in production for security
header('Content-Type: text/html; charset=UTF-8');

// Security check - only allow in development/testing
if (!isset($_GET['debug']) || $_GET['debug'] !== 'allow') {
    http_response_code(404);
    exit('Not found');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Debug - Hostinger</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .debug-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
        .error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .info { background: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto; }
        .test-links { margin: 20px 0; }
        .test-links a { display: block; margin: 5px 0; padding: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 3px; }
        .test-links a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Token Debug - Hostinger Environment</h1>
        
        <div class="debug-section info">
            <h3>Environment Info</h3>
            <pre><?php
echo "Server: " . $_SERVER['SERVER_NAME'] . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Current Time: " . date('Y-m-d H:i:s') . "\n";
            ?></pre>
        </div>

        <div class="debug-section">
            <h3>Test Token</h3>
            <p>Token yang akan ditest: <code>NTU2ZWE2MWMtOGRhZC00NDQxLTgwMDEtYTcwZjNkNDQ4ZmZhOjA2MmVlYTgzLTk1YjAtNDEwZi04ODc1LTlhZTQxODRjMTM1MQ==</code></p>
            
            <?php
            $test_token = "NTU2ZWE2MWMtOGRhZC00NDQxLTgwMDEtYTcwZjNkNDQ4ZmZhOjA2MmVlYTgzLTk1YjAtNDEwZi04ODc1LTlhZTQxODRjMTM1MQ==";
            
            try {
                $decoded = base64_decode($test_token);
                if ($decoded === false) {
                    throw new Exception("Failed to decode base64");
                }
                
                $parts = explode(':', $decoded);
                if (count($parts) !== 2) {
                    throw new Exception("Invalid token format - expected 2 parts, got " . count($parts));
                }
                
                echo '<div class="debug-section success">';
                echo '<h4>✅ Token Decode Success</h4>';
                echo '<pre>';
                echo "Decoded: " . $decoded . "\n";
                echo "Class ID: " . $parts[0] . "\n";
                echo "School ID: " . $parts[1] . "\n";
                echo '</pre>';
                echo '</div>';
                
            } catch (Exception $e) {
                echo '<div class="debug-section error">';
                echo '<h4>❌ Token Decode Failed</h4>';
                echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
                echo '</div>';
            }
            ?>
        </div>

        <div class="debug-section">
            <h3>File System Check</h3>
            <?php
            $files_to_check = [
                'index.html',
                'router.php',
                '.htaccess'
            ];
            
            foreach ($files_to_check as $file) {
                $file_path = __DIR__ . '/' . $file;
                $exists = file_exists($file_path);
                $readable = $exists ? is_readable($file_path) : false;
                
                echo '<div class="debug-section ' . ($exists ? 'success' : 'error') . '">';
                echo '<h4>' . ($exists ? '✅' : '❌') . ' ' . $file . '</h4>';
                echo '<p>Path: ' . $file_path . '</p>';
                echo '<p>Exists: ' . ($exists ? 'Yes' : 'No') . '</p>';
                if ($exists) {
                    echo '<p>Readable: ' . ($readable ? 'Yes' : 'No') . '</p>';
                    echo '<p>Size: ' . filesize($file_path) . ' bytes</p>';
                }
                echo '</div>';
            }
            ?>
        </div>

        <div class="test-links">
            <h3>Test Links</h3>
            <a href="/external/<?php echo $test_token; ?>" target="_blank">
                🔗 Test External Route (Same Tab)
            </a>
            <a href="/external/<?php echo $test_token; ?>/debug" target="_blank">
                🔧 Test Debug Route
            </a>
            <a href="/external/<?php echo $test_token; ?>/test" target="_blank">
                🧪 Test Connection Route
            </a>
            <a href="/" target="_blank">
                🏠 Home Page
            </a>
        </div>

        <div class="debug-section info">
            <h3>Perbaikan yang Dilakukan</h3>
            <ul>
                <li>✅ Menghapus <code>index.php</code> yang konflik dengan React app</li>
                <li>✅ Membuat <code>router.php</code> untuk SPA routing</li>
                <li>✅ Update <code>.htaccess</code> untuk menggunakan router.php</li>
                <li>✅ Sekarang <code>index.html</code> bisa diakses sebagai halaman utama</li>
            </ul>
        </div>

        <div class="debug-section info">
            <h3>Next Steps</h3>
            <ol>
                <li>Upload file yang sudah diperbaiki ke server</li>
                <li>Klik link "Test External Route" di atas</li>
                <li>Jika masih error, cek browser console untuk error JavaScript</li>
                <li>Jika halaman blank, coba link "Test Debug Route"</li>
                <li>Bandingkan dengan environment lain (local/vercel)</li>
            </ol>
        </div>
    </div>
</body>
</html>
