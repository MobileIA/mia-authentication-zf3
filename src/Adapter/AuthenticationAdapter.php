<?php

namespace MIAAuthentication\Adapter;

use Zend\Authentication\Result;

/**
 * Description of AuthenticationAdapter
 *
 * @author matiascamiletti
 */
class AuthenticationAdapter implements \Zend\Authentication\Adapter\AdapterInterface
{
    /**
     *
     * @var int
     */
    protected $appId;
    /**
     * User email.
     * @var string 
     */
    protected $email;
    /**
     * Password
     * @var string 
     */
    protected $password;
    /**
     *
     * @var \MIAAuthentication\Table\UserTable
     */
    protected $table;
    
    public function __construct($table)
    {
        $this->table = $table;
    }
    
    public function authenticate()
    {
        // Buscar si existe el usuario
        // If there is no such user, return 'Identity Not Found' status.
        /*if ($user == null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND, 
                null, 
                ['Invalid credentials.']); }  */    
        
        // If the user with such email exists, we need to check if it is active or retired.
        // Do not allow retired users to log in.
        /*if ($user->getStatus()==User::STATUS_RETIRED) {
            return new Result(
                Result::FAILURE, 
                null, 
                ['User is retired.']);        
        }*/
        
        $service = new \MobileIA\Auth\MobileiaAuth('2', '$2y$10$yfxndt.xX5OatbEC38JTOeMBUEA114poy4kXYJ5ALuYlN2kCHaDTy');
        $response = $service->authenticate($this->email, $this->password);
        if($response === false){
            // If password check didn't pass return 'Invalid Credential' failure status.
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, ['Invalid credentials.']); 
        }
        
        // Buscar información del usuario interna
        $user = $this->table->fetchByMIAId($response->user_id);
        
        // Great! The password hash matches. Return user identity (email) to be
        // saved in session for later use.
        return new Result(Result::SUCCESS, $user, ['Authenticated successfully.']); 
    }
    /**
     * Set app Id
     * @param int $appId
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
    }
    
    /**
     * Sets user email.     
     */
    public function setEmail($email) 
    {
        $this->email = $email;        
    }
    
    /**
     * Sets password.     
     */
    public function setPassword($password) 
    {
        $this->password = (string)$password;        
    }
}