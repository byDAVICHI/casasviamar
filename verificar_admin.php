<?php
require_once 'config/config.php';
require_once 'config/app/Conexion.php';
require_once 'config/app/Query.php';

class VerificarAdmin {
    private $query;
    
    public function __construct() {
        $this->query = new Query();
    }
    
    public function verificarYCrearAdmin() {
        echo "<h2>🔍 Verificando Usuario Administrador</h2>";
        echo "<hr>";
        
        // Verificar si existe el usuario admin
        $sql = "SELECT * FROM usuarios WHERE usuario = 'admin'";
        $adminExistente = $this->query->select($sql);
        
        if ($adminExistente) {
            echo "<p>✅ <strong>Usuario admin encontrado:</strong></p>";
            echo "<ul>";
            echo "<li><strong>ID:</strong> " . $adminExistente['id'] . "</li>";
            echo "<li><strong>Usuario:</strong> " . $adminExistente['usuario'] . "</li>";
            echo "<li><strong>Nombre:</strong> " . $adminExistente['nombre'] . "</li>";
            echo "<li><strong>Correo:</strong> " . $adminExistente['correo'] . "</li>";
            echo "<li><strong>Perfil:</strong> " . $adminExistente['perfil'] . "</li>";
            echo "</ul>";
            
            // Verificar si la contraseña es correcta
            $claveCorrecta = password_verify('admin123', $adminExistente['clave']);
            
            if ($claveCorrecta) {
                echo "<p>✅ <strong>La contraseña es correcta (admin123)</strong></p>";
                echo "<p>🎉 <strong>El usuario administrador ya está configurado correctamente.</strong></p>";
            } else {
                echo "<p>❌ <strong>La contraseña NO es correcta. Actualizando...</strong></p>";
                $this->actualizarContrasena($adminExistente['id']);
            }
            
        } else {
            echo "<p>❌ <strong>Usuario admin NO encontrado. Creando usuario...</strong></p>";
            $this->crearUsuarioAdmin();
        }
        
        echo "<hr>";
        echo "<h3>🔐 Credenciales Finales del Administrador:</h3>";
        echo "<div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; border-left: 4px solid #28a745;'>";
        echo "<p><strong>USUARIO:</strong> admin</p>";
        echo "<p><strong>CONTRASEÑA:</strong> admin123</p>";
        echo "<p><strong>PERFIL:</strong> administrador</p>";
        echo "</div>";
        
        // Verificación final
        $this->verificacionFinal();
    }
    
    private function actualizarContrasena($id) {
        $nuevaClave = password_hash('admin123', PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET clave = ? WHERE id = ?";
        $resultado = $this->query->save($sql, [$nuevaClave, $id]);
        
        if ($resultado) {
            echo "<p>✅ <strong>Contraseña actualizada correctamente.</strong></p>";
        } else {
            echo "<p>❌ <strong>Error al actualizar la contraseña.</strong></p>";
        }
    }
    
    private function crearUsuarioAdmin() {
        $clave = password_hash('admin123', PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (usuario, nombre, correo, clave, perfil) VALUES (?, ?, ?, ?, ?)";
        $datos = [
            'admin',
            'Administrador',
            'admin@casasviamar.com',
            $clave,
            'administrador'
        ];
        
        $resultado = $this->query->insert($sql, $datos);
        
        if ($resultado > 0) {
            echo "<p>✅ <strong>Usuario administrador creado correctamente con ID: $resultado</strong></p>";
        } else {
            echo "<p>❌ <strong>Error al crear el usuario administrador.</strong></p>";
        }
    }
    
    private function verificacionFinal() {
        echo "<h3>🔍 Verificación Final:</h3>";
        $sql = "SELECT * FROM usuarios WHERE usuario = 'admin'";
        $admin = $this->query->select($sql);
        
        if ($admin) {
            $claveCorrecta = password_verify('admin123', $admin['clave']);
            
            echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-top: 10px;'>";
            echo "<tr style='background-color: #f8f9fa;'>";
            echo "<th style='padding: 10px; text-align: left;'>Campo</th>";
            echo "<th style='padding: 10px; text-align: left;'>Valor</th>";
            echo "<th style='padding: 10px; text-align: left;'>Estado</th>";
            echo "</tr>";
            
            echo "<tr>";
            echo "<td style='padding: 10px;'>Usuario</td>";
            echo "<td style='padding: 10px;'>" . $admin['usuario'] . "</td>";
            echo "<td style='padding: 10px;'>" . ($admin['usuario'] === 'admin' ? '✅ Correcto' : '❌ Incorrecto') . "</td>";
            echo "</tr>";
            
            echo "<tr>";
            echo "<td style='padding: 10px;'>Contraseña</td>";
            echo "<td style='padding: 10px;'>admin123 (encriptada)</td>";
            echo "<td style='padding: 10px;'>" . ($claveCorrecta ? '✅ Correcto' : '❌ Incorrecto') . "</td>";
            echo "</tr>";
            
            echo "<tr>";
            echo "<td style='padding: 10px;'>Perfil</td>";
            echo "<td style='padding: 10px;'>" . $admin['perfil'] . "</td>";
            echo "<td style='padding: 10px;'>" . ($admin['perfil'] === 'administrador' ? '✅ Correcto' : '❌ Incorrecto') . "</td>";
            echo "</tr>";
            
            echo "</table>";
            
            if ($admin['usuario'] === 'admin' && $claveCorrecta && $admin['perfil'] === 'administrador') {
                echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-top: 15px;'>";
                echo "<h4>🎉 ¡ÉXITO TOTAL!</h4>";
                echo "<p>El usuario administrador está configurado correctamente con las credenciales exactas solicitadas.</p>";
                echo "</div>";
            } else {
                echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-top: 15px;'>";
                echo "<h4>❌ Error en la configuración</h4>";
                echo "<p>Hay problemas con la configuración del usuario administrador.</p>";
                echo "</div>";
            }
        }
    }
}

// Ejecutar la verificación
echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Verificación Usuario Administrador - CasasViaMar</title>";
echo "<style>";
echo "body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 20px; background-color: #f8f9fa; }";
echo ".container { max-width: 800px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }";
echo "h2, h3 { color: #333; }";
echo "hr { border: none; height: 2px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: 20px 0; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<div class='container'>";

try {
    $verificador = new VerificarAdmin();
    $verificador->verificarYCrearAdmin();
} catch (Exception $e) {
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;'>";
    echo "<h4>❌ Error en la ejecución:</h4>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

echo "</div>";
echo "</body>";
echo "</html>";
?>
