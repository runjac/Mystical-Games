<?php
session_start();

require 'conexion/conex.php';

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$importe = 0;
$total = 0;

if(!isset($_SESSION['id_usuario'])){
    header('Location: login.php');
}

if(!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0){
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
    <link rel="stylesheet" href="css/carrito.css">
    <link rel="stylesheet" href="css/comprar.css">
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
        <div class="d-flex justify-content-center">
            
            <div class="fomurlario-comprar ">

                <div class="formulario-datos"> 
                    <div class="formulario-data">  
                        <h3 class="roboto-regular text-light p-2 fs-5">Dirección de facturación</h3>
                        <div class="formulario-personales" class="p-2">
                            <div class="group-formulario w-100 d-flex justify-content-between flex-wrap">
                                <input type="text" name="" class="text-light" id="nombre" class="p-3" placeholder="Nombre completo">
                                <input type="text" name="" class="text-light" id="direccion" class="p-3 " placeholder="Direccion">
                            </div>
                            <input type="text" name="" id="pais" class="p-3 w-100  mt-1 text-light" placeholder="Pais">
                        </div>
                    </div>
                    <div class="formulario-tarjeta">
                        <h3 class="roboto-regular text-light p-2 fs-5-">Métodos de pago</h3>
                        <img src="https://usa.visa.com/dam/VCOM/regional/ve/romania/blogs/hero-image/visa-logo-800x450.jpg" alt="" srcset="" width="15%"class="pb-2">
                        <div class="group-tarjeta">
                            <label for="" class="roboto-regular fs-6 text-light w-100">Número de tarjeta</label>
                            <input type="text" name=""  id="tarjeta" class="w-100 text-light" placeholder="**** **** **** ****">
                        </div>
                        <div class="group-tarjeta">
                            <label for="" class="roboto-regular fs-6 text-light w-100">Titular de la tarjeta</label>
                            <input type="text" name="" id="titular"  class="w-100 text-light" placeholder="M. GAMES">
                        </div>
                        <div class="group-tarje d-flex w-100 flex-wrap">
                            <div class="group-tarjetas col-md-6 col-12">
                                <label for="" class="roboto-regular fs-6 text-light w-100">Fecha de caducidad</label>
                                <input type="text" name="" class="text-light" id="caducidad" placeholder="MM/YY">
                            </div>
                            <div class="group-tarjetas  col-md-6 col-12">
                                <label for="" class="roboto-regular fs-6 text-light w-100">CVV</label>
                                <input type="text" name="" class="text-light" id="cvv" placeholder="CVC">
                            </div>      
                        </div>
                    </div>
                </div>
                <div class="w-100">
                    <h3 class="roboto-regular text-light p-2 fs-5 resumen">Resumen</h3>
                    <div class="resumen-pago">
                        <!-- productos comprados -->
                        <?php foreach($carrito as $item): ?>
                            <?php
                            $importe = $item['precio'] * $item['cantidad'];
                            $total += $importe;
                            ?>
                            <div class="subtotal-pagar roboto-light text-light">
                                <h3 class="text-light roboto-bold fs-6"><?php echo $item['titulo'] ?> (<?php echo $item['cantidad'] ?>)</h3>
                                $ <?php echo $importe ?>
                            </div>
                            <div class="hr-pago"></div>
                        <?php endforeach ?>

                        <!-- productos comprados -->
                    </div>
                    <div class="formulario-pagar">
                        <!-- total -->
                        <div class="total roboto-bold text-light">

                            <h3 class="text-light roboto-bold">Total:</h3>
                            $ <?php echo $total ?>
                        </div>
                        <!-- total -->
                        <button class="roboto-bold" id="btn-pagar">Pagar</button>
                    </div>
                </div>

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
        $('#btn-pagar').click(function(){
            
            var nombre = $('#nombre').val();
            var direccion = $('#direccion').val();
            var pais = $('#pais').val();
            var tarjeta = $('#tarjeta').val();
            var titular = $('#titular').val();
            var caducidad = $('#caducidad').val();
            var cvv = $('#cvv').val();

            $.ajax({
                url: 'ajax-comprar.php',
                method: 'post',
                data: {nombre, direccion, pais, tarjeta, titular, caducidad, cvv},
                success: function(data){
                    data = JSON.parse(data);
                    if(data.status){
                        location.href = 'finalizar.php';
                    }
                }
            });
        });
    </script>
</body>
</html>