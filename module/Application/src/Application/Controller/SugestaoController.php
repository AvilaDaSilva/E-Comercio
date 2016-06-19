<?php

namespace Application\Controller;


use Application\Entity\Sugestao;
use Application\Form\SugestaoForm;
use Application\Validator\SugestaoValidator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SugestaosController extends AbstractActionController
{
    public function indexAction()
    {
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $sugestao = $entityManager->getRepository('\Application\Entity\Sugestao')->findAll();

        return new ViewModel(
            array('sugestaos' => $sugestaos)
        );
    }

    public function createAction()
    {
        $form = new SugestaoForm();
        $request = $this->getRequest();

        if ($request->isPost()){
            $validator = new SugestaoValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {
                $values = $form->getData();
                $sugestao = new Sugestao();
                $sugestao->sugestao = $values['sugestao'];
                $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                $entityManager->persist($sugestao);
                $entityManager->flush();

                return $this->redirect()->toUrl('/application/sugestao');
            }
        }

        return new ViewModel(array(
            'form' => $form
        ));
    }

    public function updateAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $request = $this->getRequest();

        if ($request->isPost()){
            $values = $request->getPost();
            $sugestao = $entityManager->find('\Application\Entity\Sugestao', $id);
            $sugestao->sugestao = $values['sugestao'];
            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $entityManager->persist($sugestao);
            $entityManager->flush();

            return $this->redirect()->toUrl('/application/sugestao');
        }

        if ($id > 0) {
            $form = new SugestaoForm();
            $sugestao = $entityManager->find('\Application\Entity\Sugestao', $id);
            $form->bind($sugestao);

            return new ViewModel(array('form' => $form));
        }

        $this->request->setStatusCode(404);

        return $this->request;
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $sugestao = $entityManager->find('\Application\Entity\Sugestao', $id);
        $entityManager->remove($sugestao);
        $entityManager->flush();

        return $this->redirect()->toUrl('/application/sugestao');
    }
}