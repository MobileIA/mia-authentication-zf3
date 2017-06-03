<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MIAAuthentication\Action\Api;

/**
 * Description of AuthEditBulkAction
 *
 * @author matiascamiletti
 */
class AuthEditBulkAction extends AuthEditAction
{
    /**
     * Variable que almacena los items a editar
     * @var array
     */
    protected $items = null;

    protected function isValidParams()
    {
        // Obtener IDs desdes la consulta
        $ids = $this->controller->getParam('ids', array());
        // Validar si es un array
        if(!is_array($ids)){
            return false;
        }
        return true;
    }
    
    protected function getItems()
    {
        if($this->items == null){
            $ids = $this->controller->getParam('ids', array());
            $this->items = $this->table->fetchAllByIds($ids);
        }
        return $this->items;
    }
    
    public function execute()
    {
        // Verificar Si son validos los parametros
        if(!$this->isValidParams()){
            return $this->executeError(false);
        }
        // Verificamos si se encontro aunque sea un registro
        if($this->getItems()->count() == 0){
            return $this->executeError(false);
        }
        // Recorremos todos los registros encontrados
        $this->items->buffer();
        foreach($this->items as $item){
            // Verificar si este registro se puede editar llamando a las personalizaciones
            if(!$this->controller->allowModelEdit($item)){
                continue;
            }
            // Agregamos los nuevos parametros al item
            $item->exchangeObject($this->controller->getAllParams());
            // Guardar registro
            $this->table->save($item);
        }
        // Volver a iniciar el array
        $this->getItems()->rewind();
        // Respuesta por defecto
        return $this->executeSuccess($this->getItems()->toArray());
    }
}