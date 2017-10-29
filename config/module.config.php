<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace MIAAuthentication;

use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return array(
    'router' => [
        'routes' => [
            'authentication' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'logout' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'mobileia-register' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/mobileia/register',
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'mobileia',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\LoginController::class => InvokableFactory::class,
            Controller\ApiController::class => InvokableFactory::class
        ],
    ],
    'service_manager' => [
        'factories' => [
            Table\UserTable::class => \MIABase\Factory\TableFactory::class,
            \MobileIA\Auth\MobileiaAuth::class => Factory\MobileiaAuthFactory::class,
            \Zend\Authentication\AuthenticationService::class => Factory\AuthenticationServiceFactory::class,
            Adapter\AclAdapter::class => Factory\AclAdapterFactory::class,
        ],
    ],
    // Session configuration.
    'session_config' => [
        // Session cookie will expire in 1 hour.
        'cookie_lifetime' => 60*60*5, 
        // Session data will be stored on server maximum for 30 days.
        'gc_maxlifetime'     => 60*60*24*30, 
    ],
    // Session manager configuration.
    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    // The 'authentication_acl' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'authentication_acl' => [
        'options' => [
            // The access filter can work in 'restrictive' (recommended) or 'permissive'
            // mode. In restrictive mode all controller actions must be explicitly listed 
            // under the 'access_filter' config key, and access is denied to any not listed 
            // action for not logged in users. In permissive mode, if an action is not listed 
            // under the 'access_filter' key, access to it is permitted to anyone (even for 
            // not logged in users. Restrictive mode is more secure and recommended to use.
            'mode' => 'restrictive'
        ],
        'roles' => ['guest', 'member:guest', 'admin:member'],
        'roles_id' => [-1, 0, 1],
        'resources' => [
            Controller\LoginController::class => [
                'actions' => [
                    'index' => ['allow' => 'guest', 'deny' => 'member,admin'],
                    'logout' => ['allow' => 'member,admin', 'deny' => 'guest']
                ]
            ],
            Controller\ApiController::class => [
                'actions' => [
                    'mobileia' => ['allow' => 'guest', 'deny' => 'member,admin']
                ]
            ]
        ],
    ],
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
