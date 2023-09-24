<?php
include_once 'config.php';

$data = json_decode(file_get_contents("php://input"));



try {
    // PDO bağlantısı oluştur
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $baslangicTarihi = $data->firstDate." 00:00:00"; // Başlangıç tarihi
    $bitisTarihi = $data->secondDate." 23:59:59";    // Bitiş tarihi

    try {
        $sql = "SELECT * FROM `orders` 
        LEFT JOIN `customer` ON `customer`.`cus_id`=`orders`.`cus_id`
        WHERE `created_at` BETWEEN '".$baslangicTarihi."' AND '".$bitisTarihi."';";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Sorgu çalıştırılırken hata oluştu: " . $e->getMessage());
    }
    // Sonuçları işle veya göster
    echo json_encode($results);
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>
