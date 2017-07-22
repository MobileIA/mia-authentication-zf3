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
}