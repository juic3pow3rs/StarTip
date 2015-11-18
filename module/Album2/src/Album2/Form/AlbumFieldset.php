<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 15.11.2015
 * Time: 16:41
 */

namespace Album2\Form;

use Album2\Model\Album;
use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;

class AlbumFieldset extends Fieldset {

    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new Album());

        $this->add(array(
            'type' => 'hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'artist',
            'options' => array(
                'label' => 'The Artist'
            )
        ));

        $this->add(array(
            'type' => 'text',
            'name' => 'title',
            'options' => array(
                'label' => 'The Title'
            )
        ));
    }
}