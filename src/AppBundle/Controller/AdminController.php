<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="dashboard")
     */
    public function indexAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('login');
        }
        
 $user = $this->getUser();        
        
        return new Response();
        
        
        
        
    }
    
    /**
     * @Route("/admin/login", name="login")
     */    
    public function loginAction(Request $request)
    {
        
        if($request->getMethod() == 'POST')
        {
            $login      = $request->get('login');
            $password   = $request->get('password');
            $em         = $this->getDoctrine()->getManager();
            $User       = $em->getRepository('AppBundle:User')->findOneBy(array('login' => $login));       
            
        }
        
        
        
        
        return $this->render('admin/signin.html.twig', array());
        
        
    }
    
    public function logoutAction(Request $request)
    {
        
    }    
    
}