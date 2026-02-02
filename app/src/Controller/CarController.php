<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CarRepository;
use App\Entity\Car;
use App\Form\CarFilterType;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\GarageRepository;

final class CarController extends AbstractController
{
    #[Route('/car', name: 'app_car')]
    public function index(
        CarRepository $carRepository,
        Request $request,
        MailerInterface $mailer,
        GarageRepository $garageRepository
    ): Response {

        $garage = $garageRepository->findOneBy([]);

        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contactData = $contactForm->getData();
            // Création de l'email
            $email = (new Email())
                ->from('noreply@tondomaine.com')
                ->replyTo($contactData['email'])
                ->to('ton-email@domaine.com')
                ->subject('Contact Site : ' . $contactData['sujet'])
                ->html($this->renderView('emails/contact.html.twig', [
                    'nom' => $contactData['nom'],
                    'message' => $contactData['message'],
                    'mail' => $contactData['email']
                ]));

            $mailer->send($email);

            // Notification flash pour l'utilisateur
            $this->addFlash('success', 'Votre message a bien été envoyé !');

            // IMPORTANT : On redirige pour "nettoyer" le formulaire et éviter le renvoi au rafraîchissement
            return $this->redirectToRoute('app_home');
        }

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
            'contactForm' => $contactForm->createView(),
            'garage' => $garage,
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

    #[Route('/mentions-legales', name: 'app_legal')]
    public function legal(GarageRepository $garageRepository): Response
    {
        // On récupère les infos du garage pour les afficher dynamiquement
        $garage = $garageRepository->findOneBy([]);

        return $this->render('legal/mentions_legales.html.twig', [
            'garage' => $garage,
        ]);
    }

    #[Route('/politique-de-confidentialite', name: 'app_privacy')]
    public function privacy(GarageRepository $garageRepository): Response
    {
        return $this->render('legal/privacy.html.twig', [
            'garage' => $garageRepository->findOneBy([]),
        ]);
    }

    #[Route('/politique-cookies', name: 'app_cookies')]
    public function cookies(GarageRepository $garageRepository): Response
    {
        return $this->render('legal/cookies.html.twig', [
            'garage' => $garageRepository->findOneBy([]),
        ]);
    }
}
