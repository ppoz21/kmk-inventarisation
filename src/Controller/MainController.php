<?php


namespace App\Controller;


use App\Entity\Station;
use App\Entity\ToDoList;
use App\Entity\Train;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MainController extends CommonController
{

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function mainAction(): Response {
        if (!$this->getUser())
        {
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        $todos = $this->em->getRepository(ToDoList::class)->findByUser($user, true, ['deadline' => 'ASC']);

        $stations = count($this->em->getRepository(Station::class)->findAll());
        $trains = count($this->em->getRepository(Train::class)->findAll());
        $users = count($this->em->getRepository(User::class)->findAll());

        return new Response($this->twig->render('pages/homepage/homepage.html.twig', [
            'todos' => $todos,
            'stationsCount' => $stations,
            'trainsCount' => $trains,
            'userCount' => $users
        ]));
    }
}
