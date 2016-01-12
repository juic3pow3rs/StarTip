<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 16:41
 */

namespace Mannschaft\Form;

use Mannschaft\Model\Mannschaft;
use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;

class MannschaftFieldset extends Fieldset {

    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new Mannschaft());

        $this->add(array(
            'type' => 'hidden',
            'name' => 'm_id'
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'name',
            'options' => array(
                'label' => 'Name'
            )
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'kuerzel',
            'options' => array(
                'label' => 'Kuerzel'
            )
        ));
        
        $this->add(array(
        		'type' => 'text',
        		'name' => 'gruppe',
        		'options' => array(
        				'label' => 'Gruppe'
        		)
        ));
    }
}