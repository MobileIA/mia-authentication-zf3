<?php

namespace MIAAuthentication\Adapter;

use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

/**
 * Description of AclAdapter
 *
 * @author matiascamiletti
 */
class AclAdapter 
{
    /**
     *
     * @var \Zend\Permissions\Acl\Acl
     */
    protected $acl;
    /**
     * Contents of the 'access_filter' config key.
     * @var array
     */
    protected $config;
    
    public function __construct($config)
    {
        $this->config = $config;
        $this->acl = new \Zend\Permissions\Acl\Acl();
        // Procesar permisos
        $this->process();
    }
    /**
     * Obtiene el string del rol
     * @param int $roleId
     * @return string
     */
    public function getRoleByID($roleId)
    {
        $ids = $this->config['roles_id'];
        for($i = 0; $i < count($ids); $i++){
            if($ids[$i] == $roleId){
                $rol = $this->config['roles'][$i];
                $n = explode(':', $rol);
                if(count($n) > 1){
                    return $n[0];
                }else{
                    return $rol;
                }
            }
        }
        return '';
    }
    
    protected function process()
    {
        if(!array_key_exists('roles', $this->config)){
            return false;
        }
        $this->roles($this->config['roles']);
        $this->resources($this->config['resources']);
    }
    
    protected function roles($data)
    {
        foreach($data as $role){
            $n = explode(':', $role);
            if(count($n) > 1){
                $this->addRole($n[0], $n[1]);
            }else{
                $this->addRole($role);
            }
        }
    }
    
    protected function addRole($name, $parent = null)
    {
        $role = new Role($name);
        try {
            $this->acl->addRole($role, $parent);
        } catch (\Zend\Permissions\Acl\Exception\InvalidArgumentException $exc) {
            // Si ya existe el rol lo eliminamos
            $this->acl->removeRole($role);
            // Lo Agregamos de nuevo
            $this->acl->addRole($role, $parent);
        }
    }
    
    protected function resources($data)
    {
        foreach($data as $controller => $options){
            $this->addController($controller, $options['actions']);
        }
    }
    
    protected function addController($controller, $actions)
    {
        foreach($actions as $action => $options){
            $resource = $this->addResource($controller, $action);
            $this->allows($options['allow'], $resource);
            if(array_key_exists('deny', $options)){
                $this->denies($options['deny'], $resource);
            }
        }
    }
    
    protected function addResource($controller, $action)
    {
        $resource = new Resource($controller . ':' . $action);
        $this->acl->addResource($resource);
        return $resource;
    }
    
    protected function allows($role, $resource)
    {
        $roles = explode(',', $role);
        foreach($roles as $r){
            $this->acl->allow($r, $resource);
        }
    }
    
    protected function denies($role, $resource)
    {
        $roles = explode(',', $role);
        foreach($roles as $r){
            $this->acl->deny($r, $resource);
        }
    }
    /**
     * 
     * @return \Zend\Permissions\Acl\Acl
     */
    public function getAcl()
    {
        return $this->acl;
    }
}