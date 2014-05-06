<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Perm class.
 * @package Acl
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Permissions {     
    /**
     * Read Bit Permission
     */
    const READ = 1;
    /**
     * Add Bit Permission
     */
    const ADD = 2;
    /**
     * Edit Bit Permission
     */
    const EDIT = 4;
    /**
     * Delete Bit Permission
     */
    const DELETE = 8;
    /**
     * Publish Bit Permission
     */
    const PUBLISH = 16;        
    
    /**
     * Current user permissons
     * @var int 
     */
    private $permissions = 0;

    /**
     * User permission construct
     * @param int $permissions 
     */
    public function __construct($permissions = 0) {
        $permissions = (int)$permissions;
        $this->permissions = $permissions;
    }

    /**
     * get full permission
     * @access public
     * @return int
     */
    public static function sumDefaultPermission() {
        return (self::READ | self::ADD | self::EDIT | self::DELETE | self::PUBLISH);
    }
    /**
     * Check permission
     * @param int $bit 
     * @access public
     * @return int
     */
    public function checkPermission($bit) {
        $bit = (int)$bit;
        return ($this->permissions & $bit);
    }

    /**
     * revoke permission
     * @param int $bit 
     * @access public
     * @return void
     */
    public function revokePermission($bit) {
        $bit = (int)$bit;
        $this->permissions &= ~ $bit;
    }

    /**
     * assion permission
     * @param int $bit 
     * @access public
     * @return void
     */
    public function assignPermission($bit) {
        $bit = (int)$bit;
        $this->permissions |= $bit;
    }

    /**
     * convert permissions from integer to binary
     * @param int $bit 
     * @access public
     * @return string
     */
    public function permissionsToBinary() {
        return decbin($this->permissions);
    }
    
    /**
     * permissions getter
     * @access public
     * @return int
     */
    public function getPermissions() {
        return $this->permissions;
    }

}