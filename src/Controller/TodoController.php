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
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TodoController extends AbstractController
{

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function list(EntityManagerInterface $em, Environment $twig, int $id = null, string $slug = null): Response
    {
        if (!$id)
        {
            $user = $this->getUser();
            $other = null;
        }
        else
        {
            $user = $em->getRepository(User::class)->find($id);
            $other = $user;

            if ($user && $slug != $user->getSlug())
                return new RedirectResponse($this->generateUrl('todo_list_user', ['slug' => $user->getSlug(), 'id' => $id]), 301);
        }
        $todos = $em->getRepository(ToDoList::class)->findByUser($user, true, ['deadline' => 'ASC']);
        $done = $em->getRepository(ToDoList::class)->findByUser($user, false,  ['deadline' => 'ASC']);

        return new Response($twig->render('pages/todo/list.html.twig', [
            'todos' => $todos,
            'done' => $done,
            'other' => $other,
        ]));
    }

    public function changeStatus(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $out = [];
        $id = $request->get('id');
        $state = $request->get('state') == 'true';


        $todo = $em->getRepository(ToDoList::class)->find($id);

        if ($todo)
        {
            try {
                $todo->setDone($state);
                $em->persist($todo);
                $em->flush();
                $out['ok'] = 'ok';
            }
            catch (\Exception $e)
            {
                $out['error'] = 'error';
            }
        }


        return new JsonResponse($out, Response::HTTP_OK);
    }

    public function hideTodo(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $out = [];
        $id = $request->get('id');

        $todo = $em->getRepository(ToDoList::class)->find($id);


        if ($todo)
        {
            try {
                $todo->setDisplay(false);
                $em->persist($todo);
                $em->flush();
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

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function adminTasks(EntityManagerInterface $em, Environment $twig): Response
    {

        $tasks = $em->getRepository(ToDoList::class)->findBy(['addedByAdmin' => true, 'display' => true], ['deadline' => 'ASC']);

        return new Response($twig->render('pages/todo/admin-list.html.twig', [
            'admintasks' => $tasks
        ]));
    }

    public function addTask(Request $request, EntityManagerInterface $em): JsonResponse
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

            $em->persist($task);
            $em->flush();
            $out['ok'] = 'ok';
        }
        catch (\Exception $e)
        {
            $out['error'] = 'Błąd serwera!';
        }

        return new JsonResponse($out, Response::HTTP_OK);
    }

    public function addAdminTask(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $out = [];

        $task = new ToDoList();

        $form = $this->createForm(TodoAdminType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            try {
                $task->setAddedByAdmin(true);
                $em->persist($task);
                $em->flush();
                $out['ok'] = 'ok';
            }
            catch (\Exception $e)
            {
                $out['error'] = 'emerror';
            }
        }

        $out['html'] = $this->renderView('pages/todo/admin-form.html.twig', [
            'form' => $form->createView()
        ]);

        return new JsonResponse($out, Response::HTTP_OK);

    }

}
