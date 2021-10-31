<?php

namespace App\Controller;

use App\Entity\APIKey;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AdminController extends AbstractController
{

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function addUser(
        Request $request,
        Environment $twig,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response
    {

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user
                ->setForgetPasswordHash()
                ->setApiKey((new APIKey())
                    ->setApiKey(bin2hex(random_bytes(20)))
                    ->setActive(true)
                    ->setUser($user)
                )
                ->setPassword($user->getForgetPasswordHash())
                ->setSlug()
            ;
            $hash = $user->getForgetPasswordHash();

            $newAccountUrl = $this->generateUrl('reset_password', ['hash' => $hash], UrlGeneratorInterface::ABSOLUTE_URL);

            $email = (new TemplatedEmail())
                ->from((new Address('kmk@ppoz21.pl', 'no-reply | kmk.ppoz21.pl')))
                ->to((new Address($user->getEmail(), $user->getName() . ' ' . $user->getSurname())))
                ->subject('Utworzono konto użytkownika | kmk.ppoz21.pl')
                ->htmlTemplate('mails/new-account-mail.html.twig')
                ->context([
                    'resetUrl' => $newAccountUrl
                ])
            ;

            $mailer->send($email);

            $em->persist($user);

            $em->flush();

            return new RedirectResponse($this->generateUrl('members_list'));
        }

        return new Response($twig->render('pages/admin/add-user.html.twig', [
            'form' => $form->createView()
        ]));

    }

}