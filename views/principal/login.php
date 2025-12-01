<?php
include_once 'views/template/header-principal.php';
?>

<section class="section bg-light pb-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6" data-aos="fade-up">
                <h2 class="heading text-center"><?php echo __('auth_login'); ?></h2>
                <form id="formulario" autocomplete="off" class="check-form">
                    <div class="form-group">
                        <label class="font-weight-bold text-black"><?php echo __('auth_email'); ?></label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-envelope"></span></div>
                            <input type="text" name="usuario" class="form-control" placeholder="<?php echo __('placeholder_email'); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-black"><?php echo __('auth_password'); ?></label>
                        <div class="field-icon-wrap">
                            <div class="icon"><span class="icon-lock"></span></div>
                            <input type="password" name="clave" class="form-control" placeholder="<?php echo __('placeholder_password'); ?>" required>
                        </div>
                    </div>
                    <p class="text-center mt-3">
                        <a href="" class="text-primary"><?php echo __('auth_forgot_password'); ?></a>
                    </p>
                    <button class="btn btn-primary btn-block text-white" type="submit"><?php echo __('auth_login'); ?></button>
                </form>
                <p class="text-center mt-3">
                    <?php echo __('auth_no_account'); ?> <a href="<?php echo RUTA_PRINCIPAL . 'registro'; ?>" class="text-primary"><?php echo __('auth_register_here'); ?></a>
                </p>

            </div>
        </div>
    </div>
</section>

<?php
include_once 'views/template/footer-principal.php';
?>

<script src="<?php echo RUTA_PRINCIPAL . 'assets/principal/js/pages/login.js?v=' . time(); ?>"></script>

</body>

</html>