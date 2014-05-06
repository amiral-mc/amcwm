<?php

require_once('yii/framework/yii.php');
/**
 * Defines the AMC Webmanager framework installation path.
 */
defined('AMC_PATH') or define('AMC_PATH', dirname(__FILE__));

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AmcWm is a helper class serving common framework functionalities extends from Yii class.
 * @package AmcWm
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AmcWm extends Yii {
    /**
     * Window ajax GET index parameter 
     */

    const WINDOW_AJAX = "wajax";

    /**
     * @var array core messages categories
     */
    private static $_coreMessages = array(
        'yii' => true,
        'zii' => true,
        'amcFront' => true,
        'amcBack' => true,
        'amcTools' => true,
        'amcCore' => true,
    );

    /**
     * @var array classes path for core AmcWm classes.
     */
    private static $_amcCorePaths = array(
        'components' => '/components',
        'components.sisterRelated' => '/components/sisterRelated',
        'components.menu' => '/components/menu',
        'components.menu.params' => '/components/menu/params',
        'components.task' => '/components/task',
        'components.breadcrumb' => '/components/breadcrumb',
        'core' => '/core',
        'applications' => '/applications',
        'core.votes' => '/core/votes',
        'core.workflow' => '/core/workflow',
        'core.widgets' => '/core/widgets',
        'vendors' => '/vendors',
        'core.acl' => '/core/acl',
        'core.data' => '/core/data',
        'core.validator' => '/core/validator',
        'core.api' => '/core/api',
        'core.cache' => '/core/cache',
        'core.controllers' => '/core/controllers',
        'core.menu' => '/core/menu',
        'core.sisterRelated' => '/core/sisterRelated',
        'core.menu.params' => '/core/menu/params',
        'messages' => '/messages',
        //'widgets' => '/widgets',
        'params' => '/params',
    );

    /**
     * Create a Amc path alias and add the paths to config.
     * @param mixed $config application configuration.
     * @param boolean $isConsole
     * @access public
     * @return $config return config array after adding import to it
     */
    public static function setAmcCorePaths($config = null, $isConsole = false) {
        self::setPathOfAlias("amcwm", AMC_PATH);
        if (is_string($config)) {
            $config = require($config);
        }
        $config['import'][] = "amcwm";
        $amcCorePaths = self::$_amcCorePaths;
        if ($isConsole) {
            unset($amcCorePaths['widgets']);
        } else {
            if (!isset($config['components']['user']['class'])) {
                $config['components']['user']['class'] = "WebUser";
            }
            unset($amcCorePaths['commands']);
        }
        if (!isset($config['components']['coreMessages']['basePath'])) {
            $config['components']['coreMessages']['basePath'] = AMC_PATH . '/messages';
        }
//        if (!isset($config['components']['messages']['basePath'])) {
//            $config['components']['messages']['basePath'] = AMC_PATH . '/messages';
//        }
        if (!isset($config['components']['messages']['class'])) {
            $config['components']['messages']['class'] = 'PhpMessageSource';
        }
        if (!isset($config['components']['errorHandler']['class'])) {
            $config['components']['errorHandler']['class'] = 'ErrorHandler';
        }
        if (!isset($config['components']['db']['class'])) {
            $config['components']['db']['class'] = 'DbConnection';
        }
        
        $config['components']['mail'] = array(
            'class' => 'Mailer',
            'mailer' => 'mail',
            'charSet' => 'utf8',
            'mailerAttributes'=>array(
                'UseSendmailOptions'=>false,
            )
        );
        foreach ($amcCorePaths as $alias => $path) {
            //self::setPathOfAlias("amcwm.{$alias}", AMC_PATH . $path);
            $config['import'][] = "amcwm.{$alias}.*";
        }
        self::setPathOfAlias("widgets", self::getPathOfAlias("amcwm.widgets"));
        self::setPathOfAlias("icons", self::getPathOfAlias("amcwm.icons"));
        return $config;
    }

    /**
     * @access public
     * @return string the path of the framework
     */
    public static function getAmcWmPath() {
        return AMC_PATH;
    }

    /**
     * Creates a Web application instance.
     * @param mixed $config application configuration.
     * If a string, it is treated as the path of the file that contains the configuration;
     * If an array, it is the actual configuration information.
     * Please make sure you specify the {@link CApplication::basePath basePath} property in the configuration,
     * which should point to the directory containing all application logic, template and data.
     * If not, the directory will be defaulted to 'protected'.
     * @access public
     * @return CWebApplication
     */
    public static function createWebApplication($config = null) {
        self::$classMap['WebApplication'] = dirname(__FILE__) . '/core/WebApplication.php';
        $config = self::setAmcCorePaths($config);
        $application = self::createApplication('WebApplication', $config);
        return $application;
    }

    /**
     * Creates a console application instance.
     * @param mixed $config application configuration.
     * If a string, it is treated as the path of the file that contains the configuration;
     * If an array, it is the actual configuration information.
     * Please make sure you specify the {@link CApplication::basePath basePath} property in the configuration,
     * which should point to the directory containing all application logic, template and data.
     * If not, the directory will be defaulted to 'protected'.
     * @access public
     * @return CConsoleApplication
     */
    public static function createConsoleApplication($config = null) {
        self::$classMap['ConsoleApplication'] = dirname(__FILE__) . '/core/ConsoleApplication.php';
        $config = self::setAmcCorePaths($config, true);
        $config['import'][] = "amcwm.commands.*";
        $application = self::createApplication('ConsoleApplication', $config);
        return $application;
    }

    /**
     * redirect2UrlPath to redirect from the old normal url to a new SEO url
     * call it in the index.php
     * @param $domain the http://domain 
     */
    public static function redirect2UrlPath($domain) {
        if (isset($_GET['r'])) {
            $route = trim($_GET['r'], "/");
            foreach ($_GET as $paramKey => $paramVal) {
                $paramVal = trim(urldecode($paramVal), "/");
                if ($paramKey != 'r') {
                    $route .= "/{$paramKey}/$paramVal";
                }
            }
            //header("HTTP/1.1 301");
            header("Location:$domain/{$route}", true, 301);
            exit;
        }
    }

    /**
     * Returns the application singleton or null if the singleton has not been created yet.
     * @return WebApplication the application singleton, null if the singleton has not been created yet.
     */
    public static function app() {
        return parent::app();
    }

    /**
     * Translates a message to the specified language.
     * This method supports choice format (see {@link CChoiceFormat}),
     * i.e., the message returned will be chosen from a few candidates according to the given
     * number value. This feature is mainly used to solve plural format issue in case
     * a message has different plural forms in some languages.
     * @param string $category message category. Please use only word letters. Note, category 'yii' is
     * reserved for Yii framework core code use. See {@link CPhpMessageSource} for
     * more interpretation about message category.
     * @param string $message the original message
     * @param array $params parameters to be applied to the message using <code>strtr</code>.
     * The first parameter can be a number without key.
     * And in this case, the method will call {@link CChoiceFormat::format} to choose
     * an appropriate message translation.
     * Starting from version 1.1.6 you can pass parameter for {@link CChoiceFormat::format}
     * or plural forms format without wrapping it with array.
     * This parameter is then available as <code>{n}</code> in the message translation string.
     * @param string $source which message source application component to use.
     * Defaults to null, meaning using 'coreMessages' for messages belonging to
     * the 'yii' category and using 'messages' for the rest messages.
     * @param string $language the target language. If null (default), the {@link CApplication::getLanguage application language} will be used.
     * @return string the translated message
     * @see CMessageSource
     */
    public static function t($category, $message, $params = array(), $source = null, $language = null) {
        if ($source === null) {
            $source = isset(self::$_coreMessages[$category]) ? 'coreMessages' : 'messages';
        }
        return Yii::t($category, $message, $params, $source, $language);
    }

    /**
     * 
     * Print Translates a message to the specified language.
     * @see AmcWm.t
     * @param string $category message category.
     * @param string $message the original message
     * @param array $params parameters to be applied to the message using <code>strtr</code>.
     * @param string $source which message source application component to use.
     * @param string $language the target language
     */
    public static function pt($category, $message, $params = array(), $source = null, $language = null) {
        echo self::t($category, $message, $params, $source, $language);
    }

}
