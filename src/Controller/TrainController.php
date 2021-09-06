<?php

namespace App\Controller;

use App\Entity\Station;
use App\Entity\Train;
use App\Repository\StationRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TrainController extends CommonController
{

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function landingAction(StationRepository $stationRepository): Response
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

        return new Response($this->twig->render('pages/train-landing/train-landing.html.twig', [
            'stations' => $stations,
        ]));
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function stationAction($id = 0, $slug = null): Response
    {
        $station = $this->em->getRepository(Station::class)->find($id);
        if ($station)
        {
            if ($slug == $station->getSlug())
            {
                return new Response($this->twig->render('pages/train-on-station/train-on-station.html.twig', [
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
    public function detailsAction($id = 0, $slug = null): Response
    {
        $train = $this->em->getRepository(Train::class)->find($id);
        if ($train)
        {
            if ($slug == $train->getSlug())
            {
                return new Response($this->twig->render('pages/train-details/train-details.html.twig', [
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
