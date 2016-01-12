<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 27.12.2015
 * Time: 01:20
 */
namespace Mannschaft\Form;

use ZfcBase\InputFilter\ProvidesEventsInputFilter;
use Zend\Validator\NotEmpty;

class UploadInputFilter extends ProvidesEventsInputFilter
{

    public function __construct()
    {

    }

    public function init()
    {
    $this->add([
        'name' => 'csv-file',
        'required' => true,
        'filters' => [
            [
                'name' => 'File\RenameUpload',
                'options' => [
                    'target' => 'srv/www/zend/data/uploads',
                    'overwrite' => true,
                ]
            ]
        ],
        'validators' => [
            [
                'name' => 'File\UploadFile',
            ],
            [
                'name' => 'NotEmpty',
            ]
        ]
    ]);
    $this->getEventManager()->trigger("init", $this);
    }
}