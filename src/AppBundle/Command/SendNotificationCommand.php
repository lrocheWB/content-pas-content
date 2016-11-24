<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;




class SendNotificationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:send-notification')

            // the short description shown while running "php bin/console list"
            ->setDescription('Send notification to users.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to send notifications...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repository = $this->getContainer()->get('doctrine')->getRepository('AppBundle:User');
        $Users      = $repository->findByIsEnabled(true);
        $template   = 'default/mail.html.twig';
        $mailer     = $this->getContainer()->get('app.mailer');
        
        
        $context = $this->getContainer()->get('router')->getContext();
        
        
        $context->setHost($this->getContainer()->getParameter('host'));        
        $context->setScheme('http');
        $context->setBaseUrl('');        
        
        foreach($Users as $User){
            
            $body = $this->getContainer()->get('templating')->render('default/mail.html.twig', array('user' => $User));
            
            $params = array(
                'template'      => $template,
                'to'            => $User->getEmail(),
                'from'          => $this->getContainer()->getParameter('email_sender'),
                'from_string'   => $this->getContainer()->getParameter('email_sender_string'),
                'subject'       => 'Il est temps de voter !',
                'body_html'     => $body
            );            
            
            $mailer->send($params);        
        }
        
    }
}