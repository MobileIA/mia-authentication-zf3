<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MIAAuthentication\Form;

/**
 * Description of User
 *
 * @author matiascamiletti
 */
class User extends \MIABase\Form\Base
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct('user', $options);
        
        $this->add([
            'name' => 'mia_id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'firstname',
            'type' => 'text',
            'options' => [
                'label' => 'Nombre'
            ],
            'attributes' => [
                'placeholder' => 'Escribe el nombre'
            ]
        ]);
        $this->add([
            'name' => 'lastname',
            'type' => 'text',
            'options' => [
                'label' => 'Apellido'
            ],
            'attributes' => [
                'placeholder' => 'Escribe el apellido'
            ]
        ]);
        $this->add([
            'name' => 'email',
            'type' => 'text',
            'options' => [
                'label' => 'Email'
            ]
        ]);
        $this->add([
            'name' => 'phone',
            'type' => 'text',
            'options' => [
                'label' => 'Telefono'
            ],
            'attributes' => [
                'placeholder' => 'Escribe el telefono'
            ]
        ]);
        $this->add([
            'name' => 'photo',
            'type' => \MIAFile\Form\Element\MobileiaPhoto::class,
            'options' => [
                'label' => 'Foto'
            ],
            'attributes' => [
                'placeholder' => 'Selecciona una foto'
            ]
        ]);
        $this->add([
            'name' => 'facebook_id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Enviar',
                'id'    => 'submitbutton',
            ],
        ]);
    }
    
    public function addInputFilter()
    {
        $inputFilter = new \Zend\InputFilter\InputFilter();
        $inputFilter->add([
                    'name' => 'mia_id',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'role',
                    'required' => false,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'firstname',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\StripTags::class],
                        ['name' => \Zend\Filter\StringTrim::class],
                    ],
                    'validators' => [
                        [
                            'name' => \Zend\Validator\StringLength::class,
                            'options' => [
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 100,
                            ],
                        ],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'lastname',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\StripTags::class],
                        ['name' => \Zend\Filter\StringTrim::class],
                    ],
                    'validators' => [
                        [
                            'name' => \Zend\Validator\StringLength::class,
                            'options' => [
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 100,
                            ],
                        ],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'email',
                    'required' => true,
                    'validators' => [
                        [
                            'name' => \Zend\Validator\EmailAddress::class,
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
            'name' => 'photo',
            'required' => false,
            'filters' => [
                ['name' => \Zend\Filter\StripTags::class],
                ['name' => \Zend\Filter\StringTrim::class],
            ],
        ]);
        $inputFilter->add([
            'name' => 'phone',
            'required' => false,
            'filters' => [
                ['name' => \Zend\Filter\StripTags::class],
                ['name' => \Zend\Filter\StringTrim::class],
            ],
        ]);
        $inputFilter->add([
            'name' => 'facebook_id',
            'required' => false,
            'filters' => [
                ['name' => \Zend\Filter\StripTags::class],
                ['name' => \Zend\Filter\StringTrim::class],
            ],
        ]);
        
        $this->setInputFilter($inputFilter);
    }
}