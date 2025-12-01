// Login.js v2.0 - Con soporte para redirección de reservas
const frm = document.querySelector('#formulario');

// Obtener parámetro redirect de la URL si existe
function getRedirectParam() {
    const urlParams = new URLSearchParams(window.location.search);
    const redirect = urlParams.get('redirect');
    console.log(' Parámetro redirect encontrado:', redirect);
    if (redirect) {
        const decoded = decodeURIComponent(redirect);
        console.log(' URL decodificada:', decoded);
        return decoded;
    }
    return null;
}

document.addEventListener('DOMContentLoaded', function() {
    // Log para verificar que el script se cargó
    console.log(' Login.js v2.0 cargado correctamente');
    console.log(' URL actual:', window.location.href);
    
    // Verificar redirect al cargar
    const redirectOnLoad = getRedirectParam();
    if (redirectOnLoad) {
        console.log(' Se detectó redirect pendiente:', redirectOnLoad);
    }
    
    frm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (frm.usuario.value == '' || frm.clave.value == '') {
            alertSW('TODOS LOS CAMPOS SON REQUERIDOS', 'warning');
        } else {
            const http = new XMLHttpRequest();
            const url = base_url + 'login/verify';
            http.open("POST", url, true);
            http.send(new FormData(frm));

            http.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(' Respuesta del servidor:', this.responseText);
                    const res = JSON.parse(this.responseText);
                    alertSW(res.msg, res.tipo);
                    
                    if (res.tipo == 'success') {
                        frm.reset();
                        
                        // Obtener parámetro redirect de la URL
                        const redirectParam = getRedirectParam();
                        
                        console.log(' Decisión de redirección:');
                        console.log('   - redirectParam:', redirectParam);
                        console.log('   - res.redirect:', res.redirect);
                        
                        // Prioridad de redirección:
                        // 1. Si hay ?redirect= en la URL, usar eso (flujo de reserva)
                        // 2. Si no, usar la redirección del servidor según el rol
                        if (redirectParam) {
                            console.log(' Redirigiendo a URL del parámetro:', redirectParam);
                            window.location.href = redirectParam;
                        } else if (res.redirect) {
                            const destino = base_url + res.redirect;
                            console.log(' Redirigiendo según rol:', destino);
                            window.location.href = destino;
                        } else {
                            const fallback = base_url + 'reserva/pendiente';
                            console.log(' Redirigiendo a fallback:', fallback);
                            window.location.href = fallback;
                        }
                    }
                }
            };
        }
    });
});