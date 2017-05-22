<?php

namespace MIAAuthentication\Action;

/**
 * Description of AuthListAction
 *
 * @author matiascamiletti
 */
class AuthListAction extends \MIABase\Action\ListAction
{
    /**
     * Instancia del usuario logueado
     * @var \MIAAuthentication\Entity\User
     */
    protected $user = null;
    /**
     * Habilita que busque en la DB a traves del UserId
     * @var boolean
     */
    protected $enabledUser = true;
    
    protected function createSelect()
    {
        $select = parent::createSelect();
        // Buscamos los registros del usuario
        if($this->enabledUser){
            $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('user_id = ?', $this->user->id));
        }
        
        return $select;
    }
    
    public function setUser($user)
    {
        $this->user = $user;
    }
    
    public function disableUserSearch()
    {
        $this->enabledUser = false;
    }
    
    public function enableUserSearch()
    {
        $this->enabledUser = true;
    }
}