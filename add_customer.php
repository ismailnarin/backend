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
INSERT INTO `customer`(
    `cus_name`,
    `cus_surname`,
    `cus_phone`
)
VALUES(
    :cus_name,
    :cus_surname,
    :cus_phone
)
";

// SQL sorgusunu hazırlayın
$stmt = $conn->prepare($sql);

// Yer tutucuları veri ile bağlama
$stmt->bindParam(':cus_name', $data->cusName);
$stmt->bindParam(':cus_surname', $data->cusSurName);
$stmt->bindParam(':cus_phone', $data->cusPhone);

// SQL sorgusunu çalıştırma
$stmt->execute();

$lastInsertedId = $conn->lastInsertId();


    // Başarı mesajını döndürme
    $response['status']=200;
    $response['id']=$lastInsertedId;
} catch(PDOException $e) {
    $response['status']=400;
    $response['message']=$e->getMessage();
}
echo json_encode($response);

// Bağlantıyı kapatma
$conn = null;
?>
