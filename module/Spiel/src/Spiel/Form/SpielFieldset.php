<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 11:40
 */

namespace Spiel\Form;

use Mannschaft\Service\MannschaftServiceInterface;
use Spiel\Model\Spiel;
use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class SpielFieldset
 * @package Spiel\Form
 */
class SpielFieldset extends Fieldset {

    protected $mannschaftServiceInterface;

    /**
     * @param MannschaftServiceInterface $mannschaftServiceInterface
     * @param $name
     * @param array $options
     */
    public function __construct(
        $name = null,
        $options = array(),
        MannschaftServiceInterface $mannschaftServiceInterface
    )
    {

        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new Spiel());

        $this->mannschaftServiceInterface = $mannschaftServiceInterface;

        $mannschaften = $this->mannschaftServiceInterface->findAllMannschaften();

        $data = array();

        foreach ($mannschaften as $m) {
            $data[$m->getM_id()] = $m->getName();
        }

        $this->add(array(
            'type' => 'hidden',
            'name' => 's_id'
        ));

        $this->add(
            array(
                'type' => 'select',
                'name' => 'mannschaft1',
                'options' => array(
                    'label' => 'Mannschaft 1',
                    'empty_option' => 'Bitte Mannschaft auswaehlen',
                    'value_options' => $data,
                )
            )
        );

        $this->add(
            array(
                'type' => 'select',
                'name' => 'mannschaft2',
                'options' => array(
                    'label' => 'Mannschaft 2',
                    'empty_option' => 'Bitte Mannschaft auswaehlen',
                    'value_options' => $data,
                )
            )
        );

        $this->add(array(
        		'type' => 'hidden',
        		'name' => 'modus',
        ));

        $this->add(array(
        		'type' => 'text',
        		'name' => 'anpfiff',
                'required' => true,
        		'options' => array(
        			'label' => 'Anpfiff',
        		),
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

        $this->add(
            array(
                'type' => 'select',
                'name' => 'status',
                'options' => array(
                    'label' => 'Spielstatus',
                    'empty_option' => 'Bitte status auswaehlen',
                    'value_options' => array(
                        '0' => 'nicht beendet',
                        '1' => 'beendet'
                    ),
                )
            )
        );
    }
}