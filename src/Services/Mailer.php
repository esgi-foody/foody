<?php

namespace App\Services;

use Swift_Mailer;

class Mailer
{
    private $mailer;
    private $templating;

    /**
     * Mailer constructor.
     * @param Swift_Mailer $mailer
     * @param \Twig_Environment $templating
     */
    public function __construct(Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param $to recipient
     * @param $subject object
     * @param $emailTemplate name of email template test.html.twig => just 'test'
     * @param $params array of params ['name' => 'Foody']
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function send($to, $subject, $emailTemplate, $params)
    {
        $message = (new \Swift_Message($subject))
            ->setFrom('foody.dev.mailer@gmail.com')
            ->setTo($to)
            ->setBody(
                $this->templating->render(
                    'emails/' . $emailTemplate . '.html.twig',
                    $params
                ),
                'text/html'
            )
        ;

        $this->mailer->send($message);
    }
}