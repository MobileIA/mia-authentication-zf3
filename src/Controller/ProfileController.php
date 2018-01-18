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
            return;
        }
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
}