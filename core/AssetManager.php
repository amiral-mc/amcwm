<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * CActiveForm provides a set of methods that can help to simplify the creation
 * of complex and interactive HTML forms that are associated with data models.
 *
 * @package AmcWm.core
 * @copyright 2012, Amiral Management Corporation. All Rights Reserved..
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AssetManager extends CAssetManager {

    /**
     * Generates path segments relative to basePath.
     * @param string $file for which public path will be created.
     * @param bool $hashByName whether the published directory should be named as the hashed basename.
     * @return string path segments without basePath.
     * @since 1.1.13
     */
    protected function generatePath($file, $hashByName = false) {
        if (is_file($file))
            $pathForHashing = $hashByName ? $file : dirname($file) . filemtime($file);
        else
            $pathForHashing = $hashByName ? $file : $file . filemtime($file);

        return $this->hash($pathForHashing);
    }

}
