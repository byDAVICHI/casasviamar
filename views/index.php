<?php
include_once 'views/template/header-principal.php';
?>



<section class="section bg-light pb-0">
    <div class="container">

        <div class="row check-availabilty" id="next">
            <div class="block-32" data-aos="fade-up" data-aos-offset="-200">

                <form id="formulario" class="check-form" action="<?php echo RUTA_PRINCIPAL . 'reserva/verify'; ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-lg-0 col-lg-3">
                            <label class="font-weight-bold text-black">Fecha Llegada</label>
                            <div class="field-icon-wrap">
                                <div class="icon"><span class="icon-calendar"></span></div>
                                <input id="f_llegada" name="f_llegada" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 mb-lg-0 col-lg-3">
                            <label class="font-weight-bold text-black">Fecha Salida</label>
                            <div class="field-icon-wrap">
                                <div class="icon"><span class="icon-calendar"></span></div>
                                <input id="f_salida" name="f_salida" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0 col-lg-3">
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="habitacion" class="font-weight-bold text-black">Casa</label>
                                    <div class="field-icon-wrap">

                                        <select id="habitacion" name="habitacion" class="form-control" style="width: 250px;">
                                            <option value="">Seleccionar</option>
                                            <?php foreach ($data['habitaciones'] as $habitacion) { ?>
                                                <option value="<?php echo $habitacion['id']; ?>"><?php echo $habitacion['estilo']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 align-self-end">
                            <button class="btn btn-primary btn-block text-white" type="submit">Comprobar Disponibilidad</button>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12 col-lg-7 ml-auto order-lg-2 position-relative mb-5" data-aos="fade-up">
                <figure class="img-absolute">
                    <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/logodefinitivo.png" alt="Image" class="img-fluid">
                </figure>
                <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-2.jpg" alt="Image" class="img-fluid rounded">
            </div>
            <div class="col-md-12 col-lg-4 order-lg-1" data-aos="fade-up">
                <h2 class="heading">Bienvenido a casa en Tecolutla, Veracruz!</h2>
                <p class="mb-4">Hola, soy Nalleli Hernández, una apasionada residente de Tecolutla y ama de casa a tiempo completo. Vivo en el corazón de esta hermosa ciudad y me encantaría ofrecerte una experiencia auténtica al estilo de los locales. Durante las vacaciones de Semana Santa y de Invierno, el precio por Noche en cada casa vacacional es de 4500, sin distinción del día, debido a la alta demanda en estas temporadas. ¡Contáctame para más información al 766-115-12-03!</p>
                <p><a href="https://wa.me/527661151203" class="btn btn-primary text-white py-2 mr-3">Leer mas...</a> <span class="mr-3 font-family-serif"><em></em></span></p>
            </div>
        </div>
    </div>
</section>

<!-- VIA SOL section -->
<section class="section slider-section bg-light">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-md-7">
                <h2 class="heading" data-aos="fade-up">CASA VIA-SOL</h2>
                <p data-aos="fade-up" data-aos-delay="100">¡No esperes más! Reserve su estancia con nosotros y experimente unas vacaciones de ensueño en nuestra casa de lujo. ¡Haremos que cada momento cuente!</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="home-slider major-caousel owl-carousel mb-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs1.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs1.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs2.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs2.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs3.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs3.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs4.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs4.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs5.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs5.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs6.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs6.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                    <div class="slider-item">
                        <a href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs7.jpg" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/vs7.jpg" alt="Image placeholder" class="img-fluid"></a>
                    </div>
                </div>
                <!-- END slider -->
            </div>

            <div class="row justify-content-center text-center mb-5">
                <h2 class="heading" data-aos="fade-up">EXCELENTE UBICACIÓN</h2>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3737.659085721147!2d-97.01701732475831!3d20.47919078103624!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMjDCsDI4JzQ1LjEiTiA5N8KwMDAnNTIuMCJX!5e0!3m2!1ses!2smx!4v1744310533580!5m2!1ses!2smx" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- END section -->

<section class="section">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-md-7">
                <h2 class="heading" data-aos="fade-up">CASA VACACIONAL</h2>
                <p data-aos="fade-up" data-aos-delay="100">
                    ¡Bienvenidos a su escapada tropical de ensueño! 🌴 Descubra el encanto del paraíso en nuestra casa de vacaciones recién inaugurada, diseñada con todo lo necesario para un alojamiento de lujo.
                    La casa vacacional es cómoda y luminosa, con un estilo moderno minimalista y una cama doble lista para hacer realidad tus sueños. En mi hogar encontrarás la privacidad que necesitas, complementada con una guía local que te permitirá Saber Adónde Ir.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                <a href="#" class="room">
                    <figure class="img-wrap">
                        <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-18.jpg" alt="Free website template" class="img-fluid mb-3">
                    </figure>
                    <div class="p-3 text-center room-info">
                        <h2>COCINA</h2>
                        <span class="text-uppercase letter-spacing-1 text-navy">Inicie el día con una ducha refrescante en uno de nuestros 2 baños completos 🚽🚿, equipados con agua fría y caliente para adaptarse a su gusto. Prepare deliciosos desayunos en nuestra cocina totalmente equipada 🍴🥄🔪, y disfrute de sus comidas en nuestra sala de estar o comedor, diseñados para crear momentos inolvidables. </span>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                <a href="#" class="room">
                    <figure class="img-wrap">
                        <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-17.jpg" alt="Free website template" class="img-fluid mb-3">
                    </figure>
                    <div class="p-3 text-center room-info">
                        <h2>SALA</h2>
                        <span class="text-uppercase letter-spacing-1">Manténgase conectado con nuestro wifi de alta velocidad 🌐, y disfrute de noches de cine con nuestro TV equipado con cable y Netflix 📺. Relájese y refrésquese en nuestra piscina privada 🏊, y deje que los más pequeños se diviertan en el chapoteadero. </span>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                <a href="#" class="room">
                    <figure class="img-wrap">
                        <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-9.jpg" alt="Free website template" class="img-fluid mb-3">
                    </figure>
                    <div class="p-3 text-center room-info">
                        <h2>HABITACIÓNES</h2>
                        <span class="text-uppercase letter-spacing-1">Imagínese despertando en una de nuestras 3 recámaras climatizadas 🥶, cada una equipada con ventilador de techo y TV para su máximo confort. Ofrecemos un alojamiento flexible con 6 cómodas camas matrimoniales y 2 camas individuales, perfectas para familias o grupos de amigos. </span>
                    </div>
                </a>
            </div>


        </div>
    </div>
</section>


<section class="section slider-section bg-light">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-md-7">
                <h2 class="heading" data-aos="fade-up">FOTOS</h2>
                <p data-aos="fade-up" data-aos-delay="100">¡No esperes más! Reserve su estancia con nosotros y experimente unas vacaciones de ensueño en nuestra casa de lujo. ¡Haremos que cada momento cuente!</p>
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

            <div class="row justify-content-center text-center mb-5">
                <h2 class="heading" data-aos="fade-up">EXCELENTE UBICACIÓN</h2>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7475.34562564853!2d-97.0232328772545!3d20.478627336806582!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85dbb58e5cd30b3f%3A0xb2f30f5191f326e8!2sCasa%20vacacional!5e0!3m2!1ses!2smx!4v1690456157058!5m2!1ses!2smx" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- END section -->



<!-- END section -->
<section class="section testimonial-section">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-md-7">
                <h2 class="heading" data-aos="fade-up">OPINION DE LOS HUESPEDES</h2>
            </div>
        </div>
        <div class="row">
            <div class="js-carousel-2 owl-carousel mb-5" data-aos="fade-up" data-aos-delay="200">

                <div class="testimonial text-center slider-item">
                    <div class="author-image mb-3">
                        <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/person_1.jpg" alt="Image placeholder" class="rounded-circle mx-auto">
                    </div>
                    <blockquote>

                        <p>&ldquo;¡Qué estancia más maravillosa! La casa es espaciosa y fresca, perfecta para nuestro grupo familiar. Los niños adoraron el chapoteadero y nosotros disfrutamos de la barbacoa en el jardín. La playa a solo 4 cuadras fue la guinda del pastel. ¡Volveremos seguro!&rdquo;</p>
                    </blockquote>
                    <p><em>&mdash; Familia González - Madrid, España</em></p>
                </div>

                <div class="testimonial text-center slider-item">
                    <div class="author-image mb-3">
                        <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/person_2.jpg" alt="Image placeholder" class="rounded-circle mx-auto">
                    </div>
                    <blockquote>
                        <p>&ldquo;Increíblemente impresionados con esta casa vacacional. Desde las camas súper cómodas hasta la cocina bien equipada, todo fue perfecto. Nos encantó poder ver nuestras series favoritas en Netflix después de un día de sol y playa. ¡Un verdadero hogar lejos de casa!&rdquo;</p>
                    </blockquote>
                    <p><em>&mdash; Familia Gomez - Ciudad de México</em></p>
                </div>

                <div class="testimonial text-center slider-item">
                    <div class="author-image mb-3">
                        <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/person_3.jpg" alt="Image placeholder" class="rounded-circle mx-auto">
                    </div>
                    <blockquote>

                        <p>&ldquo;Nuestra experiencia fue excelente. Las recámaras con clima nos proporcionaron un oasis de frescura después de los días calurosos en la playa. También, la piscina fue un gran acierto. ¡Definitivamente regresaremos!&rdquo;</p>
                    </blockquote>
                    <p><em>&mdash; Familia López - Ciudad de México</em></p>
                </div>


                <div class="testimonial text-center slider-item">
                    <div class="author-image mb-3">
                        <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/person_1.jpg" alt="Image placeholder" class="rounded-circle mx-auto">
                    </div>
                    <blockquote>

                        <p>&ldquo;Nos hospedamos aquí para un viaje de amigos y fue genial. Había suficiente espacio para todos, y con dos baños completos, nunca tuvimos que hacer fila para la ducha. La cercanía a la playa y las áreas comunes para compartir, hicieron de este el lugar perfecto para nuestra escapada.&rdquo;</p>
                    </blockquote>
                    <p><em>&mdash; Grupo de Amigos - Ciudad de México</em></p>
                </div>

                <div class="testimonial text-center slider-item">
                    <div class="author-image mb-3">
                        <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/person_2.jpg" alt="Image placeholder" class="rounded-circle mx-auto">
                    </div>
                    <blockquote>
                        <p>&ldquo;¡Nos encantó nuestra estancia! La casa es moderna, limpia y acogedora. Además, tener wifi de alta velocidad fue esencial para nosotros, que necesitábamos estar conectados por trabajo. Pero, lo mejor fue terminar el día con una parrillada en el jardín. Recomendamos esta casa a cualquiera que busque unas vacaciones relajantes.&rdquo;</p>
                    </blockquote>
                    <p><em>&mdash; Familia Gutiérrez - Ciudad de México</em></p>
                </div>

                <div class="testimonial text-center slider-item">
                    <div class="author-image mb-3">
                        <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/person_3.jpg" alt="Image placeholder" class="rounded-circle mx-auto">
                    </div>
                    <blockquote>

                        <p>&ldquo;Nuestra luna de miel en esta casa fue de ensueño. La privacidad, la comodidad y los detalles de lujo hicieron que nos sintiéramos especiales. Disfrutamos de noches románticas junto a la piscina y días soleados en la playa cercana. ¡No podríamos haber pedido un lugar mejor!&rdquo;</p>
                    </blockquote>
                    <p><em>&mdash; Pareja de Novios - Puebla</em></p>
                </div>

            </div>
            <!-- END slider -->
        </div>

    </div>
</section>

<section class="section slider-section bg-light">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-md-7">
                <h2 class="heading" data-aos="fade-up">NUESTROS HUESPEDES</h2>
                <p data-aos="fade-up" data-aos-delay="100">Cada experiencia vivida en nuestra casa es única y especial. Capturamos momentos inolvidables de nuestros huéspedes para compartir la magia de su estancia. Explore nuestra galería y sea testigo de las sonrisas, aventuras y recuerdos que hacen de nuestro sitio el lugar perfecto para sus vacaciones. ¡Su próxima historia puede estar aquí!</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="home-slider major-caousel owl-carousel mb-5" data-aos="fade-up" data-aos-delay="200">
                    <?php foreach ($data['sliders'] as $slider) { ?>
                        <div class="slider-item">
                            <a href="<?php echo RUTA_PRINCIPAL . 'assets/img/sliders/' . $slider['foto'] ?>" data-fancybox="images" data-caption="Caption for this image"><img src="<?php echo RUTA_PRINCIPAL . 'assets/img/sliders/' . $slider['foto'] ?>" alt="Image placeholder" class="img-fluid"></a>
                        </div>
                    <?php } ?>

                </div>
                <!-- END slider -->
            </div>

            <div class="row justify-content-center text-center mb-5">
                <h2 class="heading" data-aos="fade-up">EXCELENTE UBICACIÓN</h2>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7475.34562564853!2d-97.0232328772545!3d20.478627336806582!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85dbb58e5cd30b3f%3A0xb2f30f5191f326e8!2sCasa%20vacacional!5e0!3m2!1ses!2smx!4v1690456157058!5m2!1ses!2smx" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- END section -->


<section class="section blog-post-entry bg-light">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-md-7">
                <h2 class="heading" data-aos="fade-up">ATRACCIÓNES

                </h2>
                <p data-aos="fade-up">¡Descubre la magia y el encanto de Tecolutla, la joya escondida de Veracruz! Aquí, los días se llenan de aventura y los recuerdos perduran para siempre.En nuestra casa vacacional, no sólo te ofrecemos un cómodo y lujoso alojamiento, sino también la puerta de entrada a estas increíbles experiencias. ¡Ven y sumérgete en todo lo que Tecolutla tiene para ofrecer!</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12 post" data-aos="fade-up" data-aos-delay="100">

                <div class="media media-custom d-block mb-4 h-100">
                    <a href="#" class="mb-4 d-block"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/banana2.png" alt="Image placeholder" class="img-fluid"></a>
                    <div class="media-body">
                        <span class="meta-post"></span>
                        <h2 class="mt-0 mb-3"><a href="#">Paseos en lancha</a></h2>
                        <p>Déjate cautivar por la belleza de los manglares de Tecolutla durante nuestros inolvidables paseos en lancha. Observa la vida silvestre en su hábitat natural y déjate sorprender por la biodiversidad de este maravilloso ecosistema.</p>
                    </div>
                </div>

            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12 post" data-aos="fade-up" data-aos-delay="200">
                <div class="media media-custom d-block mb-4 h-100">
                    <a href="#" class="mb-4 d-block"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/banana1.jpg" alt="Image placeholder" class="img-fluid"></a>
                    <div class="media-body">
                        <span class="meta-post"></span>
                        <h2 class="mt-0 mb-3"><a href="#">Emoción en la Banana</a></h2>
                        <p>¿Buscas una dosis de adrenalina? ¡Atrévete a montar en la Banana! Un divertido paseo en este inflable remolcado por una lancha a alta velocidad te hará vivir momentos llenos de diversión y risas. Es una aventura imperdible para los amantes de la emoción.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12 post" data-aos="fade-up" data-aos-delay="300">
                <div class="media media-custom d-block mb-4 h-100">
                    <a href="#" class="mb-4 d-block"><img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/banana3.jpg" alt="Image placeholder" class="img-fluid"></a>
                    <div class="media-body">
                        <span class="meta-post"></span>
                        <h2 class="mt-0 mb-3"><a href="#">Liberación de tortugas</a></h2>
                        <p>Participa en una experiencia conmovedora y memorable: la liberación de tortugas. Tecolutla es hogar de las tortugas marinas y aquí tendrás la oportunidad de ayudar a estos pequeños seres a iniciar su viaje al mar. Es una actividad maravillosa para todas las edades y una forma mágica de conectarse con la naturaleza.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include_once 'views/template/footer-principal.php';

if (!empty($_GET['respuesta']) && $_GET['respuesta'] == 'warning') {

?>
    <script>
        alertSW('TODOS LOS CAMPOS SON REQUERIDOS', 'warning');
    </script>

<?php
}
?>
<script src=" <?php echo RUTA_PRINCIPAL . 'assets/principal/js/pages/disponibilidad.js'  ?>"></script>
<script src=" <?php echo RUTA_PRINCIPAL . 'assets/principal/js/pages/index.js';  ?>">