<?php

/**
 * UserController Class
 *
 * @version 1.0.1
 */
class UserController extends \Core\Controller
{
    public function getAccess()
    {
        return [];
    }
    
    public function loginAction()
    {
        $params = [];
        
        try {
            
            if ($this->request()->isPost()) {
                $params['name'] = filter_var($this->request()->getParam('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $password = filter_var($this->request()->getParam('password'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $errorMessages = [];

                if (!$params['name']) {
                    throw new \Exception('Please enter name');
                }
                if (!$password) {
                    throw new \Exception('Please enter password');
                }

                if (!$errorMessages) {
                    $user = \User::getInstance()->fetchEntryByName($params['name']);

                    if (!$user) {
                        throw new \Exception('Invalid credentials');
                    }
                    
                    if (!Core\Auth::checkPassword($password, $user['password'])) {
                        throw new \Exception('Invalid credentials');
                    }

                    Core\Auth::login($user);

                    $this->redirect('/');
                }
            }
            
        } catch (\Exception $e) {
            
            $params['errorMessage'] = $e->getMessage();
            
        }
        
        return $this->render('user/login', $params);
    }
    
    public function logoutAction()
    {
        Core\Auth::logout();
        session_regenerate_id();
        $this->redirect('/');
    }
    
}