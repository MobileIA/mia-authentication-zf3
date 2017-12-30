<?php

namespace MIAAuthentication\Table;
/**
 * Description of PostTable
 *
 * @author matiascamiletti
 */
class UserTable extends \MIABase\Table\Base
{
    protected $tableName = 'mia_user';
    
    protected $entityClass = \MIAAuthentication\Entity\User::class;
    
    public function fetchByMIAId($id)
    {
        $rowset = $this->tableGateway->select(array('mia_id' => (int) $id));
        return $rowset->current();
    }
    /**
     * Obtiene un usuario a traves de su email
     * @param string $email
     * @return \MIAAuthentication\Entity\User|null
     */
    public function fetchByEmail($email)
    {
        return $this->tableGateway->select(array('email' => $email))->current();
    }
    /**
     * Obtiene todos los usuarios indicados por su IDs
     * @param array $ids
     * @return array
     */
    public function fetchAllByIds($ids)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where->in('id', $ids);
        return $this->tableGateway->selectWith($select);
    }
}