<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig', [
        ]);
    }

    #[Route('/aboutus', name: 'main_aboutus')]
    public function aboutus(): Response
    {
        $fichier = file_get_contents(__DIR__.'/../../data/team.json'); // récupération du Json
        $team = json_decode($fichier, true); // transformer le Json en tableau associatif, true pour avoir un tableau associatif et non indexé

        return $this->render(
            'main/aboutus.html.twig',
            compact('team')
        );
    }
}
