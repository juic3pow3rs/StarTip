<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 16:45
 */

namespace Mannschaft\Form;

use Zend\Form\Form;

class InsertMannschaftForm extends Form {

    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

        $this->add(array(
            'name' => 'mannschaft-fieldset',
            'type' => 'Mannschaft\Form\MannschaftFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Mannschaft hinzufuegen'
            )
        ));
    }
}