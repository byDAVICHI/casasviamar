<?php
include_once 'views/template/header-principal.php';
?>

<!-- Font Awesome 5 para iconos modernos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- CSS Airbnb -->
<link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL; ?>assets/principal/css/airbnb-style.css">

<div class="catalogo-container">
    <div class="container">
        <!-- Header del catálogo -->
        <div class="catalogo-header">
            <h1 class="catalogo-title"><?php echo __('catalog_title'); ?></h1>
            <p class="catalogo-subtitle"><?php echo count($data['propiedades']); ?> <?php echo __('catalog_results'); ?></p>
        </div>

        <!-- Filtros (opcional) -->
        <div class="filtros-container mb-4">
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-outline-dark rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalFiltros">
                    <i class="fas fa-sliders-h me-2"></i><?php echo __('catalog_filters'); ?>
                </button>
                <button class="btn btn-outline-dark rounded-pill px-3"><?php echo __('catalog_price_range'); ?></button>
                <button class="btn btn-outline-dark rounded-pill px-3 d-none d-md-inline-block"><?php echo __('catalog_bedrooms'); ?></button>
                <button class="btn btn-outline-dark rounded-pill px-3 d-none d-lg-inline-block"><?php echo __('catalog_amenities'); ?></button>
            </div>
        </div>

        <!-- Grid de Propiedades -->
        <div class="propiedades-grid">
            <?php if (!empty($data['propiedades'])): ?>
                <?php foreach ($data['propiedades'] as $propiedad): ?>
                    <?php 
                    $rutaImagen = obtenerRutaImagenCasa($propiedad['foto']);
                    $rating = number_format($propiedad['rating'] ?? $propiedad['calificacion_promedio'] ?? 0, 2);
                    $numEvaluaciones = $propiedad['num_evaluaciones'] ?? $propiedad['total_evaluaciones'] ?? 0;
                    $esFavorito = !empty($propiedad['es_favorito_huespedes']);
                    ?>
                    <a href="<?php echo RUTA_PRINCIPAL . 'propiedad/detalle/' . $propiedad['id']; ?>" class="propiedad-card">
                        <!-- Imagen con carrusel -->
                        <div class="propiedad-card-image">
                            <?php if ($esFavorito): ?>
                                <span class="badge-favorito">
                                    <i class="fas fa-award me-1"></i>Favorito entre huéspedes
                                </span>
                            <?php endif; ?>
                            
                            <button class="btn-favorito <?php echo isset($_SESSION['id_usuario']) && $propiedad['es_usuario_favorito'] ? 'active' : ''; ?>" 
                                    onclick="event.preventDefault(); toggleFavorito(<?php echo $propiedad['id']; ?>, this);">
                                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false">
                                    <path d="m16 28c7-4.73 14-10 14-17a6.98 6.98 0 0 0 -7-7c-1.8 0-3.58.68-4.95 2.05l-2.05 2.05-2.05-2.05a6.98 6.98 0 0 0 -4.95-2.05 6.98 6.98 0 0 0 -7 7c0 7 7 12.27 14 17z"></path>
                                </svg>
                            </button>
                            
                            <img src="<?php echo $rutaImagen; ?>" 
                                 alt="<?php echo htmlspecialchars($propiedad['estilo']); ?>"
                                 onerror="this.src='<?php echo RUTA_PRINCIPAL; ?>assets/principal/images/default-casa.jpg'">
                        </div>
                        
                        <!-- Info de la propiedad -->
                        <div class="propiedad-card-info">
                            <div class="propiedad-card-header">
                                <h3 class="propiedad-card-location">
                                    <?php echo htmlspecialchars($propiedad['estilo']); ?>
                                </h3>
                                <?php if ($rating > 0): ?>
                                    <div class="propiedad-card-rating">
                                        <i class="fas fa-star"></i>
                                        <span><?php echo $rating; ?></span>
                                        <?php if ($numEvaluaciones > 0): ?>
                                            <span class="text-muted">(<?php echo $numEvaluaciones; ?>)</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <p class="propiedad-card-title">Casa vacacional en Tecolutla</p>
                            
                            <p class="propiedad-card-details">
                                <?php echo $propiedad['capacidad']; ?> <?php echo __('property_guests'); ?> · 
                                <?php echo $propiedad['habitaciones_num'] ?? 1; ?> <?php echo __('property_bedrooms'); ?> · 
                                <?php echo $propiedad['camas'] ?? 1; ?> <?php echo __('property_beds'); ?> · 
                                <?php echo $propiedad['banos'] ?? 1; ?> <?php echo __('property_bathrooms'); ?>
                            </p>
                            
                            <p class="propiedad-card-price">
                                <strong>$<?php echo number_format($propiedad['precio'], 2); ?> MXN</strong>
                                <span><?php echo __('property_per_night'); ?></span>
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-home fa-4x text-muted mb-3"></i>
                    <h3><?php echo __('catalog_no_results'); ?></h3>
                    <p class="text-muted"><?php echo __('msg_no_results'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de Filtros -->
<div class="modal fade" id="modalFiltros" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h5 class="modal-title w-100 text-center fw-bold"><?php echo __('catalog_filters'); ?></h5>
                <div style="width: 32px;"></div>
            </div>
            <div class="modal-body">
                <!-- Rango de precio -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3"><?php echo __('catalog_price_range'); ?></h6>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label small">Precio mínimo</label>
                            <input type="number" class="form-control" id="filtro_precio_min" placeholder="$0">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Precio máximo</label>
                            <input type="number" class="form-control" id="filtro_precio_max" placeholder="$10,000+">
                        </div>
                    </div>
                </div>
                
                <!-- Habitaciones -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3"><?php echo __('catalog_bedrooms'); ?></h6>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small">Habitaciones</label>
                            <select class="form-select" id="filtro_habitaciones">
                                <option value="">Cualquiera</option>
                                <option value="1">1+</option>
                                <option value="2">2+</option>
                                <option value="3">3+</option>
                                <option value="4">4+</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small">Huéspedes</label>
                            <select class="form-select" id="filtro_huespedes">
                                <option value="">Cualquiera</option>
                                <option value="2">2+</option>
                                <option value="4">4+</option>
                                <option value="6">6+</option>
                                <option value="8">8+</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-between">
                <button type="button" class="btn btn-link text-dark fw-bold" onclick="limpiarFiltros()"><?php echo __('btn_clear'); ?></button>
                <button type="button" class="btn btn-dark px-4 rounded-pill" onclick="aplicarFiltros()"><?php echo __('btn_filter'); ?></button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/template/footer-principal.php'; ?>

<script>
// Variables globales
const base_url = '<?php echo RUTA_PRINCIPAL; ?>';

// Toggle favorito
function toggleFavorito(id, btn) {
    <?php if (!isset($_SESSION['id_usuario'])): ?>
        window.location.href = base_url + 'login';
        return;
    <?php endif; ?>
    
    const isActive = btn.classList.contains('active');
    const action = isActive ? 'quitarFavorito' : 'agregarFavorito';
    
    fetch(base_url + 'propiedad/' + action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'id_habitacion=' + id
    })
    .then(response => response.json())
    .then(data => {
        if (data.tipo === 'success') {
            btn.classList.toggle('active');
        }
    })
    .catch(error => console.error('Error:', error));
}

// Aplicar filtros
function aplicarFiltros() {
    const params = new URLSearchParams();
    
    const precioMin = document.getElementById('filtro_precio_min').value;
    const precioMax = document.getElementById('filtro_precio_max').value;
    const habitaciones = document.getElementById('filtro_habitaciones').value;
    const huespedes = document.getElementById('filtro_huespedes').value;
    
    if (precioMin) params.append('precio_min', precioMin);
    if (precioMax) params.append('precio_max', precioMax);
    if (habitaciones) params.append('habitaciones', habitaciones);
    if (huespedes) params.append('capacidad_min', huespedes);
    
    window.location.href = base_url + 'catalogo?' + params.toString();
}

// Limpiar filtros
function limpiarFiltros() {
    document.getElementById('filtro_precio_min').value = '';
    document.getElementById('filtro_precio_max').value = '';
    document.getElementById('filtro_habitaciones').value = '';
    document.getElementById('filtro_huespedes').value = '';
}
</script>

<?php include_once 'views/template/footer-principal.php'; ?>
