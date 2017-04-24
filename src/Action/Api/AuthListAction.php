<?php

namespace MIAAuthentication\Action\Api;

/**
 * Description of AuthListAction
 *
 * @author matiascamiletti
 */
class AuthListAction extends \MIABase\Action\Api\ListAction
{
    /**
     * Instancia del usuario logueado
     * @var \MIAAuthentication\Entity\User
     */
    protected $user = null;
    
    protected function createSelect()
    {
        // Creamos Select
        $select = $this->table->select();
        // Buscamos los registros del usuario
        $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('user_id = ?', $this->user->id));
        // Validar los joins si existen
        foreach($this->joins as $join){
            $select->join($join['name'], $join['on'], $join['columns']);
        }
        // Agregar wheres personalizados
        foreach($this->wheres as $predicate){
            $select->where->addPredicate($predicate);
        }
        
        return $select;
    }
    
    public function setUser($user)
    {
        $this->user = $user;
    }
}