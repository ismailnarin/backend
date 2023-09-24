<?php
include_once 'config.php';

try {
    // PDO bağlantısı oluştur
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Hata mesajlarını göster
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL sorgusunu hazırla
    $sql = "SELECT * FROM `customer`";

    // Sorguyu hazırla
    $stmt = $conn->prepare($sql);

    // Sorguyu çalıştır
    $stmt->execute();

    // Sonuçları al
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
 

    // Sonuçları işle veya göster
    echo json_encode($results);
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>
