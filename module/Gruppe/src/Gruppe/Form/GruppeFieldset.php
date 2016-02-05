<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 16:41
 */

namespace Gruppe\Form;

use Gruppe\Model\Gruppe;
use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class GruppeFieldset
 * @package Gruppe\Form
 */
class GruppeFieldset extends Fieldset {

    /**
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new Gruppe());

        $this->add(array(
            'type' => 'hidden',
            'name' => 'g_id'
        ));

        $this->add(array(
            'type' => 'hidden',
            'name' => 'user_id',
            'options' => array(
                'label' => 'User_id'
            )
        ));

       
      
        
        $this->add(array(
        		'name' => 'name',
        		'attributes'=>array(
        				'type' => 'text',
        				'required' => true,
        				'size' => '103',
        				'maxlength' => '25',
        ),
        
        		'options' => array(
        				'label' => 'Geben Sie hier den Namen der Tippgemeinschaft ein (max. 25 Zeichen)'
        		)
        ));
        
        $this->add(array(
        		'name' => 'beschreibung',
        		'attributes'=>array(
        				'type' => 'textarea',
        				'required' => true,
        				'cols' => '100',
        				'rows' => '8',
        				'maxlength' => '500',
        
        
        		),
        
        		'options' => array(
        				'label' => 'Beschreiben Sie die Tippgemeinschaft (max. 500 Zeichen)'
        		)
        ));
        
    
    }
}