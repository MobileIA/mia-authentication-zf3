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
    /**
     * Habilita que busque en la DB a traves del UserId
     * @var boolean
     */
    protected $enabledUser = true;
    
    protected function createSelect()
    {
        // Creamos Select
        $select = $this->table->select();
        // Buscamos los registros del usuario
        if($this->enabledUser){
            $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('user_id = ?', $this->user->id));
        }
        // Validar los joins si existen
        foreach($this->joins as $join){
            $select->join($join['name'], $join['on'], $join['columns']);
        }
        // Agregar wheres personalizados
        foreach($this->wheres as $predicate){
            $select->where->addPredicate($predicate);
        }
        // Configurar el orden
        if($this->order !== null){
            $select->order($this->order);
        }
        // Configurar limite
        if($this->limit > 0){
            $select->limit($this->limit);
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