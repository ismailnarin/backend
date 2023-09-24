<?php
include_once 'config.php';
$data = json_decode(file_get_contents("php://input"));

try {
    
    // PDO bağlantısı oluştur
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    try {
        $sql = "SELECT * FROM `orders` WHERE cus_id =".$data." AND `order_running_status`='1'
        ";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $order_products=[];
        foreach($orders as $item){
            $cs = array();
            $productSql="SELECT * FROM `order_product`
            LEFT JOIN `product` ON `order_product`.`product_id`=`product`.`product_id`
            WHERE `order_product`.`order_id`=".$item['id'];
            $product = $conn->prepare($productSql);
            $product->execute();
            $order_product = $product->fetchAll(PDO::FETCH_ASSOC);
            $item['product']=$order_product;
            $order_products['order'][]=$item;
        }
       
    } catch (PDOException $e) {
        die("Sorgu çalıştırılırken hata oluştu: " . $e->getMessage());
    }
    // Sonuçları işle veya göster
    echo json_encode($order_products);
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>
