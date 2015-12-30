<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 27.12.2015
 * Time: 00:53
 */
namespace Mannschaft\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\InputFilter\FileInput;
use Zend\Filter\File\RenameUpload;
use Zend\Validator\File\UploadFile;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Zend\Validator\File\MimeType;

class UploadForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('csv-file');
        $file->setLabel('Avatar Image Upload')
            ->setAttribute('id', 'csv-file');
        $this->add($file);
    }
    /**
    public function addInputFilter()
    {
        $inputFilter = new InputFilter();

        // File Input
        $fileInput = new FileInput('csv-file');
        $fileInput->setRequired(true);
        $fileInput->getValidatorChain()
            ->attach(new UploadFile());
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => './data/tmpuploads/avatar.png',
                'randomize' => true,
            )
        );
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }**/
}