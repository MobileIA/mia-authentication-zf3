<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace MIAAuthentication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LoginController extends AbstractActionController
{
    /**
     *
     * @var \MIAAuthentication\Form\Login
     */
    protected $form;
    /**
     *
     * @var ViewModel 
     */
    protected $view;
    
    public function indexAction()
    {
        // Verificar si esta logueado
        if ($this->identity() != null) {
            return $this->redirect()->toRoute('home');
        }
        // Procesar POST
        $this->verifyPost();
        // Procesar vista
        return $this->getView();
    }
    
    protected function verifyPost()
    {
        // Check if user has submitted the form
        if (!$this->getRequest()->isPost()) {
            return false;
        }
        
        // Fill in the form with POST data
        $data = $this->params()->fromPost();            
        $this->getForm()->setData($data);
        
        // Validate form
        if(!$this->getForm()->isValid()) {
            $isLoginError = true;
            return false;
        }
            
        // Get filtered and validated data
        $data = $this->getForm()->getData();
            
        // Perform login attempt.
        $result = $this->authenticate($data['email'],$data['password']);
        
        // Check result.
        if ($result->getCode() != \Zend\Authentication\Result::SUCCESS) {
            $isLoginError = true;
            return false;
        }
        
        // If user wants to "remember him", we will make session to expire in 
        // one month. By default session expires in 1 hour (as specified in our 
        // config/global.php file).
        //if ($result->getCode()==Result::SUCCESS && $rememberMe) {
            // Session cookie will expire in 1 month (30 days).
            //$this->sessionManager->rememberMe(60*60*24*30);
        //}
        
        $redirectUrl = $this->getRedirectUrl();
        // otherwise redirect to Home page.
        if($redirectUrl == '') {
            return $this->redirect()->toRoute('home');
        } else {
            $this->redirect()->toUrl($redirectUrl);
        }
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
     * The "logout" action performs logout operation.
     */
    public function logoutAction() 
    {        
        // Allow to log out only when user is logged in.
        if ($this->identity() == null) {
            throw new \Exception('The user is not logged in');
        }
        // Remove identity from session.
        $this->getAuthenticationService()->clearIdentity();

        return $this->redirect()->toRoute('authentication');
    }
    /**
     * 
     * @return string
     * @throws \Exception
     */
    protected function getRedirectUrl()
    {
        // Retrieve the redirect URL (if passed). We will redirect the user to this
        // URL after successfull login.
        $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl)>2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }
        if (!empty($redirectUrl)) {
            // The below check is to prevent possible redirect attack 
            // (if someone tries to redirect user to another domain).
            $uri = new \Zend\Uri\Uri($redirectUrl);
            if (!$uri->isValid() || $uri->getHost() != null)
                throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
        }
        
        return $redirectUrl;
    }
    /**
     * 
     * @return \MIAAuthentication\Form\Login
     */
    public function getForm()
    {
        if($this->form === null){
            $this->form = new \MIAAuthentication\Form\Login();
        }
        return $this->form;
    }
    /**
     * 
     * @return ViewModel
     */
    public function getView()
    {
        if($this->view === null){
            $this->view = new ViewModel(array(
                'form' => $this->getForm()
            ));
            $this->view->setTerminal(true);
            $this->view->setTemplate('mia-layout-lte/login/basic');
        }
        return $this->view;
    }
}
