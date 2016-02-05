<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 18.01.2016
 * Time: 14:30
 */

namespace Benutzer\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Class PictureForm
 * @package Benutzer\Form
 */
class PictureForm extends Form {

    /**
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

        $this->add(array(
            'type' => 'text',
            'name' => 'image-url',
            'options' => array(
                'label' => 'Bild Link'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Submit'
            )
        ));
    }
}