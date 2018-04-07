<?php

namespace MIAAuthentication\Controller;

/**
 * Description of ApiController
 *
 * @author matiascamiletti
 */
class ApiController extends \MIABase\Controller\Api\BaseApiController
{
    /**
     * Servicio para realizar login
     * @return \Zend\View\Model\JsonModel
     */
    public function registerAction()
    {
        // Obtenemos parametros
        $data = array(
            'email' => $this->getParam('email', ''),
            'password' => $this->getParam('password', ''), 
            'firstname' => $this->getParam('firstname', ''),
            'lastname' => $this->getParam('lastname', ''));
        // Creamos formulario
        $form = new \MIAAuthentication\Form\Register();
        // Asignamos ServiceManager
        $form->setServiceManager($this->getEvent()->getApplication()->getServiceManager());
        // Activar validadores
        $form->addInputFilter();
        // Cargamos los parametros al formulario
        $form->setData($data);
        // Validamos el formulario
        if(!$form->isValid()) {
            return $this->executeError(\MIABase\Controller\Api\Error::REQUIRED_PARAMS);
        }
        // Creamos la entidad del usuario
        $user = new \MIAAuthentication\Entity\User();
        // Actualizamos los parametros
        $this->updateParams($user);
        // Generamos el Usuario en MobileiaAuth
        $mobileiaAuth = $this->getMobileiaAuth()->registerUser($user->email, $data['password'], $user->toArray());
        // Verificamos si se creo correctamente
        if($mobileiaAuth->success){
            // Asignar MIA_IDs
            $user->mia_id = $mobileiaAuth->response->id;
        }
        // Guardamos el nuevo usuario
        $this->getUserTable()->save($user);
        // Llamamos a la funcion para generar configuraciones extras
        $this->modelSaved($user);
        // Ejecutamos el login
        $result = $this->authenticate($user->email, $data['password']);
        // Verificar si los datos son incorrectos
        if ($result->getCode() != \Zend\Authentication\Result::SUCCESS) {
            return $this->executeError(\MIABase\Controller\Api\Error::INVALID_PASSWORD);
        }
        // Verificar si se quiere guardar la sesión
        $this->getSessionManager()->rememberMe(60*60*24*15);
        
        return $this->executeSuccess(true);
    }
    /**
     * Servicio para realizar login
     * @return JsonModel
     */
    public function signinAction()
    {
        // Obtenemos parametros
        $data = array('email' => $this->getParam('email', ''), 'password' => $this->getParam('password', ''), 'remember_me' => 1);
        // Creamos formulario
        $form = new \MIAAuthentication\Form\Login();
        // Cargamos los parametros al formulario
        $form->setData($data);
        // Validamos el formulario
        if(!$form->isValid()) {
            return $this->executeError(\MIABase\Controller\Api\Error::REQUIRED_PARAMS);
        }
        // Obtenemos los datos procesados del formulario
        $params = $form->getData();
        // Ejecutamos el login
        $result = $this->authenticate($params['email'],$params['password']);
        // Verificar si los datos son incorrectos
        if ($result->getCode() != \Zend\Authentication\Result::SUCCESS) {
            return $this->executeError(\MIABase\Controller\Api\Error::INVALID_PASSWORD);
        }
        // Verificar si se quiere guardar la sesión
        if ($params['remember_me'] == 1) {
            // Session cookie will expire in 1 month (15 days).
            $this->getSessionManager()->rememberMe(60*60*24*15);
        }
        
        return $this->executeSuccess(true);
    }
    
    /**
     * Servicio que verifica si un email ya esta registrado
     * @return Json
     */
    public function existAction()
    {
        // Obtenemos email a verificar
        $email = $this->getParam('email', '');
        // Verificamos si se envio el parametro
        if($email == ''){
            return $this->executeError(\MIABase\Controller\Api\Error::REQUIRED_PARAMS);
        }
        // Buscar en la DB este email
        $user = $this->getUserTable()->fetchByEmail($email);
        // Verificamos si existe el usuario
        if($user === null){
            return $this->executeSuccess(false);
        }
        return $this->executeSuccess(true);
    }
    
    public function mobileiaAction()
    {
        // TODO: agregar validación para que no pueda cualquiera agregar usuarios
        // Verificamos que se haya enviado los datos del usuario
        if($this->getParam('id', 0) <= 0){
            return $this->executeError(\MIABase\Controller\Api\Error::REQUIRED_PARAMS);
        }
        // Buscar por el MIA_ID
        $user = $this->getUserTable()->fetchByMIAId($this->getParam('id', 0));
        // Verificamos si ya existe este MIA_ID
        if($user === null){
            // Creamos la entidad del usuario
            $user = new \MIAAuthentication\Entity\User();
        }
        // Actualizamos los parametros
        $this->updateParams($user);
        // Guardamos el nuevo usuario
        $this->getUserTable()->save($user);
        // Llamamos a la funcion para generar configuraciones extras
        $this->modelSaved($user);
        // Devolvemos datos del usuario
        return $this->executeSuccess($user->toArray());
    }
    /**
     * Metodo que se ejecuta despues de crear/modificar el usuario
     * @param \MIAAuthentication\Entity\User $user
     */
    protected function modelSaved($user){ }
    
    protected function updateParams($user)
    {
        // Actualizamos parametros
        $user->mia_id = $this->getParam('id', 0);
        $user->firstname = $this->getParam('firstname', '');
        $user->lastname = $this->getParam('lastname', '');
        $user->email = $this->getParam('email', '');
        $user->photo = $this->getParam('photo', '');
        $user->phone = $this->getParam('phone', '');
        $user->facebook_id = $this->getParam('facebook_id', '');
        if($user->facebook_id == null){
            $user->facebook_id = '';
        }
        $user->role = $this->getParam('role', \MIAAuthentication\Entity\User::ROLE_MEMBER);
    }
    
    /**
     * 
     * @param string $email
     * @param string $password
     * @return Zend\Authentication\Result
     */
    protected function authenticate($email, $password)
    {
        $service = $this->getAuthenticationService();
        /* @var $adapter \MIAAuthentication\Adapter\AuthenticationAdapter */
        $adapter = $service->getAdapter();
        $adapter->setEmail($email);
        $adapter->setPassword($password);
        // Autenticar
        return $service->authenticate();
    }
    /**
     * 
     * @return \Zend\Authentication\AuthenticationService
     */
    protected function getAuthenticationService()
    {
        return $this->getEvent()->getApplication()->getServiceManager()->get(\Zend\Authentication\AuthenticationService::class);
    }
    /**
     * 
     * @return \Zend\Session\SessionManager
     */
    protected function getSessionManager()
    {
        return $this->getEvent()->getApplication()->getServiceManager()->get(\Zend\Session\SessionManager::class);
    }
    /**
     * 
     * @return \MIAAuthentication\Table\UserTable
     */
    protected function getUserTable()
    {
        return $this->getEvent()->getApplication()->getServiceManager()->get(\MIAAuthentication\Table\UserTable::class);
    }
    /**
     * 
     * @return \MobileIA\Auth\MobileiaAuth
     */
    protected function getMobileiaAuth()
    {
        return $this->getServiceManager()->get(\MobileIA\Auth\MobileiaAuth::class);
    }
}