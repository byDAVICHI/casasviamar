<?php
include_once 'views/template/header-principal.php';
?>

<section class="section contact-section" id="next">
    <div class="container">
        <div class="row">
            <div class="col-md-7" data-aos="fade-up" data-aos-delay="100">

                <form action="#" method="post" class="bg-white p-md-5 p-4 mb-5 border">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="name">Nombre</label>
                            <input type="text" id="name" class="form-control ">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="phone">Teléfono</label>
                            <input type="text" id="phone" class="form-control ">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="email">Correo Electronico</label>
                            <input type="email" id="email" class="form-control ">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12 form-group">
                            <label for="message">Mensaje</label>
                            <textarea name="message" id="message" class="form-control " cols="30" rows="8"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <a href="https://wa.me/527661151203" class="btn btn-primary text-white">Reservar ahora</a>
                        </div>
                    </div>
                </form>

            </div>
            <div class="col-md-5" data-aos="fade-up" data-aos-delay="200">
                <div class="row">
                    <div class="col-md-10 ml-auto contact-info">
                        <p><span class="d-block">Dirección:</span> <span> Calle Juan de la Barrera, <br> Colonia Emiliano Zapata CP: 93570</span></p>
                        <p><span class="d-block">Teléfono:</span> <span> 782 214 1920 <br> 766 115 1203 </span></p>
                        <p><span class="d-block">Correo Electronico:</span> <span> casasviamar@gmail.com</span></p>
                    </div>
                </div>
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

<?php
include_once 'views/template/footer-principal.php';
?>
</body>

</html>