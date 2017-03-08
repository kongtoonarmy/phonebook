<?php

use Phalcon\Mvc\Controller;

class ContactsController extends Controller {

    public function indexAction()
    {
        $this->view->contacts = Contacts::find();
        $this->view->title = "My Contacts";
    }

    // For rendering the new contact form
    public function newAction()
    {

    }

    // For creating a new contact in db
    public function createAction()
    {
        $this->request->getPost('name');
        $contact = new Contacts();
        /*$contact->name = $this->request->getPost('name');
        $contact->phone = $this->request->getPost('phone');
        $contact->email = $this->request->getPost('email');
        $success = $contact->save();*/

        // Another way
        $success = $contact->save($this->request->getPost(), [
            'name', 'phone', 'email'
        ]);

        if ($success) {
            $this->flash->success("Contact Successfully saved");
            $this->dispatcher->forward([
                'action' => 'index'
            ]);
        } else {
            $this->flash->error("Following Errors occurred: <br/>");

            foreach ($contact->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'action' => 'new'
            ]);
        }

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