<?php
session_start();

require_once 'config/Database.php';
require_once 'models/Game.php';
require_once 'models/Cart.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize Game model
$gameModel = new Game($db);

// Get carousel items
$query = "SELECT carrusel_id, img, titulo, subtitulo FROM carrusel";
$stmt = $db->query($query);
$carrusel_items = $stmt->fetchAll();

// Get featured games
$games = $gameModel->getAllGames();

// Get all games
$gamer = $gameModel->getAllGames();

// Get comments
$sqlcomentarios="
SELECT
    comentarios.id_comentario, 
    comentarios.descripcion, 
    comentarios.fecha, 
    comentarios.titulo, 
    comentarios.id_usuario, 
    comentarios.puntaje, 
    usuarios.usuario, 
    usuarios.foto
FROM
    comentarios
    INNER JOIN
    usuarios
    ON 
        comentarios.id_usuario = usuarios.id_usuario
";

try {
    $stmtc = $db->query($sqlcomentarios);
    $comentarios = $stmtc->fetchAll();
} catch(PDOException $e) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="contenedor bg-dark">
        <!-- header -->
        <div class="position-relative" style="height: 600px;">
            <!-- navbar -->
            <?php include('include/nav.php'); ?>
            <!--navbar-->
            <!-- carrusel -->
            <div class="position-absolute top-0 start-0 w-100 z-0">
                <div id="carouselExampleCaptions" class="carousel slide">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <?php foreach ($carrusel_items as $index => $item): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <img src="<?php echo htmlspecialchars($item['img']); ?>" class="d-block w-100 slider-img" alt="<?php echo htmlspecialchars($item['titulo']); ?>">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5><?php echo htmlspecialchars($item['titulo']); ?></h5>
                                    <p><?php echo htmlspecialchars($item['subtitulo']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <!-- carrusel -->
        </div>
        <!-- header -->

        <div class="seguridad p-3 rounded-3 degradado2"> 
            <div class="d-flex justify-content-center align-items-center">
                <img src="assets/recursos/1692393188_caja.webp" alt="" width="20%">
                <div class="group-text p-2">
                    <span class="roboto-black">ENTREGAS SEGURAS</span>
                    <p>ventas seguras</p>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <img src="assets/recursos/1692393179_robot.webp" alt="" width="20%">
                <div class="group-text p-2">
                    <span class="roboto-black">CONSULTAS EN VIVO</span>
                    <p>Soporte Tecnico</p>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <img src="assets/recursos/1692393195_check.webp" alt="" width="20%">
                <div class="group-text p-2">
                    <span class="roboto-black">GARANTIA TOTAL</span>
                    <p>Certificados</p>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <img src="assets/recursos/1692393203_mando.webp" alt="" width="20%">
                <div class="group-text p-2">
                    <span class="roboto-black">JUEGOS ORIGINALES</span>
                    <p>100% Certificados</p>
                </div>
            </div>
        </div>
        <!-- Estrenos -->
        <div class="estrenos">
            <span class="text-light fs-4 roboto-regular d-flex justify-content-center align-items-center">
                <i class="fa-solid fa-film p-2"></i>ESTRENOS  
            </span>
            <div class="linea"></div>
               
                 <div class="owl-carousel d-flex -justify-content-center">
                    <?php if (isset($games) && !empty($games)): ?>
                        <?php foreach ($games as $game): ?>
                        
                                <div class="card-estrenos">
                                    <div class="card-img w-100">
                                        <img src="<?php echo htmlspecialchars($game['img']); ?>" alt="<?php echo htmlspecialchars($game['titulo']); ?>" class="w-100">
                                    </div>
                                    <div class="p-2 fs-6 estrenos-title text-left roboto-regular text-truncate ">
                                        <a href="detalles.php?id=<?php echo $game['game_id'] ?>">
                                            <?php echo htmlspecialchars($game['titulo']); ?>
                                        </a>
                                    </div>
                                    <div class="estrenos-precio text-center aclaro fw-bolder">
                                        $<?php echo htmlspecialchars($game['precio']); ?>
                                    </div>
                                    <button type="button" class="btn btn-none degradado2 w-100 text-light fblack add-carrito" data-id="<?php echo $game['game_id'] ?>">Añadir al carrito</button>
                                </div>
                        
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No hay juegos disponibles.</p>
                            <?php endif; ?>
                    </div>
                
            <div class="ver-mas w-100 d-flex justify-content-center">
                <a href="buscar.php" class="btn degradado1 text-light fblack" style="font-size: 13px;">VER MAS</a>
            </div>
        </div>
        <!-- Estrenos -->
        <!-- Barra -->
        <div class="barra">
            <div class="rounded-3 p-4 degradado1 barra d-flex justify-content-center align-items-center">
                <img src="assets/barra/1713908729_ps4-removebg-preview.png" alt="">
                <span class="roboto-light fs-4 text-light">Variedad de Juegos</span>
            </div>   
            <div class="rounded-3 pd-4 degradado2 barra d-flex justify-content-center align-items-center">
                <span class="roboto-light fs-4 text-light">Comunidades agradables</span>
                <img src="assets/barra/Super_Mario_Bros-removebg-preview.png" alt="">
            </div>
        </div>
        <!-- barra -->
        <!-- Variedad -->
        <div class="estrenos">
            <span class="text-light fs-4 roboto-regular d-flex justify-content-center align-items-center">
            <i class="fa-solid fa-clapperboard p-2"></i> </i>VARIEDAD  
            </span>
            <div class="linea"></div>
            <div class="estrenos-juegos">
                <?php if (!empty($gamer)): ?>
                    <?php foreach ($gamer as $juegos): ?>
                        
                            <div class="card-estrenos">
                                <div class="card-img w-100">
                                    <img src="<?php echo htmlspecialchars($juegos['img']); ?>" alt="<?php echo htmlspecialchars($juegos['titulo']); ?>" class="w-100">
                                </div>
                                <div class="p-2 fs-6 estrenos-title text-left  text-truncate roboto-regular">
                                        <a href="detalles.php?id=<?php echo $juegos['game_id'] ?>">
                                            <?php echo htmlspecialchars($juegos['titulo']); ?>
                                        </a>
                                </div>
                                <div class="estrenos-precio text-center aclaro fw-bolder">
                                    $<?php echo htmlspecialchars($juegos['precio']); ?>
                                </div>
                                <button type="button" class=" btn btn-none degradado2 w-100 text-light fblack add-carrito" data-id="<?php echo $juegos['game_id'] ?>">Añadir al carrito</button>
                            </div>
                       
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay juegos disponibles.</p>
                <?php endif; ?>
            </div>
            <div class="ver-mas w-100 d-flex justify-content-center">
                <a href="buscar.php" class="btn degradado1 text-light fblack" style="font-size: 13px;">VER MAS</a>
            </div>
        </div>
        <!--variedad-->

        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                <img src="assets/recursos/Banner1.webp" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                <img src="assets/recursos/Banner2.webp" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                <img src="assets/recursos/Banner3.webp" class="d-block w-100" alt="...">
                </div>
            </div>
        </div>
        <div class="comentarios-usuarios p-5">
            <span class="text-light fs-4 roboto-regular d-flex justify-content-center align-items-center">
                <i class="fa-solid fa-comment text-light p-2"> </i>Nuestos clientes opinan  
            </span>
            <div class="linea"></div>
            <div class="group-comentarios">
                <?php if(!empty($comentarios)): ?>
                    <?php foreach ($comentarios as $comentario): ?>
                        <div class="comentario-user">
                            <div class="comentario-img-text">
                                <div class="comentario-img">
                                    <img src="assets/usuarios/<?php echo $comentario['foto']; ?>">
                                </div>
                                <div class="comentario-datos">
                                    <div class="comentario-puntaje">
                                    <div class="rating p-2">
                                        <?php
                                        $puntaje = $comentario['puntaje'];
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $puntaje) {
                                                echo '<i class="fas fa-star star text-warning" data-value="'.$i.'"></i>';
                                            } else {
                                                echo '<i class="fas fa-star star text-secondary" data-value="'.$i.'"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    </div>
                                    <div class="comentario-titulo text-light roboto-regular">
                                        <?php echo htmlspecialchars($comentario['titulo']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="comentario-descripcio p-2 roboto-light">
                                        <?php echo htmlspecialchars($comentario['descripcion']); ?>
                            </div>
                            <div class="comentario-fecha-hora text-light p-2 roboto-medium">
                                        <?php echo htmlspecialchars($comentario['fecha']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else:?>
                    <p>No hay comentarios disponibles</p>
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