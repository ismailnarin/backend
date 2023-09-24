<?php
include_once 'config.php';
$response=[];

// JSON verisini alın ve PHP nesnesine çevirin
$data = json_decode(file_get_contents("php://input"));

if(isset($data) && $data!==""){
try {
    // PDO nesnesi oluşturma
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Hata raporlamasını etkinleştirme
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // SQL sorgusu: Ürün eklemek için yer tutucuları kullanın
    $sql = "
    INSERT INTO `user`(
        `user_nickname`,
        `user_name`,
        `user_surname`,
        `user_password`,
        `user_status`,
        `user_phone`,
        `is_approve`
    )
    VALUES(
        '".$data->personeUserNickName."',
        '".$data->personelName."',
        '".$data->personelSurName."',
        '".$data->personelPassword."',
        1,
        '".$data->personelPhone."',
        0
    );";

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
