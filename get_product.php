<?php
include_once 'config.php';

$data = json_decode(file_get_contents("php://input"));



try {
    // PDO bağlantısı oluştur
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Hata mesajlarını göster
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL sorgusunu hazırla
    $sql = "SELECT  `product_id` AS `id`, `category_id` AS `categoryID`, `product_name` AS `productName`, `product_card` AS `productCard`, `product_cash` AS `productCash`, `package_status` AS `openPackageStatus`, `product_packages` AS `packages`, `product_bonus_id` AS `productBonus`, `product_bonus_price` AS `productBonusPrice`, `product_photo` AS `productPhoto` FROM `product` WHERE `category_id`=".$data;

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
