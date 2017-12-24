# MobileIA Authentication

# Instalación

1. Vamos a agregar la libreria, editamos el archivo "composer.json", donde agregamos la ubicación de la misma:
```json
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/MobileIA/mia-authentication-zf3.git"
    },
    {
            "type": "git",
            "url": "https://github.com/MobileIA/mia-layout-zf3.git"
        },
    {
        "type": "git",
        "url": "https://github.com/MobileIA/mia-layout-lte-zf3.git"
    },
    {
        "type": "git",
        "url": "https://github.com/MobileIA/mia-layout-elite-zf3.git"
    },
    {
        "type": "git",
        "url": "https://github.com/MobileIA/authentication"
    }
],
```
2. Agregamos la librería requerida:
```json
"require": {
    // ... others libraries ...
    "mobileia/mia-layout-zf3": "^0.0",
    "mobileia/mia-layout-lte-zf3": "^0.0",
    "mobileia/mia-layout-elite-zf3": "^0.0",
    "mobileia/auth": "^0.0",
    "mobileia/mia-authentication-zf3": "^0.0"
},
```
3. Actualizamos composer para que descargue la libreria recientemente agregada:
```bash
$ composer update
```
4. Registrar nueva App en [MobileIA Lab](http://lab.mobileia.com).
5. Agregar APP_ID y APP_SECRET en el archivo de configuración:
```php
'mobileia_lab' => [
    'app_id' => 5343,
    'app_secret' => '$2y$10aS$I5OAGUWxqrdsJ1LGs2dsKFodssdbu1avhr5FNPRsal.aZWBossp933r9NFPzu'
]
```
6. Activar modulo en zf3, abrir archivo: config/modules.config.php
```php
return [
    // Others modules
    'MIABase',
    'MIALayout',
    'MIALayoutLTE',
    'MIALayoutElite',
    'MIAAuthentication',
    'Application',
];
```
7. Una vez activado el modulo, debemos asignar los permisos a los controladores, editar el archivo de configuración, por ejemplo: module/Application/config/module.config.php
```php
'authentication_acl' => [
    'resources' => [
        Controller\IndexController::class => [
            'actions' => [
                'index' => ['allow' => 'guest']
            ]
        ],
    ],
],
```

# Como cambiar el Template de la pantalla de Login:
1. Editamos un archivo de configuración del modulo:
```php
'mia_layout' => [
    'login_template' => 'mia-layout-elite/login/right'
]
```
