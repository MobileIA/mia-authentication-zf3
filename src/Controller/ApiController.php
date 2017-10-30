<?php

namespace MIAAuthentication\Controller;

/**
 * Description of ApiController
 *
 * @author matiascamiletti
 */
class ApiController extends \MIABase\Controller\Api\BaseApiController
{
    public function mobileiaAction()
    {
        // TODO: agregar validación para que no pueda cualquiera agregar usuarios
        // Verificamos que se haya enviado los datos del usuario
        if($this->getParam('id', 0) <= 0){
            return $this->executeError(\MIABase\Controller\Api\Error::REQUIRED_PARAMS);
        }
        // Buscar por el MIA_ID
        $user = $this->getUserTable()->fetchByMIAId($this->getParam('id', 0));
        // Verificamos si ya existe este MIA_ID
        if($user === null){
            // Creamos la entidad del usuario
            $user = new \MIAAuthentication\Entity\User();
        }
        // Actualizamos los parametros
        $this->updateParams($user);
        // Guardamos el nuevo usuario
        $this->getUserTable()->save($user);
        // Llamamos a la funcion para generar configuraciones extras
        $this->modelSaved($user);
        // Devolvemos datos del usuario
        return $this->executeSuccess($user->toArray());
    }
    /**
     * Metodo que se ejecuta despues de crear/modificar el usuario
     * @param \MIAAuthentication\Entity\User $user
     */
    protected function modelSaved($user){ }
    
    protected function updateParams($user)
    {
        // Actualizamos parametros
        $user->mia_id = $this->getParam('id', 0);
        $user->firstname = $this->getParam('firstname', '');
        $user->lastname = $this->getParam('lastname', '');
        $user->email = $this->getParam('email', '');
        $user->photo = $this->getParam('photo', null);
        if($user->facebook_id == null){
            $user->facebook_id = '';
        }
        $user->role = $this->getParam('role', \MIAAuthentication\Entity\User::ROLE_MEMBER);
    }
    
    /**
     * 
     * @return \MIAAuthentication\Table\UserTable
     */
    protected function getUserTable()
    {
        return $this->getEvent()->getApplication()->getServiceManager()->get(\MIAAuthentication\Table\UserTable::class);
    }
}