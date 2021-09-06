<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class MembersController extends CommonController
{

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function membersList(): Response
    {
        $users = $this->em->getRepository(User::class)->findAll();

        return new Response($this->twig->render('pages/members-list/members-list.html.twig', [
            'members' => $users
        ]));
    }

}
