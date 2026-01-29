<?php

namespace App\Controller;

use App\Repository\SurvivantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FiltreSurvivantController extends AbstractController
{
    #[Route('/filtre/survivant', name: 'app_filtre_survivant')]
    public function index(Request $request, SurvivantRepository $repo): Response
    {
        $puissance = $request->query->getInt('puissance');
        $race = $request->query->get('race');
        $classe = $request->query->get('classe');

        $survivants = $repo->filterByForm(
            $puissance ?: null,
            $race,
            $classe
        );

        return $this->render('filtre_survivant/filtreSurvivant.html.twig', [
            'survivants' => $survivants,
        ]);
    }
}
