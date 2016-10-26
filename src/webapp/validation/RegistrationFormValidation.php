<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\User;

class RegistrationFormValidation
{
    const MIN_USER_LENGTH = 3;
    
    private $validationErrors = [];
    
    public function __construct($username, $password, $first_name, $last_name, $phone, $company)
    {
        if ($this->auth->guest()) {
            $this->app->flash("info", "You must be logged in to do that");
            $this->app->redirect("/login");

        } else {
            $user = $this->userRepository->findByUser($username);
        }
        return $this->validate($user->username, $user->password, $user->first_name, $user->last_name, $user->phone, $user->company);
    }

    public function isGoodToGo()
    {
        return empty($this->validationErrors);
    }
    
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private function validate($username, $password, $first_name, $last_name, $phone, $company)
    {
        if (empty($password)) {
            $this->validationErrors[] = 'Password cannot be empty';
        }

        if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/', $password ) === 0) {
            $this->validationErrors[] = 'Password must contain minimum 8 characters at least 1 Uppercase letter, 1 
            Lowercase letter, 1 Number and 1 Special Character.';
        }

        if(!empty($password) and !empty($username) and !empty($first_name) and  !empty($last_name) and !empty($company)) {
            if (strpos($password, $username) !== false or strpos($password, $first_name) !== false or
                strpos($password, $last_name) !== false
            ) {
                $this->validationErrors[] = 'Password cannot contain parts of username or personal information!';
            }
        }

        if(empty($first_name)) {
            $this->validationErrors[] = "Please write in your first name";
        }

         if(empty($last_name)) {
            $this->validationErrors[] = "Please write in your last name";
        }

        if(empty($phone)) {
            $this->validationErrors[] = "Please write in your phone number";
        }

        if (! is_numeric($phone) or $phone < 00000000 or $phone > 99999999) {
            $this->validationErrors[] = 'Phone must be between 00000000 and 99999999.';
        }

        if(strlen($company) > 0 && (!preg_match('/[^0-9]/',$company)))
        {
            $this->validationErrors[] = 'Company can only contain letters';
        }

        if (preg_match('/^[A-Za-z0-9_]+$/', $username) === 0) {
            $this->validationErrors[] = 'Username can only contain letters and numbers';
        }
    }
}
