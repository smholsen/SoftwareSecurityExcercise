<?php

namespace tdt4237\webapp\controllers;

use tdt4237\webapp\Hash;
use tdt4237\webapp\models\Phone;
use tdt4237\webapp\models\Email;
use tdt4237\webapp\models\User;
use tdt4237\webapp\validation\EditUserFormValidation;
use tdt4237\webapp\validation\RegistrationFormValidation;
use tdt4237\webapp\validation\ChangePasswordValidation;

class UsersController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function show($username)
    {
        if ($this->auth->guest()) {
            $this->app->flash("info", "You must be logged in to do that");
            $this->app->redirect("/login");

        } else {
            $user = $this->userRepository->findByUser($username);

            if ($user != false && $user->getUsername() == $this->auth->getUsername()) {

                $this->render('users/showExtended.twig', [
                    'user' => $user,
                    'username' => $username
                ]);
            } else if ($this->auth->check()) {

                $this->render('users/show.twig', [
                    'user' => $user,
                    'username' => $username
                ]);
            }
        }
    }

    public function newuser()
    {
        if ($this->auth->guest()) {
            return $this->render('users/new.twig', []);
        }

        $username = $this->auth->user()->getUserName();
        $this->app->flash('info', 'You are already logged in as ' . $username);
        $this->app->redirect('/');
    }

    public function create()
    {
        $request  = $this->app->request;
        $username = $request->post('user');
        $password = $request->post('pass');
        $firstName = $request->post('first_name');
        $lastName = $request->post('last_name');
        $phone = $request->post('phone');
        $company = $request->post('company');


        $validation = new RegistrationFormValidation($username, $password, $firstName, $lastName, $phone, $company);

        if ($validation->isGoodToGo()) {
            $password = $password;
            $hasher = new Hash();
            $password = $hasher->make($password);
            $salt = $hasher->getSalt();
            $user = new User($username, $password, $firstName, $lastName, $phone, $company, $salt);
            $this->userRepository->save($user);
            $this->app->flash('success', "Profile successfully created. Log in to continue.");
            return $this->app->redirect('/login');
        }

        $errors = $validation->getValidationErrors();
        $this->app->flashNow('error', $errors);
        $this->render('users/new.twig', ['username' => $username, 'firstname' => $firstName, 'lastname' => $lastName, 'phonenumber' => $phone, 'companyname' => $company]);
    }

    public function edit()
    {
        $this->makeSureUserIsAuthenticated();

        $this->render('users/edit.twig', [
            'user' => $this->auth->user()
        ]);
    }

    public function editpw()
    {
        $this->makeSureUserIsAuthenticated();
        //$this->app->redirect('/profile/edit/pwchange');
        $this->render('users/newpw.twig', []);
    }

    public function updatepw()
    {

        $this->makeSureUserIsAuthenticated();
        $user = $this->auth->user();

        $request    = $this->app->request;
        $oldpw      = $request->post('oldpw');
        $newpw1     = $request->post('newpw1');
        $newpw2     = $request->post('newpw2');
        
        $validation = new ChangePasswordValidation($user,$oldpw,$newpw1,$newpw2);

        if ($validation->isGoodToGo()) {
            $password = $newpw1;
            $hasher = new Hash();
            $password = $hasher->make($password);
            $salt = $hasher->getSalt();
            $user->setHash($password);
            $user->setSalt($salt);

            $this->userRepository->updatePassword($user);

            $this->app->flashNow('info', 'Password updated.');
            //return $this->render('users/edit.twig', ['user' => $user]);
        }
        $this->app->flashNow('error', join('<br>', $validation->getValidationErrors()));
        $this->render('users/newpw.twig', []);
        
    }

    public function update()
    {
        $this->makeSureUserIsAuthenticated();
        $user = $this->auth->user();

        $request    = $this->app->request;
        $email      = $request->post('email');
        $firstName  = $request->post('first_name');
        $lastName  = $request->post('last_name');
        $phone    = $request->post('phone');
        $company   = $request->post('company');

        $validation = new EditUserFormValidation($email, $phone, $company);

        if ($validation->isGoodToGo()) {
            $user->setEmail(new Email($email));
            $user->setCompany($company);
            $user->setPhone(new Phone($phone));
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $this->userRepository->save($user);

            $this->app->flashNow('success', 'Your profile was successfully saved.');
            //return $this->render('users/edit.twig', ['user' => $user]);
        }

        $this->app->flashNow('error', join('<br>', $validation->getValidationErrors()));
        $this->render('users/edit.twig', ['user' => $user]);
    }

    public function destroy($username)
    {
        if ($this->auth->isAdmin()) {
            if ($this->userRepository->deleteByUsername($username) === 1) {
                $this->app->flash('info', "Sucessfully deleted '$username'");
                $this->app->redirect('/admin');
                return;
            }
        }

        $this->app->flash('info', "An error ocurred. Unable to delete user '$username'. Maybe you are not an admin?");
        if ($this->auth->isAdmin()) {
            $this->app->redirect('/admin');
        } else {
            $this->app->redirect('/');
        }
    }

    public function makeSureUserIsAuthenticated()
    {
        if ($this->auth->guest()) {
            $this->app->flash('info', 'You must be logged in to edit your profile.');
            $this->app->redirect('/login');
        }
    }
}
