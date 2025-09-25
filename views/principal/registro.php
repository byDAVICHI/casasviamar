<?php
include_once 'views/template/header-principal.php';
?>

<section class="section bg-light pb-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6" data-aos="fade-up">
                <h2 class="heading text-center">Crear Cuenta</h2>
                <form id="formulario" autocomplete="off" class="check-form">
                    <div class="form-group">
                        <label class="font-weight-bold text-black">Nombre Completo</label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-user"></span></div>
                            <input type="text" name="nombre" class="form-control" placeholder="Ingrese su nombre completo" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-black">Apellidos</label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-user"></span></div>
                            <input type="text" name="apellido" class="form-control" placeholder="Ingrese sus apellidos" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-black">Usuario</label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-user"></span></div>
                            <input type="text" name="usuario" class="form-control" placeholder="Ingrese su nombre de usuario" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-black">Correo Electrónico</label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-envelope"></span></div>
                            <input type="email" name="correo" class="form-control" placeholder="Ingrese su correo electrónico" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-black">Contraseña</label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-lock"></span></div>
                            <input type="password" name="clave" class="form-control" placeholder="Cree una contraseña" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-black">Confirmar Contraseña</label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-lock"></span></div>
                            <input type="password" name="confirmar" class="form-control" placeholder="Confirme su contraseña" required>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block text-white" type="submit">Crear Cuenta</button>
                </form>
                <p class="text-center mt-3">
                    ¿Ya tienes cuenta? <a href="<?php echo RUTA_PRINCIPAL . 'login'; ?>" class="text-primary">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>
</section>


<?php
include_once 'views/template/footer-principal.php';
?>

<script src=" <?php echo RUTA_PRINCIPAL . 'assets/principal/js/pages/registro.js'  ?>"></script>

</body>

</html>