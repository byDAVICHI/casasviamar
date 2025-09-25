<section class="section bg-image overlay" style="background-image: url('<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/Casaviamar-15.jpg');">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 text-center mb-4 mb-md-0 text-md-left" data-aos="fade-up">
                <h2 class="text-white font-weight-bold">EL MEJOR LUGAR PARA QUEDARSE. RESERVA YA!</h2>
            </div>
            <div class="col-12 col-md-6 text-center text-md-right" data-aos="fade-up" data-aos-delay="200">
                <a href="https://wa.me/527661151203" class="btn btn-outline-white-primary py-3 text-white px-5">RESERVAR AHORA</a>
            </div>
        </div>
    </div>
</section>

<footer class="section footer-section">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-3 mb-5">
                <ul class="list-unstyled link">
                    <li><a href="#">Acerca de</a></li>
                    <li><a href="#">Terminos &amp; Condiciones</a></li>
                    <li><a href="#">Politica de Privacidad</a></li>
                    <li><a href="<?php echo RUTA_PRINCIPAL . 'views/admin/'; ?>index.php">Administrador</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-5">
                <ul class="list-unstyled link">
                    <li><a href="#">Casas Vacacionales Via-Mar</a></li>
                    <li><a href="#">Acerca de</a></li>
                    <li><a href="#">Contacto</a></li>
                    <li><a href="#">Restaurant</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-5 pr-md-5 contact-info">
                <!-- <li>198 West 21th Street, <br> Suite 721 New York NY 10016</li> -->
                <p><span class="d-block"><span class="ion-ios-location h5 mr-3 text-primary"></span>Dirección:</span> <span> Calle Juan de la Barrera, <br> Colonia Emiliano Zapata CP: 93570</span></p>
                <p><span class="d-block"><span class="ion-ios-telephone h5 mr-3 text-primary"></span>Teléfono:</span> <span> 782 214 1920 <br> 766 115 1203 </span></p>
                <p><span class="d-block"><span class="ion-ios-email h5 mr-3 text-primary"></span>Correo Electronico:</span> <span> casasviamar@gmail.com</span></p>
            </div>
            <div class="col-md-3 mb-5">
                <p>Suscríbete</p>
                <form action="#" class="footer-newsletter">
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email...">
                        <button type="submit" class="btn"><span class="fa fa-paper-plane"></span></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row pt-5">
            <p class="col-md-6 text-left">
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                Copyright &copy;<script>
                    document.write(new Date().getFullYear());
                </script>
                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </p>

            <p class="col-md-6 text-right social">
                <a href="https://wa.me/527661151203"><span class="fa fa-whatsapp"></span></a>
                <a href="https://www.facebook.com/profile.php?id=100064771851775"><span class="fa fa-facebook"></span></a>
                <a href="https://www.instagram.com/casasviamar/"><span class="fa fa-instagram"></span></a>
            </p>
        </div>
    </div>
</footer>

<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>js/jquery-3.3.1.min.js"></script>
<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>js/jquery-migrate-3.0.1.min.js"></script>
<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>js/popper.min.js"></script>
<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>js/bootstrap.min.js"></script>
<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>js/owl.carousel.min.js"></script>
<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>js/jquery.stellar.min.js"></script>
<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>js/jquery.fancybox.min.js"></script>


<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>js/aos.js"></script>

<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>js/bootstrap-datepicker.js"></script>
<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>js/jquery.timepicker.min.js"></script>

<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>assets/principal/js/pages/index.js"></script>

<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>js/main.js"></script>

<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>fullcalendar/index.global.min.js"></script>
<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>fullcalendar/es.global.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const base_url = '<?php echo RUTA_PRINCIPAL; ?>';

    function alertSW(mensaje, tipo) {

        Swal.fire({
            position: "top-end",
            icon: tipo,
            title: mensaje,
            showConfirmButton: false,
            timer: 5500,
            toast: true
        });

    }
</script>