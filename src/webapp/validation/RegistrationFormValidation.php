<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\User;

class RegistrationFormValidation
{
    const MIN_USER_LENGTH = 3;
    const MAX_INPUT_FIELD_LENGTH = 40;
    
    private $validationErrors = [];
    
    public function __construct($username, $password, $first_name, $last_name, $phone, $company)
    {
        $this->validate($username, $password, $first_name, $last_name, $phone, $company);
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
        if (strlen($password) > RegistrationFormValidation::MAX_INPUT_FIELD_LENGTH){
            $this->validationErrors[] = 'Too long password';
        }

        if (strlen($username) > RegistrationFormValidation::MAX_INPUT_FIELD_LENGTH){
            $this->validationErrors[] = 'Too long username';
        }

        if (strlen($first_name) > RegistrationFormValidation::MAX_INPUT_FIELD_LENGTH){
            $this->validationErrors[] = 'Too long first name';
        }

        if (strlen($last_name) > RegistrationFormValidation::MAX_INPUT_FIELD_LENGTH){
            $this->validationErrors[] = 'Too long last name';
        }

        if (strlen($phone) > 8){
            $this->validationErrors[] = 'Too long phone no.';
        }

        if (strlen($company) > RegistrationFormValidation::MAX_INPUT_FIELD_LENGTH){
            $this->validationErrors[] = 'Too long company name';
        }

        if (empty($password)) {
            $this->validationErrors[] = 'Password cannot be empty';
        }

        if(empty($first_name)) {
            $this->validationErrors[] = "Please write in your first name";
        }

         if(empty($last_name)) {
            $this->validationErrors[] = "Please write in your last name";
        }

        if(empty($phone)) {
            $this->validationErrors[] = "Please write in your post code";
        }

        if (strlen($phone) != "8") {
            $this->validationErrors[] = "Phone number must be exactly eight digits";
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
