<?php
include "../../php/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form submitted, process the request
    if (isset($_POST['updatePaymentBtn'])) {
        $id_order = $_POST['id_order'];
        $new_payment_status = $_POST['new_payment_status'];

        // Update status pembayaran
        $query_update_payment = "UPDATE payments
                                SET payment_status = '$new_payment_status'
                                WHERE id_order = $id_order";

        mysqli_query($koneksi, $query_update_payment);

        echo "<script>alert('Status pembayaran berhasil diperbarui.');</script>";
    }

    // Query untuk mendapatkan data pembayaran berdasarkan ID Order
    if (isset($_POST['searchOrderBtn'])) {
        $id_order_search = $_POST['id_order_search'];

        // Query mencari nama penumpang
        $query_search_name = "SELECT passengers.full_name_passengers
                          FROM passengers
                          INNER JOIN orders ON passengers.id_passenger = orders.id_passenger
                          WHERE orders.id_order = $id_order_search";
                          
        $result_search_name = mysqli_query($koneksi, $query_search_name);

        $query_payment_search = "SELECT * FROM payments WHERE id_order = $id_order_search";
        $result_payment_search = mysqli_query($koneksi, $query_payment_search);
        $payment_search = mysqli_fetch_assoc($result_payment_search);

        if ($result_search_name) {
            $row = mysqli_fetch_assoc($result_search_name);
            $full_name_passengers_search = $row['full_name_passengers'];
        } else {
            // Handle ketika query tidak berhasil
            echo "Error in searching name: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>
    <div id="admin-container">
        <h1 class="adminPageTitle">Admin Page</h1>
        <div class="admin-content">
            <h2 class="section-title">Update Status Pembayaran</h2>
            <form method="post" action="">
                <!-- Form untuk mencari ID Order -->
                <label for="id_order_search">Cari ID Order:</label>
                <input type="text" id="id_order_search" name="id_order_search" required>
                <button type="submit" name="searchOrderBtn">Cari</button>

                <!-- Form untuk update status pembayaran -->
                <?php if (isset($payment_search)) { ?>
                    <p>Nama Penumpang: <?php echo $full_name_passengers_search; ?></p>

                    <label for="new_payment_status">Status Pembayaran Baru:</label>
                    <select id="new_payment_status" name="new_payment_status" required>
                        <option value="Menunggu Konfirmasi" <?php echo ($payment_search['payment_status'] == 'Menunggu Konfirmasi') ? 'selected' : ''; ?>>Menunggu Konfirmasi</option>
                        <option value="Lunas" <?php echo ($payment_search['payment_status'] == 'Lunas') ? 'selected' : ''; ?>>Lunas</option>
                        <option value="Ditolak" <?php echo ($payment_search['payment_status'] == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                    </select>

                    <input type="hidden" name="id_order" value="<?php echo $id_order_search; ?>">
                    <button type="submit" name="updatePaymentBtn">Update Status Pembayaran</button>
                <?php } ?>
            </form>
        </div>
    </div>
</body>
</html>
