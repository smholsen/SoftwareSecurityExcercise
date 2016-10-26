<?php

namespace tdt4237\webapp;

use Symfony\Component\Config\Definition\Exception\Exception;

class Hash
{

    protected $salt;


    public function __construct()
    {
        $randomStr = $this->generateRandomString(10);
        $this->salt = hash('sha256',$randomStr);
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function make($plaintext)
    {
        return hash('sha256', $plaintext . $this->salt);

    }

    public function check($plaintext, $hash, $saltFromDB)
    {
        return hash('sha256', $plaintext . $saltFromDB) === $hash;
    }

   private function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"#$%&/()=?';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
   return $randomString;
    }
    
}
