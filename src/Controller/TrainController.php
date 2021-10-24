<?php

namespace App\Controller;

use App\Entity\Station;
use App\Entity\Train;
use App\Repository\StationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TrainController extends AbstractController
{

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function landing(StationRepository $stationRepository, Environment $twig): Response
    {
        if (count($stations = $this->getUser()->getStations()) == 1)
        {
            $id = $stations->first()->getId();
            $slug = $stations->first()->getSlug();
            return new RedirectResponse($this->generateUrl('train_on_station', ['id'=> $id, 'slug'=>$slug]), 301);
        }
        else
        {
            $stations = $stationRepository->findAll();
        }

        return new Response($twig->render('pages/train/landing.html.twig', [
            'stations' => $stations,
        ]));
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function station(EntityManagerInterface $em, Environment $twig ,$id = 0, $slug = null): Response
    {
        $station = $em->getRepository(Station::class)->find($id);
        if ($station)
        {
            if ($slug == $station->getSlug())
            {
                return new Response($twig->render('pages/train/on-station.html.twig', [
                    'station' => $station,
                ]));
            }
            else
            {
                $slug = $station->getSlug();
                return new RedirectResponse($this->generateUrl('train_on_station', ['id'=> $id, 'slug'=>$slug]), 301);
            }
        }
        else
        {
            throw new NotFoundHttpException("Nie znaleziono stacji!");
        }
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function details(EntityManagerInterface $em, Environment $twig, $id = 0, $slug = null): Response
    {
        $train = $em->getRepository(Train::class)->find($id);
        if ($train)
        {
            if ($slug == $train->getSlug())
            {
                return new Response($twig->render('pages/train/details.html.twig', [
                    'train' => $train,
                ]));
            }
            else
            {
                $slug = $train->getSlug();
                return new RedirectResponse($this->generateUrl('train_details', ['id' => $id, 'slug' => $slug]));
            }
        }
        else
        {
            throw new NotFoundHttpException("Nie znaleziono składu!");
        }
    }
}
