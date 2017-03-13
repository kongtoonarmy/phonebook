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

    public function editAction($id)
    {
        if (!$this->request->getPost()) {
            $contact = Contacts::findFirst($id);
            if (!$contact) {
                $this->flash->error("Don't try to smart and edit and invalid contact.");
                $this->dispatcher->forward(['action' => 'index']);
            } else {
                $this->tag->displayTo("id", $contact->id);
                $this->tag->displayTo("name", $contact->name);
                $this->tag->displayTo("email", $contact->email);
                $this->tag->displayTo("phone", $contact->phone);
            }
        } else {
            $this->flash->error("Invalid Request!!!");
            $this->dispatcher->forward(['action' => 'index']);
        }
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
            $this->flash->setAutomaticHtml(true);
            $this->flash->error("Following Errors occurred: <br/>");

            foreach ($contact->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'action' => 'new'
            ]);
        }

    }

    // For updating
    public function updateAction($id)
    {
        if (!$this->request->getPost()) {
            $this->flash->error("Invalid Request!!!");
            $this->dispatcher->forward(['action' => 'index']);
        } else {
            $id = $this->request->getPost("id");
            $contact = Contacts::findFirst($id);

            if (!$contact) {
                $this->flash->error("No such record found");
                $this->dispatcher->forward(['action' => 'index']);
            } else {
                $success = $contact->save($this->request->getPost(), array('name', 'phone', 'email'));
                if (!$success) {
                    $this->flash->error("Following Errors occurred: <br>");
                    foreach ($contact->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                    $this->dispatcher->forword(array(
                        'action' => 'edit',
                        'params' => array($contact->id)
                    ));
                }
                $this->flash->success("Contact Successfully Updated!");
            }
        }
        $this->dispatcher->forward(['action' => 'index']);
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