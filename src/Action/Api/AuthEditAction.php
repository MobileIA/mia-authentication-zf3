<?php

namespace MIAAuthentication\Action\Api;

/**
 * Description of AuthEditAction
 *
 * @author matiascamiletti
 */
class AuthEditAction extends AuthAddAction
{
    
    public function isValid()
    {
        if($this->getModel() === null){
            return false;
        }
        return true;
    }
    
    protected function save()
    {
        $this->getModel()->exchangeObject($this->controller->getAllParams());
        $this->table->save($this->getModel());
    } 
    
    public function execute()
    {
        // Verificar Si el registro existe
        if(!$this->isValid()){
            return $this->executeError(false);
        }
        // Verificar si este registro se puede editar llamando a las personalizaciones
        if(!$this->controller->allowModelEdit($this->getModel())){
            return $this->executeError(false);
        }
        // Guardar modelo
        $this->save();
        // Generar array para la respuesta
        if(method_exists($this->controller, 'configEditResponse')){
            return $this->controller->configEditResponse($this->getModel()->toArray());
        }
        // Respuesta por defecto
        return $this->executeSuccess($this->getModel()->toArray());
    }
    
    protected function getModel()
    {
        if($this->model == null){
            $entityId = $this->controller->getParam('id', 0);
            $this->model = $this->table->fetchById($entityId);
        }
        return $this->model;
    }
}