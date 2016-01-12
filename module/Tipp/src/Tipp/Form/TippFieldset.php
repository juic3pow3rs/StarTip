<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 15.12.2015
 * Time: 16:41
 */

namespace Tipp\Form;

use Tipp\Model\Tipp;
use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;

class TippFieldset extends Fieldset {

    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new Tipp());

        $this->add(array(
            'type' => 'hidden',
            'name' => 't_id'
        ));

        $this->add(array(
            'type' => 'hidden',
            'name' => 'b_id',
          
        ));
        
        
        $this->add(array(
        		'name' => 'tipp1',
        		'attributes'=>array(
        				'type' => 'text',
        				'required' => true,
        				'size' => '2',
        				'maxlength' => '2',
        
        
        		),
        
        		'options' => array(
        				'label' => 'Wie viele Tore wird die Erste Mannschaft Ihrer Meinung nach erziehlen?'
        		)
        ));
        
        $this->add(array(
        		'name' => 'tipp2',
        		'attributes'=>array(
        				'type' => 'text',
        				'required' => true,
        				'size' => '2',
        				'maxlength' => '2',
        
        
        		),
        
        		'options' => array(
        				'label' => 'Wie viele Tore wird die Zweite Mannschaft Ihrer Meinung nach erziehlen?'
        		)
        ));
        
      
    }
}