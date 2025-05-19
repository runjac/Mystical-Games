<?php
session_start(); // Inicia la sesión
require 'conexion/conex.php'; // Incluye la conexión a la base de datos

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['email'])) {
        $email = $_POST['email'];

        // Verificar si el correo existe en la base de datos de usuarios
        $query = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        $result = $query->fetch();

        if ($result) {
            $userId = $result['id_usuario'];

            // Generar un código de 6 dígitos
            $recoveryCode = rand(100000, 999999);

            // Insertar el código en la base de datos
            $stmt = $pdo->prepare("INSERT INTO recuperar (codigo, id_usuario) VALUES (:codigo, :id_usuario)");
            $stmt->bindParam(':codigo', $recoveryCode);
            $stmt->bindParam(':id_usuario', $userId);
            $stmt->execute();

            // Enviar el código al correo electrónico del usuario
            try {
                $mail = new PHPMailer(true);
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'jaccespedes295@gmail.com';
                $mail->Password = 'acuario295';
                $mail->SMTPSecure ='ssl';
                $mail->Port = 465;
                $mail->setFrom('jaccespedes295@gmail.com', 'MYstical games');
                $mail->addAddress($email, $email);
                $mail->isHTML(true);
                $mail->Subject = 'Recuperar contraseña';
                $mail->Body = 'Su código de recuperación es: ' . $recoveryCode;

                $mail->send();
                $success_message = "El código de recuperación ha sido enviado a tu correo electrónico.";
            } catch (Exception $e) {
                $error_message = "Hubo un error al enviar el correo. Por favor, intenta de nuevo. Error: {$mail->ErrorInfo}";
            }
        } else {
            $error_message = "No se encontró una cuenta con ese correo electrónico.";
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
                    <h2 class="text-center text-light fw-medium fs-5 fmerriweather">Recuperar Contraseña</h2>
                    <hr class="text-light w-50 mx-auto">
                    
                    <!-- Mostrar mensaje de error si existe -->
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Mostrar mensaje de éxito si existe -->
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3 datos">
                            <input type="email" name="email" class="form-control p-3" id="email" placeholder="Correo" required>
                        </div>
                        <div class="d-grid pt-4">
                            <button type="submit" class="btn degradado2 fblack fw-bold btn-login">Recuperar Contraseña</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="login-image"></div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>
