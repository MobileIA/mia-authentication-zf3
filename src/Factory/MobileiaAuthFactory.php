<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MIAAuthentication\Factory;

/**
 * Description of MobileiaAuthFactory
 *
 * @author matiascamiletti
 */
class MobileiaAuthFactory implements \Zend\ServiceManager\Factory\FactoryInterface
{
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        // Obtenemos configuración
        $config = $container->get('Config');
        // Verificamos que exista la key
        if(!array_key_exists('mobileia_lab', $config)){
            // Iniciamos un array, ya que no se encontro una configuración
            $appId = 0;
            $appSecret = '';
        }else{
            $appId = $config['mobileia_lab']['app_id'];
            $appSecret = $config['mobileia_lab']['app_secret'];
        }
        // Creamos el objeto
        return new \MobileIA\Auth\MobileiaAuth($appId, $appSecret);
    }
}
