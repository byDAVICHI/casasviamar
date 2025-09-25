<?php
include_once 'views/template/header-principal.php';
?>

<section class="section bg-light pb-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6" data-aos="fade-up">
                <h2 class="heading text-center">Iniciar Sesión</h2>
                <form id="formulario" autocomplete="off" class="check-form">
                    <div class="form-group">
                        <label class="font-weight-bold text-black">Correo Electrónico</label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-envelope"></span></div>
                            <input type="text" name="usuario" class="form-control" placeholder="Ingrese su usuario o correo electrónico" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-black">Contraseña</label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-lock"></span></div>
                            <input type="password" name="clave" class="form-control" placeholder="Ingrese su contraseña" required>
                        </div>
                    </div>
                    <p class="text-center mt-3">
                        <a href="" class="text-primary">OLVIDASTE TU CONTRASEÑA?</a>
                    </p>
                    <button class="btn btn-primary btn-block text-white" type="submit">Iniciar Sesión</button>
                </form>
                <p class="text-center mt-3">
                    ¿No tienes cuenta? <a href="<?php echo RUTA_PRINCIPAL . 'registro'; ?>" class="text-primary">Regístrate aquí</a>
                </p>

            </div>
        </div>
    </div>
</section>

<?php
include_once 'views/template/footer-principal.php';
?>

<script src=" <?php echo RUTA_PRINCIPAL . 'assets/principal/js/pages/login.js'  ?>"></script>

</body>

</html>