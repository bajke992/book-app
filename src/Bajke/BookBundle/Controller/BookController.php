<?php

namespace Bajke\BookBundle\Controller;

use Bajke\BookBundle\Entity\Book;
use Bajke\BookBundle\Form\BookType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class BookController extends BaseController {

    /**
     * @Route("/book", name="book_list")
     * @Template()
     */
    public function listAction(){
        $user = $this->checkUser();
        if(!$user){ return new RedirectResponse($this->generateUrl('index')); }

        $books = $user->getBooks();

        return array('books' => $books);
    }

    /**
     * @Route("/book/create", name="book_create")
     * @Template("BookBundle:Book:_form.html.twig")
     */
    public function createAction(Request $request){
        $user = $this->checkUser();
        if(!$user){ return new RedirectResponse($this->generateUrl('index')); }

        $em = $this->getDoctrine()->getManager();
        $book = new Book();
        $book->setOwner($user);

        $this->denyAccessUnlessGranted('create', $book, 'Unauthorized access!');

        $form = $this->createForm(new BookType(), $book, array('is_owner_disabled' => true));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $book->setTitle($data->getTitle());
            $book->setDescription($data->getDescription());

            $em->persist($book);
            $em->flush();

            $this->flashMessage(array(
                'alert' => 'success',
                'title' => 'Success!',
                'message' => 'Successfully created Book id: #'.$book->getId().'!'
            ));

            return new RedirectResponse($this->generateUrl('book_list'));
        }

        return array('create' => true, 'book' => $book, 'form' => $form->createView());
    }

    /**
     * @Route("/book/update")
     * @Template("BookBundle:Book:_form.html.twig")
     */
    public function updateAction(Request $request){
        $user = $this->checkUser();
        if(!$user){ return new RedirectResponse($this->generateUrl('index')); }

        $em = $this->getDoctrine()->getManager();
        $id = $request->get("id");
        $book = $em->getRepository('BookBundle:Book')->find($id);

        if(!$book){
            throw $this->createNotFoundException('No Book fount for id: ' . $id);
        }

        $this->denyAccessUnlessGranted('update', $book, 'Unauthorized access!');

        $form = $this->createForm(new BookType(), $book, array('is_edit' => true, 'is_owner_disabled' => true));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $book->setTitle($data->getTitle());
            $book->setDescription($data->getDescription());

            $em->flush();

            $this->flashMessage(array(
                'alert' => 'success',
                'title' => 'Success!',
                'message' => 'Successfully updated Book id: #'.$book->getId().'!'
            ));

            return new RedirectResponse($this->generateUrl('book_list'));
        }

        return array('create' => false, 'book' => $book, 'form' => $form->createView());
    }

    /**
     * @Route("/book/delete")
     *
     */
    public function deleteAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $id = $request->get("id");
        $book = $em->getRepository('BookBundle:Book')->find($id);

        if(!$book){
            throw $this->createNotFoundException('No Book fount for id: ' . $id);
        }

        $this->denyAccessUnlessGranted('delete', $book, 'Unauthorized access!');

        $em->remove($book);
        $em->flush();

        $this->flashMessage(array(
            'alert' => 'success',
            'title' => 'Success!',
            'message' => 'Successfully deleted Book id: #'.$id.'!'
        ));

        return new RedirectResponse($this->generateUrl('book_list'));
    }

}