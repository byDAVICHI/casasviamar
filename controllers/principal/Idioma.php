<?php
/**
 * Controlador de Idioma
 * Maneja el cambio de idioma del sistema
 */

class Idioma extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Cambiar idioma
     * @param string $code Código de idioma (ej: es_mx, en_us)
     */
    public function cambiar($code = '')
    {
        if (empty($code)) {
            $this->redirect('');
            return;
        }
        
        // Limpiar el código
        $code = strClean($code);
        
        // Cambiar idioma usando el helper
        $lang = Language::getInstance();
        $result = $lang->setLanguage($code);
        
        // Obtener URL de retorno
        $returnUrl = $_SERVER['HTTP_REFERER'] ?? RUTA_PRINCIPAL;
        
        // Redirigir a la página anterior o al inicio
        header('Location: ' . $returnUrl);
        exit;
    }
    
    /**
     * Obtener idiomas disponibles (AJAX)
     */
    public function getIdiomas()
    {
        header('Content-Type: application/json');
        
        $lang = Language::getInstance();
        $idiomas = $lang->getAvailableLanguages();
        $actual = $lang->getCurrentLanguage();
        
        echo json_encode([
            'tipo' => 'success',
            'idiomas' => $idiomas,
            'actual' => $actual
        ]);
        exit;
    }
    
    /**
     * Obtener traducciones actuales (AJAX)
     */
    public function getTraducciones()
    {
        header('Content-Type: application/json');
        
        $lang = Language::getInstance();
        
        echo json_encode([
            'tipo' => 'success',
            'traducciones' => $lang->getAllTranslations(),
            'idioma' => $lang->getCurrentLanguageInfo()
        ]);
        exit;
    }
    
    /**
     * Método de redirección helper
     */
    private function redirect($path)
    {
        header('Location: ' . RUTA_PRINCIPAL . $path);
        exit;
    }
}
