<?php

namespace MIAAuthentication\Controller;

abstract class AuthCrudController extends \MIABase\Controller\Api\CrudController
{
    /**
     * Instancia del usuario logueado
     * @var \MIAAuthentication\Entity\User
     */
    protected $user = null;
    
    /**
     * Inicializador
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        // Validar Access Token
        if(!$this->isValidAccessToken()){
            $e->setViewModel(new \Zend\View\Model\JsonModel(array(
                'success' => false,
                'response' => false
            )));
            $e->stopPropagation();
        }
        parent::onDispatch($e);
    }
    
    public function indexAction()
    {
        $action = new \MIAAuthentication\Action\Api\AuthListAction();
        $action->setTable($this->getTable());
        $action->setController($this);
        $action->setUser($this->getUser());
        return $action->execute();
    }
    /**
     * Obtiene el usuario logueado
     * @return \MIAAuthentication\Entity\User
     */
    protected function getUser()
    {
        if($this->user === null){
            $this->user = $this->getEvent()
                    ->getApplication()
                    ->getServiceManager()
                    ->get(\MIAAuthentication\Table\UserTable::class)
                    ->fetchByMIAId($this->getMobileiaAuth()->getCurrentUserID());
        }
        return $this->user;
    }
    
    protected function isValidAccessToken()
    {
        // Obtenemos el Access Token
        $access_token = $this->getParam('access_token', '');
        // Verificamos si es valido
        $service = $this->getMobileiaAuth();
        if($service->isValidAccessToken($access_token)){
            return true;
        }
        
        return false;
    }
    /**
     * 
     * @return \MobileIA\Auth\MobileiaAuth
     */
    public function getMobileiaAuth()
    {
        return $this->getEvent()->getApplication()->getServiceManager()->get(\MobileIA\Auth\MobileiaAuth::class);
    }
}