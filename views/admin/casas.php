<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="icon" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/logodefinitivo.png" type="image/png">
    <link rel="shortcut icon" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/logodefinitivo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            border-radius: 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            padding: 2rem;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .casa-card {
            margin-bottom: 1.5rem;
        }
        .casa-image {
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
        .casa-placeholder {
            height: 200px;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: #1976d2;
            font-size: 3rem;
        }
        .btn-action {
            margin: 0 2px;
            padding: 0.375rem 0.75rem;
            border-radius: 8px;
        }
        .btn-crear {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            border: none;
            color: white;
            font-weight: 600;
        }
        .btn-crear:hover {
            background: linear-gradient(135deg, #4a9428 0%, #96d9b8 100%);
            color: white;
        }
        .estado-activo {
            background-color: #d4edda;
            color: #155724;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .estado-inactivo {
            background-color: #f8d7da;
            color: #721c24;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .precio-destacado {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .casa-info {
            padding: 1rem;
        }
        .casa-titulo {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .casa-descripcion {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .casa-detalles {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .detalle-item {
            text-align: center;
        }
        .detalle-valor {
            font-weight: 600;
            color: #333;
        }
        .detalle-label {
            font-size: 0.8rem;
            color: #666;
        }
        .search-container {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="p-3">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-user-shield"></i> Admin Panel
                    </h4>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="<?php echo RUTA_ADMIN; ?>dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="<?php echo RUTA_ADMIN; ?>reservas">
                            <i class="fas fa-calendar-alt me-2"></i> Calendario de Reservas
                        </a>
                        <a class="nav-link" href="<?php echo RUTA_ADMIN; ?>nueva_reservacion">
                            <i class="fas fa-plus-circle me-2"></i> Nueva Reservación
                        </a>
                        <a class="nav-link active" href="<?php echo RUTA_ADMIN; ?>casas">
                            <i class="fas fa-home me-2"></i> Gestión de Casas
                        </a>
                        <hr class="my-3">
                        <a class="nav-link" href="<?php echo RUTA_PRINCIPAL; ?>" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i> Ver Sitio Web
                        </a>
                        <a class="nav-link" href="<?php echo RUTA_ADMIN; ?>logout">
                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">
                            <i class="fas fa-home text-primary me-2"></i>
                            Gestión de Casas Vacacionales
                        </h2>
                        <p class="text-muted mb-0">Administra el catálogo de casas vacacionales disponibles</p>
                    </div>
                    <div>
                        <span class="text-muted me-3">
                            Bienvenido, <strong><?php echo $_SESSION['nombre_admin'] ?? 'Administrador'; ?></strong>
                        </span>
                    </div>
                </div>

                <!-- Barra de búsqueda y acciones -->
                <div class="search-container">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" id="buscarCasa" placeholder="Buscar por nombre, capacidad o precio...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filtroEstado">
                                <option value="">Todos los estados</option>
                                <option value="1">Activas</option>
                                <option value="0">Inactivas</option>
                            </select>
                        </div>
                        <div class="col-md-3 text-end">
                            <button class="btn btn-crear" id="btnNuevaCasa">
                                <i class="fas fa-plus me-2"></i>Nueva Casa
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Contador de casas -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Total de casas: <strong id="totalCasas"><?php echo count($data['casas']); ?></strong>
                        </div>
                    </div>
                </div>

                <!-- Grid de casas -->
                <div class="row" id="casasContainer">
                    <?php if (!empty($data['casas'])): ?>
                        <?php foreach ($data['casas'] as $casa): ?>
                            <div class="col-lg-4 col-md-6 casa-item" data-casa-id="<?php echo $casa['id']; ?>">
                                <div class="card casa-card">
                                    <!-- Imagen de la casa -->
                                    <div class="card-img-top">
                                        <?php if (!empty($casa['foto'])): ?>
                                            <img src="<?php echo RUTA_PRINCIPAL . 'assets/principal/images/' . $casa['foto']; ?>" 
                                                 class="casa-image w-100" alt="<?php echo $casa['estilo']; ?>">
                                        <?php else: ?>
                                            <div class="casa-placeholder">
                                                <i class="fas fa-home"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Información de la casa -->
                                    <div class="casa-info">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="casa-titulo"><?php echo $casa['estilo']; ?></h5>
                                            <span class="<?php echo $casa['estado'] == 1 ? 'estado-activo' : 'estado-inactivo'; ?>">
                                                <?php echo $casa['estado'] == 1 ? 'Activa' : 'Inactiva'; ?>
                                            </span>
                                        </div>
                                        
                                        <p class="casa-descripcion"><?php echo $casa['descripcion']; ?></p>
                                        
                                        <div class="casa-detalles">
                                            <div class="detalle-item">
                                                <div class="detalle-valor">#<?php echo $casa['numero']; ?></div>
                                                <div class="detalle-label">Número</div>
                                            </div>
                                            <div class="detalle-item">
                                                <div class="detalle-valor"><?php echo $casa['capacidad']; ?></div>
                                                <div class="detalle-label">Personas</div>
                                            </div>
                                            <div class="detalle-item">
                                                <div class="detalle-valor precio-destacado">$<?php echo number_format($casa['precio'], 2); ?></div>
                                                <div class="detalle-label">Por noche</div>
                                            </div>
                                        </div>
                                        
                                        <!-- Acciones -->
                                        <div class="d-flex justify-content-center gap-2 mt-3">
                                            <button class="btn btn-outline-primary btn-action btn-sm" onclick="verCasa(<?php echo $casa['id']; ?>)">
                                                <i class="fas fa-eye"></i> Ver
                                            </button>
                                            <button class="btn btn-outline-warning btn-action btn-sm" onclick="editarCasa(<?php echo $casa['id']; ?>)">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <button class="btn btn-outline-danger btn-action btn-sm" onclick="eliminarCasa(<?php echo $casa['id']; ?>, '<?php echo $casa['estilo']; ?>')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-home fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No hay casas vacacionales registradas</h4>
                                <p class="text-muted">Comienza agregando tu primera casa vacacional</p>
                                <button class="btn btn-crear" id="btnPrimeraCasa">
                                    <i class="fas fa-plus me-2"></i>Agregar Primera Casa
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar casa -->
    <div class="modal fade" id="modalCasa" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCasaTitle">
                        <i class="fas fa-home me-2"></i>Nueva Casa Vacacional
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formCasa">
                        <input type="hidden" id="casaId" name="id">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estilo" class="form-label">
                                        <i class="fas fa-tag me-1"></i>Nombre/Estilo *
                                    </label>
                                    <input type="text" class="form-control" id="estilo" name="estilo" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="numero" class="form-label">
                                        <i class="fas fa-hashtag me-1"></i>Número *
                                    </label>
                                    <input type="number" class="form-control" id="numero" name="numero" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="capacidad" class="form-label">
                                        <i class="fas fa-users me-1"></i>Capacidad *
                                    </label>
                                    <input type="number" class="form-control" id="capacidad" name="capacidad" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precio" class="form-label">
                                        <i class="fas fa-dollar-sign me-1"></i>Precio por noche *
                                    </label>
                                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">
                                        <i class="fas fa-toggle-on me-1"></i>Estado
                                    </label>
                                    <select class="form-select" id="estado" name="estado">
                                        <option value="1">Activa</option>
                                        <option value="0">Inactiva</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">
                                <i class="fas fa-link me-1"></i>Slug (URL amigable)
                            </label>
                            <input type="text" class="form-control" id="slug" name="slug" placeholder="Se genera automáticamente si se deja vacío">
                        </div>

                        <!-- Gestión de imagen -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-image me-1"></i>Foto principal
                            </label>
                            
                            <!-- Preview de imagen actual -->
                            <div id="imagenPreview" class="mb-3" style="display: none;">
                                <div class="position-relative d-inline-block">
                                    <img id="imagenActual" src="" alt="Imagen actual" class="img-thumbnail" style="max-width: 200px; max-height: 150px; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" id="btnEliminarImagen" style="transform: translate(50%, -50%);">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Imagen actual: <span id="nombreImagenActual"></span></small>
                                </div>
                            </div>
                            
                            <!-- Input para subir nueva imagen -->
                            <div class="input-group">
                                <input type="file" class="form-control" id="inputImagen" accept="image/*">
                                <button type="button" class="btn btn-outline-primary" id="btnSubirImagen">
                                    <i class="fas fa-upload me-1"></i>Subir
                                </button>
                            </div>
                            <div class="form-text">
                                Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo: 5MB
                            </div>
                            
                            <!-- Input oculto para el nombre del archivo -->
                            <input type="hidden" id="foto" name="foto">
                        </div>

                        <div class="mb-3">
                            <label for="video" class="form-label">
                                <i class="fas fa-video me-1"></i>Video
                            </label>
                            <input type="text" class="form-control" id="video" name="video" placeholder="URL del video">
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Descripción *
                            </label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="btnGuardarCasa">
                        <i class="fas fa-save me-2"></i>Guardar Casa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script src="<?php echo RUTA_PRINCIPAL; ?>assets/admin/js/casas.js"></script>
</body>
</html>
