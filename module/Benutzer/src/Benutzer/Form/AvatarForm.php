<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 16.01.2016
 * Time: 14:57
 */

namespace Benutzer\Form;

use Zend\Form\Element;
use Zend\Form\Form;

/**
 * Class AvatarForm
 * @package Benutzer\Form
 */
class AvatarForm extends Form
{
    /**
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('image-file');
        $file->setLabel('Avatar Image Upload')
            ->setAttribute('id', 'image-file');
        $this->add($file);
    }
}