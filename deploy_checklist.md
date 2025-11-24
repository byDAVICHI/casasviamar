# 🚀 CHECKLIST DE DESPLIEGUE A PRODUCCIÓN

## ❌ PROBLEMA IDENTIFICADO
Los cambios de la rama `dev` no se reflejan en producción después del merge a `master` debido a:

1. **Configuraciones hardcodeadas** para desarrollo local
2. **URLs absolutas** en JavaScript que solo funcionan en localhost
3. **Archivos de test** que no deberían estar en producción
4. **Falta de detección automática** de entorno

---

## ✅ SOLUCIÓN IMPLEMENTADA

### 1. **Configuración Automática por Entorno**
- ✅ Detección automática de producción vs desarrollo
- ✅ Configuración dinámica de base de datos
- ✅ URLs automáticas según el entorno

### 2. **JavaScript Dinámico**
- ✅ Detección automática de hostname
- ✅ URLs base configuradas dinámicamente
- ✅ Logs de debug para verificar entorno

### 3. **Limpieza de Archivos**
- ✅ Script de configuración para producción
- ✅ .gitignore para excluir archivos de test
- ✅ Identificación automática de archivos a eliminar

---

## 📋 PASOS PARA DESPLEGAR

### **PASO 1: Aplicar Cambios en Rama DEV**
```bash
# 1. Ejecutar script de configuración
php setup_production.php

# 2. Aplicar cambios propuestos en:
#    - config/config.php (detección automática de entorno)
#    - assets/admin/js/nueva_reservacion.js (URLs dinámicas)

# 3. Eliminar archivos de test (si los hay)
git rm test_*.php debug_*.php check_*.php fix_*.php verificar_*.php

# 4. Commit de cambios
git add .
git commit -m "feat: Configuración automática por entorno para producción"
```

### **PASO 2: Merge a Master**
```bash
# 1. Cambiar a master
git checkout master

# 2. Merge desde dev
git merge dev

# 3. Verificar que no hay archivos de test
ls test_* debug_* check_* fix_* verificar_* 2>/dev/null || echo "✅ Sin archivos de test"

# 4. Push a repositorio
git push origin master
```

### **PASO 3: Desplegar en Hostinger**
```bash
# 1. Conectar por FTP/SSH a Hostinger
# 2. Subir archivos o hacer git pull
# 3. Verificar permisos de archivos
# 4. Probar URLs principales
```

---

## 🔍 VERIFICACIONES POST-DESPLIEGUE

### **URLs a Probar:**
- ✅ `https://www.casasviamar.com/` (sitio principal)
- ✅ `https://www.casasviamar.com/admin/` (login admin)
- ✅ `https://www.casasviamar.com/admin/dashboard` (panel)
- ✅ `https://www.casasviamar.com/admin/reservas` (calendario)
- ✅ `https://www.casasviamar.com/admin/nueva_reservacion` (CRUD)

### **Funcionalidades Críticas:**
- ✅ Login de administrador (admin/admin123)
- ✅ Carga de usuarios y casas en formularios
- ✅ Cálculo automático de precios
- ✅ CRUD completo de reservas
- ✅ Calendario interactivo
- ✅ Eliminación real de reservas

### **Logs a Revisar:**
- ✅ Consola del navegador (sin errores 404)
- ✅ Logs de PHP en Hostinger
- ✅ Verificar conexión a base de datos

---

## 🛠️ COMANDOS DE EMERGENCIA

### **Si algo falla en producción:**
```bash
# Rollback rápido
git checkout master
git reset --hard HEAD~1
git push --force origin master
```

### **Debug en producción:**
```php
// Agregar temporalmente en index.php
echo "Entorno: " . (defined('ENVIRONMENT') ? ENVIRONMENT : 'NO DETECTADO');
echo "Host: " . $_SERVER['HTTP_HOST'];
echo "Ruta: " . RUTA_PRINCIPAL;
```

---

## 📞 CONTACTO DE SOPORTE
- **Hostinger**: Panel de control + soporte técnico
- **Base de datos**: Verificar credenciales en cPanel
- **SSL**: Verificar certificado HTTPS

---

**🎯 RESULTADO ESPERADO:** Sistema completamente funcional en producción con detección automática de entorno y sin archivos de desarrollo.
