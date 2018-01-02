<?php

namespace MIAAuthentication\Form;

/**
 * Description of Post
 *
 * @author matiascamiletti
 */
class Register extends \MIABase\Form\Base
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct('post', $options);
        
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
            'name' => 'firstname',
            'type' => 'text',
            'options' => [
                'label' => 'Nombre'
            ],
        ]);
        $this->add([
            'name' => 'lastname',
            'type' => 'text',
            'options' => [
                'label' => 'Apellido'
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
    
    public function addInputFilter()
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
                [
                    'name' => \Zend\Validator\Db\NoRecordExists::class,
                    'options' => [
                        'table' => 'mia_user',
                        'field' => 'email',
                        'adapter' => $this->getServiceManager()->get('Zend\Db\Adapter\Adapter')
                    ]
                ]
            ],
        ]);
        $inputFilter->add([
            'name' => 'password',
            'required' => true,
        ]);
        $inputFilter->add([
            'name' => 'firstname',
            'required' => true,
        ]);
        $inputFilter->add([
            'name' => 'lastname',
            'required' => true,
        ]);
    }
}