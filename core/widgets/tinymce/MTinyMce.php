<?php

/**
 * MTinyMce is an input widget based on TinyMCE and the jQuery TinyMCE plugin.
 *
 * @author hany
 */
class MTinyMce extends CInputWidget {
    /**
     * These are useful defaults in case you forget to set htmlOptions['cols']
     * and htmlOptions['rows']. However, since width and heigth are defined
     * below, the cols and rows don't actually matter (they're here just for
     * XHTML compliance)
     */

    const COLS = 40;
    const ROWS = 10;

    /**
     * Don't change these unless you're having name conflicts. They should be the
     * same as the used in the jquery.tinymce.js plugin file
     */
    const COOKIE_SUFFIX = '_editorMode';
    const SWITCH_SUFFIX = '_switch';

    //**************************************************************************
    // Widget properties
    //**************************************************************************

    /**
     * The mode for this editor: html or text
     */
    private $mode = 'html';

    /**
     * The editor's language
     *
     * @var string
     */
    private $language = 'en';

    /**
     * You can use one of these predefined setups for the editor:
     *
     * - simple: This page shows you the simple theme and it's core functionality.
     * - full: This includes shows all available buttons and plugins that are
     *         included in the TinyMCE core package.
     *
     * @var string
     */
    private $editorTemplate = 'simple';

    /**
     * The width of the editor.
     * This will be overriden if you set htmlOptions['style'] = 'width:...';
     *
     * @var string
     */
    private $width = '100%';

    /**
     * The heigth of the editor.
     * This will be overriden if you set htmlOptions['style'] = 'heigth:...';
     *
     * @var integer
     */
    private $height = '400px';

    /**
     * This option enables you to specify make editor instances in readonly mode. 
     * When they are in readonly mode nothing can be changed and the contents is 
     * just presented to the user.
     *
     * @var boolean
     */
    private $readOnly = false;

    /**
     * The TinyMCE options. It is an associative array. Follow this example:
     *
     * $options['theme_advanced_toolbar_location'] = 'top';
     *
     * The keys and the corresponding values are the configuration options from
     * this page:
     *
     * @link http://wiki.moxiecode.com/index.php/TinyMCE:Configuration
     *
     * You can control the toolbars following these instructions:
     *
     * @link http://wiki.moxiecode.com/index.php/TinyMCE:Control_reference
     *
     * @var array
     */
    private $options = array();

    /**
     * Plugins to load. The more you load the slower is the first load.
     * You may want to extend the class and redefine this property to your needs.
     *
     * For an almost full editor:
     *
     * array('spellchecker','table','save','emotions','insertdatetime','preview','searchreplace','print','contextmenu','paste','fullscreen','noneditable','layer','visualchars');
     *
     * This variable is significative only if $this->options == array()
     *
     * @var array
     */
    private $plugins = array();

    /**
     * Font families to display on the font list.
     * You may want to redefine this property to your needs.
     * Notice that the only useful values are the most comon: Arial, Times New
     * Romn and Helvetica, since they have equivalents in most operating systems.
     *
     * This variable is significative only if $this->options == array()
     *
     * @var string
     */
    private $fontFamilies = array(
        'Andale Mono' => 'andale mono',
        'Arial' => 'arial,helvetica,sans-serif',
        'Arial Black' => 'arial black',
        'Book Antiqua' => 'book antiqua',
        'Comic Sans MS' => 'comic sans ms',
        'Courier New' => 'courier new,courier,monospace',
        'Georgia' => 'georgia',
        'Helvetica' => 'helvetica',
        'Impact' => 'impact',
        'Symbol' => 'symbol',
        'Tahoma' => 'tahoma',
        'Terminal' => 'terminal',
        'Times New Roman' => 'times new roman,roman,serif',
        'Trebuchet MS' => 'trebuchet ms',
        'Verdana' => 'verdana',
        'Webdings' => 'webdings',
        'Wingdings' => 'wingdings'
    );

    /**
     * Font sizes to display in the font size list. This is a list of comma separated integers.
     * You may want to redefine this property to your needs.
     *
     * This variable is significative only if $this->options == array()
     *
     * @var string
     */
    private $fontSizes = array('1', '2', '3', '4', '5', '6', '7');

    /**
     * If you include an URL pointing to a CSS file, this will be used by the
     * editor to try to present the content as if it were on the target page.
     *
     * @var string
     */
    private $contentCSS = '';

    /**
     * The CSS style of the label tab
     *
     * @var string
     */
    private $labelStyle = 'color:navy;font-family:sans-serif;font-size:8pt;background-color:#F0F0EE;border-style:solid;border-width:0px 1px 1px 1px;border-color:#CCCCCC;text-decoration:none;padding:1px 3px 3px 3px;margin:2px 0 0 0;';

    /**
     * The CSS class of the label tab. A sample CSS file is provided inside the
     * css directory. Simple registerCssFile in your view and use mceSwitchLabel.
     * Or define your own.
     *
     * @var string
     */
    private $labelClass = '';

    /**
     * The labels for the HTML->plain text mode switch. The first element is the
     * "text to plain" label, and the second element is the "html to text" label
     *
     * @var array
     */
    private $switchLabels = array(0 => 'وضع النص', 1 => 'وضع HTML');

    /**
     * Wheter to use the HTML->plain text mode switch.
     *
     * Note: if useSwitch==true then the editor will be inside a <div> tag. Else
     * it will be a normal <textarea> tag. Take this in account when designing
     * your form.
     *
     * @var boolean
     */
    private $useSwitch = false;

    /**
     * Use cookies to "learn" the editor's mode?
     *
     * @var boolean
     */
    private $useCookies = true;

    /**
     * Whetever to use the PHP compressor code. You must check if your server is
     * already using zlib. If it is using zlib for output compression, and you
     * set this to true, the editor won't work and you'll get javascript errors.
     * Be careful.
     *
     * @link http://wiki.moxiecode.com/index.php/TinyMCE:Compressor/PHP
     *
     * @var boolean
     */
    private $useCompression = true;
    protected $rteFileManager = array("/backend/uploads/default/index", 'op' => "rte", "dialog" => "");
    //***************************************************************************
    // Local properties
    //***************************************************************************
    /**
     * Valid bundled languages.
     *
     * @var array
     */
    private $validLanguages = array(
        'ar', 'az', 'be', 'bg', 'bn', 'br', 'bs', 'ca',
        'ch', 'cs', 'cy', 'da', 'de', 'dv', 'el', 'en',
        'es', 'et', 'fa', 'fi', 'fr', 'gl', 'gu', 'he',
        'hi', 'hr', 'hu', 'ia', 'id', 'ii', 'is', 'it',
        'ja', 'ko', 'lt', 'lv', 'mk', 'ml', 'mn', 'ms',
        'nb', 'nl', 'nn', 'no', 'pl', 'pt', 'ro', 'ru',
        'sc', 'se', 'si', 'sk', 'sl', 'sq', 'sr', 'sv',
        'ta', 'te', 'th', 'tr', 'tt', 'tw', 'uk', 'vi',
        'zh'
    );

    /**
     * Valid predefined editor templates
     *
     * @var array
     */
    private $validEditorTemplates = array('simple', 'full', 'custom');

    //***************************************************************************
    // Constructor
    //***************************************************************************

    public function __construct($owner = null) {
        parent::__construct($owner);
        $langs = $this->controller->getTranslationLanguages();
        if ($this->controller->action->getId() == "translate") {
            $lang = Yii::app()->request->getParam("tlang", key($langs));
        }
        else{
            $lang = Controller::getContentLanguage();
        }
        $this->setLanguage($lang);
    }

    public function setRteFileManager($route) {
        $this->rteFileManager[0] = $route;
    }

    //***************************************************************************
    // Setters and getters
    //***************************************************************************

    /**
     * Setter
     *
     * @param array $value 
     */
    public function setOptions($value) {
        if (!is_array($value))
            throw new CException(Yii::t('MTinyMce', 'options must be an array'));
        $this->options = $value;
    }

    /**
     * Getter
     *
     * @return array
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * Setter
     *
     * @param string $value the heigth
     */
    public function setHeight($value) {
        if (!preg_match("/[\d]+[px|\%]/", $value))
            throw new CException(Yii::t('MTinyMce', 'height must be a string of digits terminated by "%" or "px"'));
        $this->height = $value;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * Setter
     *
     * @param string $value the width
     */
    public function setWidth($value) {
        if (!preg_match("/[\d]+[px|\%]/", $value))
            throw new CException(Yii::t('MTinyMce', 'width must be a string of digits terminated by "%" or "px"'));
        $this->width = $value;
    }

    /**
     * Getter
     *
     * @return <type>
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * Setter
     *
     * @param string $value the template
     */
    public function setEditorTemplate($value) {
        if (!in_array($value, $this->validEditorTemplates))
            throw new CException(Yii::t('MTinyMce', 'editorTemplate must be one of {temp}', array('{temp}' => implode(',', $this->validEditorTemplates))));
        $this->editorTemplate = $value;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getEditorTemplate() {
        return $this->editorTemplate;
    }

    /**
     * Setter
     *
     * @param string $value the style string
     */
    public function setLabelStyle($value) {
        $this->labelStyle = $value;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getLabelStyle() {
        return $this->labelStyle;
    }

    /**
     * Setter
     *
     * @param string $value the class name
     */
    public function setLabelClass($value) {
        $this->labelClass = $value;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getLabelClass() {
        return $this->labelClass;
    }

    /**
     * Setter
     *
     * @param bolean $value 
     */
    public function setUseCompression($value) {
        if (!is_bool($value))
            throw new CException(Yii::t('EWebFeed', 'useCompression must be boolean'));
        $this->useCompression = $value;
    }

    /**
     * Getter
     *
     * @return boolean
     */
    public function getUseCompression() {
        return $this->useCompression;
    }

    /**
     * Set the switch labels
     *
     * @param unknown_type $value
     */
    public function setSwitchLabels($value) {
        if (!is_array($value) || count($value) != 2)
            throw new CException(Yii::t('MTinyMce', 'The switch labels must be provided as array(text_mode_label, html_mode_label) array'));
        $this->switchLabels = $value;
    }

    /**
     * Returns the labels
     *
     * @return array
     */
    public function getSwitchLabels() {
        return $this->switchLabels;
    }

    /**
     * Will we use the mode switch?
     *
     * @param boolean $value
     */
    public function setUseSwitch($value) {
        if (!is_bool($value))
            throw new CException(Yii::t('MTinyMce', 'useSwitch must be boolean'));
        $this->useSwitch = $value;
    }

    /**
     * Will we use the mode switch?
     *
     * @return boolean
     */
    public function getUseSwitch() {
        return $this->useSwitch;
    }

    /**
     * Will we use cookies?
     *
     * @param boolean $value
     */
    public function setUseCookies($value) {
        if (!is_bool($value))
            throw new CException(Yii::t('MTinyMce', 'useCookies must be boolean'));
        $this->useCookies = $value;
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    public function getUseCookies() {
        return $this->useCookies;
    }

    /**
     * Set the language for TinyMCE. Please check the langs directory to know
     * which languages are available.
     *
     * @param string $value
     */
    public function setLanguage($value) {
        $lang = (($p = strpos($value, '_')) !== false) ? str_replace('_', '-', $value) : $value;
        if (in_array($lang, $this->validLanguages)) {
            $this->language = $lang;
        } else {
            $suffix = empty($lang) ? 'en' : ($p !== false) ? strtolower(substr($lang, 0, $p)) : strtolower($lang);
            if (in_array($suffix, $this->validLanguages))
                $this->language = $suffix;
        }
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Set the mode (values can be 'HTML' or 'Plain')
     *
     * @param string $value
     */
    public function setMode($value) {
        if ($value !== 'html' && $value !== 'text')
            throw new CException(Yii::t('MTinyMce', 'mode must be "html" or "text"'));
        $this->mode = $value;
    }

    /**
     * Returns the editor mode
     *
     * @return string
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * The plugins which will be installed
     *
     * @param array $value
     */
    public function setPlugins($value) {
        if (!is_array($value))
            throw new CException(Yii::t('MTinyMce', 'plugins must be an array of strings'));
        $this->plugins = $value;
    }

    /**
     * Returns the plugins
     *
     * @return array
     */
    public function getPlugins() {
        return $this->plugins;
    }

    /**
     * Sets the font families.
     * Format: Title=>font families
     *
     * @param array $value
     */
    public function setFontFamilies($value) {
        if (!is_array($value))
            throw new CException(Yii::t('MTinyMce', 'fontFamilies must be an array of strings'));
        $this->fontFamilies = $value;
    }

    /**
     * Returns the font families
     *
     * @return array
     */
    public function getFontFamilies() {
        return $this->fontFamilies;
    }

    /**
     * Setter
     *
     * @param array $value the font sizes array
     */
    public function setFontSizes($value) {
        if (!is_array($value))
            throw new CException(Yii::t('MTinyMce', 'fontSizes must be an array of integers'));
        $this->fontSizes = $value;
    }

    /**
     * Getter
     *
     * @return array
     */
    public function getFontSizes() {
        return $this->fontSizes;
    }

    /**
     * Setter
     *
     * @param string $value the URL
     */
    public function setContentCSS($value) {
        if (!is_string($value))
            throw new CException(Yii::t('MTinyMce', 'contentCSS must be an URL'));
        $this->contentCSS = $value;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getContentCSS() {
        return $this->contentCSS;
    }

    /**
     * Setter
     *
     * @param boolean $value
     */
    public function setReadOnly($value) {
        if (!is_bool($value))
            throw new CException(Yii::t('MTinyMce', 'readOnly must be boolean'));
        $this->readOnly = $value;
    }

    /**
     * Getter
     *
     * @return boolean
     */
    public function getReadOnly() {
        return $this->readOnly;
    }

    /**
     * Will we use file manager
     *
     * @param boolean $value
     */
    public function setUseElFinder($value) {
        if (!is_bool($value))
            throw new CException(Yii::t('MTinyMce', 'useSwitch must be boolean'));
        $this->useElFinder = $value;
    }

    /**
     * Will we use file manager
     *
     * @return boolean
     */
    public function getUseElFinder() {
        return $this->useElFinder;
    }

    //***************************************************************************
    // Some useful templates
    //***************************************************************************

    /**
     * A simple editor, minimal options
     *
     * @link http://tinymce.moxiecode.com/examples/simple.php
     *
     * @return array
     */
    protected function makeSimpleEditor() {
        $options = array();
        $options['theme'] = 'simple';
        $options['directionality'] = ($this->language == "ar") ? "rtl" : "ltr";
        return $options;
    }

    /**
     * A full editor (example modified to make it more logic)
     *
     * @link http://tinymce.moxiecode.com/examples/full.php
     *
     * @param string $url the base URL for tinymce in assets
     * @return array
     */
    protected function makeFullEditor($url = '') {
        $options = array();

        $this->plugins = array('safari', 'pagebreak', 'style', 'layer', 'table', 'save', 'advhr', 'advimage', 'advlink', 'emotions', 'spellchecker', 'inlinepopups', 'insertdatetime', 'preview', 'media', 'searchreplace', 'print', 'contextmenu', 'paste', 'directionality', 'fullscreen', 'noneditable', 'visualchars', 'nonbreaking', 'xhtmlxtras', 'template', 'youtubeIframe');

        if ($this->contentCSS !== '') {
            $sc = "styleselect,";
            $c1 = '';
            $c2 = 'cite,abbr,acronym,|,';
        } else {
            $sc = '';
            $c1 = ',|,cite,abbr,acronym';
            $c2 = '';
        }

        if (!empty($this->fontFamilies)) {
            $options['theme_advanced_fonts'] = implode(',', $this->fontFamilies);
        }
        if (!empty($this->fontSizes)) {
            $options['theme_advanced_font_sizes'] = implode(',', $this->fontSizes);
        }
        $options['directionality'] = ($this->language == "ar") ? "rtl" : "ltr";
        $options['theme'] = 'advanced';
        $options['theme_advanced_toolbar_location'] = 'top';
        $options['theme_advanced_toolbar_align'] = 'left';
        $options['theme_advanced_path_location'] = 'bottom';
        $options['theme_advanced_buttons1'] = "save,newdocument,print,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,undo,redo,|,removeformat,cleanup,|,spellchecker,|,visualaid,visualchars,|,ltr,rtl,|,code,preview,fullscreen,|,help";
        $options['theme_advanced_buttons2'] = "{$sc}formatselect,fontselect,fontsizeselect,|,forecolor,backcolor,|,bold,italic,underline,strikethrough,|,sub,sup{$c1}";
        $options['theme_advanced_buttons3'] = "justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,hr,advhr,nonbreaking,pagebreak,blockquote,|,charmap,emotions,media,image,|,link,unlink,anchor,|,insertdate,inserttime";
        $options['theme_advanced_buttons4'] = "{$c2}tablecontrols,|,insertlayer,moveforward,movebackward,absolute,|,styleprops,del,ins,attribs,|,template,|,youtubeIframe";
        $options['theme_advanced_toolbar_location'] = "'top'";
        $options['theme_advanced_toolbar_align'] = "'left'";
        $options['theme_advanced_statusbar_location'] = "bottom";
        $options['theme_advanced_toolbar_location'] = 'top';
        $options['theme_advanced_toolbar_align'] = 'left';
        $options['theme_advanced_path_location'] = 'bottom';
        $options['theme_advanced_resize_horizontal'] = true;
        $options['theme_advanced_resizing'] = true;
        $options['spellchecker_languages'] = '+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv';
        $options['valid_elements'] = 'iframe[src|title|width|height|allowfullscreen|frameborder|class|id],object[classid|width|height|codebase|*],param[name|value|_value|*],embed[type|width|height|src|*]';

        if ($url !== '') {
            $options['spellchecker_rpc_url'] = $url . '/tiny_mce/plugins/spellchecker/rpc.php';
        }

        //$options['onchange_callback'] = "function(editor) {tinyMCE.triggerSave();$('#' + editor.id).valid();}";

        return $options;
    }

    // **************************************************************************
    // Options creators
    // **************************************************************************

    protected function makeCompressor() {
        $options = array();

        $options['plugins'] = implode(',', $this->plugins);
        $options['themes'] = ($this->editorTemplate === 'simple') ? 'simple' : 'advanced';
        $options['languages'] = $this->language;

        $options['disk_cache'] = true;
        $options['debug'] = false;

        return CJavaScript::encode($options);
    }

    /**
     * Get the options for the TinyMCE editor. You may want to extend the class
     * and to override this method to customize globally the options, so every
     * editor will have the same l&f and the same behavior. Set 'options'=>array()
     * in the view to get the defaults, otherwise you won't have editor :-)
     *
     * @param array $value
     */
    protected function makeOptions($url = '') {
        list($name, $id) = $this->resolveNameID();

        if ($this->mode === 'html') {
            $options['mode'] = 'exact';
            $options['elements'] = $id;
        } else {
            $options['mode'] = 'none';
        }

        $options['language'] = $this->language;
        $options['readonly'] = $this->readOnly;

        // avoid URL headaches
        $options['relative_urls'] = false;
        $options['remove_script_host'] = true;

        // XHTML compliance
        $options['convert_fonts_to_spans'] = true;

        // avoid problems with browser tabs
        $options['fullscreen_new_window'] = false;

        // avoid problems with HTMLPurifier (see README.TXT)
        $options['media_use_script'] = true;

        // to make the content look like if it were in your target page
        if ($this->contentCSS !== '') {
            $options['content_css'] = $this->contentCSS;
        }

        if ($this->rteFileManager) {
            $routeParams = $this->rteFileManager;
            $route = array_shift($routeParams);
            $actionUrl = Yii::app()->createUrl($route, $routeParams);
            if (Yii::app()->getUrlManager()->getUrlFormat() == 'path') {
                $actionUrl .= '?';
            }
            $options['file_browser_callback'] = 'js:function(field_name, url, type, win) {             
            tinyMCE.activeEditor.windowManager.open({
                file : "' . $actionUrl . '&field=" + field_name + "&url=" + url + "",
                title : "File Manager",
                width : 640,
                height : 450,
                resizable : "no",
                inline : "yes",
                close_previous : "no"
            }, {
                window : win,
                input : field_name
            });
            return false;}';
        }

        if ($this->editorTemplate !== '') {
            switch ($this->editorTemplate) {
                case 'simple':
                    $template = $this->makeSimpleEditor();
                    break;
                case 'full':
                    $template = $this->makeFullEditor($url);
                    break;
                case 'custom':
                    $template = $this->makeCustomEditor($url);
                    break;
            }
            $options = array_merge($options, $template);
        }

        if (!empty($this->plugins) && is_array($this->plugins)) {
            $options['plugins'] = implode(',', $this->plugins);
        }

        // here any option is overriden by user's options
        if (is_array($this->options)) {
            $options = array_merge($options, $this->options);
        }

        return CJavaScript::encode($options);
    }

    /**
     * Executes the widget.
     * This method registers all needed client scripts and renders
     * the text field.
     */
    public function init() {
        list($name, $id) = $this->resolveNameID();

        if ($this->useCookies) {
            if (isset($_COOKIE[$id . self::COOKIE_SUFFIX]) && in_array($_COOKIE[$id . self::COOKIE_SUFFIX], array('text', 'html'))) {
                $this->setMode($_COOKIE[$id . self::COOKIE_SUFFIX]);
            }
        }

        $baseDir = dirname(__FILE__);
        $assets = Yii::app()->getAssetManager()->publish($baseDir . DIRECTORY_SEPARATOR . 'assets', true);

        $tinyOptions = $this->makeOptions($assets);
        $jsUseCookies = ($this->useCookies) ? 'true' : 'false';
        $jsMode = strval($this->mode);
        $jsToggleLabels = CJavaScript::encode($this->switchLabels);

        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');

        if ($this->useCompression) {
            $cs->registerScriptFile($assets . '/tiny_mce/tiny_mce_gzip.js');
            $gzOptions = $this->makeCompressor();
            $js = <<<EOP
tinyMCE_GZ.init({$gzOptions});
EOP;
            $cs->registerScript('Yii.' . get_class($this) . '#' . $id . '_gz', $js, CClientScript::POS_HEAD);
        } else {
            $cs->registerScriptFile($assets . '/tiny_mce/tiny_mce.js');
        }

        $cs->registerScriptFile($assets . '/jquery/jquery.tinymce.js');
        $cs->registerScriptFile($assets . '/embedmedia/embed.js');

        $this->htmlOptions['id'] = $id;
        if (!array_key_exists('style', $this->htmlOptions)) {
            $this->htmlOptions['style'] = "width:{$this->width};height:{$this->height};";
        }
        if (!array_key_exists('cols', $this->htmlOptions)) {
            $this->htmlOptions['cols'] = self::COLS;
        }
        if (!array_key_exists('rows', $this->htmlOptions)) {
            $this->htmlOptions['rows'] = self::ROWS;
        }

        $js = <<<EOP
jQuery("#{$id}").tinyMCE({$tinyOptions}, '{$jsMode}', {$jsUseCookies});
EOP;
        $cs->registerScript('Yii.' . get_class($this) . '#' . $id, $js, CClientScript::POS_LOAD);

        if ($this->hasModel()) {
            $textarea = CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
        } else {
            $textarea = CHtml::textArea($name, $this->value, $this->htmlOptions);
        }

        $html = '';
        if ($this->useSwitch && !$this->readOnly) {
            $label = $this->switchLabels[($this->mode == 'html' ? 0 : 1)];
            $css = ($this->labelClass !== '') ? array('class' => $this->labelClass) : array('style' => $this->labelStyle);
            $switchOptions = array_merge(array('id' => $id . self::SWITCH_SUFFIX), $css);
            $uri = rawurlencode('$("#' . $id . '").toggleModMTinyMce(' . $jsToggleLabels . ')');
            $link = CHtml::link($label, 'javascript:' . $uri, $switchOptions);
            $switch = CHtml::tag('div', array(), CHtml::tag('span', array(), $link));
            $html = CHtml::tag('div', array(), $textarea . $switch);
        } else {
            $html = $textarea;
        }

        echo $html;
    }

    /**
     * A custom editor (example modified to make it more logic)
     *
     * @link http://tinymce.moxiecode.com/examples/full.php
     *
     * @param string $url the base URL for tinymce in assets
     * @return array
     */
    protected function makeCustomEditor($url = '') {
        $options = array();

        $this->plugins = array('safari', 'pagebreak', 'style', 'layer', 'table', 'save', 'advhr', 'advimage', 'advlink', 'emotions', 'spellchecker', 'inlinepopups', 'insertdatetime', 'preview', 'media', 'searchreplace', 'print', 'contextmenu', 'paste', 'directionality', 'fullscreen', 'noneditable', 'visualchars', 'nonbreaking', 'xhtmlxtras', 'template');

        if ($this->contentCSS !== '') {
            $sc = "styleselect,";
            $c1 = '';
            $c2 = 'cite,abbr,acronym,|,';
        } else {
            $sc = '';
            $c1 = ',|,cite,abbr,acronym';
            $c2 = '';
        }

        if (!empty($this->fontFamilies)) {
            $options['theme_advanced_fonts'] = implode(',', $this->fontFamilies);
        }
        if (!empty($this->fontSizes)) {
            $options['theme_advanced_font_sizes'] = implode(',', $this->fontSizes);
        }

        $options['theme'] = 'advanced';
        $options['skin'] = 'o2k7';
        $options['theme_advanced_toolbar_location'] = 'top';
        $options['theme_advanced_toolbar_align'] = 'left';
        $options['theme_advanced_path_location'] = 'bottom';
        $options['theme_advanced_buttons1'] = "{$sc}formatselect,|,forecolor,backcolor,|,bold,italic,underline,strikethrough,|,pastetext,pasteword,|,undo,redo,|,spellchecker,|,code,fullscreen";
        $options['theme_advanced_buttons2'] = "justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,blockquote,|,media,image,|,link,unlink";
        $options['theme_advanced_buttons3'] = "";
        $options['theme_advanced_toolbar_location'] = "'top'";
        $options['theme_advanced_toolbar_align'] = "'left'";
        $options['theme_advanced_statusbar_location'] = "bottom";
        $options['theme_advanced_toolbar_location'] = 'top';
        $options['theme_advanced_toolbar_align'] = 'left';
        $options['theme_advanced_path_location'] = 'bottom';
        $options['theme_advanced_resize_horizontal'] = true;
        $options['theme_advanced_resizing'] = true;
        $options['spellchecker_languages'] = '+Russian=ru,English=en';
        if ($url !== '') {
            $options['spellchecker_rpc_url'] = $url . '/tiny_mce/plugins/spellchecker/rpc.php';
        }

        return $options;
    }

}
