<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CarRepository;
use App\Entity\Car;
use App\Form\CarFilterType;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CarController extends AbstractController
{
    #[Route('/car', name: 'app_car')]
    public function index(CarRepository $carRepository, Request $request): Response
    {
        $form = $this->createForm(CarFilterType::class);
        $form->handleRequest($request);

        $filterData = $form->getData();

        $cars = $carRepository->findByFilters($filterData);

        if ($request->isXmlHttpRequest()) {
            return $this->render('car/_list_results.html.twig', [
                'cars' => $cars,
            ]);
        }
        return $this->render('car/index.html.twig', [
            'controller_name' => 'CarController',
            'cars' => $carRepository->findAll(),
            'filterForm' => $form->createView(),
        ]);
    }

    #[Route('/car/{id}', name: 'app_car_show')]
    public function show(Car $car): Response
    {
        return $this->render('car/show.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/api/models-by-brand/{brand}', name: 'api_models_by_brand', methods: ['GET'])]
    public function getModelsByBrand(string $brand, CarRepository $carRepository): JsonResponse
    {
        $models = $carRepository->findModelsByBrand($brand);
        $cars = $carRepository->findBy(['brand' => $brand]);
        $html = $this->renderView('car/_list_results.html.twig', [
            'cars' => $cars,
        ]);
        return new JsonResponse([
            'models' => $models,
            'html'   => $html
        ]);
    }
}
