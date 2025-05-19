<?php 
session_start(); // Asegúrate de iniciar la sesión

require 'conexion/conex.php';
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$importe = 0;
$total = 0;

// Consultas SQL...
$query = "SELECT carrusel_id, img, titulo, subtitulo FROM carrusel";
$stmt = $pdo->query($query);
$carrusel_items = $stmt->fetchAll();
$sqlgame = "
SELECT
	consolas.nombres, 
    games.game_id,
	games.titulo, 
	games.img, 
	games.fecha_estreno, 
	games.precio
FROM
	games
	INNER JOIN
	consolas
	ON 
		games.console_id = consolas.consola_id
";

try {
    $stmti = $pdo->query($sqlgame);
    $gamer = $stmti->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
    <link rel="stylesheet" href="css/carrito.css">
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
        <div class="position-absolute top-0 start-0 w-100 z-0">
        <!-- imagen -->
            <img src="assets/detalles/leon-resident-evil-4-remake_7680x4320_xtrafondos.com.jpg" class="img-fluid detalle-img" alt="...">
        <!-- imagen -->
        </div>
    </div>  
    <div class="carrito-productos">
        <div class="col-md-12 col-lg-8 col-12 p-3 mb-2" style="background-color: #181B1E;border-radius: 20px;">
         <!-- productos comprados -->
         <?php foreach($carrito as $item): ?>
        <?php

        $importe = $item['precio'] * $item['cantidad'];
        $total += $importe;
        ?>
            <div class="carrito-cesta">
                <!-- imagen -->
                    <div class="car-prod-img">
                        <img src="<?php echo $item['img'] ?>">
                    </div>

                   
                    <div class="car-prod-text d-flex justify-content-center flex-column">
                        <h3 class=" roboto-bold"><?php echo $item['titulo'] ?></h3>
                        <div class="car-prod-eliminar">
                            <a href="javascript:void(0)" class="delete-carrito" data-id="<?php echo $item['id'] ?>">
                                <i class="fa-solid fa-trash-can text-light hoveri"></i>
                            </a>
                        </div>
                    </div>
                    <div class="carrito-precio">
                        <p class="text-light roboto-regular">$ <?php echo $importe ?></p>
                        <select class="car-select update-carrito" data-id="<?php echo $item['id'] ?>">
                            <option value="<?php echo $item['cantidad'] ?>" selected><?php echo $item['cantidad'] ?></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>   
            </div>
            <div class="hr-carrito"></div>
        <?php endforeach; ?>
            <!-- productos comprados -->

        </div>

        <?php 

        $igv = $total * 0.18;
        $subtotal = $total - $igv; 

        ?>
        <div class="carrito-pagar col-md-12 col-lg-3 col-12 p-3 text-light">
            <!-- total -->
               <div class="subtotal-pagar roboto-light ">
                    <h3 class="text-light roboto-light fs-6">Subtotal:</h3>
                    $ <?php echo $subtotal ?>
               </div>
               <div class="igv">
                 <h3 class="text-light roboto-light fs-6">IGV (18%):</h3>
                    $ <?php echo $igv ?>
               </div>
               <div class="total roboto-bold text-light">
                    <h3 class="text-light roboto-bold">Total:</h3>
                    $ <?php echo $total ?>
               </div>
               <a href="comprar.php" class="p-2  roboto-bold w-100 pagar-car">
                    Proceder con el pago
               </a>  
            <!-- total -->
        </div>
       
        
    </div>

    <div class="recomendaciones d-flex justify-content-center flex-column">
        <span class="roboto-medium text-light">JUEGOS RECOMENDADOS</span>
        <hr class="text-light gb-light">
        <div class="owl-carousel d-flex -justify-content-center">
                <?php if (!empty($gamer)): ?>
                    <?php foreach ($gamer as $juegos): ?>
                        
                            <div class="card-estrenos">
                                <div class="card-img w-100">
                                    <img src="<?php echo htmlspecialchars($juegos['img']); ?>" alt="<?php echo htmlspecialchars($juegos['titulo']); ?>" class="w-100">
                                </div>
                                <div class="p-1 fs-6 estrenos-title text-center fw-semibold text-truncate roboto-black">
                                         <a href="detalles.php?id=<?php echo $juegos['game_id'] ?>">
                                            <?php echo htmlspecialchars($juegos['titulo']); ?>
                                        </a>
                                </div>
                                <div class="estrenos-precio text-center aclaro fw-bolder">
                                    $<?php echo htmlspecialchars($juegos['precio']); ?>
                                </div>
                                <a href="<?php echo isset($_SESSION['user_id']) ? 'game_details.php?game_id=' . htmlspecialchars($juegos['game_id']) : 'login.php'; ?>" class="btn btn-none degradado2 w-100 text-light fblack">Comprar</a>
                            </div>
                        
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay juegos disponibles.</p>
                <?php endif; ?>
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
<script>
        $(document).ready(function(){
                $(".owl-carousel").owlCarousel({
                    loop: true,
                    margin: 10,
                    nav: true,
                    navText: ['<i class="fas fa-caret-left"></i>', '<i class="fas fa-caret-right"></i>'], // Iconos de FontAwesome
                    autoplay: true, // Activar autoplay
                    autoplayTimeout: 3000, // Tiempo de espera entre diapositivas en milisegundos
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 2
                        },
                        800: {
                            items: 3
                        },
                        1000: {
                            items: 4
                        },
                        1500: {
                            items: 6
                        }
                    }
                });
        });
</script>
</body>
</html>