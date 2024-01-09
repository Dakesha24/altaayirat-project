<?php
session_start();

if (!isset($_SESSION['result_cari'])) {
    header("Location: ../cari-pesawat/cari-pesawat.php");
}

$result_cari = $_SESSION['result_cari'];
unset($_SESSION['result_cari']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="hasil-cari.css" />
    <title>Hasil Pencarian</title>
</head>
<body>

    <div class="container">
        <h2>Hasil Pencarian</h2>

        <?php
        if ($result_cari && count($result_cari) > 0) {
            echo "<table>";
            echo "<tr>
                    <th>Departure</th>
                    <th>Arrival</th>
                    <th>Dari Kota</th>
                    <th>Ke Kota</th>
                    <th>Nama Pesawat</th>
                    <th>Kelas</th>
                    <th>Nama Maskapai</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>";

            foreach ($result_cari as $row_cari) {
                echo "<tr>";
                echo "<td>{$row_cari['departure']}</td>";
                echo "<td>{$row_cari['arrival']}</td>";
                echo "<td>{$row_cari['dari_kota']}</td>";
                echo "<td>{$row_cari['ke_kota']}</td>";
                echo "<td>{$row_cari['name_plane']}</td>";
                echo "<td>{$row_cari['name_class']}</td>";
                echo "<td>{$row_cari['name_airline']}</td>"; 
                echo "<td>{$row_cari['ticket_price']}</td>";
                echo "<td><a href='../pesan/pesan.php?id_schedule={$row_cari['id_schedule']}'>Pesan Sekarang</a></td>";
                echo "</tr>";
            }            

            echo "</table>";
        } else {
            echo "<p>Tidak ada hasil pencarian.</p>";
        }
        ?>
    </div>

</body>
</html>
