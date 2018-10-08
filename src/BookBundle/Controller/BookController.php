<?php

namespace BookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends Controller
{
    public function indexAction()
    {
        
        $book = array();
        
        return $this->render('@Book/Book/index.html.twig', array('book' => $book));
          
    }
    
    
    
}
