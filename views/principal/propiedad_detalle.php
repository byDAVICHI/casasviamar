<?php
include_once 'views/template/header-principal.php';

// Datos de la propiedad
$propiedad = $data['propiedad'];
$fotos = $propiedad['fotos'] ?? [];
$amenidades = $propiedad['amenidades'] ?? [];
$evaluaciones = $propiedad['evaluaciones'] ?? [];
$estadisticas = $propiedad['estadisticas'] ?? [];

// Valores calculados
$rating = number_format($estadisticas['promedio_general'] ?? $propiedad['calificacion_promedio'] ?? 0, 2);
$totalEvaluaciones = $estadisticas['total_evaluaciones'] ?? $propiedad['total_evaluaciones'] ?? 0;
$precioNoche = $propiedad['precio'];
$tarifaLimpieza = $propiedad['tarifa_limpieza'] ?? 0;

// Foto principal
$fotoPrincipal = obtenerRutaImagenCasa($propiedad['foto']);
?>

<!-- Font Awesome 5 para iconos modernos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- CSS Airbnb -->
<link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL; ?>assets/principal/css/airbnb-style.css">
<!-- Leaflet CSS para mapa -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Estilos específicos para página de detalle - Ocultar hero y header transparente -->
<style>
    /* Ocultar hero del template principal COMPLETAMENTE */
    .site-hero,
    .site-hero.overlay,
    section.site-hero {
        display: none !important;
        height: 0 !important;
        min-height: 0 !important;
        max-height: 0 !important;
        overflow: hidden !important;
        visibility: hidden !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    /* Header fijo blanco */
    header.site-header,
    .site-header.js-site-header {
        background: #fff !important;
        position: sticky !important;
        top: 0;
        z-index: 1000;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 10px 0;
    }
    
    .site-header .site-logo a,
    .site-logo a {
        color: #222 !important;
    }
    
    .header-controls .lang-btn {
        background: rgba(0, 0, 0, 0.05) !important;
        border-color: rgba(0, 0, 0, 0.1) !important;
        color: #222 !important;
    }
    
    .header-controls .site-menu-toggle span {
        background: #222 !important;
    }
    
    /* Body con fondo blanco */
    body {
        background: #fff !important;
    }
    
    /* Footer ajuste */
    .site-footer {
        margin-top: 0;
    }
    
    /* Asegurar que el contenido empiece desde arriba */
    .detalle-container {
        margin-top: 0;
        padding-top: 0;
    }
    
    /* Responsive móvil - Barra inferior */
    @media (max-width: 767px) {
        .detalle-container {
            padding-bottom: 80px !important;
        }
    }
</style>

<!-- Script para remover el hero del DOM -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Remover el hero section del DOM
    const heroSection = document.querySelector('.site-hero');
    if (heroSection) {
        heroSection.remove();
    }
});
</script>

<div class="detalle-container">
    <!-- Header -->
    <div class="detalle-header">
        <h1 class="detalle-title"><?php echo htmlspecialchars($propiedad['estilo']); ?></h1>
        <div class="detalle-subtitle">
            <?php if ($rating > 0): ?>
                <span class="detalle-rating">
                    <i class="fas fa-star"></i> <?php echo $rating; ?>
                </span>
                <span class="separator"></span>
                <span class="detalle-reviews"><?php echo $totalEvaluaciones; ?> evaluaciones</span>
                <span class="separator"></span>
            <?php endif; ?>
            <?php if (!empty($propiedad['es_favorito_huespedes'])): ?>
                <span><i class="fas fa-award me-1"></i>Favorito entre huéspedes</span>
                <span class="separator"></span>
            <?php endif; ?>
            <span class="detalle-location">
                <i class="fas fa-map-marker-alt me-1"></i>
                <?php echo htmlspecialchars($propiedad['direccion'] ?? 'Tecolutla, Veracruz, México'); ?>
            </span>
            
            <div class="detalle-actions">
                <button onclick="compartir()"><i class="fas fa-share-alt"></i> Compartir</button>
                <button onclick="toggleFavorito(<?php echo $propiedad['id']; ?>)">
                    <i class="<?php echo isset($data['es_favorito']) && $data['es_favorito'] ? 'fas' : 'far'; ?> fa-heart"></i> Guardar
                </button>
            </div>
        </div>
    </div>

    <!-- Galería Hero -->
    <div class="galeria-hero">
        <div class="galeria-hero-main">
            <img src="<?php echo $fotoPrincipal; ?>" 
                 alt="<?php echo htmlspecialchars($propiedad['estilo']); ?>"
                 onclick="abrirGaleria(0)"
                 onerror="this.src='<?php echo RUTA_PRINCIPAL; ?>assets/principal/images/default-casa.jpg'">
        </div>
        <div class="galeria-hero-grid">
            <?php 
            $fotosGrid = array_slice($fotos, 0, 4);
            $totalFotos = count($fotos);
            foreach ($fotosGrid as $index => $foto): 
                $rutaFoto = RUTA_PRINCIPAL . 'assets/principal/images/propiedades/' . $foto['url_imagen'];
            ?>
                <div class="galeria-hero-item">
                    <img src="<?php echo $rutaFoto; ?>" 
                         alt="Foto <?php echo $index + 2; ?>"
                         onclick="abrirGaleria(<?php echo $index + 1; ?>)"
                         onerror="this.src='<?php echo RUTA_PRINCIPAL; ?>assets/principal/images/default-casa.jpg'">
                    <?php if ($index === 3 && $totalFotos > 4): ?>
                        <button class="btn-mostrar-fotos" onclick="abrirGaleria(0)">
                            <i class="fas fa-th"></i> Mostrar todas las fotos
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <?php // Rellenar con imagen principal si no hay suficientes fotos
            for ($i = count($fotosGrid); $i < 4; $i++): ?>
                <div class="galeria-hero-item">
                    <img src="<?php echo $fotoPrincipal; ?>" 
                         alt="Foto <?php echo $i + 2; ?>"
                         onclick="abrirGaleria(0)">
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="detalle-content">
        <!-- Columna Izquierda - Información -->
        <div class="detalle-info">
            <!-- Info del Host -->
            <div class="info-host">
                <div class="info-host-details">
                    <h2>Casa vacacional alojada por Via-Mar</h2>
                    <p>
                        <?php echo $propiedad['capacidad']; ?> huéspedes · 
                        <?php echo $propiedad['habitaciones_num'] ?? 1; ?> habitación · 
                        <?php echo $propiedad['camas'] ?? 1; ?> cama · 
                        <?php echo $propiedad['banos'] ?? 1; ?> baño
                    </p>
                </div>
                <div class="info-host-avatar">
                    <img src="<?php echo RUTA_PRINCIPAL; ?>assets/principal/images/logodefinitivo.png" alt="Via-Mar">
                </div>
            </div>

            <!-- Highlights -->
            <div class="info-highlights">
                <?php if (!empty($propiedad['es_favorito_huespedes'])): ?>
                <div class="highlight-item">
                    <div class="highlight-icon"><i class="fas fa-award"></i></div>
                    <div class="highlight-text">
                        <h4>Favorito entre huéspedes</h4>
                        <p>Uno de los alojamientos más populares según las valoraciones de los huéspedes.</p>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="highlight-item">
                    <div class="highlight-icon"><i class="fas fa-door-open"></i></div>
                    <div class="highlight-text">
                        <h4>Llegada autónoma</h4>
                        <p>Accede a la propiedad usando la caja de seguridad para llaves.</p>
                    </div>
                </div>
                
                <div class="highlight-item">
                    <div class="highlight-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="highlight-text">
                        <h4>Cancelación gratuita antes de 48 horas</h4>
                        <p>Si cambias de opinión, recibirás un reembolso completo.</p>
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            <div class="info-descripcion">
                <p id="descripcionTexto">
                    <?php echo nl2br(htmlspecialchars($propiedad['descripcion'])); ?>
                </p>
                <button class="btn-mostrar-mas" onclick="toggleDescripcion()">
                    Mostrar más <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <!-- Dónde vas a dormir -->
            <div class="info-dormitorio">
                <h3>Dónde vas a dormir</h3>
                <div class="dormitorio-card">
                    <img src="<?php echo $fotoPrincipal; ?>" alt="Recámara">
                    <h4>Recámara</h4>
                    <p><?php echo $propiedad['camas'] ?? 1; ?> cama<?php echo ($propiedad['camas'] ?? 1) > 1 ? 's' : ''; ?></p>
                </div>
            </div>

            <!-- Amenidades -->
            <div class="info-amenidades">
                <h3>Lo que ofrece este lugar</h3>
                <div class="amenidades-grid">
                    <?php 
                    $amenidadesMostrar = array_slice($amenidades, 0, 10);
                    foreach ($amenidadesMostrar as $amenidad): 
                    ?>
                        <div class="amenidad-item">
                            <i class="<?php echo $amenidad['icono'] ?? 'fas fa-check'; ?>"></i>
                            <span><?php echo htmlspecialchars($amenidad['nombre']); ?></span>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($amenidades)): ?>
                        <!-- Amenidades por defecto -->
                        <div class="amenidad-item"><i class="fas fa-wifi"></i><span>Wifi</span></div>
                        <div class="amenidad-item"><i class="fas fa-utensils"></i><span>Cocina</span></div>
                        <div class="amenidad-item"><i class="fas fa-parking"></i><span>Estacionamiento gratuito</span></div>
                        <div class="amenidad-item"><i class="fas fa-tv"></i><span>TV</span></div>
                        <div class="amenidad-item"><i class="fas fa-snowflake"></i><span>Aire acondicionado</span></div>
                        <div class="amenidad-item"><i class="fas fa-tshirt"></i><span>Lavadora</span></div>
                    <?php endif; ?>
                </div>
                <?php if (count($amenidades) > 10): ?>
                    <button class="btn-mostrar-amenidades" onclick="mostrarTodasAmenidades()">
                        Mostrar las <?php echo count($amenidades); ?> amenidades
                    </button>
                <?php endif; ?>
            </div>

            <!-- Calendario de disponibilidad -->
            <div class="info-calendario">
                <h3><?php echo $data['noches'] ?? 0; ?> noches en Tecolutla</h3>
                <p><?php echo date('j \d\e F \d\e Y'); ?></p>
                <div id="calendario-disponibilidad"></div>
            </div>
        </div>

        <!-- Columna Derecha - Widget de Reserva (Sticky) -->
        <div class="reserva-widget" data-precio="<?php echo $precioNoche; ?>" data-limpieza="<?php echo $tarifaLimpieza; ?>">
            <div class="reserva-precio">
                <span class="reserva-precio-valor">$<?php echo number_format($precioNoche, 2); ?> MXN</span>
                <span class="reserva-precio-periodo">por noche</span>
                <?php if ($rating > 0): ?>
                    <div class="reserva-rating">
                        <i class="fas fa-star"></i>
                        <span><?php echo $rating; ?></span>
                        <span class="text-muted">· <?php echo $totalEvaluaciones; ?> evaluaciones</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="reserva-form">
                <div class="reserva-fechas">
                    <div class="reserva-fecha-input">
                        <label>LLEGADA</label>
                        <input type="date" id="fecha_llegada" name="fecha_llegada" 
                               min="<?php echo date('Y-m-d'); ?>"
                               value="<?php echo $data['fecha_llegada'] ?? ''; ?>">
                    </div>
                    <div class="reserva-fecha-input">
                        <label>SALIDA</label>
                        <input type="date" id="fecha_salida" name="fecha_salida"
                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                               value="<?php echo $data['fecha_salida'] ?? ''; ?>">
                    </div>
                </div>
                
                <!-- Feedback de disponibilidad -->
                <div id="disponibilidad-feedback" class="disponibilidad-feedback" style="display: none;">
                    <div class="feedback-content">
                        <i class="fas fa-spinner fa-spin" id="feedback-loading"></i>
                        <span id="feedback-mensaje"></span>
                    </div>
                </div>
                
                <!-- Enlace al calendario -->
                <div class="ver-calendario" style="text-align: center; margin: 10px 0;">
                    <a href="#" onclick="abrirCalendarioModal(); return false;" style="color: #222; text-decoration: underline; font-size: 14px;">
                        <i class="fas fa-calendar-alt"></i> Ver calendario de disponibilidad
                    </a>
                </div>
                
                <div class="reserva-huespedes" onclick="toggleHuespedes()">
                    <label>HUÉSPEDES</label>
                    <div class="reserva-huespedes-selector">
                        <span id="huespedes_texto">1 huésped</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <input type="hidden" id="num_huespedes" value="1">
                </div>
            </div>

            <button class="btn-reservar" id="btnReservar" onclick="handleReservarClick()" disabled>
                Reservar
            </button>

            <p class="reserva-nota">Aún no se te cobrará nada</p>

            <div class="reserva-desglose" id="desglose" style="display: none;">
                <div class="desglose-item">
                    <span>$<?php echo number_format($precioNoche, 2); ?> MXN x <span id="num_noches">0</span> noches</span>
                    <span id="subtotal_noches">$0.00</span>
                </div>
                <?php if ($tarifaLimpieza > 0): ?>
                <div class="desglose-item">
                    <span>Tarifa de limpieza</span>
                    <span>$<?php echo number_format($tarifaLimpieza, 2); ?></span>
                </div>
                <?php endif; ?>
                <div class="desglose-item">
                    <span>Tarifa de servicio</span>
                    <span id="tarifa_servicio">$0.00</span>
                </div>
                <div class="desglose-total">
                    <span>Total</span>
                    <span id="total_reserva">$0.00</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluaciones -->
    <?php if ($totalEvaluaciones > 0): ?>
    <div class="info-evaluaciones">
        <div class="evaluaciones-header">
            <i class="fas fa-star fa-2x"></i>
            <div>
                <div class="evaluaciones-score"><?php echo $rating; ?></div>
                <div class="evaluaciones-title">Favorito entre huéspedes</div>
                <div class="evaluaciones-subtitle"><?php echo $totalEvaluaciones; ?> evaluaciones</div>
            </div>
        </div>

        <!-- Barras de calificación -->
        <div class="evaluaciones-barras">
            <div class="barra-item">
                <span class="barra-label">Limpieza</span>
                <div class="barra-progress">
                    <div class="barra-fill" style="width: <?php echo ($estadisticas['promedio_limpieza'] ?? 5) * 20; ?>%"></div>
                </div>
                <span class="barra-value"><?php echo number_format($estadisticas['promedio_limpieza'] ?? 5, 1); ?></span>
            </div>
            <div class="barra-item">
                <span class="barra-label">Veracidad</span>
                <div class="barra-progress">
                    <div class="barra-fill" style="width: <?php echo ($estadisticas['promedio_veracidad'] ?? 5) * 20; ?>%"></div>
                </div>
                <span class="barra-value"><?php echo number_format($estadisticas['promedio_veracidad'] ?? 5, 1); ?></span>
            </div>
            <div class="barra-item">
                <span class="barra-label">Llegada</span>
                <div class="barra-progress">
                    <div class="barra-fill" style="width: <?php echo ($estadisticas['promedio_llegada'] ?? 5) * 20; ?>%"></div>
                </div>
                <span class="barra-value"><?php echo number_format($estadisticas['promedio_llegada'] ?? 5, 1); ?></span>
            </div>
            <div class="barra-item">
                <span class="barra-label">Comunicación</span>
                <div class="barra-progress">
                    <div class="barra-fill" style="width: <?php echo ($estadisticas['promedio_comunicacion'] ?? 5) * 20; ?>%"></div>
                </div>
                <span class="barra-value"><?php echo number_format($estadisticas['promedio_comunicacion'] ?? 5, 1); ?></span>
            </div>
            <div class="barra-item">
                <span class="barra-label">Ubicación</span>
                <div class="barra-progress">
                    <div class="barra-fill" style="width: <?php echo ($estadisticas['promedio_ubicacion'] ?? 5) * 20; ?>%"></div>
                </div>
                <span class="barra-value"><?php echo number_format($estadisticas['promedio_ubicacion'] ?? 5, 1); ?></span>
            </div>
            <div class="barra-item">
                <span class="barra-label">Calidad-precio</span>
                <div class="barra-progress">
                    <div class="barra-fill" style="width: <?php echo ($estadisticas['promedio_calidad_precio'] ?? 5) * 20; ?>%"></div>
                </div>
                <span class="barra-value"><?php echo number_format($estadisticas['promedio_calidad_precio'] ?? 5, 1); ?></span>
            </div>
        </div>

        <!-- Lista de reseñas -->
        <div class="evaluaciones-lista">
            <?php foreach ($evaluaciones as $evaluacion): ?>
            <div class="evaluacion-item">
                <div class="evaluacion-header">
                    <div class="evaluacion-avatar">
                        <img src="<?php echo RUTA_PRINCIPAL; ?>assets/principal/images/person_1.jpg" alt="Usuario">
                    </div>
                    <div class="evaluacion-info">
                        <h4><?php echo htmlspecialchars($evaluacion['nombre_usuario']); ?></h4>
                        <p><?php echo date('F Y', strtotime($evaluacion['fecha_evaluacion'])); ?></p>
                    </div>
                </div>
                <p class="evaluacion-texto">
                    <?php echo htmlspecialchars($evaluacion['comentario']); ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Mapa -->
    <div class="info-mapa">
        <h3>Dónde vas a estar</h3>
        <p class="ubicacion-texto"><?php echo htmlspecialchars($propiedad['direccion'] ?? 'Tecolutla, Veracruz, México'); ?></p>
        <!-- IMPORTANTE: altura inline para garantizar que Leaflet renderice correctamente -->
        <div class="mapa-container" id="mapa" style="height: 400px; width: 100%; min-height: 400px;"></div>
    </div>
</div>

<!-- Modal Galería -->
<div class="modal-galeria" id="modalGaleria">
    <div class="modal-galeria-header">
        <button class="modal-galeria-close" onclick="cerrarGaleria()">
            <i class="fas fa-times"></i>
        </button>
        <span id="galeriaCounter">1 / <?php echo max(1, count($fotos)); ?></span>
        <div></div>
    </div>
    <div class="modal-galeria-content">
        <div class="modal-galeria-grid" id="galeriaGrid">
            <!-- Se llena dinámicamente -->
        </div>
    </div>
</div>

<!-- Modal de Reserva para Móvil -->
<div class="modal-reserva-movil" id="modalReservaMovil">
    <div class="modal-reserva-content">
        <div class="modal-reserva-header">
            <h3>Reservar esta propiedad</h3>
            <button class="modal-reserva-close" onclick="cerrarModalReserva()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-reserva-body">
            <div class="reserva-precio">
                <span class="reserva-precio-valor">$<?php echo number_format($precioNoche, 2); ?> MXN</span>
                <span class="reserva-precio-periodo">por noche</span>
                <?php if ($rating > 0): ?>
                    <div class="reserva-rating" style="display: flex; margin-left: auto;">
                        <i class="fas fa-star"></i>
                        <span><?php echo $rating; ?></span>
                        <span class="text-muted">· <?php echo $totalEvaluaciones; ?> evaluaciones</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="reserva-form">
                <div class="reserva-fechas">
                    <div class="reserva-fecha-input">
                        <label>LLEGADA</label>
                        <input type="date" id="modal_fecha_llegada" name="modal_fecha_llegada" 
                               min="<?php echo date('Y-m-d'); ?>"
                               onchange="sincronizarFechas('llegada')">
                    </div>
                    <div class="reserva-fecha-input">
                        <label>SALIDA</label>
                        <input type="date" id="modal_fecha_salida" name="modal_fecha_salida"
                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                               onchange="sincronizarFechas('salida')">
                    </div>
                </div>
                
                <div class="reserva-huespedes" onclick="toggleHuespedesModal()">
                    <label>HUÉSPEDES</label>
                    <div class="reserva-huespedes-selector">
                        <span id="modal_huespedes_texto">1 huésped</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
            
            <!-- Enlace al calendario -->
            <div class="ver-calendario">
                <a href="#" onclick="abrirCalendarioDesdeModal(); return false;">
                    <i class="fas fa-calendar-alt"></i> Ver calendario de disponibilidad
                </a>
            </div>
            
            <!-- Feedback de disponibilidad en modal -->
            <div id="modal-disponibilidad-feedback" class="disponibilidad-feedback" style="display: none;">
                <div class="feedback-content">
                    <span id="modal-feedback-mensaje"></span>
                </div>
            </div>

            <button class="btn-reservar" id="btnReservarModal" onclick="iniciarReserva()" disabled>
                Reservar
            </button>

            <p class="reserva-nota">Aún no se te cobrará nada</p>

            <div class="reserva-desglose" id="modal-desglose" style="display: none;">
                <div class="desglose-item">
                    <span>$<?php echo number_format($precioNoche, 2); ?> MXN x <span id="modal_num_noches">0</span> noches</span>
                    <span id="modal_subtotal_noches">$0.00</span>
                </div>
                <?php if ($tarifaLimpieza > 0): ?>
                <div class="desglose-item">
                    <span>Tarifa de limpieza</span>
                    <span>$<?php echo number_format($tarifaLimpieza, 2); ?></span>
                </div>
                <?php endif; ?>
                <div class="desglose-item">
                    <span>Tarifa de servicio</span>
                    <span id="modal_tarifa_servicio">$0.00</span>
                </div>
                <div class="desglose-total">
                    <span>Total</span>
                    <span id="modal_total_reserva">$0.00</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Calendario de Disponibilidad -->
<div class="modal fade" id="modalCalendario" tabindex="-1" aria-labelledby="modalCalendarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #FF385C 0%, #E31C5F 100%); color: white;">
                <h5 class="modal-title" id="modalCalendarioLabel">
                    <i class="fas fa-calendar-alt me-2"></i> Calendario de Disponibilidad
                </h5>
                <button type="button" class="btn-close btn-close-white" onclick="cerrarCalendarioModal()" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="d-flex gap-3 justify-content-center">
                        <span><span style="display:inline-block;width:20px;height:20px;background:#dc3545;border-radius:4px;"></span> Ocupado</span>
                        <span><span style="display:inline-block;width:20px;height:20px;background:#28a745;border-radius:4px;"></span> Disponible</span>
                        <span><span style="display:inline-block;width:20px;height:20px;background:#ffc107;border-radius:4px;"></span> Tu selección</span>
                    </div>
                </div>
                <div id="calendarioDisponibilidad" style="min-height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarCalendarioModal()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Estilos para feedback de disponibilidad -->
<style>
.disponibilidad-feedback {
    padding: 12px;
    border-radius: 8px;
    margin: 10px 0;
    text-align: center;
}
.disponibilidad-feedback.disponible {
    background-color: #d4edda;
    border: 1px solid #28a745;
    color: #155724;
}
.disponibilidad-feedback.no-disponible {
    background-color: #f8d7da;
    border: 1px solid #dc3545;
    color: #721c24;
}
.disponibilidad-feedback.cargando {
    background-color: #fff3cd;
    border: 1px solid #ffc107;
    color: #856404;
}
.feedback-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.btn-reservar:disabled {
    background-color: #ccc !important;
    cursor: not-allowed;
}
#calendarioDisponibilidad .fc-day-today {
    background-color: rgba(255, 56, 92, 0.1) !important;
}
</style>

<?php include_once 'views/template/footer-principal.php'; ?>

<!-- FullCalendar -->
<script src="<?php echo RUTA_PRINCIPAL; ?>assets/principal/fullcalendar/index.global.min.js"></script>
<script src="<?php echo RUTA_PRINCIPAL; ?>assets/principal/fullcalendar/es.global.min.js"></script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Variables globales para esta página (base_url ya está definida en footer)
const propiedadId = <?php echo $propiedad['id']; ?>;
const precioNoche = <?php echo $precioNoche; ?>;
const tarifaLimpieza = <?php echo $tarifaLimpieza; ?>;
const latitud = <?php echo !empty($propiedad['latitud']) ? floatval($propiedad['latitud']) : 20.4833; ?>;
const longitud = <?php echo !empty($propiedad['longitud']) ? floatval($propiedad['longitud']) : -97.0167; ?>;

// Fotos para la galería
const fotos = [
    '<?php echo $fotoPrincipal; ?>',
    <?php foreach ($fotos as $foto): ?>
    '<?php echo RUTA_PRINCIPAL . 'assets/principal/images/propiedades/' . $foto['url_imagen']; ?>',
    <?php endforeach; ?>
];

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    initMapa();
    initCalculadorPrecios();
});

// Inicializar mapa
function initMapa() {
    const contenedorMapa = document.getElementById('mapa');
    
    // Verificar que el contenedor existe
    if (!contenedorMapa) {
        console.error('❌ Contenedor del mapa no encontrado');
        return;
    }
    
    // Verificar que Leaflet está cargado
    if (typeof L === 'undefined') {
        console.error('❌ Leaflet no está cargado');
        return;
    }
    
    // Validar coordenadas
    const lat = parseFloat(latitud) || 20.4833;
    const lng = parseFloat(longitud) || -97.0167;
    
    console.log('🗺️ Inicializando mapa en:', lat, lng);
    
    try {
        // Crear el mapa
        const map = L.map('mapa', {
            center: [lat, lng],
            zoom: 14,
            scrollWheelZoom: false // Evitar zoom accidental al hacer scroll
        });
        
        // Agregar capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Círculo aproximado (estilo Airbnb - ubicación aproximada)
        L.circle([lat, lng], {
            color: '#FF385C',
            fillColor: '#FF385C',
            fillOpacity: 0.2,
            radius: 300,
            weight: 2
        }).addTo(map);
        
        // Marcador central
        const marcador = L.marker([lat, lng]).addTo(map);
        marcador.bindPopup('<strong><?php echo addslashes($propiedad["estilo"]); ?></strong><br><?php echo addslashes($propiedad["direccion"] ?? "Tecolutla, Veracruz"); ?>');
        
        // Forzar redimensionado del mapa después de cargarlo (solución para el problema de altura)
        setTimeout(function() {
            map.invalidateSize();
            console.log('✅ Mapa redimensionado correctamente');
        }, 250);
        
        console.log('✅ Mapa inicializado correctamente');
        
    } catch (error) {
        console.error('❌ Error al inicializar el mapa:', error);
        contenedorMapa.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#666;"><i class="fas fa-map-marker-alt" style="font-size:48px;margin-right:16px;"></i><span>No se pudo cargar el mapa</span></div>';
    }
}

// Variable para el calendario
let calendarioModal = null;

// Calculador de precios con verificación de disponibilidad
function initCalculadorPrecios() {
    const fechaLlegada = document.getElementById('fecha_llegada');
    const fechaSalida = document.getElementById('fecha_salida');
    
    fechaLlegada.addEventListener('change', function() {
        // Ajustar fecha mínima de salida
        if (this.value) {
            const minSalida = new Date(this.value);
            minSalida.setDate(minSalida.getDate() + 1);
            fechaSalida.min = minSalida.toISOString().split('T')[0];
            
            // Si la fecha de salida es menor, limpiarla
            if (fechaSalida.value && fechaSalida.value <= this.value) {
                fechaSalida.value = '';
            }
        }
        verificarYCalcular();
    });
    
    fechaSalida.addEventListener('change', verificarYCalcular);
    
    // Si hay fechas precargadas, verificar
    if (fechaLlegada.value && fechaSalida.value) {
        verificarYCalcular();
    }
}

// Verificar disponibilidad y calcular precio
function verificarYCalcular() {
    const fechaLlegadaInput = document.getElementById('fecha_llegada');
    const fechaSalidaInput = document.getElementById('fecha_salida');
    const fechaLlegada = fechaLlegadaInput.value;
    const fechaSalida = fechaSalidaInput.value;
    
    if (!fechaLlegada || !fechaSalida) {
        ocultarFeedback();
        document.getElementById('desglose').style.display = 'none';
        document.getElementById('btnReservar').disabled = true;
        return;
    }
    
    if (new Date(fechaSalida) <= new Date(fechaLlegada)) {
        mostrarFeedback('La fecha de salida debe ser posterior a la de llegada', 'no-disponible');
        document.getElementById('desglose').style.display = 'none';
        document.getElementById('btnReservar').disabled = true;
        return;
    }
    
    // Mostrar loading
    mostrarFeedback('<i class="fas fa-spinner fa-spin"></i> Verificando disponibilidad...', 'cargando');
    
    // Llamar al API
    fetch(base_url + 'propiedad/verificarDisponibilidad', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `id_habitacion=${propiedadId}&fecha_inicio=${fechaLlegada}&fecha_fin=${fechaSalida}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.disponible) {
            // Disponible - mostrar feedback verde y habilitar botón
            mostrarFeedback('<i class="fas fa-check-circle"></i> ' + data.mensaje, 'disponible');
            document.getElementById('btnReservar').disabled = false;
            
            // Actualizar desglose de precios
            actualizarDesglose(data);
        } else {
            // No disponible - mostrar feedback rojo y deshabilitar botón
            mostrarFeedback('<i class="fas fa-times-circle"></i> ' + data.mensaje, 'no-disponible');
            document.getElementById('btnReservar').disabled = true;
            document.getElementById('desglose').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarFeedback('<i class="fas fa-exclamation-triangle"></i> Error al verificar disponibilidad', 'no-disponible');
        document.getElementById('btnReservar').disabled = true;
    });
}

// Mostrar feedback de disponibilidad
function mostrarFeedback(mensaje, tipo) {
    const feedback = document.getElementById('disponibilidad-feedback');
    const contenido = document.getElementById('feedback-mensaje');
    
    feedback.className = 'disponibilidad-feedback ' + tipo;
    contenido.innerHTML = mensaje;
    feedback.style.display = 'block';
}

// Ocultar feedback
function ocultarFeedback() {
    document.getElementById('disponibilidad-feedback').style.display = 'none';
}

// Actualizar desglose de precios con datos del servidor
function actualizarDesglose(data) {
    document.getElementById('num_noches').textContent = data.noches;
    document.getElementById('subtotal_noches').textContent = '$' + data.subtotal.toLocaleString('es-MX', {minimumFractionDigits: 2});
    document.getElementById('tarifa_servicio').textContent = '$' + data.tarifa_servicio.toLocaleString('es-MX', {minimumFractionDigits: 2});
    document.getElementById('total_reserva').textContent = '$' + data.precio_total.toLocaleString('es-MX', {minimumFractionDigits: 2});
    
    document.getElementById('desglose').style.display = 'block';
}

// Variable para instancia del modal
let modalCalendarioInstance = null;

// Abrir modal del calendario
function abrirCalendarioModal() {
    const modalElement = document.getElementById('modalCalendario');
    modalCalendarioInstance = new bootstrap.Modal(modalElement);
    modalCalendarioInstance.show();
    
    // Inicializar calendario si no existe
    setTimeout(() => {
        initCalendarioDisponibilidad();
    }, 300);
}

// Cerrar modal del calendario
function cerrarCalendarioModal() {
    if (modalCalendarioInstance) {
        modalCalendarioInstance.hide();
    } else {
        // Fallback: cerrar directamente
        const modalElement = document.getElementById('modalCalendario');
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();
        } else {
            // Fallback manual
            modalElement.classList.remove('show');
            modalElement.style.display = 'none';
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
        }
    }
}

// Inicializar FullCalendar en el modal
function initCalendarioDisponibilidad() {
    const calendarEl = document.getElementById('calendarioDisponibilidad');
    
    // Si ya existe, destruir y recrear
    if (calendarioModal) {
        calendarioModal.destroy();
    }
    
    const fechaLlegada = document.getElementById('fecha_llegada').value;
    const fechaSalida = document.getElementById('fecha_salida').value;
    
    // Construir URL con fechas seleccionadas
    let eventosUrl = base_url + 'propiedad/getReservasCalendario?id=' + propiedadId;
    if (fechaLlegada && fechaSalida) {
        eventosUrl += '&f_llegada=' + fechaLlegada + '&f_salida=' + fechaSalida;
    }
    
    calendarioModal = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        height: 'auto',
        events: eventosUrl,
        eventClick: function(info) {
            if (info.event.id !== 'consulta') {
                Swal.fire({
                    icon: 'info',
                    title: 'Fecha Ocupada',
                    text: `Del ${info.event.startStr} al ${info.event.endStr}`,
                    confirmButtonColor: '#FF385C'
                });
            }
        },
        dateClick: function(info) {
            // Al hacer clic en una fecha, seleccionarla
            const fechaLlegadaInput = document.getElementById('fecha_llegada');
            const fechaSalidaInput = document.getElementById('fecha_salida');
            
            if (!fechaLlegadaInput.value || (fechaLlegadaInput.value && fechaSalidaInput.value)) {
                // Si no hay fecha de llegada o ambas están llenas, setear llegada
                fechaLlegadaInput.value = info.dateStr;
                fechaSalidaInput.value = '';
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'info',
                    title: 'Fecha de llegada: ' + info.dateStr,
                    text: 'Ahora selecciona la fecha de salida',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                // Setear fecha de salida
                if (info.dateStr > fechaLlegadaInput.value) {
                    fechaSalidaInput.value = info.dateStr;
                    // Cerrar modal y verificar
                    cerrarCalendarioModal();
                    verificarYCalcular();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fecha inválida',
                        text: 'La fecha de salida debe ser posterior a la de llegada',
                        confirmButtonColor: '#FF385C'
                    });
                }
            }
        }
    });
    
    calendarioModal.render();
}

// Iniciar reserva
function iniciarReserva() {
    const fechaLlegada = document.getElementById('fecha_llegada').value;
    const fechaSalida = document.getElementById('fecha_salida').value;
    const numHuespedes = document.getElementById('num_huespedes').value;
    
    if (!fechaLlegada || !fechaSalida) {
        Swal.fire('Error', 'Por favor selecciona las fechas de llegada y salida', 'warning');
        return;
    }
    
    // Construir URL de verificación con parámetros
    const params = new URLSearchParams({
        habitacion: propiedadId,
        f_llegada: fechaLlegada,
        f_salida: fechaSalida,
        huespedes: numHuespedes
    });
    const urlReserva = base_url + 'reserva/verify?' + params.toString();
    
    <?php if (!isset($_SESSION['id_usuario'])): ?>
        // Si no está logueado, ir a login con redirect a la URL de verificación
        window.location.href = base_url + 'login?redirect=' + encodeURIComponent(urlReserva);
        return;
    <?php endif; ?>
    
    // Si está logueado, ir directo a verificar disponibilidad
    window.location.href = urlReserva;
}

// Toggle favorito
function toggleFavorito(id) {
    <?php if (!isset($_SESSION['id_usuario'])): ?>
        window.location.href = base_url + 'login';
        return;
    <?php endif; ?>
    
    fetch(base_url + 'propiedad/toggleFavorito', {
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
            Swal.fire('', data.msg, 'success');
        }
    });
}

// Compartir
function compartir() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo addslashes($propiedad['estilo']); ?>',
            text: 'Mira esta increíble casa vacacional en Tecolutla',
            url: window.location.href
        });
    } else {
        // Copiar al portapapeles
        navigator.clipboard.writeText(window.location.href);
        Swal.fire('', 'Enlace copiado al portapapeles', 'success');
    }
}

// Galería
function abrirGaleria(index = 0) {
    const modal = document.getElementById('modalGaleria');
    const grid = document.getElementById('galeriaGrid');
    
    // Llenar galería
    grid.innerHTML = fotos.map((foto, i) => 
        `<img src="${foto}" alt="Foto ${i + 1}" onclick="verFoto(${i})" onerror="this.src='${base_url}assets/principal/images/default-casa.jpg'">`
    ).join('');
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function cerrarGaleria() {
    document.getElementById('modalGaleria').classList.remove('active');
    document.body.style.overflow = '';
}

// Toggle descripción
function toggleDescripcion() {
    const texto = document.getElementById('descripcionTexto');
    texto.style.maxHeight = texto.style.maxHeight ? '' : 'none';
}

// Toggle huéspedes
function toggleHuespedes() {
    Swal.fire({
        title: 'Número de huéspedes',
        input: 'number',
        inputValue: document.getElementById('num_huespedes').value,
        inputAttributes: {
            min: 1,
            max: <?php echo $propiedad['capacidad']; ?>
        },
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            const num = Math.min(Math.max(1, result.value), <?php echo $propiedad['capacidad']; ?>);
            document.getElementById('num_huespedes').value = num;
            document.getElementById('huespedes_texto').textContent = num + ' huésped' + (num > 1 ? 'es' : '');
        }
    });
}

// Cerrar modal con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarGaleria();
        cerrarModalReserva();
    }
});

// ==================== FUNCIONES PARA MODAL MÓVIL ====================

// Detectar si es móvil
function isMobile() {
    return window.innerWidth <= 767;
}

// Manejar click en botón reservar
function handleReservarClick() {
    if (isMobile()) {
        // En móvil, abrir modal para completar datos
        abrirModalReserva();
    } else {
        // En desktop, proceder con la reserva
        iniciarReserva();
    }
}

// Abrir modal de reserva móvil
function abrirModalReserva() {
    const modal = document.getElementById('modalReservaMovil');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Sincronizar valores del widget principal al modal
    const fechaLlegada = document.getElementById('fecha_llegada').value;
    const fechaSalida = document.getElementById('fecha_salida').value;
    const numHuespedes = document.getElementById('num_huespedes').value;
    
    document.getElementById('modal_fecha_llegada').value = fechaLlegada;
    document.getElementById('modal_fecha_salida').value = fechaSalida;
    document.getElementById('modal_huespedes_texto').textContent = numHuespedes + ' huésped' + (numHuespedes > 1 ? 'es' : '');
    
    // Si hay fechas, verificar disponibilidad
    if (fechaLlegada && fechaSalida) {
        verificarYCalcularModal();
    }
}

// Cerrar modal de reserva móvil
function cerrarModalReserva() {
    const modal = document.getElementById('modalReservaMovil');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Cerrar modal al hacer clic fuera
document.addEventListener('click', function(e) {
    const modal = document.getElementById('modalReservaMovil');
    if (e.target === modal) {
        cerrarModalReserva();
    }
});

// Sincronizar fechas entre modal y widget principal
function sincronizarFechas(tipo) {
    const modalLlegada = document.getElementById('modal_fecha_llegada');
    const modalSalida = document.getElementById('modal_fecha_salida');
    const widgetLlegada = document.getElementById('fecha_llegada');
    const widgetSalida = document.getElementById('fecha_salida');
    
    if (tipo === 'llegada') {
        widgetLlegada.value = modalLlegada.value;
        // Ajustar fecha mínima de salida
        if (modalLlegada.value) {
            const minSalida = new Date(modalLlegada.value);
            minSalida.setDate(minSalida.getDate() + 1);
            modalSalida.min = minSalida.toISOString().split('T')[0];
            
            if (modalSalida.value && modalSalida.value <= modalLlegada.value) {
                modalSalida.value = '';
                widgetSalida.value = '';
            }
        }
    } else {
        widgetSalida.value = modalSalida.value;
    }
    
    // Verificar disponibilidad
    verificarYCalcularModal();
}

// Verificar disponibilidad desde el modal
function verificarYCalcularModal() {
    const fechaLlegada = document.getElementById('modal_fecha_llegada').value;
    const fechaSalida = document.getElementById('modal_fecha_salida').value;
    
    // Sincronizar con widget principal
    document.getElementById('fecha_llegada').value = fechaLlegada;
    document.getElementById('fecha_salida').value = fechaSalida;
    
    if (!fechaLlegada || !fechaSalida) {
        ocultarFeedbackModal();
        document.getElementById('modal-desglose').style.display = 'none';
        document.getElementById('btnReservarModal').disabled = true;
        return;
    }
    
    if (new Date(fechaSalida) <= new Date(fechaLlegada)) {
        mostrarFeedbackModal('La fecha de salida debe ser posterior a la de llegada', 'no-disponible');
        document.getElementById('modal-desglose').style.display = 'none';
        document.getElementById('btnReservarModal').disabled = true;
        return;
    }
    
    // Mostrar loading
    mostrarFeedbackModal('<i class="fas fa-spinner fa-spin"></i> Verificando disponibilidad...', 'cargando');
    
    // Llamar al API
    fetch(base_url + 'propiedad/verificarDisponibilidad', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `id_habitacion=${propiedadId}&fecha_inicio=${fechaLlegada}&fecha_fin=${fechaSalida}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.disponible) {
            mostrarFeedbackModal('<i class="fas fa-check-circle"></i> ' + data.mensaje, 'disponible');
            document.getElementById('btnReservarModal').disabled = false;
            document.getElementById('btnReservar').disabled = false;
            actualizarDesgloseModal(data);
            // También actualizar el widget principal
            actualizarDesglose(data);
        } else {
            mostrarFeedbackModal('<i class="fas fa-times-circle"></i> ' + data.mensaje, 'no-disponible');
            document.getElementById('btnReservarModal').disabled = true;
            document.getElementById('btnReservar').disabled = true;
            document.getElementById('modal-desglose').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarFeedbackModal('<i class="fas fa-exclamation-triangle"></i> Error al verificar', 'no-disponible');
        document.getElementById('btnReservarModal').disabled = true;
    });
}

// Mostrar feedback en modal
function mostrarFeedbackModal(mensaje, tipo) {
    const feedback = document.getElementById('modal-disponibilidad-feedback');
    const contenido = document.getElementById('modal-feedback-mensaje');
    
    feedback.className = 'disponibilidad-feedback ' + tipo;
    contenido.innerHTML = mensaje;
    feedback.style.display = 'block';
}

// Ocultar feedback en modal
function ocultarFeedbackModal() {
    document.getElementById('modal-disponibilidad-feedback').style.display = 'none';
}

// Actualizar desglose en modal
function actualizarDesgloseModal(data) {
    document.getElementById('modal_num_noches').textContent = data.noches;
    document.getElementById('modal_subtotal_noches').textContent = '$' + data.subtotal.toLocaleString('es-MX', {minimumFractionDigits: 2});
    document.getElementById('modal_tarifa_servicio').textContent = '$' + data.tarifa_servicio.toLocaleString('es-MX', {minimumFractionDigits: 2});
    document.getElementById('modal_total_reserva').textContent = '$' + data.precio_total.toLocaleString('es-MX', {minimumFractionDigits: 2});
    
    document.getElementById('modal-desglose').style.display = 'block';
}

// Toggle huéspedes en modal
function toggleHuespedesModal() {
    Swal.fire({
        title: 'Número de huéspedes',
        input: 'number',
        inputValue: document.getElementById('num_huespedes').value,
        inputAttributes: {
            min: 1,
            max: <?php echo $propiedad['capacidad']; ?>
        },
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#FF385C'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            const num = Math.min(Math.max(1, result.value), <?php echo $propiedad['capacidad']; ?>);
            document.getElementById('num_huespedes').value = num;
            document.getElementById('huespedes_texto').textContent = num + ' huésped' + (num > 1 ? 'es' : '');
            document.getElementById('modal_huespedes_texto').textContent = num + ' huésped' + (num > 1 ? 'es' : '');
        }
    });
}

// Abrir calendario desde el modal (cierra el modal primero)
function abrirCalendarioDesdeModal() {
    cerrarModalReserva();
    setTimeout(() => {
        abrirCalendarioModal();
    }, 300);
}

// Habilitar botón de reserva en móvil al inicio (para abrir modal)
if (isMobile()) {
    document.getElementById('btnReservar').disabled = false;
    document.getElementById('btnReservar').textContent = 'Ver opciones';
}

// Actualizar al cambiar tamaño de ventana
window.addEventListener('resize', function() {
    const btnReservar = document.getElementById('btnReservar');
    const fechaLlegada = document.getElementById('fecha_llegada').value;
    const fechaSalida = document.getElementById('fecha_salida').value;
    
    if (isMobile()) {
        btnReservar.disabled = false;
        btnReservar.textContent = (fechaLlegada && fechaSalida) ? 'Reservar' : 'Ver opciones';
    } else {
        btnReservar.textContent = 'Reservar';
        if (!fechaLlegada || !fechaSalida) {
            btnReservar.disabled = true;
        }
    }
});
</script>

</body>
</html>
