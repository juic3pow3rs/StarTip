<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 16:45
 */

namespace Benutzer\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;

class SuchBenutzerForm extends Form {

    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

        $this->add(array(
            'type' => 'text',
            'name' => 'username',
            'options' => array(
                'label' => 'Benutzername'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Suche'
            )
        ));
    }
}