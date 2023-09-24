<?php
include_once 'config.php';
$response=[];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["file"])) {
    $target_dir = "uploads/"; // Dosyanın kaydedileceği dizin
    $file_extension = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . "." . $file_extension; // Rastgele isim oluştur

    $target_file = $target_dir . $new_filename;

    // Dosyayı belirtilen dizine kaydet
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $response["file_name"]=$target_file;
        $response["status"]=200;
    } else {
        $response["message"]="Dosya Yüklenemedi";
        $response["status"]=400;
    }
} else {
    $response["message"]="Başarısız İstek";
    $response["status"]=400;
}
echo json_encode($response);
