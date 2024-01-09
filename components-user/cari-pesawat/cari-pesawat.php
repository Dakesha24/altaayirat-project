<?php
session_start();
include("../../php/koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dari = $_POST["dari"];
    $ke = $_POST["ke"];
    $tanggal_pergi = $_POST["tanggal_pergi"];
    $kelas = $_POST["kelas"];

    $query_cari = "
    SELECT 
        schedule.id_schedule, 
        schedule.departure, 
        schedule.arrival, 
        schedule.ticket_price,
        departure_airport.airport_city AS dari_kota, 
        arrival_airport.airport_city AS ke_kota, 
        plane.name_plane, 
        class.name_class,
        airline.name_airline
    FROM 
        schedules AS schedule
        JOIN routes ON schedule.id_route = routes.id_route
        JOIN airports AS departure_airport ON routes.departure_airport = departure_airport.id_airport
        JOIN airports AS arrival_airport ON routes.arrival_airport = arrival_airport.id_airport
        JOIN planes AS plane ON schedule.id_plane = plane.id_plane
        JOIN classes AS class ON plane.id_class = class.id_class
        JOIN airlines AS airline ON plane.id_airline = airline.id_airline
    WHERE 
        routes.departure_airport = $dari 
        AND routes.arrival_airport = $ke
        AND schedule.departure >= '$tanggal_pergi' 
        AND class.id_class = $kelas";


    $result_cari = mysqli_query($koneksi, $query_cari);

    $_SESSION['result_cari'] = mysqli_fetch_all($result_cari, MYSQLI_ASSOC);

    header("Location: ../hasil-cari/hasil-cari.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cari-pesawat.css" />
    <title>Cari Pesawat</title>
</head>
<body>

    <div class="container">
        <h2>Cari Pesawat</h2>
        <form action="" method="post">
            <label for="dari">Dari:</label>
            <select id="dari" name="dari" required>
                <option value="">Pilih Kota Keberangkatan</option>
                <?php
                $query_dari = "SELECT * FROM airports";
                $result_dari = mysqli_query($koneksi, $query_dari);

                while ($row_dari = mysqli_fetch_assoc($result_dari)) {
                    echo "<option value='{$row_dari['id_airport']}'>
                        {$row_dari['airport_country']}, {$row_dari['airport_city']} : {$row_dari['airport_name']} - {$row_dari['airport_code']}
                    </option>";
                }
                ?>
            </select>

            <label for="ke">Ke:</label>
            <select id="ke" name="ke" required>
                <option value="">Pilih Kota Tujuan</option>
                <?php
                $query_ke = "SELECT * FROM airports";
                $result_ke = mysqli_query($koneksi, $query_ke);

                while ($row_ke = mysqli_fetch_assoc($result_ke)) {
                    echo "<option value='{$row_ke['id_airport']}'>
                        {$row_ke['airport_country']}, {$row_ke['airport_city']} : {$row_ke['airport_name']} - {$row_ke['airport_code']}
                    </option>";
                }
                ?>
            </select>

            <label for="tanggal_pergi">Tanggal Pergi:</label>
            <input type="date" id="tanggal_pergi" name="tanggal_pergi" required>

            <label for="kelas">Kelas:</label>
            <select id="kelas" name="kelas" required>
                <option value="">Pilih Kelas</option>
                <?php
                $query_kelas = "SELECT * FROM classes";
                $result_kelas = mysqli_query($koneksi, $query_kelas);

                while ($row_kelas = mysqli_fetch_assoc($result_kelas)) {
                    echo "<option value='{$row_kelas['id_class']}'>{$row_kelas['name_class']}</option>";
                }
                ?>
            </select>

            <button type="submit">Cari Pesawat</button>
        </form>
    </div>

</body>
</html>
