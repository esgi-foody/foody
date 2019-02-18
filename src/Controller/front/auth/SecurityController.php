<?php

namespace App\Controller\front\auth;

use App\Form\ForgottenPasswordType;
use App\Form\ResetPasswordType;
use App\Form\UserType;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\Mailer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 * @Route(name="app_front_auth_")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $helper): Response
    {
        return $this->render('front/auth/login.html.twig', [
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     * @throws \Exception
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/forgottenPassword", name="forgottenPassword")
     */
    public function forgottenPassword(Request $request, UserRepository $userRepository, Mailer $mailer): Response
    {
        $form = $this->createForm(ForgottenPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $userRepository->findOneBy(['email' => $data['email']]);

            if ($user) {
                $token = bin2hex(random_bytes(10));
                $user->setLostPasswordToken($token);
                $this->getDoctrine()->getManager()->flush();
                $mailer->send($user->getEmail(), 'Foody : Réinitialisation de mot de passe', 'forgottenPassword', [
                    'SERVER_URL' => $this->getParameter('SERVER_URL'),
                    'token' => $token
                ]);
            }

            return $this->redirectToRoute('app_front_auth_emailSent');
        }

        return $this->render('front/auth/forgottenPassword.html.twig', [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/emailSent", name="emailSent")
     */
    public function emailSent()
    {
        return $this->render(
            'front/auth/emailSent.html.twig');
    }

    /**
     * @Route("/resetPassword/{lostPasswordToken}", name="resetPassword")
     */
    public function resetPassword(Request $request, User $user, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(ResetPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $password = $encoder->encodePassword($user, $data['password']);

            $user->setPassword($password);
            $user->setLostPasswordToken(null);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_front_auth_login');
        }

        return $this->render('front/auth/resetPassword.html.twig', [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/register", name="registration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function registerAction(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, Mailer $mailer)
    {
        if ($this->getUser() instanceof User) {
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);



        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $token = bin2hex(random_bytes(10));
            $user->setRegisterToken($token);
            $this->getDoctrine()->getManager()->flush();
            $mailer->send($user->getEmail(), 'Foody : Activation de votre compte', 'emailConfirmation', [
                'SERVER_URL' => $this->getParameter('SERVER_URL'),
                'token' => $token
            ]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_front_auth_login');
        }


        return $this->render('front/auth/register.html.twig', [
                'form' => $form->createView(),
            ]
        );

    }

    /**
     * @Route("/registerSuccess/{registerToken}", name="registerSuccess")
     */
    public function registerSuccess(Request $request, User $user)
    {
        $currentToken = $request->attributes->filter('registerToken');
        $userToken = $user->getRegisterToken();

        if ($currentToken == $userToken) {
            $user->setRegisterToken(null);
            $user->setStatus(1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

        }

        return $this->render('front/auth/registerSuccess.html.twig');
    }
}
