<?php

namespace MIAAuthentication\Action\Api;

/**
 * Description of AuthAddAction
 *
 * @author matiascamiletti
 */
class AuthAddAction extends \MIABase\Action\Api\Base
{
    /**
     * Instancia del usuario logueado
     * @var \MIAAuthentication\Entity\User
     */
    protected $user = null;

    protected function getParams()
    {
        $params = $this->controller->getAllParams();
        $params->user_id = $this->user->id;
        return $params;
    }
    
    protected function save()
    {
        $this->getModel()->exchangeObject($this->getParams());
        $this->table->save($this->getModel());
        if(method_exists($this->controller, 'modelSaved')){
            $this->controller->modelSaved($this->getModel());
        }
    }    
    
    public function execute()
    {
        // Guardar modelo
        $this->save();
        // Generar array para la respuesta
        if(method_exists($this->controller, 'configAddResponse')){
            return $this->controller->configAddResponse($this->getModel()->toArray());
        }
        // Respuesta por defecto
        return new \Zend\View\Model\JsonModel(array(
            'success' => true, 
            'response' => $this->getModel()->toArray()
        ));
    }
    
    protected function getModel()
    {
        if($this->model == null){
            $className = $this->table->getEntityClass();
            $this->model = new $className;
        }
        return $this->model;
    }
    
    public function setUser($user)
    {
        $this->user = $user;
    }
}