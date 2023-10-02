<?php
include_once 'config.php';
$response=[];
$data = json_decode(file_get_contents("php://input"));

try {
   // PDO nesnesi oluşturma
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

// Hata raporlamasını etkinleştirme
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// SQL sorgusu: Ürün eklemek için yer tutucuları kullanın
$sql = "
UPDATE
    `customer`
SET
    `status` = 0
WHERE
    `cus_id` =".$data."
";

// SQL sorgusunu hazırlayın
$stmt = $conn->prepare($sql);

// SQL sorgusunu çalıştırma
$stmt->execute();

    // Başarı mesajını döndürme
    $response['status']=200;

} catch(PDOException $e) {
    $response['status']=400;
    $response['message']=$e->getMessage();
}
echo json_encode($response);

// Bağlantıyı kapatma
$conn = null;
?>
