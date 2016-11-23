<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\DateType;


use AppBundle\Entity\Rate;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }
    
    /**
     * @Route("/send-notifications", name="send_notifications")
     */    
    public function sendNotification(Request $request){
        
        
        $token = $request->get('t');
        
        if($token != '$2y$12$yqrkKUihuZ1mC1kWnFP4Su/4ZFL.C0GkAUysGYx4tLnDChmEAk3OS')
        {
            return new Response('Accès refusé.');            
        }

        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $Users      = $repository->findByIsEnabled(true);
        $template   = 'default/mail.html.twig';
        $mailer     = $this->get('app.mailer');
        
        foreach($Users as $User){
            
            $body = $this->renderView('default/mail.html.twig', array('user' => $User));
            
            $params = array(
                'template'      => $template,
                'to'            => $User->getEmail(),
                'from'          => $this->getParameter('email_sender'),
                'from_string'   => $this->getParameter('email_sender_string'),
                'subject'       => 'Il est temps de voter !',
                'body_html'     => $body
            );            
            
            $mailer->send($params);        
        }
        
        return new Response();
        
    }
            
    /**
     * @Route("/rate", name="rate")
     */
    public function rateAction(Request $request){
        $token          = $request->query->get('token');
        $rating         = $request->query->get('rating');
        $date           = $request->query->get('date');
        
        $user_repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $rate_repository = $this->getDoctrine()->getRepository('AppBundle:Rate');

        $date = new \DateTime($date);
        $date->add(new \DateInterval('P1D'));
        $now = new \DateTime();
        
        if(!in_array((int)$rating, range(1, 5))){
            return new Response("Cette note n'est pas valide");
        }
        
        $User           = $user_repository->findOneByToken($token);
        $em             = $this->getDoctrine()->getManager();
        $userAsRated    = $em->getRepository('AppBundle:Rate')->hasRatedToday($User);

        if(!$User){
            return new Response('Utilisateur non reconnu.');
        } 
        
        if($userAsRated){
            return new Response('Vous avez déjà voté pour cette journée.');
        }         

        $Rate       = new Rate();
        $Rate->setRate((int)$rating);
        $createdAt  =  new \DateTime("now");
        $Rate->setCreatedAt($createdAt);
        $Rate->setUser($User);
        $em->persist($Rate);
        $em->flush();
        
        return $this->redirectToRoute('stats', array());
    }
    
    /**
     * @Route("/stats", name="stats")
     */
    public function statsAction(Request $request)
    {
        $now                = new \DateTime('now');
        $firstSprintStart   = new \DateTime('2016/07/12');
        $featureId          = $request->get('feature_id') ? $request->get('feature_id') : 0;
        $interval           = $firstSprintStart->diff($now);
        $stats = array();

        $sprintPeriods = array();
        for($i = 1; $i < 52; $i++)
        {
            $firstSprintStart   = new \DateTime('2016/07/5');
            if($i%2 == 1)
            {
                $sprintPeriods['SPRINT ' . ($i-1)] = $firstSprintStart->add(new \DateInterval('P'.$i.'W'))->format('d-m-Y');
            }
        }
        
        if($request->isMethod('POST'))
        {
            $postedDateStart    = '';
            $postedDateEnd      = '';
            $dateStart          = new \DateTime($postedDateStart);
        }

        $dateStart  = new \DateTime('first day of this month');
        $dateEnd    = new \DateTime('now');
        
        $em         = $this->getDoctrine()->getManager();
        $Rates      = $em->getRepository('AppBundle:Rate')->findAllByPeriod($dateStart, $dateEnd);
        $Feature    = $em->getRepository('AppBundle:Feature')->find($featureId);
        $Features   = $em->getRepository('AppBundle:Feature')->findAll();
        
        $RatesByValue   = $em->getRepository('AppBundle:Rate')->findAllByValue($dateStart, $dateEnd);
        $totalRates     = count($Rates);
        $labels         = Rate::getLabels();
        
        foreach($RatesByValue as $rate)
        {
                $stats[] = array(
                    'percent' => $rate['countRates'] / $totalRates,
                    'label'   => $labels[$rate['rate']],
                    'rate'    => $rate['rate'],
                );                
        }
        
        $title = "Moyenne de l'humeur des équipes du ". $dateStart->format('d/m/Y') ." au " . $dateEnd->format('d/m/Y');
        
        if($Feature){
            $title = "Moyenne de l'humeur de l'équipe ". $Feature->getName() ." du ". $dateStart->format('d/m/Y') ." au " . $dateEnd->format('d/m/Y');
        }
        
        
        $form = $this->createFormBuilder()
            ->add('sprint_start', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, array(
                'choices' => $sprintPeriods,
                'label'   => 'Sprint',
                'data'    => ''
            ))
            ->getForm();        
        
        
        
   
        
        return $this->render('default/stats.html.twig', [
            'base_dir'          => realpath($this->getParameter('kernel.root_dir').'/..'),
            'date_start'        => $dateStart,
            'date_end'          => $dateEnd,
            'title'             => $title,
            'stats'             => $stats,
            'rates'             => $Rates,
            'total_rates'       => $totalRates,
            'features'          => $Features,
            'sprint_periods'    => $sprintPeriods,
            'form'              => $form->createView()
        ]);
    }    
}
