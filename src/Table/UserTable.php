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
     * Obtiene un usuario a traves de su Facebook ID
     * @param string $facebookId
     * @return \MIAAuthentication\Entity\User|null
     */
    public function fetchByFacebook($facebookId)
    {
        return $this->tableGateway->select(array('facebook_id' => $facebookId))->current();
    }
    /**
     * Devuelve el usuario a traves de su telefono
     * @param string $phone
     * @return \MIAAuthentication\Entity\User
     */
    public function fetchByPhone($phone)
    {
        // Verificar si el telefono es valido
        if($phone == ''){
            return null;
        }
        // Devolver usuario
        return $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use($phone) {
            $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('phone LIKE ?', '%' . substr($phone, -8)));
            $select->where->addPredicate(new \Zend\Db\Sql\Predicate\Expression('deleted = 0'));
        })->current();
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