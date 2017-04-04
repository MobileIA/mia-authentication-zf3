<?php

namespace MIAAuthentication\Factory;

/**
 * Description of AuthenticationServiceFactory
 *
 * @author matiascamiletti
 */
class AuthenticationServiceFactory implements \Zend\ServiceManager\Factory\FactoryInterface
{
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $sessionManager = $container->get(\Zend\Session\SessionManager::class);
        $authStorage = new \Zend\Authentication\Storage\Session('Zend_Auth', 'session', $sessionManager);
        $table = $container->get(\MIAAuthentication\Table\UserTable::class);
        $authAdapter = new \MIAAuthentication\Adapter\AuthenticationAdapter($table);
        
        // Create the service and inject dependencies into its constructor.
        return new \Zend\Authentication\AuthenticationService($authStorage, $authAdapter);
    }
}
