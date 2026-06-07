<?php
require_once 'koneksi.php';
$res = mysqli_query($KONEKSI, "DESCRIBE intrakulikuler");
$columns = [];
while ($row = mysqli_fetch_assoc($res)) {
    $columns[] = $row['Field'];
}
echo "Columns: " . implode(', ', $columns);
?>
