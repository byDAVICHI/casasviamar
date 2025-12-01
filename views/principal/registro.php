<?php
include_once 'views/template/header-principal.php';
?>

<section class="section bg-light pb-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6" data-aos="fade-up">
                <h2 class="heading text-center"><?php echo __('auth_register'); ?></h2>
                <form id="formulario" autocomplete="off" class="check-form">
                    <div class="form-group">
                        <label class="font-weight-bold text-black"><?php echo __('auth_full_name'); ?></label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-user"></span></div>
                            <input type="text" name="nombre" class="form-control" placeholder="<?php echo __('placeholder_full_name'); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-black"><?php echo __('auth_last_name'); ?></label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-user"></span></div>
                            <input type="text" name="apellido" class="form-control" placeholder="<?php echo __('placeholder_last_name'); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-black"><?php echo __('auth_username'); ?></label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-user"></span></div>
                            <input type="text" name="usuario" class="form-control" placeholder="<?php echo __('placeholder_username'); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-black"><?php echo __('auth_email'); ?></label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-envelope"></span></div>
                            <input type="email" name="correo" class="form-control" placeholder="<?php echo __('placeholder_email'); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-black"><?php echo __('auth_password'); ?></label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-lock"></span></div>
                            <input type="password" name="clave" class="form-control" placeholder="<?php echo __('placeholder_create_password'); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-black"><?php echo __('auth_confirm_password'); ?></label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-lock"></span></div>
                            <input type="password" name="confirmar" class="form-control" placeholder="<?php echo __('placeholder_confirm_password'); ?>" required>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block text-white" type="submit"><?php echo __('auth_register'); ?></button>
                </form>
                <p class="text-center mt-3">
                    <?php echo __('auth_have_account'); ?> <a href="<?php echo RUTA_PRINCIPAL . 'login'; ?>" class="text-primary"><?php echo __('auth_login_here'); ?></a>
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