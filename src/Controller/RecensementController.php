<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HabitantRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\AdresseRepository;
use Symfony\Component\HttpFoundation\Request;

class RecensementController extends AbstractController
{
    #[Route('/recensement ', name: 'app_habitant')]
    #[Route('/ ', name: 'app_habitant_')]
    public function index(): Response
    {
        return $this->render('habitant/index.html.twig', [
        ]);
    }


    #[Route('/recensement/ajout_habitant', name: 'app_habitant_add', methods: ['GET'])]
    public function add_habitant(): Response
    {
        return $this->render('habitant/habitant_add.html.twig', [
        ]);
    }


    #[Route('/recensement/edit/{id}', name: 'app_habitant_edit', methods: ['GET'])]
    public function edit_habitant(int $id): Response
    {
        return $this->render('habitant/habitant_edit.html.twig', [
            'uid_habitant' => $id,
        ]);
    }

    #[Route('/recensement/list', name: 'app_habitant_list', methods: ['GET'])]
    public function list_habitant(): Response
    {
        return $this->render('habitant/habitant_list.html.twig', [
        ]);
    }

    #[Route('/recensement/list/search', name: 'app_habitant_list_search', methods: ['GET'])]
    public function list_search_habitant(Request $request, HabitantRepository $repository): Response
    {        
        
        $searchTerm = $request->query->get('term');

        return $this->render('habitant/habitant_list.html.twig', [
        ]);
    }


    



}
