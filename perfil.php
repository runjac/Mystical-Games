
<?php
session_start(); // Asegúrate de iniciar la sesión
require 'conexion/conex.php';

if (isset($_SESSION['id_usuario'])) {
    // Obtener el id_usuario de la sesión
    $id_usuario = $_SESSION['id_usuario'];

    // Preparar la consulta SQL
    $query = "SELECT * FROM usuarios WHERE id_usuario = ?";
     // Preparar la declaración SQL
     $stmt = $pdo->prepare($query);

     // Bind the parameter
     $stmt->bindParam(1, $id_usuario, PDO::PARAM_INT);
 
     // Ejecutar la consulta
     $stmt->execute();
 
     // Obtener los resultados
     $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
else{
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mystical Games</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/detalles.css">
    <link rel="stylesheet" href="css/buscar.css">
    <link rel="stylesheet" href="css/perfil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<div class="contenedor bg-dark">
    <div class="position-relative" style="height: 200px;">  
        <!-- header -->
        <?php include('include/nav.php'); ?>
        <!-- header -->
        <div class="position-absolute top-0 start-0 w-100 z-0 perfil-background">
        <!-- imagen -->
        <!-- imagen -->
        </div>
    </div>
    <div class="container p-2 d-flex justify-content-center align-items-center flex-column">
        <h3 class="p-2 roboto-regular text-light text-center">Perfil</h3>
    
    <div class="hr-liena"></div>
        <div class="perfil-usuario">
            <img src="assets/usuarios/<?php echo $result['foto'] ?>" alt="">
            <span class="text-light roboto-regular"><?php echo $result['usuario'] ?></span>
        </div>
    </div>
   <!--footer-->
   <?php include('include/footer.php'); ?>
        <!--footer-->
    
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/carrito.js"></script>
</body>
</html>