<?php

namespace tdt4237\webapp;

use Symfony\Component\Config\Definition\Exception\Exception;

class Hash
{

    protected $salt;


    public function __construct()
    {
        $this->salt = random_bytes(32);
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
    
}
