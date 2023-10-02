<?php
include_once 'config.php';
$data = json_decode(file_get_contents("php://input"));

try {
    
    // PDO bağlantısı oluştur
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    try {
        $sql = "SELECT * FROM `orders` WHERE `cus_id`=".$data;
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $order_products = array();

        foreach($orders as $item){
            $cs = array();
            $productSql="SELECT * FROM `order_product`
            LEFT JOIN `product` ON `order_product`.`product_id`=`product`.`product_id`
            WHERE `order_product`.`order_id`=".$item['id'];
            $product = $conn->prepare($productSql);
            $product->execute();
            $order_product = $product->fetchAll(PDO::FETCH_ASSOC);
            foreach($order_product as $oitem){      
                $seansSql="SELECT * FROM `order_seans` WHERE `order_product_id`=".$oitem['order_product_id'];
                $seans = $conn->prepare($seansSql);
                $seans->execute();
                $order_seans=$seans->fetchAll(PDO::FETCH_ASSOC);
                $oitem['seans']=$order_seans;
                $item['product'][]=$oitem;
            }
            $cs['order'][]=$item;
            $order_products[]=$cs;
        }
       
    } catch (PDOException $e) {
        die("");
    }
    // Sonuçları işle veya göster
    echo json_encode($order_products);
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>
