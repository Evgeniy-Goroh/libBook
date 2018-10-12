<?php

namespace BookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends Controller
{
    
    /**
     * Matches /book exactly
     *
     * @Route("/book", name="book_homepage")
     */
    public function indexAction()
    {
        
       // $entityManager = $this->getDoctrine()->getManager();
        
        var_dump($entityManager);
        
        $book = array();
        
        return $this->render('@Book/Book/index.html.twig', array('book' => $book));
          
    }
    
    
    
}
