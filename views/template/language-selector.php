<?php
/**
 * Componente Selector de Idiomas
 * Incluir en el navbar: <?php include 'views/template/language-selector.php'; ?>
 */

$langHelper = Language::getInstance();
$currentLang = $langHelper->getCurrentLanguageInfo();
$availableLangs = $langHelper->getAvailableLanguages();
?>

<!-- Selector de Idiomas con Banderas -->
<div class="dropdown language-selector">
    <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" 
            type="button" 
            id="languageDropdown" 
            data-bs-toggle="dropdown" 
            aria-expanded="false">
        <span class="flag-icon flag-icon-<?php echo $currentLang['flag_icon']; ?> me-2"></span>
        <span class="d-none d-md-inline"><?php echo strtoupper($currentLang['code']); ?></span>
        <span class="d-md-none"><?php echo $currentLang['flag']; ?></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end language-dropdown" aria-labelledby="languageDropdown">
        <li class="dropdown-header"><?php echo __('language_select'); ?></li>
        <li><hr class="dropdown-divider"></li>
        <?php foreach ($availableLangs as $langCode => $langInfo): ?>
        <li>
            <a class="dropdown-item d-flex align-items-center <?php echo ($langCode === $langHelper->getCurrentLanguage()) ? 'active' : ''; ?>" 
               href="<?php echo RUTA_PRINCIPAL; ?>idioma/cambiar/<?php echo $langCode; ?>">
                <span class="flag-icon flag-icon-<?php echo $langInfo['flag_icon']; ?> me-2"></span>
                <span><?php echo $langInfo['name']; ?></span>
                <?php if ($langCode === $langHelper->getCurrentLanguage()): ?>
                <i class="fas fa-check ms-auto text-success"></i>
                <?php endif; ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

<style>
/* Estilos del selector de idiomas */
.language-selector .dropdown-toggle {
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.language-selector .dropdown-toggle:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.language-dropdown {
    min-width: 220px;
    max-height: 400px;
    overflow-y: auto;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    border: none;
    padding: 0.5rem;
}

.language-dropdown .dropdown-item {
    border-radius: 8px;
    padding: 0.6rem 1rem;
    transition: all 0.2s ease;
}

.language-dropdown .dropdown-item:hover {
    background-color: #f8f9fa;
}

.language-dropdown .dropdown-item.active {
    background-color: #FF385C;
    color: white;
}

.language-dropdown .dropdown-item.active:hover {
    background-color: #e31c5f;
}

.language-dropdown .dropdown-header {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Flag icons usando emojis como fallback */
.flag-icon {
    font-size: 1.2rem;
    line-height: 1;
}

/* Responsive */
@media (max-width: 768px) {
    .language-selector .dropdown-toggle {
        padding: 0.4rem 0.8rem;
    }
    
    .language-dropdown {
        min-width: 200px;
    }
}
</style>
