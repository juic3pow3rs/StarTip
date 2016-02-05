<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 12:20
 */

namespace Post\Form;

use Zend\Form\Form;

/**
 * Class InsertPostForm
 * @package Post\Form
 */
class InsertPostForm extends Form {

    /**
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

      
        $this->add(array(
            'name' => 'post-fieldset',
            'type' => 'Post\Form\PostFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Neuer Eintrag'
            )
        ));
        
        
       
    }
}