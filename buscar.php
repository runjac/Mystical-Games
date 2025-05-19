<?php
session_start(); // Asegúrate de iniciar la sesión

require 'conexion/conex.php';

// Obtener las categorías de la base de datos
$sqlCategorias = "SELECT categorias.nombres, categorias.categorias_id FROM categorias";

try {
    $stmtCat = $pdo->query($sqlCategorias);
    $categorias = $stmtCat->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Obtener los parámetros de la URL
$console = isset($_GET['console']) ? $_GET['console'] : '';
$titulo = isset($_GET['titulo']) ? $_GET['titulo'] : '';
$plataforma = isset($_GET['plataforma']) ? $_GET['plataforma'] : '';
$genero = isset($_GET['genero']) ? $_GET['genero'] : '';
$ordenar_por = $_GET['ordenar_por'] ?? null; // Recibe el valor del select de ordenamiento
$query = isset($_GET['query']) ? $_GET['query'] : '';
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$precio_inicio = isset($_GET['precio_inicio']) ? $_GET['precio_inicio'] : '';
$precio_fin = isset($_GET['precio_fin']) ? $_GET['precio_fin'] : '';

$sqlgame = "
SELECT
    games.game_id,
    consolas.nombres AS consola,
    games.titulo,
    games.img,
    games.fecha_estreno,
    games.precio,
    game_categorias.categorias_id,
    GROUP_CONCAT(categorias.nombres SEPARATOR ', ') AS generos
FROM
    games
INNER JOIN
    consolas ON games.console_id = consolas.consola_id
INNER JOIN
    game_categorias ON games.game_id = game_categorias.game_id
INNER JOIN
    categorias ON categorias.categorias_id = game_categorias.categorias_id
WHERE 1=1
";

// Agregar la cláusula WHERE si hay una consola específica
if ($console) {
    $sqlgame .= " AND consolas.nombres = :console";
}

// Agregar la cláusula WHERE si hay un término de búsqueda
if ($query) {
    $sqlgame .= " AND (consolas.nombres LIKE :query OR games.titulo LIKE :query OR games.precio LIKE :query)";
}

// Agregar la cláusula WHERE si hay un título, plataforma y género específicos
if ($titulo && $plataforma && $genero) {
    $sqlgame .= " AND games.titulo LIKE :titulo AND consolas.nombres = :plataforma AND categorias.categorias_id = :genero";
    
} else {
    // Agregar la cláusula WHERE si solo hay un título específico
    if ($titulo) {
        $tituloTerm = "%$titulo%";
        $sqlgame .= " AND games.titulo LIKE :titulo";
    }

    // Agregar la cláusula WHERE si solo hay una plataforma específica
    if ($plataforma) {
        $sqlgame .= " AND consolas.nombres = :plataforma";
    }

    // Agregar la cláusula WHERE si solo hay un género específico
    if ($genero) {
        $sqlgame .= " AND categorias.categorias_id = :genero";
    }
}

// Agregar la cláusula WHERE para rango de fechas
if ($fecha_inicio && $fecha_fin) {
    $sqlgame .= " AND games.fecha_estreno BETWEEN :fecha_inicio AND :fecha_fin";
}

// Agregar la cláusula WHERE para rango de precios
if ($precio_inicio && $precio_fin) {
    $sqlgame .= " AND games.precio BETWEEN :precio_inicio AND :precio_fin";
}

// Agregar cláusula GROUP BY
$sqlgame .= "
GROUP BY
    games.game_id, consolas.nombres, games.titulo, games.img, games.fecha_estreno, games.precio";

// Agregar cláusula ORDER BY según el parámetro de ordenamiento
switch ($ordenar_por) {
    case 'precio_desc':
        $sqlgame .= " ORDER BY games.precio DESC";
        break;
    case 'precio_asc':
        $sqlgame .= " ORDER BY games.precio ASC";
        break;
    case 'fecha_desc':
        $sqlgame .= " ORDER BY games.fecha_estreno DESC";
        break;
    case 'fecha_asc':
        $sqlgame .= " ORDER BY games.fecha_estreno ASC";
        break;
    default:
        // Orden predeterminado si no se selecciona ninguno
        $sqlgame .= " ORDER BY games.game_id DESC";
        break;
}

try {
    $stmti = $pdo->prepare($sqlgame);
    
    // Enlazar el parámetro de la consola si existe
    if ($console) {
        $stmti->bindParam(':console', $console);
    }

    // Enlazar el parámetro de búsqueda si existe
    if ($query) {
        $searchTerm = "%$query%";
        $stmti->bindParam(':query', $searchTerm);
    }

    // Enlazar el parámetro del título si existe
    if ($titulo) {
        $tituloTerm = "%$titulo%";
        $stmti->bindParam(':titulo', $tituloTerm);
    }

    // Enlazar el parámetro de la plataforma si existe
    if ($plataforma) {
        $stmti->bindParam(':plataforma', $plataforma);
    }

    // Enlazar el parámetro del género si existe
    if ($genero) {
        $stmti->bindParam(':genero', $genero);
    }

    // Enlazar el parámetro de la fecha de inicio si existe
    if ($fecha_inicio) {
        $stmti->bindParam(':fecha_inicio', $fecha_inicio);
    }

    // Enlazar el parámetro de la fecha de fin si existe
    if ($fecha_fin) {
        $stmti->bindParam(':fecha_fin', $fecha_fin);
    }

    // Enlazar el parámetro del precio de inicio si existe
    if ($precio_inicio) {
        $stmti->bindParam(':precio_inicio', $precio_inicio);
    }

    // Enlazar el parámetro del precio de fin si existe
    if ($precio_fin) {
        $stmti->bindParam(':precio_fin', $precio_fin);
    }

    $stmti->execute();
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
    <link rel="stylesheet" href="css/buscar.css">
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
        <div class="position-absolute top-0 start-0 w-100 z-0 buscar-background">
        <!-- imagen -->
        <!-- imagen -->
        </div>
    </div> 
    <div class="container">
        <div class="formulario-buscar">
        <div class="formulariouno">
            <h1 class="roboto-regular text-light">Buscar Videojuegos</h1>
            <form action="buscar.php" method="GET">
                <input type="text" name="titulo" id="titulo" class="w-100 nombre-producto" placeholder="Nombre del videojuego" value="<?php echo isset($_GET['titulo']) ? htmlspecialchars($_GET['titulo']) : ''; ?>">
                <div class="group-busca">
                    <select name="plataforma" id="plataforma" class="nombre-producto">
                        <option value="">Plataforma</option>
                        <option value="PC" <?php if (isset($_GET['plataforma']) && $_GET['plataforma'] == 'PC') echo 'selected'; ?>>PC</option>
                        <option value="PlayStation" <?php if (isset($_GET['plataforma']) && $_GET['plataforma'] == 'PlayStation') echo 'selected'; ?>>PlayStation</option>
                        <option value="Xbox" <?php if (isset($_GET['plataforma']) && $_GET['plataforma'] == 'Xbox') echo 'selected'; ?>>Xbox</option>
                        <option value="Nintendo" <?php if (isset($_GET['plataforma']) && $_GET['plataforma'] == 'Nintendo') echo 'selected'; ?>>Nintendo</option>
                        <option value="Movil" <?php if (isset($_GET['plataforma']) && $_GET['plataforma'] == 'Movil') echo 'selected'; ?>>Movil</option>
                    </select>
                    <select name="genero" id="genero" class="nombre-producto">
                        <option value="">Género</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo htmlspecialchars($categoria['categorias_id']); ?>" <?php if (isset($_GET['genero']) && $_GET['genero'] == $categoria['categorias_id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($categoria['nombres']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="ordenar_por" id="ordenar_por" class="nombre-producto">
                        <option value="">Ordenar por</option>
                        <option value="precio_desc" <?php if (isset($_GET['ordenar_por']) && $_GET['ordenar_por'] == 'precio_desc') echo 'selected'; ?>>Precio Mayor a Precio Menor</option>
                        <option value="precio_asc" <?php if (isset($_GET['ordenar_por']) && $_GET['ordenar_por'] == 'precio_asc') echo 'selected'; ?>>Precio Menor a Precio Mayor</option>
                        <option value="fecha_desc" <?php if (isset($_GET['ordenar_por']) && $_GET['ordenar_por'] == 'fecha_desc') echo 'selected'; ?>>Fecha Mayor a Fecha Menor</option>
                        <option value="fecha_asc" <?php if (isset($_GET['ordenar_por']) && $_GET['ordenar_por'] == 'fecha_asc') echo 'selected'; ?>>Fecha Menor a Fecha Mayor</option>
                    </select>
                </div>
                <div class="grupo-buscar">
                    <p class="fecha-buscar roboto-regular">Fechas: </p>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="nombre-producto" value="<?php echo isset($_GET['fecha_inicio']) ? htmlspecialchars($_GET['fecha_inicio']) : ''; ?>">
                    <input type="date" name="fecha_fin" id="fecha_fin" class="nombre-producto" value="<?php echo isset($_GET['fecha_fin']) ? htmlspecialchars($_GET['fecha_fin']) : ''; ?>">
                    <p class="fecha-buscar roboto-regular">Precios: </p>
                    <input type="number" name="precio_inicio" id="precio_inicio" class="nombre-producto" placeholder="Precio inicio" value="<?php echo isset($_GET['precio_inicio']) ? htmlspecialchars($_GET['precio_inicio']) : ''; ?>">
                    <input type="number" name="precio_fin" id="precio_fin" class="nombre-producto" placeholder="Precio final" value="<?php echo isset($_GET['precio_fin']) ? htmlspecialchars($_GET['precio_fin']) : ''; ?>">
                </div>
                <div class="button-buscar w-100 d-flex justify-content-center">
                    <input type="submit" value="Buscar" class="buscar-btn roboto-regular">
                </div>
            </form>

        </div>

            <div class="formulariodos">
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
                    <p class="text-light roboto-light">No hay juegos disponibles.</p>
                <?php endif; ?>
            </div>
            </div>

        </div>
        <div class="resultados-buscar">

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
