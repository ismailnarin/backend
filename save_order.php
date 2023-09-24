<?php
include_once 'config.php';
$data = json_decode(file_get_contents("php://input"));


if(isset($data) && $data!==""){
try {
    // PDO nesnesi oluşturma
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Hata raporlamasını etkinleştirme
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // SQL sorgusu: Ürün eklemek için yer tutucuları kullanın
    $sql = "
    INSERT INTO `orders`(
        `cus_id`,
        `order_card_amaount`,
        `order_cash_amount`,
        `order_cash_price`,
        `order_card_price`,
        `order_is_card`,
        `order_is_cash`,
        `order_discount`,
        `order_running_status`,
        `order_running_price`,
        `order_price`
    )
    VALUES(
        ".$data->customerID.",
        ".$data->orderCardAmount.",
        ".$data->orderCashAmount.",
        ".$data->orderCashPrice.",
        ".$data->orderCardPrice.",
        ".$data->orderIsCard.",
        ".$data->orderIsCash.",
        ".$data->orderDiscount.",
        ".$data->orderRunningStatus.",
        ".$data->orderRunningPrice.",
        ".$data->orderPrice."
    )
    ";
    // SQL sorgusunu hazırlayın
    $stmt = $conn->prepare($sql);

    // SQL sorgusunu çalıştırma
    $stmt->execute();
    $lastInsertId = $conn->lastInsertId();
    foreach ($data->orderList as $item) {
        try {
            $sql = "INSERT INTO `order_product`(
                `order_id`,
                `product_id`,
                `product_seans_number`,
                `cash_price`,
                `card_price`,
                `quantity`,
                `personel`
            )
            VALUES(
                '" . $lastInsertId . "',
                '" . $item->productID . "',
                '" . $item->seansNumber . "',
                '" . $item->cashPrice . "',
                '" . $item->cardPrice . "',
                '" . $item->quantity . "',
                '" . $item->personel . "'
            )"; 
            $order_product = $conn->prepare($sql);
            $order_product->execute();
            $order_product_id = $conn->lastInsertId();
            $seansSql = "INSERT INTO `order_seans`(`order_product_id`,`seans_status`)VALUES";
            $values = [];
            for($i=0 ;$i<$item->seansNumber;$i++ ){
                $seansStatus=0;
                if($item->seansNumber==1){
                    $seansStatus=1;
                }
                $values[] = "('$order_product_id','$seansStatus')";
            }
            $seansSql .= implode(', ', $values);
            $seansProduct=$conn->prepare($seansSql);
            $seansProduct->execute();


        } catch (PDOException $e) {
            echo "Bağlantı hatası: " . $e->getMessage();
        }
    }
    

    // Başarı mesajını döndürme
    echo "sipariş başarıyla oluşturuldu";
} catch(PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}}

// Bağlantıyı kapatma
$conn = null;
?>
