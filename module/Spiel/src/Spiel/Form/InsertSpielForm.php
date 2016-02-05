<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 12:20
 */

namespace Spiel\Form;

use Zend\Form\Form;
use Mannschaft\Service\MannschaftServiceInterface;

/**
 * Class InsertSpielForm
 * @package Spiel\Form
 */
class InsertSpielForm extends Form {

    //protected $mannschaftServiceInterface;

    //public function __construct(MannschaftServiceInterface $mannschaftServiceInterface, $name = null, $options = array()) {
    public function init() {
        //parent::__construct($name, $options);

        //$data = $mannschaftServiceInterface->findAllMannschaften();

        $this->add(array(
            'name' => 'spiel-fieldset',
            'type' => 'Spiel\Form\SpielFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Neues Spiel'
            )
        ));
        
        
       
    }
}