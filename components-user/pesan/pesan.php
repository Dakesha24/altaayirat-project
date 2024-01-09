<?php
include "../../php/koneksi.php";

// Cek apakah ada nilai 'id_schedule' yang diterima
if (isset($_GET['id_schedule'])) {
    $id_schedule = $_GET['id_schedule'];

    // Ambil data kursi yang tersedia sesuai kelas
    $query_kursi = "SELECT seats.id_seat, seats.number_seat, seats.status
                    FROM seats
                    INNER JOIN classes ON seats.id_seat = classes.id_seat
                    INNER JOIN planes ON classes.id_class = planes.id_class
                    INNER JOIN schedules ON planes.id_plane = schedules.id_plane
                    WHERE schedules.id_schedule = $id_schedule";
    $result_kursi = mysqli_query($koneksi, $query_kursi);

    // Ambil data schedule
    $query_schedule = "SELECT * FROM schedules WHERE id_schedule = $id_schedule";
    $result_schedule = mysqli_query($koneksi, $query_schedule);
    $schedule = mysqli_fetch_assoc($result_schedule);

    // Lakukan pengecekan data pribadi dan proses pemesanan
    if (isset($_POST['submitBtn'])) {
        if (!empty($_POST['full_name_passengers']) && !empty($_POST['email']) && !empty($_POST['phone_number'])) {
            // Lakukan proses pemesanan sesuai kebutuhan
            $full_name = mysqli_real_escape_string($koneksi, $_POST['full_name_passengers']);
            $email = mysqli_real_escape_string($koneksi, $_POST['email']);
            $phone_number = mysqli_real_escape_string($koneksi, $_POST['phone_number']);
            $birth_date = mysqli_real_escape_string($koneksi, $_POST['birth_date']);
            $gender = mysqli_real_escape_string($koneksi, $_POST['gender']);
            $payment_method = mysqli_real_escape_string($koneksi, $_POST['payment_method']);
            $selected_seat = $_POST['kursi'];

            // Contoh: Simpan data pemesanan ke tabel orders, passengers, dan lain-lain
            $query_insert_passenger = "INSERT INTO passengers (full_name_passengers, phone_number, email, birth_date, gender) 
                                       VALUES ('$full_name', '$phone_number', '$email', '$birth_date', '$gender')";
            mysqli_query($koneksi, $query_insert_passenger);
            $id_passenger = mysqli_insert_id($koneksi);

            $query_insert_order = "INSERT INTO orders (id_passenger, id_schedule, date_order) 
                                   VALUES ($id_passenger, $id_schedule, NOW())";
            mysqli_query($koneksi, $query_insert_order);
            $id_order = mysqli_insert_id($koneksi);

            $query_insert_payment = "INSERT INTO payments (id_order, payment_status, payment_method) 
                                     VALUES ($id_order, 'Menunggu Konfirmasi', '$payment_method')";
            mysqli_query($koneksi, $query_insert_payment);
            $id_payment = mysqli_insert_id($koneksi);

            $query_insert_ticket = "INSERT INTO tickets (id_payment) VALUES ($id_payment)";
            mysqli_query($koneksi, $query_insert_ticket);

            // Simpan kursi yang dipilih
            $query_update_seat = "UPDATE seats SET status = 'dipesan' WHERE id_seat = $selected_seat";
            mysqli_query($koneksi, $query_update_seat);

            // Redirect ke halaman pembayaran dengan membawa ID Order
            header("Location: ../pembayaran/pembayaran.php?id_order=$id_order");
            exit();
        } else {
            echo "<script>alert('Mohon isi semua kolom data pribadi.');</script>";
        }
    }
} else {
    // Tambahkan logika atau tindakan yang sesuai jika 'id_schedule' tidak ada
    echo "ID Schedule tidak ditemukan.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Tiket</title>
    <link rel="stylesheet" type="text/css" href="pesan.css">
</head>
<body>
    <div id="pesan-container">
        <h1 class="pesanPageTitle">Pesan Tiket</h1>
        <div class="pesan-content">
            <!-- Bagian Data Pribadi -->
            <div class="section-container">
                <h2 class="section-title">Data Pribadi</h2>
                <form class='personForm' action="" method="post">
                    <div class="label-container">
                        <label for="full_name_passengers">Nama Lengkap:</label>
                        <input class="inputan" type="text" name="full_name_passengers" id="full_name_passengers" placeholder='Nama Lengkap'/>
                    </div>
                    <div class="label-container">
                        <label for="email">Email:</label>
                        <input class="inputan" type="text" name="email" id="email" placeholder='Email'/>
                    </div>
                    <div class="label-container">
                        <label for="phone_number">No. Handphone:</label>
                        <input class="inputan" type="text" name="phone_number" id="phone_number" placeholder='No. Handphone'/>
                    </div>

                    <!-- Tambahkan input untuk tanggal lahir -->
                    <div class="label-container">
                        <label for="birth_date">Tanggal Lahir:</label>
                        <input type="date" name="birth_date" id="birth_date" />
                    </div>

                    <!-- Tambahkan input untuk jenis kelamin -->
                    <div class="label-container">
                        <label>Jenis Kelamin:</label>
                        <div class="gender-buttons">
                            <div class="gender-buttons-laki-laki">
                                <input type="radio" name="gender" value="Laki-laki" id="gender_l" />
                                <label for="gender_l">Laki-laki</label>
                            </div>
                            <div class="gender-buttons-laki-laki">
                                <input type="radio" name="gender" value="Perempuan" id="gender_p" />
                                <label for="gender_p">Perempuan</label>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Memilih Kursi -->
                    <div class="section-container">
                        <h2 class="section-title">Pilih Kursi</h2>
                        <ul class="seat-list">
                            <?php while ($row_kursi = mysqli_fetch_assoc($result_kursi)) : ?>
                                <li>
                                    <input type="radio" name="kursi" value="<?php echo $row_kursi['id_seat']; ?>" <?php echo ($row_kursi['status'] == 'tersedia') ? 'checked' : 'disabled'; ?>>
                                    <?php echo $row_kursi['number_seat']; ?> (<?php echo $row_kursi['status']; ?>)
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>

                    <div class="section-container">
                        <h2 class="section-title">Metode Pembayaran</h2>
                        <div class="label-container">
                            <label for="label-title">Pilih Metode Pembayaran</label>
                            <select name="payment_method" id="payment_method">
                                <option value="BCA">BCA</option>
                                <option value="BRI">BRI</option>
                                <option value="MANDIRI">Mandiri</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bagian Total Bayar -->
                    <div class="section-container">
                        <h2 class="section-title">Total Bayar</h2>
                        <p>Harga Tiket: <?php echo $schedule['ticket_price']; ?></p>
                        <button type='submit' name='submitBtn' class="submitBtn">Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
