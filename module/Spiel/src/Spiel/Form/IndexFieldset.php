<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 11:40
 */

namespace Spiel\Form;

use Spiel\Model\Spiel;
use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;

class IndexFieldset extends Fieldset {

    public function __construct($name = null, $options = array()) {
    	
        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new Spiel());

        $this->add(array(
            'type' => 'hidden',
            'name' => 's_id'
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'mannschaft1',
        		'options' => array(
        				'label' => 'Mannschaft 1'
        		)
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'mannschaft2',
            'options' => array(
                'label' => 'Mannschaft 2'
            )
        ));
        
        $this->add(array(
        		'type' => 'text',
        		'name' => 'modus',
        		'options' => array(
        				'label' => 'Modus'
        		)
        ));
        
        $this->add(array(
        		'type' => 'text',
        		'name' => 'anpfiff',
        		'options' => array(
        				'label' => 'Anpfiff'
        		)
        ));
        
        $this->add(array(
        		'type' => 'text',
        		'name' => 'tore1',
        		'options' => array(
        				'label' => 'Tore1'
        		)
        ));
        
        $this->add(array(
        		'type' => 'text',
        		'name' => 'tore2',
        		'options' => array(
        				'label' => 'Tore2'
        		)
        ));
        
        $this->add(array(
        		'type' => 'text',
        		'name' => 'punkte1',
        		'options' => array(
        				'label' => 'Punkte1'
        		)
        ));
        
        $this->add(array(
        		'type' => 'text',
        		'name' => 'punkte2',
        		'options' => array(
        				'label' => 'Punkte2'
        		)
        ));
        $this->add(array(
        		'type' => 'text',
        		'name' => 'gelb1',
        		'options' => array(
        				'label' => 'Gelb1'
        		)
        ));
        
        $this->add(array(
        		'type' => 'text',
        		'name' => 'gelb2',
        		'options' => array(
        				'label' => 'Gelb2'
        		)
        ));
        
        $this->add(array(
        		'type' => 'text',
        		'name' => 'rot1',
        		'options' => array(
        				'label' => 'Rot1'
        		)
        ));
        
        $this->add(array(
        		'type' => 'text',
        		'name' => 'rot2',
        		'options' => array(
        				'label' => 'Rot2'
        		)
        ));

		$this->add(array(

		));
	
    }
}