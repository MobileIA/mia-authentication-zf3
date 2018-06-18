<?php

namespace MIAAuthentication\Controller;

use Zend\View\Model\ViewModel;

/**
 * Description of ProfileController
 *
 * @author matiascamiletti
 */
class ProfileController extends \MIABase\Controller\BaseController
{
    public function indexAction()
    {
        // Procesar POST
        $this->verifyPost();
        // Configuramos el layout
        $this->layout($this->getLayoutVar('profile_layout', 'mia-layout-elite'));
        // Creamos vista
        $view = new ViewModel(array(
            
        ));
        // Asignamos el template de la vista
        $view->setTemplate($this->getLayoutVar('profile_template', 'mia-layout-elite/profile/index'));
        // Devolvemos vista
        return $view;
    }
    /**
     * Funcion que se encarga de validar el formulario de cambio de contraseña del usuario
     * @return ViewModel
     */
    public function changePasswordAction()
    {
        // Verificar si es una respuesta POST
        if (!$this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('profile');
        }
        // Obtener contraseña anterior
        $oldPassword = $this->params()->fromPost('old-password', '');
        // Verificamos si se ingreso la contraseña
        if($oldPassword == ''){
            $this->flashMessenger()->addErrorMessage('Debe ingresar su actual contraseña.');
            return $this->redirect()->toRoute('profile', array('change' => 1));
        }
        // Validar si es la contraseña correcta
        if($this->getMobileiaAuth()->authenticate($this->identity()->email, $oldPassword) === false){
            $this->flashMessenger()->addErrorMessage('Su contraseña actual no es correcta.');
            return $this->redirect()->toRoute('profile', array('change' => 1));
        }
        // Obtener nueva contraseña
        $newPassword = $this->params()->fromPost('new-password', '');
        $rePassword = $this->params()->fromPost('re-password', '');
        // Validar si es distinta.
        if($oldPassword == $newPassword){
            $this->flashMessenger()->addErrorMessage('Tu contraseña debe ser diferente a la anterior!');
            return $this->redirect()->toRoute('profile', array('change' => 1));
        }
        // Validar si se escribio correctamente la contraseña
        if($newPassword == '' || $newPassword != $rePassword){
            $this->flashMessenger()->addErrorMessage('Las contraseñas no coinciden');
            return $this->redirect()->toRoute('profile', array('change' => 1));
        }
        // Enviar a cambiar la contraseña
        $this->getMobileiaAuth()->changePasswordUser($this->identity()->mia_id, $newPassword);
        $this->flashMessenger()->addSuccessMessage('Se ha cambiado su contraseña');
        return $this->redirect()->toRoute('profile');
    }
    /**
     * Funcion que se encarga de verificar si se envio el formulario y guardar los datos
     */
    protected function verifyPost()
    {
        // Check if user has submitted the form
        if (!$this->getRequest()->isPost()) {
            return false;
        }
        // Obtener parametros enviados
        $firstname = $this->params()->fromPost('firstname', '');
        $lastname = $this->params()->fromPost('lastname', '');
        $phone = $this->params()->fromPost('phone', '');
        // Verificar si se enviaron los parametros requeridos
        if($firstname == ''||$lastname == ''){
            $this->flashMessenger()->addErrorMessage('El nombre y apellido no pueden estar vacio.');
            return $this->redirect()->toRoute('profile');
        }
        // Guardar nuevos datos
        $user = $this->identity();
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->phone = $phone;
        $this->getUserTable()->save($user);
        // Enviar mensaje
        $this->flashMessenger()->addSuccessMessage('Se han guardado sus datos correctamente.');
        return $this->redirect()->toRoute('profile');
    }
    
    /**
     * Devuelve Variable del Layout
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getLayoutVar($key, $default)
    {
        // Obtenemos helper
        $miaLayout = $this->getEvent()->getApplication()->getServiceManager()->get('ViewHelperManager')->get('miaLayout');
        // Obtenemos variable
        return $miaLayout()->get($key, $default);
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