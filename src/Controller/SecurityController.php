<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SecurityController extends AbstractController
{
    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function login(AuthenticationUtils $authenticationUtils, Environment $twig): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('index');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return new Response($twig->render('pages/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]));
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function forgetPassword(
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        EntityManagerInterface $em,
        Environment $twig,
        MailerInterface $mailer,
        string $hash = null
    ): Response
    {
        $error = null;
        $success = null;

        if ($hash)
        {
            $form = null;

            $user = $em->getRepository(User::class)->findOneBy(['forgetPasswordHash' => $hash]);

            if ($user)
            {
                $form = $this->createForm(ResetPasswordFormType::class, $user);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    // encode the plain password
                    $user->setPassword(
                        $passwordEncoder->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );

                    $user->removeForgetPasswordHash();
                    $em->persist($user);
                    $em->flush();

                    return new RedirectResponse($this->generateUrl('index'));
                }
                $form = $form->createView();
            }
            else
            {
                $error = 'Niepoprawny link resetuj??cy!';
            }

            return new Response($twig->render('pages/security/reset-password.html.twig', [
                'reset_form' => $form,
                'error' => $error,
                'success' => $success
            ]));
        }
        else {
            $form = $this->createFormBuilder()
                ->add('email', EmailType::class, [
                    'required' => true,
                    'label' => false
                ])
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $email = $form->get('email')->getData();
                $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
                if ($user) {
                    $user->setForgetPasswordHash();
                    $hash = $user->getForgetPasswordHash();
                    $success = 'Poprawnie wys??ano e-mail z linkiem do zresetowania has??a';
                    $em->persist($user);
                    $em->flush();

                    $resetPasswordUrl = $this->generateUrl('reset_password', ['hash' => $hash], UrlGeneratorInterface::ABSOLUTE_URL);

                    $email = (new TemplatedEmail())
                        ->from((new Address('kmk@ppoz21.pl', 'no-reply | kmk.ppoz21.pl')))
                        ->to((new Address($user->getEmail(), $user->getName() . ' ' . $user->getSurname())))
                        ->subject('Odzywkiwanie has??a | kmk.ppoz21.pl')
                        ->htmlTemplate('mails/reset-password-mail.html.twig')
                        ->context([
                            'resetUrl' => $resetPasswordUrl
                        ])
                    ;

                    try {
                        $mailer->send($email);
                    }
                    catch (TransportExceptionInterface $e)
                    {
                        $success = null;
                        $error = 'Wyst??pi?? b????d podczas wysy??ania wiadomo??ci';
                    }

                } else {
                    $error = 'Nie znaleziono u??ytkownika z takim adresem e-mail!';
                }
            }

            return new Response($twig->render('pages/security/forget-password.html.twig', [
                'forget_form' => $form->createView(),
                'error' => $error,
                'success' => $success
            ]));
        }
    }

    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
