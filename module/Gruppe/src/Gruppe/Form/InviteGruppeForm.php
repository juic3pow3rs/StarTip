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

/**
 * Class InviteGruppeForm
 * @package Gruppe\Form
 */
class InviteGruppeForm extends Form {

    /**
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

        $this->add(array(
        		'name' => 'username',
        		'attributes'=>array(
        				'type' => 'text',
        				'required' => true,
        				
        		),
        
        		'options' => array(
        				'label' => 'Benutzername?'
        		)
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Einladen'
            )
        ));
    }
}