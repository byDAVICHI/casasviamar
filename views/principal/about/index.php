<?php
include_once 'views/template/header-principal.php';
?>
<section class="py-5 bg-light" id="next">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12 col-lg-7 ml-auto order-lg-2 position-relative mb-5" data-aos="fade-up">
                <figure class="img-absolute">
                    <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/logodefinitivo.png" alt="Free Website Template by Templateux" class="img-fluid">
                </figure>
                <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-3.jpg" alt="Casa Vacacional Via-Mar" class="img-fluid rounded">
            </div>
            <div class="col-md-12 col-lg-4 order-lg-1" data-aos="fade-up">
                <h2 class="heading"><?php echo __('about_welcome_title'); ?></h2>
                <p class="mb-4"><?php echo __('about_welcome_text'); ?></p>
                <p><a href="#" class="btn btn-primary text-white py-2 mr-3"><?php echo __('btn_read_more'); ?></a> <span class="mr-3 font-family-serif"><em>o</em></span> <a href="https://vimeo.com/channels/staffpicks/93951774" data-fancybox class="text-uppercase letter-spacing-1"><?php echo __('btn_watch_video'); ?></a></p>
            </div>

        </div>
    </div>
</section>


<!-- END .block-2 -->

<section class="section slider-section bg-light">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-md-7">
                <h2 class="heading" data-aos="fade-up"><?php echo __('about_photos_title'); ?></h2>
                <p data-aos="fade-up" data-aos-delay="100"><?php echo __('about_photos_text'); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="home-slider major-caousel owl-carousel mb-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-2.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-2.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-6.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-6.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-8.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-8.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-18.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-18.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-14.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-14.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-15.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-15.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-21.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-21.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                </div>
                <!-- END slider -->
            </div>

        </div>
    </div>
</section>
<!-- END section -->

<?php
include_once 'views/template/footer-principal.php';
?>

</body>

</html>