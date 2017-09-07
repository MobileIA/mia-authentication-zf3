<?php

namespace MIAAuthentication\Form;

/**
 * Description of Post
 *
 * @author matiascamiletti
 */
class Login extends \MIABase\Form\Base
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct('post', $options);
        
        // call this method to add filtering/validation rules
        $this->addInputFilter();
        
        $this->add([
            'name' => 'email',
            'type' => 'email',
            'options' => [
                'label' => 'Email'
            ],
        ]);
        $this->add([
            'name' => 'password',
            'type' => 'password',
            'options' => [
                'label' => 'Contraseña'
            ],
        ]);
        $this->add([
            'name' => 'remember_me',
            'type' => 'checkbox',
            'options' => [
                'label' => 'Recordarme'
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Ingresar',
                'id'    => 'submitbutton',
            ],
        ]);
    }
    
    protected function addInputFilter()
    {
        $inputFilter = new \Zend\InputFilter\InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'password',
            'required' => true,
        ]);
        $inputFilter->add([
            'name' => 'remember_me',
            'required' => false,
        ]);
    }
}