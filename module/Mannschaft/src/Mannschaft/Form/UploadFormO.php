<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 26.12.2015
 * Time: 21:48
 */

namespace Mannschaft\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\InputFilter\FileInput;
use Zend\Filter\File\RenameUpload;
use Zend\Validator\File\Size;
use Zend\Validator\File\Extension;
use Zend\Validator\File\MimeType;

class UploadForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->setInputFilter($this->createInputFilter());
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('csv-file');
        $file->setLabel('Mannschaft CSV Upload')
             ->setAttribute('id', 'csv-file')
             ->setAttribute('class', 'form-control');
        $this->add($file);

    }

    public function createInputFilter()
    {
        $inputFilter = new InputFilter();
        // File Input
        $file = new FileInput('csv-file');
        $file->setRequired(true);

        $file->getValidatorChain()
             ->attach(new Size(array(
                    'options' => array(
                        'max' => 1000
                    )
                )
             ))
             ->attach(new Extension(array(
                     'extension' => array(
                         'csv'
                     )
                 )
             ));
             /**->attach(new MimeType(array(
                    'messageTemplates' => array(
                        MimeType::FALSE_TYPE => 'The file is not an allowed type',
                        MimeType::NOT_DETECTED => 'The file type was not detected',
                        MimeType::NOT_READABLE => 'The file type was not readable',
                    ),
                    'options' => array(
                        'enableHeaderCheck' => true,
                        'mimeType' => 'text/csv'
                    )
                )
             ));**/

        $file->getFilterChain()->attach(new RenameUpload(array(
            'options' => array(
                'target' => sprintf(
                    "%s/data/uploads/mnnschftn.%s.csv",
                    __DIR__ . '/../../../../..',
                    time()
                ),
                'overwrite' => true,
            )
        )));

        /**$file->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'          => 'srv/www/zend/data/uploads/',
                'overwrite'       => true,
                'use_upload_name' => true,
            )
        );**/

        $inputFilter->add($file);

        return $inputFilter;
    }
}