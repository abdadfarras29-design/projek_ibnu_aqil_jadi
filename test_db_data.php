<?php
require_once 'koneksi.php';
$res = mysqli_query($KONEKSI, "SELECT * FROM intrakulikuler");
$data = [];
while ($row = mysqli_fetch_assoc($res)) {
    $data[] = $row;
}
echo json_encode($data, JSON_PRETTY_PRINT);
?>
