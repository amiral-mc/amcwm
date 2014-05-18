<?php
/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */

// fix for fcgi
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once(dirname(__FILE__) . '/amc.php');

if (isset($config)) {
    $app = AmcWm::createConsoleApplication($config);
    $app->commandRunner->addCommands(YII_PATH . '/cli/commands');
} else {
    $app = AmcWm::createConsoleApplication(array('basePath' => dirname(__FILE__) . '/cli'));
}
$env = @getenv('YII_CONSOLE_COMMANDS');
if (!empty($env))
    $app->commandRunner->addCommands($env);

$app->run();