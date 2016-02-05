<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 15.11.2015
 * Time: 16:45
 */

namespace Album2\Form;

use Zend\Form\Form;

/**
 * Class InsertAlbumForm
 * @package Album2\Form
 */
class InsertAlbumForm extends Form {

    /**
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, $options = array()) {

        parent::__construct($name, $options);

        $this->add(array(
            'name' => 'album-fieldset',
            'type' => 'Album2\Form\AlbumFieldset',
            'options' => array(
                'use_as_base_fieldset' => true
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Add new Album'
            )
        ));
    }
}