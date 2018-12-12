<?php

namespace BookBundle\Controller;

use BookBundle\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\Serializer\Expression\ExpressionEvaluator;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\DeserializationContext;

/**
 * Api controller.
 *
 * @Route("/api/v1/books")
 */
class ApiController extends Controller
{
    public function __construct()
    {
    }
    
    /**
     * Get lists all book.
     *
     * @Route("")
     *
     * @Method("GET")
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository('BookBundle:Book')->findBy([], ['readIt' => 'DESC']);
        
        return $this->getRequest($books);
    }
    
    /**
     * add book.
     *
     * @Route("/add")
     *
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request)
    {
        die('add');
        
        return ;
    }
    
    /**
     * edit book.
     *
     * @Route("/{id}/edit")
     *
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        die('edit');
        
        return ;
    }
    
    
    public function getRequest($data)
    {
        $response = [
            'success' => true,
            'response' => $data
        ];
        
        $serializer = SerializerBuilder::create()->build();
        $request = $serializer->serialize($response, 'json');
        
        return new Response($request);
    }
}
