<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HabitantRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\AdresseRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Habitant;
use App\Entity\Adresse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HabitantController extends AbstractController
{
    #[Route('/habitant/get/all', name: 'app_habitant_all', methods: ['POST', 'GET'])]
    public function getAllHabitants(HabitantRepository $repository): Response
    {
        $habitants = $repository->findAll();
        $habitantsArray = [];

        foreach ($habitants as $habitant) {
            $habitantsArray[] = [
                'id' => $habitant->getId(),
                'nom' => $habitant->getNom(),
                'prenom' => $habitant->getPrenom(),
                'dateNaissance' => $habitant->getDateNaissance()->format('Y-m-d'),
                'genre' => $habitant->getGenre(),
                'numero_address' => $habitant->getAdresseHabitant()->getNumero(),
                'rue_address' => $habitant->getAdresseHabitant()->getNomRue(),
                'adresse' => $habitant->getAdresseHabitant()->getNumero() . ' ' . $habitant->getAdresseHabitant()->getNomRue()

            ];
        }

        return new JsonResponse($habitantsArray);
    }

    #[Route('/habitant/add', name: 'app_habitant_create', methods: ['POST'])]
    public function addHabitant(Request $request, EntityManagerInterface $entityManager, AdresseRepository $adresseRepository, HabitantRepository $habitantRepository, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);
        

        //


        $numero = strtolower($data['numero_address']);
        $nomRue = strtolower($data['rue_address']);

        if (
            $habitantRepository->findOneBy([
                'Nom' => $data['nom'],
                'Prenom' => $data['prenom'],
                'DateNaissance' => new \DateTime($data['date_naissance']),
            ])
        ) {
            return new JsonResponse(['status' => 'Habitant alredy here'], Response::HTTP_ALREADY_REPORTED);

        }

        // Check if the address already exists
        $adresse = $adresseRepository->findOneBy([
            'numero' => $numero,
            'nomRue' => $nomRue
        ]);

        // If the address does not exist, create a new one
        if (!$adresse) {
            $adresse = new Adresse();
            $adresse->setNumero($numero);
            $adresse->setNomRue($nomRue);
            $entityManager->persist($adresse);
        }

        // Create a new Habitant
        $habitant = new Habitant();
        $habitant->setNom($data['nom']);
        $habitant->setPrenom($data['prenom']);
        $habitant->setDateNaissance(new \DateTime($data['date_naissance']));
        $habitant->setGenre($data['genre']);
        $habitant->setAdresseHabitant($adresse);

        $errors = $validator->validate($habitant);

    
        if (count($errors) > 0) {
            return new JsonResponse(['status' => 'Habitant not valid'], Response::HTTP_NOT_ACCEPTABLE);
        }

        $entityManager->persist($habitant);

        $entityManager->flush();

        return new JsonResponse(['status' => 'Habitant added'], Response::HTTP_CREATED);
    }


    #[Route('/habitant/update/{id}', name: 'app_habitant_update', methods: ['PUT'])]
    public function updateHabitant(int $id, Request $request, EntityManagerInterface $entityManager, HabitantRepository $habitantRepository, AdresseRepository $adresseRepository): Response
    {
        $habitant = $habitantRepository->find($id);

        if (!$habitant) {
            return new JsonResponse(['status' => 'Habitant not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        // Update fields
        $habitant->setNom($data['nom'] ?? $habitant->getNom());
        $habitant->setPrenom($data['prenom'] ?? $habitant->getPrenom());
        if ($data['date_naissance'] != null)
            $habitant->setDateNaissance(new \DateTime($data['date_naissance']) ?? $habitant->getDateNaissance());
        $habitant->setGenre($data['genre'] ?? $habitant->getGenre());

        // Update Adresse if provided
        if (isset($data['numero_address']) && isset($data['rue_address'])) {
            $numero = strtolower($data['numero_address']);
            $nomRue = strtolower($data['rue_address']);

            $adresse = $adresseRepository->findOneBy(['numero' => $numero, 'nomRue' => $nomRue]);

            if (!$adresse) {
                $adresse = new Adresse();
                $adresse->setNumero($numero);
                $adresse->setNomRue($nomRue);
                $entityManager->persist($adresse);
            }

            $habitant->setAdresseHabitant($adresse);
        }

        $entityManager->flush();

        return new JsonResponse(['status' => 'Habitant updated'], Response::HTTP_OK);
    }

    #[Route('/habitant/delete/{id}', name: 'app_habitant_delete', methods: ['DELETE'])]
    public function deleteHabitant(int $id, EntityManagerInterface $entityManager, HabitantRepository $habitantRepository): Response
    {
        $habitant = $habitantRepository->find($id);

        if (!$habitant) {
            return new JsonResponse(['status' => 'Habitant not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($habitant);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Habitant deleted'], Response::HTTP_OK);
    }

    #[Route('/habitant/get/{id}', name: 'app_habitant_get', methods: ['GET'])]
    public function getHabitantById(int $id, HabitantRepository $repository): Response
    {
        $habitant = $repository->find($id);

        if (!$habitant) {
            return new JsonResponse(['status' => 'Habitant not found'], Response::HTTP_NOT_FOUND);
        }

        $habitantData = [
            'id' => $habitant->getId(),
            'nom' => $habitant->getNom(),
            'prenom' => $habitant->getPrenom(),
            'dateNaissance' => $habitant->getDateNaissance()->format('Y-m-d'),
            'genre' => $habitant->getGenre(),
            'numero_address' => $habitant->getAdresseHabitant()->getNumero(),
            'rue_address' => $habitant->getAdresseHabitant()->getNomRue(),

            'adresse' => $habitant->getAdresseHabitant()->getNumero() . ' ' . $habitant->getAdresseHabitant()->getNomRue()
        ];

        return new JsonResponse($habitantData);
    }

    #[Route('/habitant/search', name: 'app_habitant_search', methods: ['GET'])]
    public function searchHabitants(Request $request, HabitantRepository $repository): Response
    {
        $searchTerm = $request->query->get('term');

        $habitants = $repository->searchByTerm($searchTerm);
        $habitantsArray = [];

        foreach ($habitants as $habitant) {
            $habitantsArray[] = [
                'id' => $habitant->getId(),
                'nom' => $habitant->getNom(),
                'prenom' => $habitant->getPrenom(),
                'dateNaissance' => $habitant->getDateNaissance()->format('Y-m-d'),
                'genre' => $habitant->getGenre(),
                'numero_address' => $habitant->getAdresseHabitant()->getNumero(),
                'rue_address' => $habitant->getAdresseHabitant()->getNomRue(),
                'adresse' => $habitant->getAdresseHabitant()->getNumero() . ' ' . $habitant->getAdresseHabitant()->getNomRue()

            ];
        }

        return new JsonResponse($habitantsArray);
    }


}
