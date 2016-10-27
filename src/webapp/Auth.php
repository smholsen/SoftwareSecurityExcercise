<?php

namespace tdt4237\webapp;

use Exception;
use tdt4237\webapp\Hash;
use tdt4237\webapp\repository\IpRepository;
use tdt4237\webapp\repository\UserRepository;

class Auth
{

    /**
     * @var Hash
     */
    private $hash;

    /**
     * @var
     */

    private $userRepository;

    private  $ipRepository;

    public function __construct(UserRepository $userRepository, Hash $hash, IpRepository $ipRepository)
    {
        $this->userRepository = $userRepository;
        $this->hash           = $hash;
        $this-> ipRepository = $ipRepository;


        $_SESSION['ip']       = $this->getIp();
    }


    public function getIp()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }

    public function checkCredentials($username, $password)
    {

        $user = $this->userRepository->findByUser($username);


        if ($user === false) {
            return false;
        }
        
<<<<<<< Updated upstream
=======
        if ($this->hash->check($password, $user->getHash(), $user->getSalt())){
            $_SESSION['failedLogin'] = 0;
            $this->ipRepository->saveNewIp(session_id(), $_SESSION['ip']);
            $this->debug_to_console('row stored in db. retrieved: this ip from current sessionid:' . $this->ipRepository->getIpBySessid(session_id()));
            return true;
        } else {
            if (!isset($_SESSION['failedLogin'])){
                $_SESSION['failedLogin'] = 1;
            } else {
                $_SESSION['failedLogin'] += 1;
            }
            sleep(pow($_SESSION['failedLogin'], 2)); # Do not allow logins during duration.
        }
>>>>>>> Stashed changes

        return $this->hash->check($password, $user->getHash(), $user->getSalt());

    }

    /**
     * Check if is logged in.
     */
    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function ipCheck(){
        $currIp = $this->getIp();
        $this->debug_to_console($this->ipRepository->getIpBySessid(session_id()) . "  || " . $currIp);
        return $this->ipRepository->getIpBySessid(session_id()) == $currIp;
    }

    function debug_to_console( $data ) {

        if ( is_array( $data ) )
            $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
        else
            $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

        echo $output;
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

    public function destroyWithMessage(){
        // Looks like you are allready logged in.
        session_destroy();
    }


}
