<?php

return array(
    'tables' => array(
        array(
            'id' => 1,
            'name' => 'configuration',
        ),
        array(
            'id' => 2,
            'name' => 'system_attributes',
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcConfigurationController',
                'attributes' => 'AmcAttributesController',
            ),
        ),
    ),
    'attributesTables' => array(
        'dir_companies_attributes',
    ),
    'usedModules' => array(
        'directory' => array(
            'name' => 'directory',
            'tables' => array(
                'dir_companies_attributes' => 
                array('name' => 'dir_companies_attributes'
                    , 'primaryKey' => 'company_attribute_id'
                    , 'foreignKey' => 'company_id'),
            ),
        ),
    ),
    'attributesTypes' => array(
        '1' => array('name' => "phone", 'dataType' => 'text', 'length' => 15, 'translate' => false, 'systemOnly' => false),
        '2' => array('name' => "fax", 'dataType' => 'text', 'length' => 15, 'translate' => false, 'systemOnly' => false),
        '3' => array('name' => "mobile", 'dataType' => 'text', 'length' => 15, 'translate' => false, 'systemOnly' => false),
        '4' => array('name' => "decmial", 'dataType' => 'decmial', 'length' => 15, 'translate' => false, 'systemOnly' => true),
        '5' => array('name' => "integer", 'dataType' => 'integer', 'length' => 15, 'translate' => false, 'systemOnly' => true),
        '6' => array('name' => "address", 'dataType' => 'text', 'length' => 255, 'translate' => true, 'systemOnly' => false),
        '7' => array(
            'name' => "email",
            'dataType' => 'email',
            'length' => 60,
            'translate' => false,
            'systemOnly' => false,
            'rules' => array(
                array('value', 'email'),
            ),
        ),
        '8' => array(
            'name' => "url",
            'dataType' => 'url',
            'length' => 100,
            'translate' => false,
            'systemOnly' => false,
            'rules' => array(
                array('value', 'url'),
            ),
        ),
        '9' => array(
            'name' => "textBox",
            'dataType' => 'text',
            'length' => 255,
            'translate' => true,
            'systemOnly' => true,            
        ),
        '10' => array('name' => "textArea", 'dataType' => 'text',  'length' => 0, 'translate' => true, 'systemOnly' => true),
        '11' => array('name' => "file", 'dataType' => 'file', 'length' => 0, 'translate' => false, 'systemOnly' => true),
        '12' => array('name' => "multiFiles", 'dataType' => 'files', 'length' => 0, 'translate' => false, 'systemOnly' => true, 'length' => 4),
    ),
);
