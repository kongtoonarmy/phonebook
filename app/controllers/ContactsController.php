<?php

use Phalcon\Mvc\Controller;

class ContactsController extends Controller {

    public function indexAction()
    {
        // $this->view->setParamToView('title', "My Contacts");
        // $this->view->title = "My Contacts";
        //$this->view->setVar('title', "My Contacts");

        $this->view->setVars([
            'title' => "My Contacts...",
            'count' => "0000"
        ]);
    }
}