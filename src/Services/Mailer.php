<?php

namespace App\Services;

use Swift_Mailer;

class Mailer
{
    private $mailer;
    private $templating;

    public function __construct(Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function send($to, $subject, $mailTemplate, $params)
    {
        $message = (new \Swift_Message($subject))
            ->setFrom('foody.dev.mailer@gmail.com')
            ->setTo($to)
            ->setBody(
                $this->templating->render(
                    'emails/' . $mailTemplate . '.html.twig',
                    $params
                ),
                'text/html'
            )
        ;

        $this->mailer->send($message);
    }
}