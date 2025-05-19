<?php
session_start(); // Asegúrate de iniciar la sesión

require 'conexion/conex.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $stmt = $pdo->prepare('SELECT
        games.game_id, 
        games.titulo, 
        games.descripcion, 
        games.fecha_estreno, 
        games.img, 
        games.precio, 
        games.stock, 
        games.portada, 
        GROUP_CONCAT(categorias.nombres SEPARATOR ", ") AS categorias
    FROM
        categorias
    INNER JOIN
        game_categorias
    ON 
        categorias.categorias_id = game_categorias.categorias_id
    INNER JOIN
        games
    ON 
        games.game_id = game_categorias.game_id
    WHERE 
        games.game_id = :id
    GROUP BY
        games.game_id');

$stmt->execute(['id' => $id]);
$row = $stmt->fetch();
}else{
    header('Location: index.php');
}

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
            <img src="assets/videojuegos/portada/<?php echo $row['portada'] ?>" class="img-fluid detalle-img" alt="...">
        <!-- imagen -->
        </div>
    </div>  
    <!-- detalles -->
    <div class="detalles z-1 gb-primary p-4">
        <span class="roboto-regular fs-2 text-light  text-center w-100"><?php echo $row['titulo'] ?></span>
        <div class="lineas"></div>
        <p id="word-container" class="text-light text-center roboto-light p-2 mb-2"><?php echo $row['categorias'] ?></p>
       
        <div class="row col-10 ">
            <div class="col-md-12 col-lg-4 detalles-img">
                <img src="<?php echo $row['img'] ?>" alt="">
            </div>
            <div class="col-md-12 col-lg-8 detalles-carrito">
                <!-- precio-->
                <div class="precio roboto-bold d-flex align-items-baseline flex-wrap">
                    $<?php echo $row['precio'] ?>
                    <span class="stock roboto-light ">stock: <?php echo $row['stock'] ?> unidades</span>
                </div> 
                <!-- precio-->
                <!-- estrellas-->
                <div class="puntaje d-flex p-2">
                    <i class="fa-solid fa-star yclaro "></i>
                    <i class="fa-solid fa-star yclaro"></i>
                    <i class="fa-solid fa-star yclaro"></i>
                    <i class="fa-solid fa-star yclaro"></i>
                    <i class="fa-solid fa-star text-light"></i>
                </div>
                <!-- estrellas-->
                <!-- descripcion-->
                <p class="roboto-light descripcion"><?php echo $row['descripcion'] ?></p>
                <!-- descripcion-->
                <!-- estreno-->
                <span class="text-light roboto-bold">Fecha de estreno</span>
                <p class="roboto-light " style="color:#868686;"><?php echo $row['fecha_estreno'] ?></p>
                <!-- estreno -->
                <!-- anadir a carrito -->
                <div class="carrito d-flex flex-wrap">
                    <div class="col-md-4 col-lg-4 col-12">
                        <span class="cantidad text-light roboto-bold">Cantidad</span>
                        <div class="number-container ">
                            <button id="decrement"><i class="fas fa-minus p-2"></i></button>
                            <div id="number-display" class="number-display">1</div>
                            <button id="increment"><i class="fas fa-plus p-2"></i></button>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-6 col-12 car-botones d-flex flex-column gap-2">
                        <button type="button" data-id="<?php echo $row['game_id'] ?>" class="degradado2 button-car-botones text-dark text-center roboto-regular add-carrito">Añadir a carrito</button>
                        <a href="checkout.php" class="degradado2 text-dark text-center roboto-regular">Comprar ahora</a>
                    </div>
                </div>
                <!-- anadir a carrito -->

                <p class="text-light roboto-light pt-3">Version de prueba</p>
                <a href="" class="btn vclaro">Descarga la Version de Prueba</a>

            </div>
        </div>
        
    </div>
    <!-- detalles -->
    <!-- caractristicas -->
    <div class="caracteristicas col-10 p-4">
        <nav>
            <div class="nav nav-tabs d-flex border-tabs justify-content-center text-light" id="nav-tab" role="tablist">
                <button class="nav-link  caract-tab  btclaro bg-gris" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Trailer</button>
                <button class="nav-link  caract-tab  btclaro bg-gris" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Comentarios</button>
                <button class="nav-link caract-tab  btclaro bg-gris" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Requisitos</button>
                <button class="nav-link caract-tab  btclaro bg-gris" id="nav-tutoriales-tab" data-bs-toggle="tab" data-bs-target="#nav-tutoriales" type="button" role="tab" aria-controls="nav-tutoriales" aria-selected="false">Tutoriales</button>
                <button class="nav-link caract-tab  btclaro bg-gris" id="nav-especificaciones-tab" data-bs-toggle="tab" data-bs-target="#nav-especificaciones" type="button" role="tab" aria-controls="nav-especificaciones" aria-selected="false">Especificaciones</button>
            </div>
        </nav>
        <div class="tab-content text-light mb-4" id="nav-tabContent">

                <div class="tab-pane fade show " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                    <div class="video-youtube w-100 d-flex justify-content-center item-align-center p-4 ">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/O75Ip4o1bs8?si=kPEsDg647QXxjAtw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                    <div class="comentarios">
                        <div class="img-comentarios d-flex col-12 justify-content-center">
                            <img src="https://picsum.photos/200/300" alt="comentarios">
                        </div>
                        <div class="comentarios-text ">
                            <div class="comentarios-autor roboto-bold">
                                Teckaby
                            </div>
                            <div class="comentario-puntaje">
                                <i class="fa-solid fa-star yclaro "></i>
                                <i class="fa-solid fa-star yclaro"></i>
                                <i class="fa-solid fa-star yclaro"></i>
                                <i class="fa-solid fa-star yclaro"></i>
                                <i class="fa-solid fa-star text-light"></i>
                            </div>
                            <div class="comentario-text roboto-light ">
                                Fueron muy rápidos en entregarme mi código solo pague en oxxo y en cuestión de minutos me los mandaron. 100% recomendable si eres fan de los videojuegos
                            </div>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['id_usuario'])): ?>
                        <div class="comentar">
                        <div class="comentar-input">
                            <div class="comentar-img d-flex justify-content-center w-100">
                              <img src="https://picsum.photos/200/300" alt="comentar">
                              <input type="text" class="form-control p-3 comentar-text" id="comentar-input" placeholder="Que te parecio el juego">
                           </div>
                           
                            <div class="puntajes d-flex justify-content-center align-items-center">
                                <span class="roboto-light">Marca tu Puntaje: </span>
                                <div class="rating  p-2">
                                    <i class="fas fa-star star" data-value="1"></i>
                                    <i class="fas fa-star star" data-value="2"></i>
                                    <i class="fas fa-star star" data-value="3"></i>
                                    <i class="fas fa-star star" data-value="4"></i>
                                    <i class="fas fa-star star" data-value="5"></i>
                                </div>
                                <input type="hidden" id="rating-value" name="rating-value" value="0">
                            </div>
                            <input type="submit" value="Comentar" class="btn btn-primary roboto-light p-2">
                        </div>
                        
                    </div>
                        <?php endif; ?>
                   
                    

                </div>

                <div class="tab-pane fade p-3 m-3" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab" tabindex="0">
                  <div class="group-text d-flex flex-wrap">
                     <span class="roboto-bold col-md-6 col-sm-12 col-12"> OS:</span>
                     <p class="roboto-light col-md-6 col-sm-12 col-12">Windows (64-bit) 10</p>
                  </div>
                  <div class="group-text d-flex  flex-wrap">
                    <span class="roboto-bold col-md-6 col-sm-12 col-12">Procesador: </span>
                    <p class="roboto-light col-md-6 col-sm-12 col-12">>i5 3550 / RYZEN 5 2500X</p>
                  </div>
                  <div class="group-text d-flex  flex-wrap">
                    <span class="roboto-bold col-md-6 col-sm-12 col-12">Memory: </span>
                    <p class="roboto-light col-md-6 col-sm-12 col-12">>4 GB RAM</p>
                  </div>
                  <div class="group-text d-flex flex-wrap">
                     <span class="roboto-bold col-md-6 col-sm-12 col-12">Graphics: </span>
                     <p class="roboto-light col-md-6 col-sm-12 col-12">NVIDIA GTX 1050 / AMD R9 270X</p>
                  </div>
                  <div class="group-text d-flex flex-wrap">
                     <span class="roboto-bold col-md-6 col-sm-12 col-12">DirectX: </span>
                     <p class="roboto-light col-md-6 col-sm-12 col-12">Version 11</p>
                  </div>
                  <div class="group-text d-flex  flex-wrap">
                     <span class="roboto-bold col-md-6 col-sm-12 col-12">Tamaño:</span>
                     <p class="roboto-light col-md-6 col-sm-12 col-12">5gb</p>
                  </div>    
                          
                </div>
                
                <div class="tab-pane fade" id="nav-tutoriales" role="tabpanel" aria-labelledby="nav-tutoriales-tab" tabindex="0">
                    <div class="video-youtube w-100 d-flex justify-content-center item-align-center p-4 ">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/bOg3fICqzFA?si=DBODvdxi8MXk4khY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>

                <div class="tab-pane fade p-3 m-3" id="nav-especificaciones" role="tabpanel" aria-labelledby="nav-especificaciones-tab" tabindex="0">
                  <div class="group-text d-flex flex-wrap">
                     <span class="roboto-bold col-md-6 col-sm-12 col-12"> Saga:</span>
                     <p class="roboto-light col-md-6 col-sm-12 col-12">Revidencial evil</p>
                  </div>
                  <div class="group-text d-flex  flex-wrap">
                    <span class="roboto-bold col-md-6 col-sm-12 col-12">Idioma: </span>
                    <p class="roboto-light col-md-6 col-sm-12 col-12">Español</p>
                  </div>
                  <div class="group-text d-flex  flex-wrap">
                    <span class="roboto-bold col-md-6 col-sm-12 col-12">Desarrolladores: </span>
                    <p class="roboto-light col-md-6 col-sm-12 col-12">Avalanche Software</p>
                  </div>
                  <div class="group-text d-flex flex-wrap">
                     <span class="roboto-bold col-md-6 col-sm-12 col-12">Plataformas: </span>
                     <p class="roboto-light col-md-6 col-sm-12 col-12">Nintendo Switch</p>
                  </div>
                  <div class="group-text d-flex flex-wrap">
                     <span class="roboto-bold col-md-6 col-sm-12 col-12">Clasificación: </span>
                     <p class="roboto-light col-md-6 col-sm-12 col-12"> ESRB M Maduros 17+</p>
                  </div>
                  <div class="group-text d-flex  flex-wrap">
                     <span class="roboto-bold col-md-6 col-sm-12 col-12">Publisher o Editor:</span>
                     <p class="roboto-light col-md-6 col-sm-12 col-12">Capcom</p>
                  </div>    
                          
                </div>

        </div>
       
    </div>
    <!-- caractristicas -->
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
<script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const decrementButton = document.getElementById('decrement');
            const incrementButton = document.getElementById('increment');
            const numberDisplay = document.getElementById('number-display');

            let currentValue = 0;
            const minValue = 1;
            const maxValue = 100;

            decrementButton.addEventListener('click', () => {
                if (currentValue > minValue) {
                    currentValue--;
                    numberDisplay.textContent = currentValue;
                }
            });

            incrementButton.addEventListener('click', () => {
                if (currentValue < maxValue) {
                    currentValue++;
                    numberDisplay.textContent = currentValue;
                }
            });
        });
    </script>

<script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const wordContainer = document.getElementById('word-container');
            const words = wordContainer.textContent.split(',');
            wordContainer.innerHTML = ''; // Clear the original content

            words.forEach(word => {
                const span = document.createElement('span');
                span.className = 'word';
                span.textContent = word.trim();
                wordContainer.appendChild(span);
            });
        });
    </script>

<script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const stars = document.querySelectorAll('.star');
            const ratingValue = document.getElementById('rating-value');

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const value = star.getAttribute('data-value');
                    ratingValue.value = value;
                    stars.forEach((s, index) => {
                        if (index < value) {
                            s.classList.add('checked');
                        } else {
                            s.classList.remove('checked');
                        }
                    });
                });
            });
        });
    </script><script>
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.querySelectorAll('.carousel .carousel-item');

            items.forEach((el) => {
                const minPerSlide = 5;
                let next = el.nextElementSibling;
                for (let i = 1; i < minPerSlide; i++) {
                    if (!next) {
                        next = items[0];
                    }
                    let cloneChild = next.cloneNode(true);
                    el.appendChild(cloneChild.children[0]);
                    next = next.nextElementSibling;
                }
            });
        });
    </script>

</body>
</html>
