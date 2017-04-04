<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MIAAuthentication\Factory;

/**
 * Description of AclAdapterFactory
 *
 * @author matiascamiletti
 */
class AclAdapterFactory implements \Zend\ServiceManager\Factory\FactoryInterface
{
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        // Get contents of 'access_filter' config key (the AuthManager service
        // will use this data to determine whether to allow currently logged in user
        // to execute the controller action or not.
        $config = $container->get('Config');
        if(!array_key_exists('authentication_acl', $config)){
            $config['authentication_acl'] = [];
        }
        
        return new \MIAAuthentication\Adapter\AclAdapter($config['authentication_acl']);
    }
}