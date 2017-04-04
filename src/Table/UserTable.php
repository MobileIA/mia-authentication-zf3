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
}