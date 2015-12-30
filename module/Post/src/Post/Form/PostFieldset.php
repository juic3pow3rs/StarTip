<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 11:40
 */

namespace Post\Form;

use Post\Model\Post;
use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;

class PostFieldset extends Fieldset {

    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new Post());

        $this->add(array(
            'type' => 'hidden',
            'name' => 'p_id'
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'b_id',
            'options' => array(
                'label' => 'b_id'
            )
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'g_id',
            'options' => array(
                'label' => 'g_id'
            )
        ));
     
        
        $this->add(array(
        		'name' => 'betreff',
        		'attributes'=>array(
        				'type' => 'text',
        				'required' => true,
        				'size' => '123',
        				'maxlength' => '60',
        
        
        		),
        
        		'options' => array(
        				'label' => 'Betreff'
        		)
        ));
        
        $this->add(array(
        		'name' => 'text',
        		'attributes'=>array(
        				'type' => 'textarea',
        				'required' => true,
        				'cols' => '120',
        				'rows' => '15',
        				'maxlength' => '2500',
        				
        				
        		),
        		
        		'options' => array(
        				'label' => 'Text max. 2500 Zeichen'
        		)
        ));
        
        $this->add(array(
        		'type' => 'hidden',
        		'name' => 'datum_zeit'
        ));
    }
}