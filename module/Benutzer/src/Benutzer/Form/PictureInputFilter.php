<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 18.01.2016
 * Time: 14:36
 */

namespace Benutzer\Form;

use ZfcBase\InputFilter\ProvidesEventsInputFilter;
use Zend\Validator\NotEmpty;

/**
 * Class PictureInputFilter
 * @package Benutzer\Form
 */
class PictureInputFilter extends ProvidesEventsInputFilter
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
            'name' => 'image-url',
            'required' => true,
            'filters' => [
            ],
            'validators' => [
                [
                    'name' => 'Regex',
                    'options' => array(
                        'pattern' => '/\b(https?:\/\/i\.imgur\.com\/\w+(.jpg)?(.png)?)/',
                        'messages' => array(
                            \Zend\Validator\Regex::NOT_MATCH => 'Invalid input, only imgur links of .png or .jpg Files allowed',
                        ),
                    ),
                ],
            ]
        ]);
        $this->getEventManager()->trigger("init", $this);
    }
}