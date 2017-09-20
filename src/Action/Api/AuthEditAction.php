<?php

namespace MIAAuthentication\Action\Api;

/**
 * Description of AuthEditAction
 *
 * @author matiascamiletti
 */
class AuthEditAction extends AuthAddAction
{
    /**
     *
     * @var \MIABase\Entity
     */
    protected $old = null;
    
    public function isValid()
    {
        if($this->getModel() === null){
            return false;
        }
        return true;
    }
    
    protected function save()
    {
        // Copiamos datos antes de editar
        $this->old->exchange($this->getModel()->toArray());
        // Cargamos nuevos datos
        $this->getModel()->exchangeObject($this->controller->getAllParams());
        // Guardamos en la DB
        $this->table->save($this->getModel());
        // Verificamos si existe una funcionalidad extra al ser editado
        if(method_exists($this->controller, 'modelEdited')){
            $this->controller->modelEdited($this->old, $this->getModel());
        }
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
    
    public function getModel()
    {
        if($this->model == null){
            $entityId = $this->controller->getParam('id', 0);
            $this->model = $this->table->fetchById($entityId);
            // Crear objeto para almacenar los datos viejos
            $className = $this->table->getEntityClass();
            $this->old = new $className;
        }
        return $this->model;
    }
}