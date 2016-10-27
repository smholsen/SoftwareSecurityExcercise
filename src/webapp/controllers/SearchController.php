<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\models\Patent;
use tdt4237\webapp\validation\SearchFormValidation;

class SearchController extends Controller
{

	public function search()

    {
        if ($this->auth->guest()) 
        {
            $this->app->flash("info", "You must be logged in to do that");
            $this->app->redirect("/login");

        }else{
        	$result = [];
        	$needle  = $this->app->request->post('searchquery');
            $patent = $this->patentRepository->all();

            $validation = new SearchFormValidation($needle);
            if ($validation->isGoodToGo()) 
            {
                if($patent != null)
                {
                    $patent->sortByDate();
                    foreach ($patent as $haystack) 
                    {
                        if ((preg_match("/\b".$needle."\b/i", $haystack->getTitle())) or (preg_match("/\b".$needle."\b/i", $haystack->getCompany())))
                        {
                            $result[] = $haystack;
                        }
                    }
                }
            }
            $this->app->flashNow('error', join('<br>', $validation->getValidationErrors()));
            
            $this->render('patents/searchResult.twig', ['patent' => $result]);
        }
    }


}