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

/**
 * Class TippFieldset
 * @package Tipp\Form
 */
class TippFieldset extends Fieldset {

	/**
	 * @param null $name
	 * @param array $options
     */
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
        		'type' =>'Zend\Form\Element\Number',
        		'name' => 'tipp1',
        		'options' => array(
        				'label' => 'Anzahl Tore der ersten Mannschaft?'
        		),
        		'attributes'=>array(
        			    'required' => true,
        				'min'=> '0',
        				'max'=> '20',
        				'step' => '1',
        				
        
        
        		),
        
        		
        ));
        
        $this->add(array(
        		'type' =>'Zend\Form\Element\Number',
        		'name' => 'tipp2',
        		
        		'options' => array(
        				'label' => 'Anzahl Tore der zweiten Mannschaft?'
        		),
        		'attributes'=>array(
        				 'required' => true,
        				'min'=> '0',
        				'max'=> '20',
        				'step' => '1',
        				
        
        
        		),
        
        		
        ));
        
      
    }
}