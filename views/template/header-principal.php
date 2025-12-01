<!DOCTYPE HTML>
<html lang="<?php echo lang()->getCurrentLanguageInfo()['code']; ?>">

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
    
    <!-- Flag Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css">
    
    <!-- Bootstrap 5 (para dropdown del selector de idiomas) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
    
    <!-- Estilos del Selector de Idiomas -->
    <style>
        /* Contenedor de controles del header */
        .header-controls {
            position: relative;
            z-index: 1001;
        }
        
        /* Botón del selector de idiomas */
        .lang-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            padding: 8px 16px;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .lang-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
        }
        
        .lang-btn .fi {
            font-size: 18px;
            border-radius: 2px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }
        
        .lang-btn .lang-code {
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .lang-btn i {
            font-size: 10px;
            opacity: 0.8;
        }
        
        /* Dropdown del selector de idiomas */
        .lang-menu {
            min-width: 220px;
            max-height: 400px;
            overflow-y: auto;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 8px;
            margin-top: 10px !important;
        }
        
        .lang-menu .dropdown-item {
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            color: #333;
            transition: all 0.2s ease;
        }
        
        .lang-menu .dropdown-item:hover {
            background: #f5f5f5;
        }
        
        .lang-menu .dropdown-item.active {
            background: linear-gradient(135deg, #c9a227, #d4af37);
            color: #fff;
        }
        
        .lang-menu .dropdown-item .fi {
            font-size: 20px;
            border-radius: 3px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        
        .lang-menu .dropdown-item i.fa-check {
            color: #fff;
            font-size: 12px;
        }
        
        /* Ajuste para el menú hamburguesa */
        .header-controls .site-menu-toggle {
            position: relative !important;
            top: auto !important;
            right: auto !important;
            float: none !important;
            margin: 0 !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
        }
        
        .header-controls .site-menu-toggle span {
            display: block;
            width: 25px;
            height: 2px;
            background: #fff;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .lang-btn {
                padding: 6px 12px;
            }
            
            .lang-btn .lang-code {
                display: none;
            }
            
            .lang-btn .fi {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <header class="site-header js-site-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-4 col-lg-4 site-logo" data-aos="fade"><a href="<?php echo RUTA_PRINCIPAL . 'principal' ?>">VIA-MAR</a></div>
                <div class="col-8 col-lg-8">
                    <!-- Contenedor de controles del header -->
                    <div class="header-controls d-flex align-items-center justify-content-end gap-3" data-aos="fade">
                        <!-- Selector de Idioma -->
                        <?php 
                        $langHelper = Language::getInstance();
                        $currentLang = $langHelper->getCurrentLanguageInfo();
                        $availableLangs = $langHelper->getAvailableLanguages();
                        ?>
                        <div class="dropdown lang-dropdown">
                            <button class="lang-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="fi fi-<?php echo $currentLang['flag_icon']; ?>"></span>
                                <span class="lang-code"><?php echo strtoupper($currentLang['code']); ?></span>
                                <i class="fa fa-chevron-down ms-1"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end lang-menu">
                                <?php foreach ($availableLangs as $langCode => $langInfo): ?>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center <?php echo ($langCode === $langHelper->getCurrentLanguage()) ? 'active' : ''; ?>" 
                                       href="<?php echo RUTA_PRINCIPAL; ?>idioma/cambiar/<?php echo $langCode; ?>">
                                        <span class="fi fi-<?php echo $langInfo['flag_icon']; ?> me-2"></span>
                                        <span><?php echo $langInfo['name']; ?></span>
                                        <?php if ($langCode === $langHelper->getCurrentLanguage()): ?>
                                        <i class="fa fa-check ms-auto"></i>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <!-- Menú Hamburguesa -->
                        <div class="site-menu-toggle js-site-menu-toggle">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <!-- END header-controls -->

                    <div class="site-navbar js-site-navbar">
                        <nav role="navigation">
                            <div class="container">
                                <div class="row full-height align-items-center">
                                    <div class="col-md-6 mx-auto">
                                        <ul class="list-unstyled menu">
                                            <li class="active"><a href="<?php echo RUTA_PRINCIPAL . 'principal' ?>"><?php echo __('menu_home'); ?></a></li>
                                            <li><a href="<?php echo RUTA_PRINCIPAL . 'habitacion' ?>"><?php echo __('menu_house'); ?></a></li>
                                            <li><a href="<?php echo RUTA_PRINCIPAL . 'blog' ?>"><?php echo __('menu_blog'); ?></a></li>
                                            <li><a href="<?php echo RUTA_PRINCIPAL . 'about' ?>"><?php echo __('menu_about'); ?></a></li>
                                            <li><a href="<?php echo RUTA_PRINCIPAL . 'servicio' ?>"><?php echo __('menu_attractions'); ?></a></li>
                                            <li><a href="<?php echo RUTA_PRINCIPAL . 'contacto' ?>"><?php echo __('menu_contact'); ?></a></li>
                                            <li><a href="<?php echo RUTA_PRINCIPAL . 'reservacion' ?>"><?php echo __('menu_reservation'); ?></a></li>
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
                    <span class="custom-caption text-uppercase text-white d-block  mb-3"><?php echo __('hero_welcome'); ?> <span class="fa fa-star text-primary"></span> <?php echo __('hero_house'); ?></span>
                    <span class="custom-caption text-uppercase text-white d-block  mb-3"><?php echo $data['title']; ?> <br>
                        <h1 class="heading"><?php echo __('hero_best_place'); ?></h1>
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