<?php
/**
 * Language Helper - Sistema de Internacionalización (i18n)
 * Casas Via-Mar
 */

class Language
{
    private static $instance = null;
    private $currentLang = 'es_mx';
    private $translations = [];
    private $availableLanguages = [];
    
    /**
     * Constructor privado (Singleton)
     */
    private function __construct()
    {
        $this->loadAvailableLanguages();
        $this->detectLanguage();
        $this->loadTranslations();
    }
    
    /**
     * Obtener instancia única (Singleton)
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Cargar idiomas disponibles
     */
    private function loadAvailableLanguages()
    {
        $this->availableLanguages = [
            'es_mx' => [
                'code' => 'es',
                'name' => 'Español (MX)',
                'flag' => '🇲🇽',
                'flag_icon' => 'mx',
                'file' => 'es_mx.php'
            ],
            'en_us' => [
                'code' => 'en',
                'name' => 'English (US)',
                'flag' => '🇺🇸',
                'flag_icon' => 'us',
                'file' => 'en_us.php'
            ],
            'pt_br' => [
                'code' => 'pt',
                'name' => 'Português (BR)',
                'flag' => '🇧🇷',
                'flag_icon' => 'br',
                'file' => 'pt_br.php'
            ],
            'fr_fr' => [
                'code' => 'fr',
                'name' => 'Français (FR)',
                'flag' => '🇫🇷',
                'flag_icon' => 'fr',
                'file' => 'fr_fr.php'
            ],
            'de_de' => [
                'code' => 'de',
                'name' => 'Deutsch (DE)',
                'flag' => '🇩🇪',
                'flag_icon' => 'de',
                'file' => 'de_de.php'
            ],
            'no_no' => [
                'code' => 'no',
                'name' => 'Norsk (NO)',
                'flag' => '🇳🇴',
                'flag_icon' => 'no',
                'file' => 'no_no.php'
            ],
            'sv_se' => [
                'code' => 'sv',
                'name' => 'Svenska (SE)',
                'flag' => '🇸🇪',
                'flag_icon' => 'se',
                'file' => 'sv_se.php'
            ],
            'zh_cn' => [
                'code' => 'zh',
                'name' => '简体中文',
                'flag' => '🇨🇳',
                'flag_icon' => 'cn',
                'file' => 'zh_cn.php'
            ],
            'ja_jp' => [
                'code' => 'ja',
                'name' => '日本語',
                'flag' => '🇯🇵',
                'flag_icon' => 'jp',
                'file' => 'ja_jp.php'
            ],
            'ko_kr' => [
                'code' => 'ko',
                'name' => '한국어',
                'flag' => '🇰🇷',
                'flag_icon' => 'kr',
                'file' => 'ko_kr.php'
            ],
            'ru_ru' => [
                'code' => 'ru',
                'name' => 'Русский',
                'flag' => '🇷🇺',
                'flag_icon' => 'ru',
                'file' => 'ru_ru.php'
            ]
        ];
    }
    
    /**
     * Detectar idioma desde sesión, cookie o navegador
     */
    private function detectLanguage()
    {
        // 1. Prioridad: Sesión
        if (isset($_SESSION['lang']) && array_key_exists($_SESSION['lang'], $this->availableLanguages)) {
            $this->currentLang = $_SESSION['lang'];
            return;
        }
        
        // 2. Segunda prioridad: Cookie
        if (isset($_COOKIE['lang']) && array_key_exists($_COOKIE['lang'], $this->availableLanguages)) {
            $this->currentLang = $_COOKIE['lang'];
            $_SESSION['lang'] = $this->currentLang;
            return;
        }
        
        // 3. Tercera prioridad: Header del navegador
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            foreach ($this->availableLanguages as $key => $lang) {
                if ($lang['code'] === $browserLang) {
                    $this->currentLang = $key;
                    $_SESSION['lang'] = $this->currentLang;
                    return;
                }
            }
        }
        
        // 4. Por defecto: Español (MX)
        $this->currentLang = 'es_mx';
        $_SESSION['lang'] = $this->currentLang;
    }
    
    /**
     * Cargar archivo de traducciones
     */
    private function loadTranslations()
    {
        $langFile = dirname(__DIR__) . '/lang/' . $this->availableLanguages[$this->currentLang]['file'];
        
        if (file_exists($langFile)) {
            $this->translations = require $langFile;
        } else {
            // Fallback a español
            $fallbackFile = dirname(__DIR__) . '/lang/es_mx.php';
            if (file_exists($fallbackFile)) {
                $this->translations = require $fallbackFile;
            }
        }
    }
    
    /**
     * Obtener traducción por clave
     * @param string $key Clave de traducción
     * @param array $params Parámetros para reemplazar en la traducción
     * @return string
     */
    public function get($key, $params = [])
    {
        $translation = $this->translations[$key] ?? $key;
        
        // Reemplazar parámetros si existen
        if (!empty($params) && is_string($translation)) {
            foreach ($params as $param => $value) {
                $translation = str_replace(':' . $param, $value, $translation);
            }
        }
        
        return $translation;
    }
    
    /**
     * Cambiar idioma
     * @param string $langCode Código de idioma (ej: es_mx, en_us)
     * @return bool
     */
    public function setLanguage($langCode)
    {
        if (array_key_exists($langCode, $this->availableLanguages)) {
            $this->currentLang = $langCode;
            $_SESSION['lang'] = $langCode;
            
            // Guardar cookie por 30 días
            setcookie('lang', $langCode, time() + (30 * 24 * 60 * 60), '/');
            
            // Recargar traducciones
            $this->loadTranslations();
            
            return true;
        }
        return false;
    }
    
    /**
     * Obtener idioma actual
     * @return string
     */
    public function getCurrentLanguage()
    {
        return $this->currentLang;
    }
    
    /**
     * Obtener información del idioma actual
     * @return array
     */
    public function getCurrentLanguageInfo()
    {
        return $this->availableLanguages[$this->currentLang];
    }
    
    /**
     * Obtener todos los idiomas disponibles
     * @return array
     */
    public function getAvailableLanguages()
    {
        return $this->availableLanguages;
    }
    
    /**
     * Obtener todas las traducciones
     * @return array
     */
    public function getAllTranslations()
    {
        return $this->translations;
    }
    
    /**
     * Verificar si existe una traducción
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->translations[$key]);
    }
}

/**
 * Función helper global para traducciones
 * Uso: __('nav_home') o __('greeting', ['name' => 'Juan'])
 */
function __($key, $params = [])
{
    return Language::getInstance()->get($key, $params);
}

/**
 * Función helper para obtener la instancia de Language
 */
function lang()
{
    return Language::getInstance();
}
