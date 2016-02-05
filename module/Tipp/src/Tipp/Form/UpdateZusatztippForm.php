<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 22.12.2015
 * Time: 17:43
 */

namespace Tipp\Form;

use Tipp\Service\TippServiceInterface;
use Zend\Form\Form;

/**
 * Class UpdateZusatztippForm
 * @package Tipp\Form
 */
class UpdateZusatztippForm extends Form {

    protected $tippServiceInterface;

    /**
     * @param null $name
     * @param array $options
     * @param TippServiceInterface $tippServiceInterface
     */
    public function __construct(
        $name = null,
        $options = array(),
        TippServiceInterface $tippServiceInterface
    ) {

        parent::__construct($name, $options);

        $this->tippServiceInterface = $tippServiceInterface;

        $i = 0;

        $stati = $tippServiceInterface->isActive();
        $status = array();

        foreach ($stati as $s) {
            $status[++$i] = $s['status'];
        }

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Radio',
                'name' => 'status1',
                'options' => array(
                    'label' => '1. Platz aktivieren?',
                    'value_options' => array(
                        array(
                            'value' => '0',
                            'label' => 'Nein',
                            'selected' => !$status[1]
                        ),
                        array(
                            'value' => '1',
                            'label' => 'Ja',
                            'selected'=> $status[1]
                        )
                    ),
                ),
            )
        );

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Radio',
                'name' => 'status2',
                'options' => array(
                    'label' => '2. Platz aktivieren?',
                    'value_options' => array(
                        array(
                            'value' => '0',
                            'label' => 'Nein',
                            'selected' => !$status[2]
                        ),
                        array(
                            'value' => '1',
                            'label' => 'Ja',
                            'selected'=> $status[2]
                        )
                    ),
                ),
            )
        );

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Radio',
                'name' => 'status3',
                'options' => array(
                    'label' => '3. Platz aktivieren?',
                    'value_options' => array(
                        array(
                            'value' => '0',
                            'label' => 'Nein',
                            'selected' => !$status[3]
                        ),
                        array(
                            'value' => '1',
                            'label' => 'Ja',
                            'selected'=> $status[3]
                        )
                    ),
                ),
            )
        );

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Radio',
                'name' => 'status4',
                'options' => array(
                    'label' => 'Fairste Mannschaft aktivieren?',
                    'value_options' => array(
                        array(
                            'value' => '0',
                            'label' => 'Nein',
                            'selected' => !$status[4]
                        ),
                        array(
                            'value' => '1',
                            'label' => 'Ja',
                            'selected'=> $status[4]
                        )
                    ),
                ),
            )
        );

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Radio',
                'name' => 'status5',
                'options' => array(
                    'label' => 'Unfairste Mannschaft aktivieren?',
                    'value_options' => array(
                        array(
                            'value' => '0',
                            'label' => 'Nein',
                            'selected' => !$status[5]
                        ),
                        array(
                            'value' => '1',
                            'label' => 'Ja',
                            'selected'=> $status[5]
                        )
                    ),
                ),
            )
        );

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Radio',
                'name' => 'status6',
                'options' => array(
                    'label' => 'Mannschaft mit meisten Toren aktivieren?',
                    'value_options' => array(
                        array(
                            'value' => '0',
                            'label' => 'Nein',
                            'selected' => !$status[6]
                        ),
                        array(
                            'value' => '1',
                            'label' => 'Ja',
                            'selected'=> $status[6]
                        )
                    ),
                ),
            )
        );

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Update Zusatztipps'
            )
        ));
    }
}