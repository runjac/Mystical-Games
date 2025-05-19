<?php
session_start();

require_once 'config/Database.php';
require_once 'models/User.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize User model
$userModel = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        if ($userModel->login($email, $password)) {
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Correo o contraseña incorrectos.";
        }
    } else {
        $error_message = "Por favor, complete todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mystical Games</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid login-container bg-dark position-relative">
        <div class="login d-flex">
            <div class="login-form d-flex align-items-center justify-content-center">
                <div class="w-50">
                    <div class="w-100 mx-auto logo-brand">
                        <a class="d-flex align-items-center justify-content-center" href="index.php">
                            <img src="assets/logo-removebg-preview.png" alt="" style="width:20%;">
                            <span class="fmerriweather yclaro fw-bold">Mystical</span>
                            <span class="fblack aclaro">Games</span>
                        </a>
                    </div>
                    <h2 class="text-center text-light fw-medium fs-5 fmerriweather">Ingresa para ver novedades</h2>
                    <hr class="text-light w-50 mx-auto">
                    
                    <!-- Mostrar mensaje de error si existe -->
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <form action="login.php" method="POST">
                        <div class="mb-3 datos">
                            <input type="email" name="email" class="form-control p-3" id="email" placeholder="Correo" required>
                        </div>
                        <div class="mb-3 datos">
                            <input type="password" name="password" class="form-control p-3" id="password" placeholder="***********" required>
                        </div>
                        <div class="d-grid pt-4">
                            <button type="submit" class="btn degradado2 fblack fw-bold btn-login">Login</button>
                        </div>
                    </form>
                    <div class="d-flex justify-content-between p-2" style="font-size:13px;">
                        <a href="registrar.php" class="text-light">¿No tienes una cuenta?</a>
                        <a href="recuperar.php" class="text-light text-end">¿Has olvidado la contraseña?</a>
                    </div>
                </div>
            </div>
            <div class="login-image"></div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>