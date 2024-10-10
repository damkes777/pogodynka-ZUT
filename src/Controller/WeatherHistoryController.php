<?php

namespace App\Controller;

use App\Entity\WeatherHistory;
use App\Form\WeatherHistoryType;
use App\Repository\WeatherHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/weather/history')]
final class WeatherHistoryController extends AbstractController
{
    #[Route(name: 'app_weather_history_index', methods: ['GET'])]
    #[isGranted('ROLE_WEATHER_HISTORY_INDEX')]
    public function index(WeatherHistoryRepository $weatherHistoryRepository): Response
    {
        return $this->render('weather_history/index.html.twig', [
            'weather_histories' => $weatherHistoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_weather_history_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_WEATHER_HISTORY_NEW')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $weatherHistory = new WeatherHistory();
        $form           =
            $this->createForm(WeatherHistoryType::class, $weatherHistory, ['validation_groups' => ['new']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($weatherHistory);
            $entityManager->flush();

            return $this->redirectToRoute('app_weather_history_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weather_history/new.html.twig', [
            'weather_history' => $weatherHistory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_history_show', methods: ['GET'])]
    #[isGranted('ROLE_WEATHER_HISTORY_SHOW')]
    public function show(WeatherHistory $weatherHistory): Response
    {
        return $this->render('weather_history/show.html.twig', [
            'weather_history' => $weatherHistory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_weather_history_edit', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_WEATHER_HISTORY_EDIT')]
    public function edit(
        Request $request,
        WeatherHistory $weatherHistory,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(WeatherHistoryType::class, $weatherHistory, ['validation_groups' => ['edit']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_weather_history_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weather_history/edit.html.twig', [
            'weather_history' => $weatherHistory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_history_delete', methods: ['POST'])]
    #[IsGranted('ROLE_WEATHER_HISTORY_DELETE')]
    public function delete(
        Request $request,
        WeatherHistory $weatherHistory,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $weatherHistory->getId(), $request->getPayload()
                                                                                 ->getString('_token'))) {
            $entityManager->remove($weatherHistory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_weather_history_index', [], Response::HTTP_SEE_OTHER);
    }
}
