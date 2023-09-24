<?php
include_once 'config.php';

$response=[];
// JSON verisini alın ve PHP nesnesine çevirin
$data = json_decode(file_get_contents("php://input"));

$type="";
if($data->type=="cash"){
    $type="order_cash_price = order_cash_price + (SELECT order_running_price FROM (SELECT order_running_price FROM orders WHERE id = ".$data->id.") AS subquery), order_is_cash = 1,";
}else{
    $type="order_card_price = order_card_price + (SELECT order_running_price FROM (SELECT order_running_price FROM orders WHERE id = ".$data->id.") AS subquery), order_is_card = 1,";
}


if(isset($data) && $data!==""){
try {
    // PDO nesnesi oluşturma
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Hata raporlamasını etkinleştirme
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // SQL sorgusu: Ürün eklemek için yer tutucuları kullanın
    // $tarih = date('Y-m-d H:i:s');
    $sql = "UPDATE orders
    SET ".$type."
        order_running_price = 0,
        order_running_status = 0
    WHERE `orders`.`id` = ".$data->id.";";
    // SQL sorgusunu hazırlayın
    $stmt = $conn->prepare($sql);


    // SQL sorgusunu çalıştırma
    $stmt->execute();

    // Başarı mesajını döndürme
    $response['status']=200;
    $response['message']="Personel Başarıyla Eklendi";

} catch(PDOException $e) {
    $response['status']=400;
    $response['message']= $e->getMessage();

}
}else{
    $response['status']=400;
}
echo json_encode($response);
// Bağlantıyı kapatma
$conn = null;
?>
