<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 06.11.2015
 * Time: 17:13
 */

namespace Album\Form;

use Zend\Form\Form;

/**
 * Class AlbumForm
 * @package Album\Form
 */
class AlbumForm extends Form {

    /**
     * @param null $name
     */
    public function __construct ($name = null) {

        //we want to ignore the name passed
        parent::__construct('album');

        $this->add(
            array(
                'name' => 'id',
                'type' => 'Hidden',
            )
        );
        $this->add(
            array(
                'name' => 'title',
                'type' => 'Text',
                'options' => array(
                    'label' => 'Title',
                ),
            )
        );
        $this->add(
            array(
                'name' => 'artist',
                'type' => 'Text',
                'options' => array(
                    'label' => 'Artist',
                ),
            )
        );
        $this->add(
            array(
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => array(
                    'value' => 'Go',
                    'id' => 'submitbutton',
                ),
            )
        );

    }

}