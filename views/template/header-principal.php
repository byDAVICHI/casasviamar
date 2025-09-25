<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo TITLE . ' | ' . $data['title']; ?></title>
    <link rel="icon" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/logodefinitivo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=|Roboto+Sans:400,700|Playfair+Display:400,700">

    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/animate.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/aos.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/jquery.timepicker.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/fancybox.min.css">

    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>fonts/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>fonts/fontawesome/css/font-awesome.min.css">



    <!-- Theme Style -->
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/style.css">
</head>

<body>
    <header class="site-header js-site-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-6 col-lg-4 site-logo" data-aos="fade"><a href="<?php echo RUTA_PRINCIPAL . 'principal' ?>">VIA-MAR</a></div>
                <div class="col-6 col-lg-8">


                    <div class="site-menu-toggle js-site-menu-toggle" data-aos="fade">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <!-- END menu-toggle -->

                    <div class="site-navbar js-site-navbar">
                        <nav role="navigation">
                            <div class="container">
                                <div class="row full-height align-items-center">
                                    <div class="col-md-6 mx-auto">
                                        <ul class="list-unstyled menu">
                                            <li class="active"><a href="<?php echo RUTA_PRINCIPAL . 'principal' ?>">HOME</a></li>
                                            <li><a href="<?php echo RUTA_PRINCIPAL . 'habitacion' ?>">CASA</a></li>
                                            <li><a href="<?php echo RUTA_PRINCIPAL . 'blog' ?>">BLOG</a></li>
                                            <li><a href="<?php echo RUTA_PRINCIPAL . 'about' ?>">ACERCA DE</a></li>
                                            <li><a href="<?php echo RUTA_PRINCIPAL . 'servicio' ?>">ATRACCIÓNES</a></li>
                                            <li><a href="<?php echo RUTA_PRINCIPAL . 'contacto' ?>">CONTACTO</a></li>
                                            <li><a href="<?php echo RUTA_PRINCIPAL . 'reservacion' ?>">RESERVACIÓN</a></li>
                                            <li><a href="https://wa.me/527661151203">WHATSAPP</a></li>
                                            <li><a href="https://www.facebook.com/profile.php?id=100064771851775">FACEBOOK</a></li>
                                            <li><a href="https://www.instagram.com/casasviamar/">INSTAGRAM</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- END head -->

    <section class="site-hero overlay" style="background-image: url(<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-17.jpg)" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row site-hero-inner justify-content-center align-items-center">
                <div class="col-md-10 text-center" data-aos="fade-up">
                    <span class="custom-caption text-uppercase text-white d-block  mb-3">BIENVENIDO A <span class="fa fa-star text-primary"></span> CASA</span>
                    <span class="custom-caption text-uppercase text-white d-block  mb-3"><?php echo $data['title']; ?> <br>
                        <h1 class="heading">EL MEJOR LUGAR PARA QUEDARSE</h1>
                </div>
            </div>
        </div>

        <a class="mouse smoothscroll" href="#next">
            <div class="mouse-icon">
                <span class="mouse-wheel"></span>
            </div>
        </a>
    </section>
    <!-- END section -->