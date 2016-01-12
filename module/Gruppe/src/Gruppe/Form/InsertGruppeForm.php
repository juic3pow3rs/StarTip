<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 16:45
 */

namespace Gruppe\Form;

use Zend\Form\Form;

class InsertGruppeForm extends Form {

    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

        $this->add(array(
            'name' => 'gruppe-fieldset',
            'type' => 'Gruppe\Form\GruppeFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Add new Gruppe'
            )
        ));
    }
}