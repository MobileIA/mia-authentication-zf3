# MobileIA Authentication

# Instalación

1. Agregar APP_ID y APP_SECRET en el archivo de configuración:

```php
'mobileia_lab' => [
    'app_id' => 5343,
    'app_secret' => '$2y$10aS$I5OAGUWxqrdsJ1LKFodssdbu1avhr5FNPRsal.aZWBossp933r9NFPzu'
]
```

2. Generar Factory en Module.php

```php
'factories' => [
    \MobileIA\Auth\MobileiaAuth::class => function($container){
        $config = $container->get('Config');
        return new \MobileIA\Auth\MobileiaAuth($config['mobileia_lab']['app_id'], $config['mobileia_lab']['app_secret']);
    },
]
```
