<?php

namespace tdt4237\webapp\validation;

class EditUserFormValidation
{
    private $validationErrors = [];
    const MAX_INPUT_FIELD_LENGTH = 40;
    
    public function __construct($email, $phone, $company)
    {
        $this->validate($email, $phone, $company);
    }
    
    public function isGoodToGo()
    {
        return \count($this->validationErrors) === 0;
    }
    
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private function validate($email, $phone, $company)
    {
        if (strlen($email) > EditUserFormValidation::MAX_INPUT_FIELD_LENGTH){
            $this->validationErrors[] = 'Too long email';
        }

        if (strlen($phone) > 8){
            $this->validationErrors[] = 'Too long phone no';
        }

        if (strlen($company) > EditUserFormValidation::MAX_INPUT_FIELD_LENGTH){
            $this->validationErrors[] = 'Too long company name';
        }

        $this->validateEmail($email);
        $this->validatePhone($phone);
        $this->validateCompany($company);
    }
    
    private function validateEmail($email)
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->validationErrors[] = "Invalid email format on email";
        }
    }
    
    private function validatePhone($phone)
    {
        if (! is_numeric($phone) or $phone < 00000000 or $phone > 99999999) {
            $this->validationErrors[] = 'Phone number must be between 00000000 and 99999999.';
        }
    }

    private function validateCompany($company)
    {
        if(strlen($company) > 0 && (!preg_match('/[^0-9]/',$company)))
        {
            $this->validationErrors[] = 'Company can only contain letters';
        }
    }
}
