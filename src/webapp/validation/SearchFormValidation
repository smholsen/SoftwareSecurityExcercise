<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\Patent;

class SearchFormValidation {

    private $validationErrors = [];
    const MAX_INPUT_FIELD_LENGTH = 40;

    public function __construct($searchQuery) {
        return $this->validate($searchQuery);
    }

    public function isGoodToGo()
    {
        return \count($this->validationErrors) ===0;
    }

    public function getValidationErrors()
    {
    return $this->validationErrors;
    }

    public function validate($searchQuery)
    {
        if (strlen($searchQuery) > SearchFormValidation::MAX_INPUT_FIELD_LENGTH){
            $this->validationErrors[] = 'Too long search string';
        }


        if ($searchQuery == null) {
            $this->validationErrors[] = "Empty search string";

        }
       
       

        return $this->validationErrors;
    }


}
