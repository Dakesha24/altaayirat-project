<?php
include "../../php/koneksi.php";

if (isset($_GET["id_order"])) {
    $id_order = $_GET['id_order'];

    // Ambil data order
    $query_order = "SELECT orders.*, passengers.full_name_passengers, schedules.departure, schedules.arrival, schedules.ticket_price, seats.number_seat, airlines.name_airline, planes.name_plane, routes.departure_airport AS code_departure, routes.arrival_airport AS code_arrival
                    FROM orders
                    INNER JOIN passengers ON orders.id_passenger = passengers.id_passenger
                    INNER JOIN schedules ON orders.id_schedule = schedules.id_schedule
                    INNER JOIN planes ON schedules.id_plane = planes.id_plane
                    INNER JOIN classes ON planes.id_plane = classes.id_class
                    INNER JOIN seats ON classes.id_class = seats.id_seat
                    INNER JOIN airlines ON planes.id_airline = airlines.id_airline
                    INNER JOIN routes ON schedules.id_route = routes.id_route
                    WHERE orders.id_order = $id_order";
    $result_order = mysqli_query($koneksi, $query_order);

    // Ambil data pembayaran
    $query_payment = "SELECT * FROM payments WHERE id_order = $id_order";
    $result_payment = mysqli_query($koneksi, $query_payment);
    $payment = mysqli_fetch_assoc($result_payment);
    
    // Lakukan pengecekan data order
    if (mysqli_num_rows($result_order) > 0) {
        $order = mysqli_fetch_assoc($result_order);
    } else {
        // Tambahkan logika atau tindakan yang sesuai jika 'id_order' tidak ditemukan
        echo "ID Order tidak ditemukan.";
    }
    
} else {
    echo "ID Order tidak tersedia.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Penerbangan</title>
    <link rel="stylesheet" type="text/css" href="ticket.css">
</head>
<body>
    <div id="ticket-container">
        <h1 class="ticketPageTitle">Tiket Penerbangan</h1>
        <div class="ticket-content">
            <div class="section-container">
                <h2 class="section-title">Detail Tiket</h2>
                <p>ID Order: <?php echo $order['id_order']; ?></p>
                <p>Nama Pemesan: <?php echo $order['full_name_passengers']; ?></p>
                <p>Maskapai: <?php echo $order['name_airline']; ?></p>
                <p>Nama Pesawat: <?php echo $order['name_plane']; ?></p>
                <p>Kode Bandara Keberangkatan: <?php echo $order['code_departure']; ?></p>
                <p>Kode Bandara Kedatangan: <?php echo $order['code_arrival']; ?></p>
                <p>Kursi: <?php echo $order['number_seat']; ?></p>
                <p>Tanggal Keberangkatan: <?php echo $order['departure']; ?></p>
                <p>Tanggal Kedatangan: <?php echo $order['arrival']; ?></p>
                <p>Total Bayar: <?php echo $order['ticket_price']; ?></p>
            </div>
        </div>
    </div>
</body>
</html>