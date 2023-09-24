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
    $sql = "UPDATE
                `user`
            SET
                `user_nickname` = '".$data->personelNickName."',
                `user_name` = '".$data->personelName."',
                `user_surname` = '".$data->personelSurName."',
                `user_password` = '".$data->personelPassword."',
                `user_phone` = '".$data->personelPhone."',
                `is_approve` = '".$data->personelApprove."'
            WHERE
                `user_id`=".$data->personelID;

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
