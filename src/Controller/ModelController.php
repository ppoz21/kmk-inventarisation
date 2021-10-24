<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Locomotive;
use App\Entity\StationLog;
use App\Entity\Train;
use App\Entity\TrainLog;
use App\Entity\User;
use App\Form\CarType;
use App\Form\LocomotiveType;
use App\Form\ModelAddType;
use App\Repository\CarRepository;
use App\Repository\LocomotiveRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ModelController extends AbstractController
{

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function modelForm(
        EntityManagerInterface $em,
        Environment $twig,
        Request $request,
        LocomotiveRepository $locomotiveRepository,
        CarRepository $carRepository,
        ?string $type = null,
        ?int $id = null
    ): Response
    {
        $form = null;

        /** @var User $user */
        $user = $this->getUser();

        if (!$type)
        {
            $form = $this->createForm(ModelAddType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $type = $form->get('model_type')->getData();

                return new RedirectResponse($this->generateUrl('model_add_type', ['type' => $type]));
            }
        }
        elseif($type == 'lokomotywa')
        {
            $locomotive = null;
            if ($id)
            {
                $locomotive = $locomotiveRepository->find($id);
            }

            if (!$locomotive)
            {
                $locomotive = new Locomotive();
                $train = new Train();
                $locomotive->setTrain($train);
                $log = (new TrainLog())
                    ->setTrain($train)
                    ->setUser($user)
                    ->setDate(new DateTime())
                    ->setContent('Dodano skład')
                ;
                $train->addLog($log);
                $new = true;
                $lastStaion = null;
            }
            else
            {
                $train = $locomotive->getTrain();
                $log = (new TrainLog())
                    ->setTrain($train)
                    ->setUser($user)
                    ->setDate(new DateTime())
                    ->setContent('Edytowano lokomotywę')
                ;
                $train->addLog($log);
                $new = false;
                $lastStaion = $train->getStation();
            }

            $form = $this->createForm(LocomotiveType::class, $locomotive);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $station = $locomotive->getTrain()->getStation();

                if ($new || $station !== $lastStaion)
                {

                    $stationLog = (new StationLog())
                        ->setUser($user)
                        ->setDate((new DateTime()))
                        ->setContent('Dodano skład z lokomotywą ' . $locomotive->getTypeAndNumber())
                        ->setStation($station)
                    ;

                    $station->addLog($stationLog);

                }

                $em->persist($locomotive);
                $em->flush();

                return new RedirectResponse($this->generateUrl('train_details', ['id' => $train->getId(), 'slug' => $train->getSlug()]));
            }

        }
        elseif($type == 'wagon')
        {
            $car = null;

            if ($id)
            {
                $car = $carRepository->find($id);
            }

            if (!$car)
            {
                $car = new Car();
                $new = true;
                $oldTrain = null;
            }
            else
            {
                $new = false;
                $oldTrain = $car->getTrain();
            }

            $form = $this->createForm(CarType::class, $car);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $train = $car->getTrain();

                if ($new)
                {
                    $log = (new TrainLog())
                        ->setUser($user)
                        ->setDate(new DateTime())
                        ->setTrain($train)
                        ->setContent('Dodano wagon ' . $car->getNumber())
                    ;

                    $train->addLog($log);
                }
                if ($train !== $oldTrain and $oldTrain instanceof Train)
                {
                    $log = (new TrainLog())
                        ->setUser($user)
                        ->setDate(new DateTime())
                        ->setTrain($train)
                        ->setContent('Usunięto wagon ' . $car->getNumber())
                    ;

                    $oldTrain->addLog($log);

                    $log2 = (new TrainLog())
                        ->setUser($user)
                        ->setDate(new DateTime())
                        ->setTrain($train)
                        ->setContent('Dodano wagon ' . $car->getNumber())
                    ;

                    $train->addLog($log2);
                }

                $em->persist($car);
                $em->flush();

                return new RedirectResponse($this->generateUrl('train_details', ['id' => $train->getId(), 'slug' => $train->getSlug()]));
            }
        }
        else
        {
            throw new NotFoundHttpException('Nie znaleziono typu modelu do dodania!');
        }

        return new Response($twig->render('pages/model/add.html.twig', [
            'type' => $type,
            'form' => $form?->createView()
        ]));
    }

    public function list(Environment $twig, LocomotiveRepository $locomotiveRepository, CarRepository $carRepository, string $type): Response
    {
        if ($type == 'lokomotywy')
        {
            $models = $locomotiveRepository->findAll();
        }
        elseif ($type == 'wagony')
        {
            $models = $carRepository->findAll();
        }
        else
        {
            throw new NotFoundHttpException('Nie znaleziono typu modelu do wyświetlenia!');
        }

        return new Response($twig->render('pages/model/list.html.twig', [
            'type' => $type,
            'models' => $models
        ]));
    }
}
