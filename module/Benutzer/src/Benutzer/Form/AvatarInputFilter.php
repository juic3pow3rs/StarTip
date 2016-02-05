<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 16.01.2016
 * Time: 15:04
 */

namespace Benutzer\Form;

use ZfcBase\InputFilter\ProvidesEventsInputFilter;
use Zend\Validator\NotEmpty;

/**
 * Class AvatarInputFilter
 * @package Benutzer\Form
 */
class AvatarInputFilter extends ProvidesEventsInputFilter
{

    /**
     *
     */
    public function __construct()
    {

    }

    public function init()
    {
        $this->add([
            'name' => 'image-file',
            'required' => true,
            'filters' => [
                [
                    'name' => 'File\RenameUpload',
                    'options' => [
                        'target' => 'srv/www/zend/public/upload/user',
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
                ],
                [
                    'name' => 'File\IsImage',
                ],
                [
                    'name' => 'File\ImageSize',
                    'options' => [
                        'maxWidth' => 150,
                        'maxHeight' => 150
                    ]
                ],
            ]
        ]);
        $this->getEventManager()->trigger("init", $this);
    }
}