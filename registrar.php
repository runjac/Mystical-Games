<?php
session_start();
require 'conexion/conex.php'; // Incluye la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dateOfBirth = $_POST['date'];

    // Manejo de la subida de la foto
    $foto = $_FILES['foto'];
    $fotoName = $foto['name'];
    $fotoTmpName = $foto['tmp_name'];
    $fotoSize = $foto['size'];
    $fotoError = $foto['error'];
    $fotoType = $foto['type'];

    $fotoExt = explode('.', $fotoName);
    $fotoActualExt = strtolower(end($fotoExt));
    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fotoActualExt, $allowed)) {
        if ($fotoError === 0) {
            if ($fotoSize < 5000000) {
                $fotoNewName = uniqid('', true) . "." . $fotoActualExt;
                $fotoDestination = 'assets/usuarios/' . $fotoNewName;
                move_uploaded_file($fotoTmpName, $fotoDestination);

                if (!empty($username) && !empty($email) && !empty($password) && !empty($dateOfBirth)) {
                    // Verifica si el correo electrónico ya está registrado
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email");
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    $count = $stmt->fetchColumn();

                    if ($count == 0) {
                        // Hashea la contraseña
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                        // Inserta el nuevo usuario en la base de datos
                        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, email, password, fecha_nacimiento, foto) VALUES (:username, :email, :password, :date_of_birth, :foto)");
                        $stmt->bindParam(':username', $username);
                        $stmt->bindParam(':email', $email);
                        $stmt->bindParam(':password', $hashedPassword);
                        $stmt->bindParam(':date_of_birth', $dateOfBirth);
                        $stmt->bindParam(':foto', $fotoNewName);

                        if ($stmt->execute()) {
                            // Registro exitoso, redirigir al usuario a la página de inicio de sesión
                            header("Location: login.php");
                            exit();
                        } else {
                            $error_message = "Error al registrar el usuario. Por favor, intente de nuevo.";
                        }
                    } else {
                        $error_message = "El correo electrónico ya está registrado.";
                    }
                } else {
                    $error_message = "Por favor, complete todos los campos.";
                }
            } else {
                $error_message = "El archivo de la foto es demasiado grande.";
            }
        } else {
            $error_message = "Hubo un error al subir la foto.";
        }
    } else {
        $error_message = "No puedes subir archivos de este tipo.";
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
            <div class="login-forms d-flex align-items-center justify-content-center">
                <div class="w-50">
                    <div class="w-100 mx-auto logo-brand">
                        <a class="d-flex align-items-center justify-content-center" href="index.php">
                            <img src="assets/logo-removebg-preview.png" alt="" style="width:20%;">
                            <span class="fmerriweather yclaro fw-bold">Mystical</span>
                            <span class="fblack aclaro">Games</span>
                        </a>
                    </div>
                    <h2 class="text-center text-light fw-medium fs-5 fmerriweather">Regístrate para una gran aventura</h2>
                    <hr class="text-light w-50 mx-auto">
                    
                    <!-- Mostrar mensaje de error si existe -->
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <form action="registrar.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 datos">
                            <input type="text" name="username" class="form-control p-3" id="username" placeholder="Usuario" required>
                        </div>
                        <div class="mb-3 datos">
                            <input type="email" name="email" class="form-control p-3" id="email" placeholder="Correo" required>
                        </div>
                        <div class="mb-3 datos">
                            <input type="password" name="password" class="form-control p-3" id="password" placeholder="***********" required>
                        </div>
                        <div class="mb-3 datos">
                        <label for="foto" class="text-light">Fecha Nacimiento</label>
                            <input type="date" name="date" class="form-control p-3" id="date" placeholder="Fecha de nacimiento" required>
                        </div>
                        <div class="mb-3 datos">
                            <label for="foto" class="text-light">Foto</label>
                            <input type="file" name="foto" class="form-control p-3" id="foto" accept="image/*" required>
                        </div>
                        <div class="d-grid pt-4">
                            <button type="submit" class="btn degradado2 fblack fw-bold btn-login">Registrar</button>
                        </div>
                    </form>

                    <div class="d-flex justify-content-center p-2" style="font-size:13px;">
                        <a href="login.php" class="text-light">Ya tengo cuenta</a>
                    </div>
                </div>
            </div>
            <div class="login-images"></div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>
