<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\User;
use tdt4237\webapp\Hash;

class ChangePasswordValidation
{
    
    private $validationErrors = [];
    
    public function __construct($user, $oldpw, $newpw1, $newpw2)
    {
        return $this->validate($user->getUserName(), $oldpw, $newpw1, $newpw2, $user->getHash(), $user->getSalt(), $user->getFirstName(), $user->getLastName(), $user->getPhone(), $user->getCompany());
    }

    public function isGoodToGo()
    {
        return empty($this->validationErrors);
    }
    
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private function validate($username, $oldpw, $newpw1, $newpw2, $oldhash, $oldsalt, $first_name, $last_name, $phone, $company)
    {
        if (empty($oldpw)) {
            $this->validationErrors[] = 'Password cannot be empty';
        }
        $hasher = new Hash();

        if (!$hasher->check($oldpw,$oldhash,$oldsalt)) {
            $this->validationErrors[] = 'Incorrect old password';
        }
        if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/', $newpw1 ) === 0) {
            $this->validationErrors[] = 'Password must contain minimum 8 characters at least 1 Uppercase letter, 1 
            Lowercase letter, 1 Number and 1 Special Character.';
        }

        if(!empty($newpw1)) {
            if (strpos($newpw1, $username) !== false or strpos($newpw1, $first_name) !== false or
                strpos($newpw1, $last_name) !== false or strpos($newpw1, $company) !== false or strpos($newpw1, $phone->__toString()) !== false
            ) {
                $this->validationErrors[] = 'Password cannot contain parts of username or personal information!';
            }
            if(empty($newpw2)){
                $this->validationErrors[] = 'Please confirm new password.';
            }
        }

        if (!empty($newpw2) and $newpw1 != $newpw2) {
            $this->validationErrors[] = 'Password confirmation does not match.';
        }

    }
}
