<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\models\Patent;
use tdt4237\webapp\controllers\UserController;
use tdt4237\webapp\validation\PatentValidation;

class SearchController extends Controller
{

	public function search()

    {

    	$result = [];
    	$needle  = $this->app->request->post('searchquery');
        $patent = $this->patentRepository->all();
        if($patent != null)
        {
            $patent->sortByDate();
            foreach ($patent as $haystack) {

            	if ((preg_match("/\b".$needle."\b/i", $haystack->getTitle())) or (preg_match("/\b".$needle."\b/i", $haystack->getCompany())))
            	{
            		$result[] = $haystack;
            	}
            }
        }

        $users = $this->userRepository->all();
        $this->render('patents/searchResult.twig', ['patent' => $result, 'users' => $users]);
    }

    private function searchAction($String)
    {
    	return false;
    }



}