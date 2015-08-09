<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * PhpMessageSource extends CPhpMessageSource and add full path to langaue file
 * @package AmcWm.core
 * @author Amiral Management Corporation
 * @version 1.0
 */
class PhpMessageSource extends CPhpMessageSource {

    private $_files = array();

    public $forceTranslation = true;
    /**
     * Determines the message file name based on the given category and language.
     * If the category name contains a dot, it will be split into the module class name and the category name.
     * If the first and last character in category equal ="/" then load the category from the given full path 
     * In this case, the message file will be assumed to be located within the 'messages' subdirectory of
     * the directory containing the module class file.
     * Otherwise, the message file is assumed to be under the {@link basePath}.
     * @param string $category category name
     * @param string $language language ID
     * @return string the message file path
     */
    protected function getMessageFile($category, $language) {
        if (!isset($this->_files[$category][$language])) {
            if (($pos = strpos($category, '.')) !== false) {
                $extensionClass = substr($category, 0, $pos);
                $extensionCategory = substr($category, $pos + 1);
                // First check if there's an extension registered for this class.
                if (isset($this->extensionPaths[$extensionClass]))
                    $this->_files[$category][$language] = Yii::getPathOfAlias($this->extensionPaths[$extensionClass]) . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $extensionCategory . '.php';
                else {
                    if ($extensionClass == "amcwm" || $extensionClass == "msgsbase" || $extensionClass == "application") {
                        $lastPos = strrpos($category, '.') + 1;
                        $categoryName = substr($category, $lastPos);
                        $categoryPath = substr($category, 0, strlen($category) - strlen($categoryName) - 1);
                        $this->_files[$category][$language] = Yii::getPathOfAlias($categoryPath) . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $categoryName . '.php';
                    } else {
                        if (is_dir($extensionClass)) {
                            $this->_files[$category][$language] = $extensionClass . DIRECTORY_SEPARATOR . 'messages' . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $extensionCategory . '.php';
                        } else {
                            // No extension registered, need to find it.
                            $class = new ReflectionClass($extensionClass);
                            $this->_files[$category][$language] = dirname($class->getFileName()) . DIRECTORY_SEPARATOR . 'messages' . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $extensionCategory . '.php';
                        }
                    }
                }
            } else {
                $this->_files[$category][$language] = $this->basePath . DIRECTORY_SEPARATOR . $language . DIRECTORY_SEPARATOR . $category . '.php';
            }
        }
        return $this->_files[$category][$language];
    }

}