<?php


namespace App\Controller;


use App\Entity\Station;
use App\Entity\ToDoList;
use App\Entity\Train;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MainController extends AbstractController
{

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function mainAction(EntityManagerInterface $em, Environment $twig): Response {
        if (!$this->getUser())
        {
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        $todos = $em->getRepository(ToDoList::class)->findByUser($user, true, ['deadline' => 'ASC']);

        $stations = count($em->getRepository(Station::class)->findAll());
        $trains = count($em->getRepository(Train::class)->findAll());
        $users = count($em->getRepository(User::class)->findAll());

        return new Response($twig->render('pages/homepage/homepage.html.twig', [
            'todos' => $todos,
            'stationsCount' => $stations,
            'trainsCount' => $trains,
            'userCount' => $users
        ]));
    }
}
