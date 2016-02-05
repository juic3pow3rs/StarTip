<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 12:20
 */

namespace Spiel\Form;

use Zend\Form\Form;

/**
 * Class IndexSpielForm
 * @package Spiel\Form
 */
class IndexSpielForm extends Form {

    /**
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

      
        $this->add(array(
            'name' => 'spiel-fieldset',
            'type' => 'Spiel\Form\IndexFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Add new Spiel'
            )
        ));
        
        
       
    }
}