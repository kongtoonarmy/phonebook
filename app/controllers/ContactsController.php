<?php

use Phalcon\Mvc\Controller;

class ContactsController extends Controller {

    public function indexAction()
    {
        $this->view->contacts = Contacts::find();
        $this->view->title = "My Contacts";
    }

    public function deleteAction($id)
    {
        $contacts = Contacts::findFirst($id);
        if (!$contacts) {
            $this->flash->error("Don't try to remove a contact that doesn't even exist");
        } else {
            if (!$contacts->delete()) {
                foreach ($contacts->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $this->flash->success("The contact R.I.P successful!!!");
            }
        }

        $this->dispatcher->forward([
            'action' => 'index'
        ]);
    }
}