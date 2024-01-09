<?php
include "../../php/koneksi.php";

// Cek apakah ada nilai 'id_order' yang diterima
if (isset($_GET['id_order'])) {
    $id_order = $_GET['id_order'];

    // Ambil data order
    $query_order = "SELECT orders.*, passengers.full_name_passengers, schedules.departure, schedules.arrival, schedules.ticket_price
                    FROM orders
                    INNER JOIN passengers ON orders.id_passenger = passengers.id_passenger
                    INNER JOIN schedules ON orders.id_schedule = schedules.id_schedule
                    WHERE orders.id_order = $id_order";
    $result_order = mysqli_query($koneksi, $query_order);

    // Ambil data pembayaran
    $query_payment = "SELECT * FROM payments WHERE id_order = $id_order";
    $result_payment = mysqli_query($koneksi, $query_payment);
    $payment = mysqli_fetch_assoc($result_payment);

    // Lakukan pengecekan data order
    if (mysqli_num_rows($result_order) > 0) {
        $order = mysqli_fetch_assoc($result_order);

        // Lakukan pengecekan apakah tombol "Bayar Sekarang" diklik
        if (isset($_POST['bayarBtn'])) {
            // Update status pembayaran menjadi "Menunggu Konfirmasi"
            $query_update_payment = "UPDATE payments SET payment_status = 'Menunggu Konfirmasi' WHERE id_order = $id_order";
            mysqli_query($koneksi, $query_update_payment);

            echo "<script>alert('Pembayaran berhasil! Menunggu konfirmasi dari maskapai.');</script>";

            header("Location: pembayaran.php?idid_order=$id_order");
            exit();
        }
    } else {
        // Tambahkan logika atau tindakan yang sesuai jika 'id_order' tidak ditemukan
        echo "ID Order tidak ditemukan.";
    }
} else {
    // Tambahkan logika atau tindakan yang sesuai jika 'id_order' tidak ada
    echo "ID Order tidak tersedia.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link rel="stylesheet" type="text/css" href="pembayaran.css">
</head>
<body>
    <div id="pembayaran-container">
        <h1 class="pembayaranPageTitle">Pembayaran</h1>
        <div class="pembayaran-content">
            <div class="section-container">
                <h2 class="section-title">Detail Pesanan</h2>
                <p>ID Order: <?php echo $order['id_order']; ?></p>
                <p>Nama Pemesan: <?php echo $order['full_name_passengers']; ?></p>
                <p>Tanggal Keberangkatan: <?php echo $order['departure']; ?></p>
                <p>Tanggal Kedatangan: <?php echo $order['arrival']; ?></p>
                <p>Total Bayar: <?php echo $order['ticket_price']; ?></p>
            </div>

            <div class="section-container">
                <h2 class="section-title">Metode Pembayaran</h2>
                <p>Status Pembayaran: <?php echo $payment['payment_status']; ?></p>
                <?php
                // Tambahkan kondisi untuk menampilkan tombol atau informasi tiket
                if ($payment['payment_status'] == 'Menunggu Konfirmasi') {
                    echo "<p>Menunggu konfirmasi dari maskapai.</p>";
                } else {
                    echo "<p>Tiket sudah tersedia.</p>";
                }
                ?>
                <form action="" method="post">
                    <?php
                    if ($payment['payment_status'] == 'Lunas') {
                        echo "<a href='../tiket/ticket.php?id_order=$id_order'><button type='button'>Tampilkan Tiket</button></a>";
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
