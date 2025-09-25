<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test AJAX - Usuarios</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>🧪 Test AJAX - Carga de Usuarios</h1>
    
    <div id="results"></div>
    
    <button onclick="testGetUsuarios()">🔄 Probar getUsuarios</button>
    <button onclick="testDirectURL()">🌐 Probar URL directa</button>
    
    <script>
        const base_url = '<?php echo "http://localhost/casasviamar/admin/"; ?>';
        
        function log(message, type = 'info') {
            const results = document.getElementById('results');
            const timestamp = new Date().toLocaleTimeString();
            const color = type === 'error' ? 'red' : type === 'success' ? 'green' : 'blue';
            results.innerHTML += `<p style="color: ${color};">[${timestamp}] ${message}</p>`;
        }
        
        function testGetUsuarios() {
            log('🚀 Iniciando test de getUsuarios...');
            log('📡 URL: ' + base_url + 'getUsuarios');
            
            fetch(base_url + 'getUsuarios')
                .then(response => {
                    log(`📊 Status: ${response.status} ${response.statusText}`);
                    log(`📋 Headers: ${JSON.stringify([...response.headers])}`);
                    return response.text();
                })
                .then(text => {
                    log('📄 Raw Response:');
                    log(`<pre style="background: #f0f0f0; padding: 10px;">${text}</pre>`);
                    
                    try {
                        const data = JSON.parse(text);
                        log(`✅ JSON válido! ${data.length} usuarios encontrados`, 'success');
                        
                        data.forEach((usuario, index) => {
                            log(`👤 Usuario ${index + 1}: ${usuario.nombre} ${usuario.apellido} (${usuario.correo}) - Rol: ${usuario.rol}`);
                        });
                        
                    } catch (e) {
                        log(`❌ Error parsing JSON: ${e.message}`, 'error');
                    }
                })
                .catch(error => {
                    log(`💥 Error en fetch: ${error.message}`, 'error');
                });
        }
        
        function testDirectURL() {
            log('🌐 Abriendo URL directa en nueva ventana...');
            window.open(base_url + 'getUsuarios', '_blank');
        }
        
        // Auto-ejecutar al cargar
        document.addEventListener('DOMContentLoaded', function() {
            log('🏁 Página cargada, ejecutando test automático...');
            setTimeout(testGetUsuarios, 1000);
        });
    </script>
    
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        #results { 
            border: 1px solid #ccc; 
            padding: 15px; 
            margin: 20px 0; 
            max-height: 500px; 
            overflow-y: auto; 
            background: #fafafa;
        }
        button { 
            padding: 10px 20px; 
            margin: 5px; 
            background: #667eea; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer;
        }
        button:hover { background: #5a6fd8; }
    </style>
</body>
</html>
