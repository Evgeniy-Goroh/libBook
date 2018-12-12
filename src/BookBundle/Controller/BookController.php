<?php

namespace BookBundle\Controller;

use BookBundle\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class BookController extends Controller
{
    
    /**
     * Matches /book exactly
     *
     * @Route("/book", name="book_homepage")
     */
    public function indexAction(Request $request)
    {
        $cache = new FilesystemAdapter();
        $booksAll = $cache->getItem('books.all');
        
        if (!$booksAll->isHit()) {
            $em = $this->getDoctrine()->getManager();
            $books = $em->getRepository('BookBundle:Book')->findBy([], ['readIt' => 'DESC']);
        }
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $books, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );
        
        return $this->render('@Book/Book/index.html.twig', [
            'books' => $books,
            'pagination' => $pagination
        ]);
    }
    
    /**
     * Matches /new exactly
     *
     * @Route("/new", name="book_new")
     */
    public function newAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm('BookBundle\Form\BookType', $book);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('book_homepage');
        }
        
        return $this->render('@Book/Book/new_book.html.twig', [
            'books' => $book,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Deletes a book entity.
     *
     * @Route("/book/{id}", name="book_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param Book $book
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Book $book)
    {
        $form = $this->createDeleteForm($book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($book);
            $em->flush();
        }
        
        return $this->redirectToRoute('book_homepage');
    }
    
    /**
     * Displays a form to edit an existing book entity.
     *
     * @Route("/{id}/edit", name="book_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @param Book $book
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Book $book)
    {
        $uploadsPath = $this->container->getParameter('kernel.root_dir') . '/../web/uploads';
        $cover = $book->getCover();
        $file = $book->getFile();
        
        $deleteForm = $this->createDeleteForm($book);
        
        $editForm = $this->createForm('BookBundle\Form\BookType', $book);
        $editForm
            ->add(
                'clear_cover',
                CheckBoxType::class,
                [
                    'label' => 'Clear cover',
                    'mapped' => false,
                    'required' => false,
                ]
                )
            ->add(
                'clear_file',
                CheckBoxType::class,
                [
                    'label' => 'Clear file',
                    'mapped' => false,
                    'required' => false,
                ]
                );
        
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if (empty($editForm->get('cover')->getData())) {
                $book->setCover($cover);
            }
            if (empty($editForm->get('file')->getData())) {
                $book->setFile($file);
            }
            if ($editForm->get('clear_cover')->getData() && !empty($cover)) {
                unlink($uploadsPath."/covers/{$cover}");
                $book->clearCover();
            }
            if ($editForm->get('clear_file')->getData() && !empty($file)) {
                unlink($uploadsPath."/files/{$file}");
                $book->clearFile();
            }
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('book_homepage', ['id' => $book->getId()]);
        }
        
        return $this->render('@Book/Book/edit_book.html.twig', [
            'book' => $book,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        ]);
    }
    
    /**
     * Creates a form to delete a book entity.
     *
     * @param Book $book
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Book $book)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('book_delete', ['id' => $book->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
