<?php
include_once 'views/template/header-principal.php';
?>




<div class="row justify-content-center text-center mb-5">
    <div class="col-md-7">
        <h2 class="heading" data-aos="fade-up">RESERVACIÓNES</h2>
        <p data-aos="fade-up" data-aos-delay="100">Verificar Disponibilidad</p>
    </div>
</div>

<section class="section bg-light pb-0">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-<?php echo $data['tipo']; ?> alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <strong>RESPUESTA!</strong> <?php echo $data['mensaje']; ?>
                        </div>

                        <div id='calendar'></div>
                        <!-- END CALENDAR -->
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <form method="get" id="formulario" class="check-form" action="<?php echo RUTA_PRINCIPAL . 'reserva/verify'; ?>">
                            <label class="font-weight-bold text-black">Fecha Llegada</label>
                            <div class="field-icon-wrap">
                                <div class="icon"><span class="icon-calendar"></span></div>
                                <input id="f_llegada" name="f_llegada" type="date" class="form-control" value="<?php echo $data['disponible']['f_llegada'] ?>">
                            </div>
                            <label class="font-weight-bold text-black">Fecha Salida</label>
                            <div class="field-icon-wrap">
                                <div class="icon"><span class="icon-calendar"></span></div>
                                <input id="f_salida" name="f_salida" type="date" class="form-control" value="<?php echo $data['disponible']['f_salida'] ?>">
                            </div>
                            <label for="habitacion" class="font-weight-bold text-black">Casa</label>
                            <div class="field-icon-wrap">

                                <select id="habitacion" name="habitacion" class="form-control" style="width: 215px;">
                                    <option value="">Seleccionar</option>
                                    <?php foreach ($data['habitaciones'] as $habitacion) { ?>
                                        <option value="<?php echo $habitacion['id']; ?>" <?php echo ($habitacion['id'] == $data['disponible']['habitacion']) ? 'selected' : ''; ?>>
                                            <?php echo $habitacion['estilo']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <br>
                            <div class="field-icon-wrap">
                                <button class="btn btn-primary btn-block text-white" type="submit">Comprobar </button>
                            </div>
                            <br>
                            <a href="#" class="room">
                                <figure class="img-wrap">
                                    <img src="<?php echo RUTA_PRINCIPAL . 'assets/img/habitaciones/' . $data['habitacion']['foto']; ?>" alt="Free website template" class="img-fluid mb-3">
                                    <label class="font-weight-bold text-black"><?php echo $data['habitacion']['estilo']; ?></label>
                                    <label class="price text-black">$ <?php echo $data['habitacion']['precio']; ?> | NOCHE</label>
                                </figure>
                            </a>
                            <br>
                            <div class="field-icon-wrap">
                                <?php if (!empty($_SESSION['id_usuario'])) { ?>
                                    <a href="<?php echo RUTA_PRINCIPAL . 'reserva/pendiente'; ?>" class="btn btn-primary btn-block text-white">Reservar</a>
                                <?php } else { ?>
                                    <a href="<?php echo RUTA_PRINCIPAL . 'login'; ?>" class="btn btn-primary btn-block text-white">Login</a>
                                <?php } ?>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- END section -->
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
<script src=" <?php echo RUTA_PRINCIPAL . 'assets/principal/js/pages/reservas.js'  ?>"></script>

</body>

</html>