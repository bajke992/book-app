<?php

namespace Bajke\BookBundle\Controller;

use Bajke\BookBundle\Entity\Book;
use Bajke\BookBundle\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends BaseController {

    /**
     * @Route("/api/book")
     * @Method("GET")
     */
    public function listAction(Request $request){
        $user = $this->getUserByToken($request);
        if(array_key_exists('error', $user)){
            return $this->createApiResponse($user, 200);
        }

        $books = $user->getBooks();
        $response = $this->createApiResponse(['books' => $books], 200);

        return $response;
    }

    /**
     * @Route("/api/book/{id}", name="api_book_get")
     * @Method("GET")
     */
    public function getAction(Request $request, $id){
        $user = $this->getUserByToken($request);
        if(array_key_exists('error', $user)){
            return $this->createApiResponse($user, 200);
        }

        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('BookBundle:Book')->findOneBy(array('id' => $id, 'owner' => $user));

        if(!$book){
            throw $this->createNotFoundException(
                'No book found with id: '.$id
            );
        }

//        $this->denyAccessUnlessGranted('view', $book, 'Unauthorized access!');

        $response = $this->createApiResponse($book, 200);
        return $response;
    }

    /**
     * @Route("/api/book")
     * @Method("POST")
     */
    public function createAction(Request $request){
        $user = $this->getUserByToken($request);
        if(array_key_exists('error', $user)){
            return $this->createApiResponse($user, 200);
        }

        $book = new Book();
        $book->setOwner($user);
        $form = $this->createForm(new BookType(), $book, array('is_api' => true, 'is_owner_disabled' => true));
        $this->processForm($request, $form);
        $em = $this->getDoctrine()->getManager();

//        $this->denyAccessUnlessGranted('create', $book, 'Unauthorized access!');

        $em->persist($book);
        $em->flush();

        $bookUrl = $this->generateUrl(
            'api_book_get',
            ['id' => $book->getId()]
        );

        $response = $this->createApiResponse($book, 201);
        $response->headers->set('Location', $bookUrl);

        return $response;
    }

    /**
     * @Route("/api/book/{id}", name="api_book_update")
     * @Method({"PUT", "PATCH"})
     */
    public function updateAction(Request $request, $id){
        $user = $this->getUserByToken($request);
        if(array_key_exists('error', $user)){
            return $this->createApiResponse($user, 200);
        }

        $repo = $this->getDoctrine()->getRepository('BookBundle:Book');
        $book = $repo->findOneBy(array('id' => $id));

        if(!$book){
            throw $this->createNotFoundException(
                'No book found with id: '.$id
            );
        }

        $form = $this->createForm(new BookType(), $book, array('is_api' => true, 'is_edit' => true, 'is_owner_disabled' => true));
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();

//        $this->denyAccessUnlessGranted('update', $book, 'Unauthorized access!');

        $em->persist($book);
        $em->flush();

        $bookUrl = $this->generateUrl(
            'api_book_get',
            ['id' => $book->getId()]
        );

        $response = $this->createApiResponse($book, 200);
        $response->headers->set('Location', $bookUrl);
        return $response;
    }

    /**
     * @Route("/api/book/{id}", name="api_book_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id){
        $user = $this->getUserByToken($request);
        if(array_key_exists('error', $user)){
            return $this->createApiResponse($user, 200);
        }

        $repo = $this->getDoctrine()->getRepository('BookBundle:Book');
        $book = $repo->findOneBy(array('id' => $id));

        if($book){
            $em = $this->getDoctrine()->getManager();

//            $this->denyAccessUnlessGranted('delete', $book, 'Unauthorized access!');

            $em->remove($book);
            $em->flush();
        }

        return new Response(null, 204);
    }

    private function processForm(Request $request, FormInterface $form){
        $data = json_decode($request->getContent(), true);
        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

}