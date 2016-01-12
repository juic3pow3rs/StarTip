<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 16:41
 */

namespace Benutzer\Form;

use Zend\Form\Fieldset;

class BenutzerSucheFieldset extends Fieldset {

    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);
       
        $this->add(array(
            'type' => 'text',
            'name' => 'username',
            'options' => array(
                'label' => 'Benutzername'
            )
        ));

       
    }
}