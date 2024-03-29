<?php

namespace tdt4237\webapp;
const SESSION_TIMEOUT = 900;

use Exception;
use tdt4237\webapp\Hash;
use tdt4237\webapp\repository\UserRepository;

class Auth
{

    /**
     * @var Hash
     */
    private $hash;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository, Hash $hash)
    {
        $this->userRepository = $userRepository;
        $this->hash           = $hash;
    }

    public function checkCredentials($username, $password)
    {

        $user = $this->userRepository->findByUser($username);


        if ($user === false) {
            return false;
        }
        
        if ($this->hash->check($password, $user->getHash(), $user->getSalt())){
            $_SESSION['failedLogin'] = 0;
            return true;
        } else {
            if (!isset($_SESSION['failedLogin'])){
                $_SESSION['failedLogin'] = 1;
            } else {
                $_SESSION['failedLogin'] += 1;
            }
            sleep(pow($_SESSION['failedLogin'], 2)); # Do not allow logins during duration.
        }


    }

    /**
     * Check if is logged in.
     */
    public function check()
    {
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT)) {

            // 60s = 1 minute
            session_unset();     // unset $_SESSION variable for the run-time
            session_destroy();   // destroy session data in storage
        }
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

        return isset($_SESSION['user']);
    }

    public function getUsername() {
        if(isset($_SESSION['user'])){
        return $_SESSION['user'];
        }
    }

    /**
     * Check if the person is a guest.
     */
    public function guest()
    {
        return $this->check() === false;
    }

    /**
     * Get currently logged in user.
     */
    public function user()
    {
        if ($this->check()) {
            return $this->userRepository->findByUser($_SESSION['user']);
        }

        throw new Exception('Not logged in but called Auth::user() anyway');
    }

    /**
     * Is currently logged in user admin?
     */
    public function isAdmin()
    {
        if ($this->check()) {
            return $this->userRepository->findByUser($_SESSION['user'])->isAdmin();
        }

        throw new Exception('Not logged in but called Auth::isAdmin() anyway');
    }

    public function logout()
    {
        session_destroy();
    }


}
