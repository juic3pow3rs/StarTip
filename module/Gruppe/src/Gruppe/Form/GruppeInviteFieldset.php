<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 16:41
 */

namespace Gruppe\Form;

use Zend\Form\Fieldset;

class GruppeInviteFieldset extends Fieldset {

    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

        $this->add(array(
            'type' => 'hidden',
            'name' => 'g_id'
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'username',
            'options' => array(
                'label' => 'Benutzername'
            )
        ));

       
    }
}