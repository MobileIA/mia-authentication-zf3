<?php

namespace MIAAuthentication;

class Module implements \Zend\ModuleManager\Feature\ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    /**
     * This method is called once the MVC bootstrapping is complete and allows
     * to register event listeners. 
     */
    public function onBootstrap(\Zend\Mvc\MvcEvent $event)
    {
        // Validar sessión para cuando se corrompe
        $this->validateSession($event->getApplication()->getServiceManager()->get(\Zend\Session\SessionManager::class));
        // Get event manager.
        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method. 
        $sharedEventManager->attach(\Zend\Mvc\Controller\AbstractActionController::class, \Zend\Mvc\MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
    }
    
    /**
     * Event listener method for the 'Dispatch' event. We listen to the Dispatch
     * event to call the access filter. The access filter allows to determine if
     * the current visitor is allowed to see the page or not. If he/she
     * is not authorized and is not allowed to see the page, we redirect the user 
     * to the login page.
     */
    public function onDispatch(\Zend\Mvc\MvcEvent $event)
    {
        // Get controller and action to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);
        // Convert dash-style action name to camel-case.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));
        
        /* @var $aclManager \MIAAuthentication\Adapter\AclAdapter */
        $aclManager = $event->getApplication()->getServiceManager()->get(Adapter\AclAdapter::class);
        
        try {
            /* @var $authService \Zend\Authentication\AuthenticationService */
            $authService = $event->getApplication()->getServiceManager()->get(\Zend\Authentication\AuthenticationService::class);
        } catch (\Zend\ServiceManager\Exception\ServiceNotCreatedException $exc) {
            // Eliminar session
            session_destroy();
            // Redirigir a la home
            return $controller->redirect()->toUrl('/#!/?cook=refresh');
        }
        
        if($authService->getIdentity() === null){
            $role = 'guest';
        }else{
            $role = $aclManager->getRoleByID($authService->getIdentity()->role);
        }
        
        if(!$aclManager->getAcl()->isAllowed($role, $controllerName . ':' . $actionName)){
            if($role != 'guest'){
                return $controller->redirect()->toRoute('privileges', [],['query' => ['redirectUrl' => $this->getRedirectUrl($event)]]);
            }else{
                return $controller->redirect()->toRoute('authentication', [],['query' => ['redirectUrl' => $this->getRedirectUrl($event)]]);
            }
        }
    }
    /**
     * Validamos si la sesión se rompio
     * @param \Zend\Session\SessionManager $sessionManager
     */
    protected function validateSession($sessionManager)
    {
        try {
            $sessionManager->start();
            return;
        } catch (\Exception $e) {}
        /**
         * Session validation failed: toast it and carry on.
         */
        session_unset();
    }
    
    public function getRedirectUrl($event)
    {
        // Remember the URL of the page the user tried to access. We will
        // redirect the user to that URL after successful login.
        $uri = $event->getApplication()->getRequest()->getUri();
        // Make the URL relative (remove scheme, user info, host name and port)
        // to avoid redirecting to other domain by a malicious user.
        $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);
        return $uri->toString();
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
}