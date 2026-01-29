<?php

namespace App\Controller;

use App\Repository\SurvivantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, SurvivantRepository $repo): Response
    {
        $filter = $request->query->get('filter');

        $survivants = match ($filter) {
            'za' => $repo->findByNameDesc(),
            'nain' => $repo->findNains(),
            'elf25' => $repo->findElfPuissanceMin(25),
            'archer_non_humain' => $repo->findArcherNonHumain(),
            default => $repo->findAll(),
        };

        return $this->render('home/index.html.twig', [
            'survivants' => $survivants,
        ]);
    }
}
