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
                                     id="avatarPreview"
                                     style="width: 100px; height: 100px; object-fit: cover; border: 3px solid var(--airbnb-light-gray);">
                            <?php else: ?>
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     id="avatarPlaceholder"
                                     style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--airbnb-pink), #E31C5F); color: #fff; font-size: 36px; font-weight: 600; margin: 0 auto;">
                                    <?php echo $inicialUsuario; ?>
                                </div>
                            <?php endif; ?>
                            <button class="btn btn-sm position-absolute bottom-0 end-0 rounded-circle shadow"
                                    style="background: #fff; width: 32px; height: 32px; padding: 0;"
                                    onclick="document.getElementById('inputFoto').click()">
                                <i class="fas fa-camera" style="color: var(--airbnb-gray);"></i>
                            </button>
                            <input type="file" id="inputFoto" accept="image/*" style="display: none;" onchange="subirFoto(this)">
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
                        <h5><i class="fas fa-user me-2" style="color: var(--airbnb-pink);"></i> Información Personal</h5>
                    </div>
                    <div class="card-body">
                        <form id="formDatosPersonales">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label-airbnb">Nombre</label>
                                    <input type="text" name="nombre" class="form-control form-control-airbnb" 
                                           value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-airbnb">Apellido</label>
                                    <input type="text" name="apellido" class="form-control form-control-airbnb" 
                                           value="<?php echo htmlspecialchars($usuario['apellido'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-airbnb">Nombre de Usuario</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: var(--airbnb-bg); border-color: var(--airbnb-light-gray);">@</span>
                                        <input type="text" name="usuario" class="form-control form-control-airbnb" 
                                               value="<?php echo htmlspecialchars($usuario['usuario'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-airbnb">Correo Electrónico</label>
                                    <input type="email" name="correo" class="form-control form-control-airbnb" 
                                           value="<?php echo htmlspecialchars($usuario['correo'] ?? ''); ?>" required>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-airbnb">
                                    <i class="fas fa-save me-2"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Info adicional -->
                <div class="card-airbnb mt-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-info-circle" style="font-size: 24px; color: var(--airbnb-gray);"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted small">
                                    Tu información personal se utiliza para personalizar tu experiencia y facilitar tus reservaciones.
                                    Nunca compartiremos tus datos con terceros.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/template/footer-cliente.php'; ?>

<script>
// Subir foto de perfil
function subirFoto(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('foto', input.files[0]);
        
        Swal.fire({
            title: 'Subiendo foto...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        fetch(base_url + 'perfil/subirFoto', {
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
                    location.reload();
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
                text: 'Error al subir la foto',
                confirmButtonColor: '#FF385C'
            });
        });
    }
}

// Formulario de datos personales
document.getElementById('formDatosPersonales').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    Swal.fire({
        title: 'Guardando...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    fetch(base_url + 'perfil/actualizarDatos', {
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
                location.reload();
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
            text: 'Error al guardar los datos',
            confirmButtonColor: '#FF385C'
        });
    });
});
</script>

</body>
</html>
