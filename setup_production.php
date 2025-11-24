<?php
/**
 * SCRIPT DE CONFIGURACIÓN PARA PRODUCCIÓN
 * Ejecutar antes de hacer merge a master
 */

echo "\n🚀 CONFIGURANDO PROYECTO PARA PRODUCCIÓN...\n";
echo "==========================================\n";

// 1. CREAR .gitignore SI NO EXISTE
$gitignoreContent = "# ARCHIVOS DE DESARROLLO Y TESTING
test_*.php
debug_*.php
check_*.php
fix_*.php
verificar_*.php

# DEPENDENCIAS
vendor/
node_modules/

# ARCHIVOS DE CONFIGURACIÓN LOCAL
.env
.env.local

# ARCHIVOS DE SISTEMA
.DS_Store
Thumbs.db

# LOGS
*.log
error_log

# ARCHIVOS TEMPORALES
*.tmp
*.temp

# BACKUPS
*.bak
*.backup

# ARCHIVOS DE IDE
.vscode/
.idea/
*.swp
*.swo

# ARCHIVOS DE CACHE
cache/
*.cache";

if (!file_exists('.gitignore')) {
    file_put_contents('.gitignore', $gitignoreContent);
    echo "✅ .gitignore creado\n";
} else {
    echo "ℹ️ .gitignore ya existe\n";
}

// 2. LISTAR ARCHIVOS DE TEST PARA ELIMINAR
$testFiles = glob('test_*.php');
$debugFiles = glob('debug_*.php');
$checkFiles = glob('check_*.php');
$fixFiles = glob('fix_*.php');
$verifyFiles = glob('verificar_*.php');

$allTestFiles = array_merge($testFiles, $debugFiles, $checkFiles, $fixFiles, $verifyFiles);

if (!empty($allTestFiles)) {
    echo "\n🗑️ ARCHIVOS DE TEST ENCONTRADOS:\n";
    foreach ($allTestFiles as $file) {
        echo "  - $file\n";
    }
    
    echo "\n⚠️  IMPORTANTE: Estos archivos deben eliminarse antes del merge a master\n";
    echo "Comando sugerido: git rm " . implode(' ', $allTestFiles) . "\n";
} else {
    echo "✅ No se encontraron archivos de test\n";
}

// 3. VERIFICAR CONFIGURACIÓN
echo "\n🔍 VERIFICANDO CONFIGURACIÓN...\n";

require_once 'config/config.php';

echo "Entorno detectado: " . (defined('ENVIRONMENT') ? ENVIRONMENT : 'NO DETECTADO') . "\n";
echo "Ruta principal: " . RUTA_PRINCIPAL . "\n";
echo "Base de datos: " . DATABASE . "\n";
echo "Usuario DB: " . USER . "\n";

// 4. VERIFICAR JAVASCRIPT
echo "\n🔍 VERIFICANDO JAVASCRIPT...\n";
$jsFile = 'assets/admin/js/nueva_reservacion.js';
if (file_exists($jsFile)) {
    $jsContent = file_get_contents($jsFile);
    if (strpos($jsContent, 'DETECCIÓN AUTOMÁTICA DE ENTORNO') !== false) {
        echo "✅ JavaScript configurado para detección automática\n";
    } else {
        echo "⚠️  JavaScript necesita actualización para detección automática\n";
    }
} else {
    echo "❌ JavaScript no encontrado\n";
}

echo "\n🏁 CONFIGURACIÓN COMPLETADA\n";
echo "==========================================\n";
echo "PASOS SIGUIENTES:\n";
echo "1. Aplicar los cambios propuestos en config.php y nueva_reservacion.js\n";
echo "2. Eliminar archivos de test si los hay\n";
echo "3. Hacer commit de los cambios\n";
echo "4. Hacer merge a master\n";
echo "5. Desplegar en Hostinger\n";
echo "\n🚀 ¡Listo para producción!\n";
?>
