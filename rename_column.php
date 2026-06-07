<?php
require_once 'koneksi.php';
$sql = "ALTER TABLE `intrakulikuler` CHANGE `role/jabatan` `role_jabatan` VARCHAR(255) NULL DEFAULT NULL;";
if (mysqli_query($KONEKSI, $sql)) {
    echo "Column renamed successfully\n";
} else {
    echo "Error renaming column: " . mysqli_error($KONEKSI) . "\n";
}
?>
