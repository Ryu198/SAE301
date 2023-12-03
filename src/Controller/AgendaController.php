<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgendaController extends AbstractController
{
    #[Route('/agenda', name: 'app_agenda')]
    public function index(): Response
    {
        $elements = [

            ['code' => 'WR310', 'titre' => 'Culture artistique', 'type' => 'Rendu', 'date_debut' => '10/09/2023', 'date_rendu' => '06/10/2023 23:59'],
            ['code' => 'WR303', 'titre' => 'UX Design d\'expérience', 'type' => 'Exercice', 'date_debut' => '20/09/2023', 'date_rendu' => '07/10/2023 23:59'],
            ['code' => 'WR309', 'titre' => 'UI Création et design', 'type' => 'Rendu', 'date_debut' => '15/09/2023', 'date_rendu' => '08/10/2023 23:59'],
            ['code' => 'WR312', 'titre' => 'Intégration web', 'type' => 'Partiel', 'date_debut' => '09/10/2023', 'date_rendu' => '09/10/2023 15:00'],
        ];

        return $this->render('agenda/index.html.twig', [
            'elements' => $elements,
        ]);
    }
}
