<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 04.11.2015
 * Time: 13:24
 */

namespace Album\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Class Album
 * @package Album\Model
 */
class Album implements  InputFilterAwareInterface {
    public $id;
    public $artist;
    public $title;
    protected $inputFilter;

    /**
     * @param $data
     */
    public function exchangeArray($data) {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->artist = (!empty($data['artist'])) ? $data['artist'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
    }

    /**
     * @return array
     */
    public function getArrayCopy() {
        return get_object_vars($this);
    }

    /**
     * Set input filter
     *
     * @param  InputFilterInterface $inputFilter
     * @return InputFilterAwareInterface
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        if(!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $inputFilter->add(
                array(
                    'name' => 'id',
                    'required' => 'true',
                    'filters' => array(
                        array('name' => 'Int')
                    ),
                )
            );
            $inputFilter->add(
                array(
                    'name' => 'artist',
                    'required' => 'true',
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 100,
                            ),
                        ),
                    ),
                )
            );
            $inputFilter->add(
                array(
                    'name' => 'title',
                    'required' => 'true',
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 100,
                            ),
                        ),
                    ),
                )
            );

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}