<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 15.12.2015
 * Time: 16:45
 */

namespace Tipp\Form;

use Zend\Form\Form;

/**
 * Class InsertTippForm
 * @package Tipp\Form
 */
class InsertTippForm extends Form {

    /**
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

        $this->add(array(
            'name' => 'tipp-fieldset',
            'type' => 'Tipp\Form\TippFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Add new Tipp'
            )
        ));
    }
}