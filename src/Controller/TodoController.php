<?php


namespace App\Controller;


use App\Entity\ToDoList;
use App\Entity\User;
use App\Form\TodoAdminType;
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
        return $this->render('pages/todo-list/todo-list.html.twig', [
            'todos' => $todos,
            'done' => $done,
            'other' => $other,
        ]);
    }

    public function changeStatus(Request $request): JsonResponse
    {
        $out = [];
        $id = $request->get('id');
        $state = $request->get('state') == 'true';


        $todo = $this->em->getRepository(ToDoList::class)->find($id);

        if ($todo)
        {
            try {
                $todo->setDone($state);
                $this->em->persist($todo);
                $this->em->flush();
                $out['ok'] = 'ok';
            }
            catch (\Exception $e)
            {
                $out['error'] = 'error';
            }
        }


        return new JsonResponse($out, Response::HTTP_OK);
    }

    public function hideTodo(Request $request): JsonResponse
    {
        $out = [];
        $id = $request->get('id');

        $todo = $this->em->getRepository(ToDoList::class)->find($id);


        if ($todo)
        {
            try {
                $todo->setDisplay(false);
                $this->em->persist($todo);
                $this->em->flush();
                $out['ok'] = 'ok';
            }
            catch (\Exception $e)
            {
                $out['error'] = 'emerror';
            }

        }
        else
        {
            $out['error'] = 'noobj';
        }


        return new JsonResponse($out, Response::HTTP_OK);
    }

    public function adminTasks(): Response
    {

        $tasks = $this->em->getRepository(ToDoList::class)->findBy(['addedByAdmin' => true, 'display' => true], ['deadline' => 'ASC']);

        return $this->render('parts/todo-admin-list/todo-admin-list.html.twig', [
            'admintasks' => $tasks
        ]);

    }

    public function addTask(Request $request): JsonResponse
    {
        $out = [];
        $name = $request->get('name');
        $description = $request->get('description');
        $deadline = $request->get('deadline');

        /** @var User $user */
        $user = $this->getUser();

        if ($deadline)
        {
            $deadlineObj = \DateTime::createFromFormat('Y-m-d', $deadline);
        }
        else
        {
            $deadlineObj = null;
        }

        $task = new ToDoList();

        $descriptoion = trim($description);

        try {
            $task->setTitle($name);
            $task->setDescription(strlen($descriptoion) != 0 ? $descriptoion : null);
            $task->setDeadline($deadlineObj);
            $task->addUser($user);

            $this->em->persist($task);
            $this->em->flush();
            $out['ok'] = 'ok';
        }
        catch (\Exception $e)
        {
            $out['error'] = 'Błąd serwera!';
        }

        return new JsonResponse($out, Response::HTTP_OK);
    }

    public function addAdminTask(Request $request): JsonResponse
    {
        $out = [];

        $task = new ToDoList();

        $form = $this->createForm(TodoAdminType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            try {
                $task->setAddedByAdmin(true);
                $this->em->persist($task);
                $this->em->flush();
                $out['ok'] = 'ok';
            }
            catch (\Exception $e)
            {
                $out['error'] = 'emerror';
            }
        }

        $out['html'] = $this->renderView('parts/todo-admin-form/todo-admin-form.html.twig', [
            'form' => $form->createView()
        ]);

        return new JsonResponse($out, Response::HTTP_OK);

    }

}
