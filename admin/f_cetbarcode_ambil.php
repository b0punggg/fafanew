<?php
header('Content-Type: application/json');
include "config.php";
$conn = opendtcek();
session_start();
$id_user=$_SESSION['id_user'];
$sql = "SELECT * FROM mas_brg WHERE pilih='1' AND id_user='$id_user'";
$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'text' => 'https://fafa.dhe51.id/admin/img/' . $row['kd_bar'],
        'label' => $row['nm_brg'],
    ];
}

echo json_encode($data);
?>