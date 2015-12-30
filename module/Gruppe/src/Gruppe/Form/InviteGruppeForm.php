<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 16:45
 */

namespace Gruppe\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;

class InviteGruppeForm extends Form {

    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

/**
        $this->add(array(
            'name' => 'gruppe-fieldset',
            'type' => 'Gruppe\Form\GruppeInviteFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
            )
        ));**/

   
        $this->add(array(
        		'name' => 'username',
        		'attributes'=>array(
        				'type' => 'text',
        				'required' => true,
        				
        		),
        
        		'options' => array(
        				'label' => 'Name des Benutzers'
        		)
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Invite'
            )
        ));
    }
}