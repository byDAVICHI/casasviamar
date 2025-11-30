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
                <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-3.jpg" alt="Casa Vacacional Via-Mar" class="img-fluid rounded">
            </div>
            <div class="col-md-12 col-lg-4 order-lg-1" data-aos="fade-up">
                <h2 class="heading">Bienvenido a casa en Tecolutla, Veracruz!</h2>
                <p class="mb-4">Hola, soy Nalleli Hernández, una apasionada residente de Tecolutla y ama de casa a tiempo completo. Vivo en el corazón de esta hermosa ciudad y me encantaría ofrecerte una experiencia auténtica al estilo de los locales. Durante las vacaciones de Semana Santa y de Invierno, el precio por Noche en cada casa vacacional es de 4500, sin distinción del día, debido a la alta demanda en estas temporadas. ¡Contáctame para más información al 766-115-12-03!</p>
                <p><a href="https://wa.me/527661151203" class="btn btn-primary text-white py-2 mr-3">Leer mas...</a> <span class="mr-3 font-family-serif"><em></em></span></p>
            </div>
        </div>
    </div>
</section>

<!-- CATÁLOGO DE CASAS VACACIONALES ESTILO AIRBNB -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL; ?>assets/principal/css/airbnb-style.css">

<section class="section bg-light py-5">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-md-7">
                <h2 class="heading" data-aos="fade-up">NUESTRAS CASAS VACACIONALES</h2>
                <p data-aos="fade-up" data-aos-delay="100">Descubre nuestras opciones de alojamiento y elige la perfecta para tus vacaciones</p>
            </div>
        </div>
        
        <!-- Grid de Propiedades Airbnb -->
        <div class="propiedades-grid" data-aos="fade-up">
            <?php if (!empty($data['propiedades'])): ?>
                <?php foreach ($data['propiedades'] as $propiedad): ?>
                    <?php 
                    $rutaImagen = obtenerRutaImagenCasa($propiedad['foto']);
                    $rating = number_format($propiedad['rating'] ?? $propiedad['calificacion_promedio'] ?? 0, 1);
                    $numEvaluaciones = $propiedad['num_evaluaciones'] ?? $propiedad['total_evaluaciones'] ?? 0;
                    $esFavorito = !empty($propiedad['es_favorito_huespedes']);
                    ?>
                    <a href="<?php echo RUTA_PRINCIPAL . 'propiedad/detalle/' . $propiedad['id']; ?>" class="propiedad-card">
                        <!-- Imagen -->
                        <div class="propiedad-card-image">
                            <?php if ($esFavorito): ?>
                                <span class="badge-favorito">
                                    <i class="fas fa-award me-1"></i>Favorito entre huéspedes
                                </span>
                            <?php endif; ?>
                            
                            <img src="<?php echo $rutaImagen; ?>" 
                                 alt="<?php echo htmlspecialchars($propiedad['estilo']); ?>"
                                 onerror="this.src='<?php echo RUTA_PRINCIPAL; ?>assets/principal/images/default-casa.jpg'">
                        </div>
                        
                        <!-- Info de la propiedad -->
                        <div class="propiedad-card-info">
                            <div class="propiedad-card-header">
                                <h3 class="propiedad-card-location">
                                    <?php echo htmlspecialchars($propiedad['estilo']); ?>
                                </h3>
                                <?php if ($rating > 0): ?>
                                    <div class="propiedad-card-rating">
                                        <i class="fas fa-star"></i>
                                        <span><?php echo $rating; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <p class="propiedad-card-title">Casa vacacional en Tecolutla</p>
                            
                            <p class="propiedad-card-details">
                                <?php echo $propiedad['capacidad']; ?> huéspedes · 
                                <?php echo $propiedad['habitaciones_num'] ?? 1; ?> habitación · 
                                <?php echo $propiedad['camas'] ?? 1; ?> cama · 
                                <?php echo $propiedad['banos'] ?? 1; ?> baño
                            </p>
                            
                            <p class="propiedad-card-price">
                                <strong>$<?php echo number_format($propiedad['precio'], 0); ?> MXN</strong>
                                <span>por noche</span>
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-home fa-4x text-muted mb-3"></i>
                    <h3>No hay propiedades disponibles</h3>
                    <p class="text-muted">Pronto agregaremos más casas vacacionales.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- FIN CATÁLOGO AIRBNB -->



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