<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Register - Altaayirat</title>

</head>
<body>
    <div class="register-container">
    <?php 
         
         include("../../php/koneksi.php");
         //menyimpan data yang diinput ke variabel
         if(isset($_POST['submit'])){
            $full_name = $_POST['full_name'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

         //cek apakah email sudah digunakan sebelumnya
         $verifikasi_email = mysqli_query($koneksi,
            "SELECT email FROM users WHERE email='$email'"
        );

         if(mysqli_num_rows($verifikasi_email) !=0 ){
            echo "<div class='message'>
                      <p>Email sudah digunakan.</p>
                  </div> <br>";
            echo "<a href='javascript:self.history.back()'><button class='btn'>Kembali</button>";
         }
         else{
            mysqli_query($koneksi,
                "INSERT INTO users(full_name,username,email,password) VALUES('$full_name','$username','$email','$password')"
            ) or die("Error!");

            echo "<div class='message'>
                      <p>Berhasil Registrasi!</p>
                  </div> <br>";
            echo "<a href='../login/login.php'><button class='btn'>Login Sekarang</button>";
         }
         }else{
        ?>
        <h2>Register - Altaayirat</h2>
        <form id="registerForm" action="#" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="fullname" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" class="password-input" required>
                    <span class="password-toggle" onclick="togglePassword(this)"><i class="fa fa-eye-slash"></i></span>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" name="submit" onclick="submitForm()">
            </div>
        </form>
        <a class="login-link" href="../login/login.html">Already have an account? Login here</a>
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
