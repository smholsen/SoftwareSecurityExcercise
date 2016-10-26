<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\Patent;

class PatentValidation {

    private $validationErrors = [];
    const MAX_INPUT_FIELD_LENGTH = 40;

    public function __construct($company, $title, $description, $file) {
        return $this->validate($company, $title, $description, $file);
    }

    public function isGoodToGo()
    {
        return \count($this->validationErrors) ===0;
    }

    public function getValidationErrors()
    {
    return $this->validationErrors;
    }

    public function validate($company, $title, $description, $file)
    {
        $imageFileType = pathinfo($file,PATHINFO_EXTENSION);
        // Only allow pdf
        if($imageFileType != "pdf") {
            $this->validationErrors[] =  "Sorry, only PDF files are allowed.";
        }


        if (strlen($company) > PatentValidation::MAX_INPUT_FIELD_LENGTH){
            $this->validationErrors[] = 'Too long company name';
        }

        if (strlen($title) > PatentValidation::MAX_INPUT_FIELD_LENGTH){
            $this->validationErrors[] = 'Too long title';
        }

        if (strlen($description) > 2000){
            $this->validationErrors[] = 'Too long description';
        }

        if ($company == null) {
            $this->validationErrors[] = "Company/User needed";

        }
        if ($title == null) {
            $this->validationErrors[] = "Title needed";
        }

        return $this->validationErrors;
    }


}
