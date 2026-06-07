<?php
require_once 'koneksi.php';
$stmt = mysqli_prepare($KONEKSI, "INSERT INTO `intrakulikuler` (`nama`, `role_jabatan`) VALUES ('Test', 'Test')");
if (!$stmt) {
    echo "Prepare failed: " . mysqli_error($KONEKSI) . "\n";
} else {
    echo "Prepare succeeded\n";
}
?>
