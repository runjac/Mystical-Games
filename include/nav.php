<nav class="navbar navbar-expand-lg bg-body-tertiary degradado z-1">
                <div class="container-fluid " style="width:95%;">
                    <a class="navbar-brand" href="index.php">
                        <img src="assets/logo-removebg-preview.png" alt="" >
                        <span class="fmerriweather yclaro fw-bold">Mystical</span>
                        <span class="fblack aclaro">Games</span>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars text-light rotate"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                        <li class="nav-item ">
                            <a class="nav-link  text-light fblack"  href="buscar.php?console=PC"> <i class="fa-solid fa-computer p-2"></i>PC</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light fblack" href="buscar.php?console=Playstation"><i class="fa-brands fa-playstation p-2"></i>Playstation</a>
                        </li>

                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                            </a>
                            <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link  text-light fblack" href="buscar.php?console=Xbox"> <i class="fa-brands fa-xbox p-2 "></i> Xbox</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  text-light fblack" href="buscar.php?console=Nintendo"><i class="fa-solid fa-gamepad p-2"></i>Nintendo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  text-light fblack" href="buscar.php?console=Movil"><i class="fa-solid fa-mobile-screen-button p-2"></i>Movil</a>
                        </li>
                        </ul>
                        
                        <button class="btn icon-buscar" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop"><i class="fa-solid fa-magnifying-glass"></i></button>
                        <?php if (isset($_SESSION['id_usuario'])): ?>
                            <a href="perfil.php" class="login text-light p-3">
                                <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                            </a>
                            <a href="logout.php" class="login text-light p-3">
                                <i class="fa-solid fa-power-off"></i>
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="login p-3">
                                <i class="fa-solid fa-user"></i>
                            </a>
                        <?php endif; ?>
                       
                   
                        <button type="button" class="btn position-relative"  data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="count-carrito">
                                    0
                                </span>
                        </button>
                       

                    </div>
                </div>
                
            </nav>
            
             <!--carrito-->
                <div class="offcanvas offcanvas-carrito offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title text-light roboto-regular" id="offcanvasRightLabel">Carrito</h5>
                        <button type="button" class="btn-close text-light  bg-light" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="linea-pedido"></div>
                    <div class="offcanvas-body" id="carrito-items">
                       <!-- <div class="pedido bg-dark p-3 rounded-3 mb-2">
                            <div class="pedido-img">
                                <img src="assets/videojuegos/playstation/god-of-war-ragnarok-ps4.webp" alt="">
                            </div>
                            <div class="pedido-nombre p-1">
                                <h6 class="text-light roboto-bold ">Resident Evil 4 Remake Xbsx</h6>
                                <p class="text-light roboto-regular fs-6"> $100</p>  
                            </div>
                            <div class="pedido-cantidad d-flex justify-content-center align-items-center">
                                <select class="form-select p-3 select-widt" aria-label="Default select example">
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
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                </select>

                                <a href="">
                                    <i class="fa-solid fa-trash  p-1 m-1" style="color:#555555"></i>
                                </a>
                                
                            </div>
                       </div> -->
                    </div>
                    <div class="offcanvas-footer p-3 bg-dark">
                            <div class="footer-total d-flex justify-content-between m-2">
                                <h3 class="roboto-bold fs-4">Total:</h3>
                                <h4 class="roboto-medium fs-4">$ <span id="total-carrito">0</span></h4>
                            </div>
                            <div class="footer-botones d-flex justify-content-between">
                                <a href="carrito.php" class=" footer-a d-flex align-items-center justify-content-center footer-cesta m-2 p-3  rounded-3 w-50 roboto-medium">
                                    Ir a la cesta
                                    <i class="fa-solid fa-cart-flatbed tex-darkp p-1"></i>
                                </a>
                                <a href="comprar.php" class="footer-a d-flex align-items-center justify-content-center fotter-pagar degradado2 m-2 p-3 rounded-3 w-50 roboto-regular">
                                    Pagar
                                    <i class="fa-solid fa-cash-register text-dark p-1"></i>
                                </a>
                            </div>
                    </div>
                </div>
            <!--carrito-->
             <!--buscar-->
             <div class="offcanvas offcanvas-buscar offcanvas-top " tabindex="3" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title text-light fs-6 fw-light" id="offcanvasTopLabel">Hola Gamer, indícanos lo que deseas buscar.</h5>
                        </div>
                        <form class="d-flex buscar" role="search" action="buscar.php" method="GET">
                            <input class="form-control me-2" type="search" name="query" placeholder="Indicanos que buscas y que comience la diversión" aria-label="Search">
                            <button class="btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>

            </div>
            <!--buscar-->