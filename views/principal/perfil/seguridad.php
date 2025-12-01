<?php
include_once 'views/template/header-cliente.php';

$usuario = $data['usuario'] ?? [];
$fotoUsuario = $usuario['foto'] ?? null;
$nombreCompleto = trim(($usuario['nombre'] ?? '') . ' ' . ($usuario['apellido'] ?? ''));
$inicialUsuario = strtoupper(substr($usuario['nombre'] ?? 'U', 0, 1));
?>

<div class="main-wrapper">
    <div class="container">
        <div class="row g-4">
            <!-- Columna Izquierda - Menú de Navegación -->
            <div class="col-lg-3">
                <div class="profile-nav">
                    <!-- Avatar grande -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <?php if ($fotoUsuario && file_exists('assets/img/usuarios/' . $fotoUsuario)): ?>
                                <img src="<?php echo RUTA_PRINCIPAL . 'assets/img/usuarios/' . $fotoUsuario; ?>" 
                                     alt="<?php echo htmlspecialchars($nombreCompleto); ?>" 
                                     class="rounded-circle"
                                     style="width: 100px; height: 100px; object-fit: cover; border: 3px solid var(--airbnb-light-gray);">
                            <?php else: ?>
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--airbnb-pink), #E31C5F); color: #fff; font-size: 36px; font-weight: 600; margin: 0 auto;">
                                    <?php echo $inicialUsuario; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h5 class="mt-3 mb-1 fw-bold"><?php echo htmlspecialchars($nombreCompleto ?: 'Usuario'); ?></h5>
                        <p class="text-muted small">@<?php echo htmlspecialchars($usuario['usuario'] ?? ''); ?></p>
                    </div>
                    
                    <!-- Links de navegación -->
                    <a href="<?php echo RUTA_PRINCIPAL; ?>perfil" class="profile-nav-item <?php echo ($data['active'] ?? '') === 'perfil' ? 'active' : ''; ?>">
                        <i class="fas fa-user"></i> Información Personal
                    </a>
                    <a href="<?php echo RUTA_PRINCIPAL; ?>perfil/seguridad" class="profile-nav-item <?php echo ($data['active'] ?? '') === 'seguridad' ? 'active' : ''; ?>">
                        <i class="fas fa-shield-alt"></i> Seguridad
                    </a>
                    <a href="<?php echo RUTA_PRINCIPAL; ?>perfil/reservas" class="profile-nav-item <?php echo ($data['active'] ?? '') === 'reservas' ? 'active' : ''; ?>">
                        <i class="fas fa-suitcase"></i> Mis Viajes
                    </a>
                    <a href="<?php echo RUTA_PRINCIPAL; ?>reserva/pendiente" class="profile-nav-item">
                        <i class="fas fa-clock"></i> Reservas Pendientes
                    </a>
                </div>
            </div>
            
            <!-- Columna Derecha - Contenido -->
            <div class="col-lg-9">
                <div class="card-airbnb">
                    <div class="card-header">
                        <h5><i class="fas fa-shield-alt me-2" style="color: var(--airbnb-pink);"></i> Cambiar Contraseña</h5>
                    </div>
                    <div class="card-body">
                        <form id="formCambiarPassword">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label-airbnb">Contraseña Actual</label>
                                    <div class="input-group">
                                        <input type="password" name="password_actual" id="password_actual" 
                                               class="form-control form-control-airbnb" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_actual')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-airbnb">Nueva Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" name="password_nueva" id="password_nueva" 
                                               class="form-control form-control-airbnb" required minlength="6">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_nueva')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Mínimo 6 caracteres</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-airbnb">Confirmar Nueva Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmar" id="password_confirmar" 
                                               class="form-control form-control-airbnb" required minlength="6">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmar')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-airbnb">
                                    <i class="fas fa-lock me-2"></i> Cambiar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Tips de seguridad -->
                <div class="card-airbnb mt-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="fas fa-lightbulb me-2" style="color: #ffc107;"></i> Consejos de Seguridad</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex align-items-start mb-2">
                                <i class="fas fa-check-circle me-2 mt-1" style="color: #28a745;"></i>
                                <span class="text-muted small">Usa una combinación de letras mayúsculas, minúsculas, números y símbolos.</span>
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <i class="fas fa-check-circle me-2 mt-1" style="color: #28a745;"></i>
                                <span class="text-muted small">No uses información personal como tu nombre o fecha de nacimiento.</span>
                            </li>
                            <li class="d-flex align-items-start">
                                <i class="fas fa-check-circle me-2 mt-1" style="color: #28a745;"></i>
                                <span class="text-muted small">No compartas tu contraseña con nadie.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/template/footer-cliente.php'; ?>

<script>
// Toggle visibilidad de contraseña
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Formulario de cambiar contraseña
document.getElementById('formCambiarPassword').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const passwordNueva = document.getElementById('password_nueva').value;
    const passwordConfirmar = document.getElementById('password_confirmar').value;
    
    if (passwordNueva !== passwordConfirmar) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Las contraseñas no coinciden',
            confirmButtonColor: '#FF385C'
        });
        return;
    }
    
    const formData = new FormData(this);
    
    Swal.fire({
        title: 'Cambiando contraseña...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    fetch(base_url + 'perfil/cambiarPassword', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.tipo === 'success') {
            Swal.fire({
                icon: 'success',
                title: '¡Listo!',
                text: data.msg,
                confirmButtonColor: '#FF385C'
            }).then(() => {
                document.getElementById('formCambiarPassword').reset();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.msg,
                confirmButtonColor: '#FF385C'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al cambiar la contraseña',
            confirmButtonColor: '#FF385C'
        });
    });
});
</script>

</body>
</html>
