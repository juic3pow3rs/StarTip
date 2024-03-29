<?php

namespace ZfcUser\Form;

use ZfcBase\InputFilter\ProvidesEventsInputFilter;
use ZfcUser\Options\RegistrationOptionsInterface;
use Zend\Validator\ValidatorInterface;

class RegisterFilter extends ProvidesEventsInputFilter
{
    /**
     * @var ValidatorInterface
     */
    protected $emailValidator;

    /**
     * @var ValidatorInterface
     */
    protected $usernameValidator;

    /**
     * @var RegistrationOptionsInterface
     */
    protected $options;

    public function __construct(ValidatorInterface $emailValidator, ValidatorInterface $usernameValidator, RegistrationOptionsInterface $options)
    {
        $this->setOptions($options);
        $this->emailValidator = $emailValidator;
        $this->usernameValidator = $usernameValidator;

        if ($this->getOptions()->getEnableUsername()) {
            $this->add(array(
                'name'       => 'username',
                'required'   => true,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 255,
                        ),
                    ),
                    $this->usernameValidator,
                ),
            ));
        }

        $this->add(array(
            'name'       => 'email',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                ),
                // Regex f�r check ob uni mail.
                array(
                    'name' => 'Regex',
                    'options' => array(
                        'pattern' => '/\b[A-Z0-9._%+-]+@(stud.)?uni-passau.de\b/i',
                        'messages' => array(
                            \Zend\Validator\Regex::NOT_MATCH => 'Ungueltige Eingabe, nur "@uni-passau.de" und "@stud.uni-passau.de" E-Mail Adressen erlaubt! ',
                        ),
                    ),
                ),
                $this->emailValidator
            ),
        ));

        if ($this->getOptions()->getEnableDisplayName()) {
            $this->add(array(
                'name'       => 'display_name',
                'required'   => true,
                'filters'    => array(array('name' => 'StringTrim')),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 128,
                        ),
                    ),
                ),
            ));
        }

        $this->add(array(
            'name'       => 'password',
            'required'   => true,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 6,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'passwordVerify',
            'required'   => true,
            'filters'    => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 6,
                    ),
                ),
                array(
                    'name'    => 'Identical',
                    'options' => array(
                        'token' => 'password',
                    ),
                ),
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }

    public function getEmailValidator()
    {
        return $this->emailValidator;
    }

    public function setEmailValidator($emailValidator)
    {
        $this->emailValidator = $emailValidator;

        return $this;
    }

    public function getUsernameValidator()
    {
        return $this->usernameValidator;
    }

    public function setUsernameValidator($usernameValidator)
    {
        $this->usernameValidator = $usernameValidator;

        return $this;
    }

    /**
     * set options
     *
     * @param RegistrationOptionsInterface $options
     */
    public function setOptions(RegistrationOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * get options
     *
     * @return RegistrationOptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }
}
