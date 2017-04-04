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
                'label' => 'Passsword'
            ],
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