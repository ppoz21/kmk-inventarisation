<?php


namespace App\Controller;


use App\Entity\ToDoList;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\VarDumper\Dumper\esc;

class TodoController extends AbstractController
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function listAction(int $id = null, string $slug = null): Response
    {
        if (!$id)
        {
            $user = $this->getUser();
            $other = null;
        }
        else
        {
            $user = $this->em->getRepository(User::class)->find($id);
            $other = $user;

            if ($user && $slug != $user->getSlug())
                return new RedirectResponse($this->generateUrl('todo_list_user', ['slug' => $user->getSlug(), 'id' => $id]), 301);
        }
        $todos = $this->em->getRepository(ToDoList::class)->findByUser($user, true, ['deadline' => 'ASC']);
        $done = $this->em->getRepository(ToDoList::class)->findByUser($user, false,  ['deadline' => 'ASC']);
        return $this->render('pages/todo/list.html.twig', [
            'todos' => $todos,
            'done' => $done,
            'other' => $other,
        ]);
    }

    public function changeStatus(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $state = $request->get('state') == 'true';
        $status = 500;


        $todo = $this->em->getRepository(ToDoList::class)->find($id);

        if ($todo)
        {
            $todo->setDone($state);
            $this->em->persist($todo);
            $this->em->flush();
            $status = 200;
        }


        return new JsonResponse('', $status);
    }

    public function hideTodo(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $status = 500;

        $todo = $this->em->getRepository(ToDoList::class)->find($id);

        if ($todo)
        {
            $todo->setDisplay(false);
            $this->em->persist($todo);
            $this->em->flush();
            $status = 200;
        }


        return new JsonResponse('', $status);
    }

    public function adminTasks(): Response
    {

        $tasks = $this->em->getRepository(ToDoList::class)->findBy(['addedByAdmin' => true], ['deadline' => 'DESC']);

        return $this->render('pages/todo/admintask.html.twig', [
            'admintasks' => $tasks
        ]);

    }

}
