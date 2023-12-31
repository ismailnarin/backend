<?php
include_once 'config.php';

// JSON verisini alın ve PHP nesnesine çevirin
$data = json_decode(file_get_contents("php://input"));
$product_packages = json_encode($data->packages);

try {
    // PDO nesnesi oluşturma
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Hata raporlamasını etkinleştirme
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // SQL sorgusu: Ürün eklemek için yer tutucuları kullanın
    $sql = "
    INSERT INTO `product`(
        `category_id`,
        `product_name`,
        `product_card`,
        `product_cash`,
        `package_status`,
        `product_packages`,
        `product_bonus_id`,
        `product_bonus_price`,
        `product_photo`
    )
    VALUES(
        :category_id,
        :product_name,
        :product_card,
        :product_cashe,
        :package_status,
        :product_packages,
        :product_bonus_id,
        :product_bonus_price,
        :product_photo
    )
    ";

    // SQL sorgusunu hazırlayın
    $stmt = $conn->prepare($sql);

    // Yer tutucuları veri ile bağlama
    $stmt->bindParam(':category_id', $data->productCategory);
    $stmt->bindParam(':product_name', $data->productName);
    $stmt->bindParam(':product_card', $data->productPriceCard);
    $stmt->bindParam(':product_cashe', $data->productPriceCash);
    $stmt->bindParam(':package_status', $data->openPackageCheck);
    $stmt->bindParam(':product_packages', $product_packages);
    $stmt->bindParam(':product_bonus_id', $data->productBonus);
    $stmt->bindParam(':product_bonus_price', $data->productBonusPrice);
    $stmt->bindParam(':product_photo', $data->productPhoto);

    // SQL sorgusunu çalıştırma
    $stmt->execute();

    // Başarı mesajını döndürme
    echo "Ürün başarıyla eklenmiştir.";
} catch(PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}

// Bağlantıyı kapatma
$conn = null;
?>
