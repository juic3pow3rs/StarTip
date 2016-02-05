<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 28.12.2015
 * Time: 20:59
 */

namespace Tipp\Form;

use Mannschaft\Service\MannschaftServiceInterface;
use Tipp\Service\TippServiceInterface;
use Zend\Form\Form;

/**
 * Class ZusatztippForm
 * @package Tipp\Form
 */
class ZusatztippForm extends Form {

    protected $mannschaftServiceInterface;

    protected $tippServiceInterface;

    /**
     * @param $name
     * @param array $options
     * @param MannschaftServiceInterface $mannschaftServiceInterface
     * @param TippServiceInterface $tippServiceInterface
     */
    public function __construct(
        $name = null,
        $options = array(),
        MannschaftServiceInterface $mannschaftServiceInterface,
        TippServiceInterface $tippServiceInterface
    )
    {

        parent::__construct($name, $options);

        $this->mannschaftServiceInterface = $mannschaftServiceInterface;
        $this->tippServiceInterface = $tippServiceInterface;

        $mannschaften = $this->mannschaftServiceInterface->findAllMannschaften();

        $data = array();

        foreach ($mannschaften as $m) {
            $data[$m->getM_id()] = $m->getName();
        }

        $i = 0;
        $stati = $this->tippServiceInterface->isActive();
        $status = array();

        foreach ($stati as $s) {
            $status[++$i] = $s['status'];
        }

        if ($status[1]) {
            $this->add(
                array(
                    'type' => 'select',
                    'name' => 'platz1',
                    'options' => array(
                        'label' => '1. Platz',
                        'empty_option' => 'Bitte Mannschaft auswaehlen',
                        'value_options' => $data,
                    ),
                )
            );
        }

        if ($status[2]) {
            $this->add(
                array(
                    'type' => 'select',
                    'name' => 'platz2',
                    'options' => array(
                        'label' => '2. Platz',
                        'empty_option' => 'Bitte Mannschaft auswaehlen',
                        'value_options' => $data,
                    )
                )
            );
        }

        if ($status[3]) {
            $this->add(
                array(
                    'type' => 'select',
                    'name' => 'platz3',
                    'options' => array(
                        'label' => '3. Platz',
                        'empty_option' => 'Bitte Mannschaft auswaehlen',
                        'value_options' => $data,
                    )
                )
            );
        }

        if ($status[4]) {
            $this->add(
                array(
                    'type' => 'select',
                    'name' => 'fair',
                    'options' => array(
                        'label' => 'Fairste Mannschaft',
                        'empty_option' => 'Bitte Mannschaft auswaehlen',
                        'value_options' => $data,
                    )
                )
            );
        }

        if ($status[5]) {
            $this->add(
                array(
                    'type' => 'select',
                    'name' => 'unfair',
                    'options' => array(
                        'label' => 'Unfairste Mannschaft',
                        'empty_option' => 'Bitte Mannschaft auswaehlen',
                        'value_options' => $data,
                    )
                )
            );
        }

        if ($status[6]) {
            $this->add(
                array(
                    'type' => 'select',
                    'name' => 'tore',
                    'options' => array(
                        'label' => 'Mannschaft mit meisten Toren',
                        'empty_option' => 'Bitte Mannschaft auswaehlen',
                        'value_options' => $data,
                    )
                )
            );
        }

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Abgegebn'
            )
        ));
    }
}