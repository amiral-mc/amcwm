<?php

Yii::import('bootstrap.components.Bootstrap');

class MyBootstrap extends Bootstrap
{

    /**
     *
     * @var boolean use html5 responsive
     */
    public $useResponsive = true;

    /**
     *
     * @var string document orientation ltr or rtl 
     */
    protected $orientation;

    /**
     *
     * @var string 
     */

    /**
     * @var CClientScript Something which can register assets for later inclusion on page.
     * For now it's just the `Yii::app()->clientScript`
     * copid from http://yiibooster.clevertech.biz/
     */
    public $assetsRegistry;

    /**
     * Initializes the application component.
     * This method is required by {@link IApplicationComponent} and is invoked by application.
     * If you override this method, make sure to call the parent implementation
     * so that the application component can be marked as initialized.
     */
    public function init() {
        $this->orientation = strtolower(Yii::app()->getLocale()->getOrientation());
        if (!$this->assetsRegistry)
            $this->assetsRegistry = Yii::app()->getClientScript();
        $this->addOurPackagesToYii();
        parent::init();
    }

    /**
     * Registers all Bootstrap CSS and JavaScript.
     * @since 2.1.0
     */
    public function register() {
        $this->registerAllCss();
        $this->registerCoreScripts();
    }

    /**
     * get document orientation ltr or rtl 
     * @return string
     */
    public function getOrientation() {
        return $this->orientation;
    }

    /**
     * copid from http://yiibooster.clevertech.biz/
     */
    protected function addOurPackagesToYii() {
        $bootstrapPackages = require(Yii::getPathOfAlias('bootstrap.components') . '/packages.php');
        foreach ($bootstrapPackages as $name => $definition) {
            $this->assetsRegistry->addPackage($name, $definition);
        }
    }

    /**
     * Returns the URL to the published assets folder.
     * @return string the URL
     */
    public function getAssetsUrl() {
        return parent::getAssetsUrl();
    }

    /**
     * Registers the Bootstrap CSS.
     */
    public function registerCoreCss() {
        $this->assetsRegistry->registerPackage("bootstrap");
    }

    /**
     * Registers the Bootstrap responsive CSS.
     * @since 0.9.8
     */
    public function registerResponsiveCss() {
        if ($this->useResponsive) {
            $cs = Yii::app()->getClientScript();
            $cs->registerMetaTag('width=device-width, initial-scale=1.0', 'viewport');
            $this->assetsRegistry->registerPackage("bootstrap.responsive");
        }
    }

    /**
     * Registers a CSS file in the asset's css folder
     *
     * @param string $name the css file name to register
     * @param string $media media that the CSS file should be applied to. If empty, it means all media types.
     * copid from http://yiibooster.clevertech.biz/
     * @see CClientScript::registerCssFile
     */
    public function registerAssetCss($name, $media = '') {
        $this->assetsRegistry->registerCssFile($this->getAssetsUrl() . "/css/{$name}", $media);
    }

    /**
     * Register a javascript file in the asset's js folder
     *
     * @param string $name the js file name to register
     * @param int $position the position of the JavaScript code.
     * copid from http://yiibooster.clevertech.biz/
     * @see CClientScript::registerScriptFile
     */
    public function registerAssetJs($name, $position = CClientScript::POS_END) {
        $this->assetsRegistry->registerScriptFile($this->getAssetsUrl() . "/js/{$name}", $position);
    }

}
