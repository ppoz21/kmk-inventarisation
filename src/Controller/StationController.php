<?php

namespace App\Controller;

use App\Entity\Station;
use App\Entity\StationLog;
use App\Entity\User;
use App\Form\AddStationFormType;
use App\Repository\APIKeyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StationController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function listAction(): Response
    {
        $station = new Station();
        $addForm = $this->createForm(AddStationFormType::class, $station);
        $stations = $this->em->getRepository(Station::class)->findAll();
        return $this->render('pages/station/list.html.twig', [
            'stations' => $stations,
            'addForm' => $addForm->createView()
        ]);
    }

    public function detailsAction(int $id, string $slug): Response
    {
        $station = $this->em->getRepository(Station::class)->find($id);
        if ($station)
        {
            if ($slug == $station->getSlug())
            {
                return $this->render('pages/station/details.html.twig', [
                    'station' => $station
                ]);
            }
            else
            {
                $slug = $station->getSlug();
                return new RedirectResponse($this->generateUrl('station_details', ['id'=> $id, 'slug'=>$slug]), 301);
            }
        }
        else
        {
            throw new NotFoundHttpException();
        }
    }

    public function ajaxCreateFormAction(Request $request): Response
    {
        $id = $request->get('id');
        if ($id)
        {
            $station = $this->em->getRepository(Station::class)->find($id);
            if (!$station)
            {
                $station = new Station();
            }
        }
        else
        {
            $station = new Station();
        }

        $addForm = $this->createForm(AddStationFormType::class, $station);

        return $this->render('pages/station/add-edit-form.html.twig', [
            'addForm' => $addForm->createView()
        ]);
    }

    public function ajaxAddStationAction(Request $request, APIKeyRepository $keyRepository): JsonResponse
    {
        $name = $request->get('name');
        $description = $request->get('description');
        $users = $request->get('users');
        $apiKey = $request->get('api_key');
        $id = $request->get('id');
        $response = [
            'errors' => [],
            'status' => null
        ];
        $new = false;

        $validKey = $keyRepository->findOneBy(['apiKey' => $apiKey, 'active' => true]);
        if (! $validKey)
        {
            array_push($response['errors'], 'Nieaktywny klucz API lub nie znaleziono takiego klucza!');
            $response['status'] = 'error';
        }
        else
        {
            if ($name && $description)
            {
                if ($id)
                {
                    $station = $this->em->getRepository(Station::class)->find($id);
                    if (!$station)
                    {
                        $station = new Station();
                        $new = true;
                    }
                }
                else
                {
                    $station = new Station();
                    $new = true;
                }
                $station->setName($name);
                $station->setDescription($description);
                if ($users)
                {
                    $forLogUsers = "Dodano użytkowników: ";
                    foreach ($users as $user)
                    {
                        $tempUser = $this->em->getRepository(User::class)->find($user);
                        $station->addUser($tempUser);
                        $forLogUsers .= $tempUser . ' ';
                    }
                }
                try {
                    $user = $validKey->getUser();
                    $log = new StationLog();
                    if ($new)
                    {
                        $msg = "Utworzono stację " . $station->getName() . ". ";
                    }
                    else
                    {
                        $msg = "Edytowano " . $station->getName() . ". ";
                    }
                    if (isset($forLogUsers))
                    {
                        $msg .= $forLogUsers;
                    }

                    $this->em->persist($station);
                    $this->em->flush();
                    try {
                        $log->setDate(new \DateTime());
                        $log->setStation($station);
                        $log->setUser($user);
                        $log->setContent($msg);
                        $this->em->persist($log);
                        $this->em->flush();
                    }
                    catch (\Exception $e){}

                    $response['status'] = 'success';
                }
                catch (\Exception $e)
                {
                    $response['status'] = 'error';
                    array_push($response['errors'], $e);
                }
            }
            else
            {
                array_push($response['errors'], 'Nazwa stacji i opis nie mogą być puste!');
                $response['status'] = 'error';
            }
        }

        return new JsonResponse($response, 200);
    }
}
