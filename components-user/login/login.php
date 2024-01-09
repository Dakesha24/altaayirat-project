<?php 
//memulai sesi
   session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Altaayirat</title>
    <link rel="stylesheet" href="login.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="login-container">
        <?php
            include("../../php/koneksi.php");
            if(isset($_POST['submit'])){
                //Mengambil data dari form
                $username = mysqli_real_escape_string($koneksi,$_POST['username']);
                $password = mysqli_real_escape_string($koneksi,$_POST['password']);

                //Melakukan query untuk memeriksa kombinasi email dan password
                $result = mysqli_query($koneksi,
                    "SELECT * FROM users WHERE username='$username' AND password='$password' ") or die("Select Error");
                $row = mysqli_fetch_assoc($result);

                //Jika data ditemukan, simpan informasi pengguna dalam sesi
                if(is_array($row) && !empty($row)){
                    $_SESSION['valid'] = $row['username'];
                    $_SESSION['full_name'] = $row['full_name'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['password'] = $row['password'];
                    $_SESSION['id_user'] = $row['id_user'];

                //Jika data tidak ditemukan, tampilkan pesan kesalahan
                }else{
                    echo "<div class='message'>
                      <p>Maaf, Username atau Password salah</p>
                       </div> <br>";
                   echo "<a href='login.php'><button class='btn'>Login Lagi</button>";
                
                //Jika sesi 'valid' sudah diset, arahkan pengguna ke halaman home
                }
                if(isset($_SESSION['valid'])){
                    header("Location: ../home/home.php");
                }
            }else{
        ?>
            <h2>Login - Altaayirat</h2>
            <form id="loginForm" action="" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" class="password-input" required>
                        <span class="password-toggle" onclick="togglePassword(this)"><i class="fa fa-eye-slash"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="login" class="btn">
                </div>
            </form>
            <a class="register-link" href="../register/register.php">Create an account</a>
            
        </div>

        <script>
            function togglePassword(element) {
                const passwordInput = document.getElementById('password');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    element.innerHTML = '<i class="fa fa-eye"></i>';
                } else {
                    passwordInput.type = 'password';
                    element.innerHTML = '<i class="fa fa-eye-slash"></i>';
                }
            }
        </script>
        <?php } ?>
    </body>
</html>