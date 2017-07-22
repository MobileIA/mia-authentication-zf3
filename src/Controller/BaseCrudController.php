<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MIAAuthentication\Controller;

/**
 * Description of BaseCrudController
 *
 * @author matiascamiletti
 */
class BaseCrudController extends \MIABase\Controller\CrudController
{
    protected $tableName = \MIAAuthentication\Table\UserTable::class;

    protected $formName = \MIAAuthentication\Form\User::class;

    protected $template = 'mia-layout-lte';

    protected $route = 'user';

    public function columns()
    {
        return array(
            array('type' => 'int', 'title' => 'ID', 'field' => 'id', 'is_search' => true),
            array('type' => 'string', 'title' => 'Nombre', 'field' => 'firstname', 'is_search' => true),
            array('type' => 'string', 'title' => 'Apellido', 'field' => 'lastname', 'is_search' => true),
            array('type' => 'string', 'title' => 'Email', 'field' => 'email', 'is_search' => true),
            array('type' => 'image', 'title' => 'Foto', 'field' => 'photo', 'is_search' => true),
            array('type' => 'string', 'title' => 'Telefono', 'field' => 'phone', 'is_search' => true),
            array('type' => 'actions', 'title' => 'Acciones')
        );
    }
    
    public function fields()
    {
    }
}

