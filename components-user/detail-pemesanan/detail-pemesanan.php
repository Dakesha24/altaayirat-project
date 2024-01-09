<?php
include "../../php/koneksi.php";

// Ambil data riwayat pemesanan
$query_riwayat = "SELECT orders.id_order, passengers.full_name_passengers, schedules.departure, schedules.arrival, payments.payment_status
                  FROM orders
                  INNER JOIN passengers ON orders.id_passenger = passengers.id_passenger
                  INNER JOIN schedules ON orders.id_schedule = schedules.id_schedule
                  INNER JOIN payments ON orders.id_order = payments.id_order
                  ORDER BY orders.id_order DESC";

$result_riwayat = mysqli_query($koneksi, $query_riwayat);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan</title>
    <link rel="stylesheet" type="text/css" href="riwayat.css">
</head>
<body>
    <div id="riwayat-container">
        <h1 class="riwayatPageTitle">Detail Pemesanan</h1>
        <div class="riwayat-content">
            <?php while ($row_riwayat = mysqli_fetch_assoc($result_riwayat)) : ?>
                <div class="riwayat-item">
                    <h2 class="riwayat-title">ID Order: <?php echo $row_riwayat['id_order']; ?></h2>
                    <p>Nama Pemesan: <?php echo $row_riwayat['full_name_passengers']; ?></p>
                    <p>Tanggal Keberangkatan: <?php echo $row_riwayat['departure']; ?></p>
                    <p>Tanggal Kedatangan: <?php echo $row_riwayat['arrival']; ?></p>
                    <p>Status Pembayaran: <?php echo $row_riwayat['payment_status']; ?></p>
                    
                    <?php if ($row_riwayat['payment_status'] == 'Dikonfirmasi') : ?>
                        <!-- Tampilkan tombol "Dapatkan E-Ticket" jika pembayaran sudah dikonfirmasi -->
                        <button onclick="downloadETicket(<?php echo $row_riwayat['id_order']; ?>)">Dapatkan E-Ticket</button>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        // Fungsi untuk mendownload E-Ticket
        function downloadETicket(orderId) {
            // Implementasikan logika pengunduhan E-Ticket sesuai kebutuhan
            alert("E-Ticket berhasil diunduh untuk Order ID: " + orderId);
        }
    </script>
</body>
</html>
