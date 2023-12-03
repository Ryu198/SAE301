<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RechercheController extends AbstractController
{
    #[Route('/recherche/resultat', name: 'recherche_resultat', methods: ['GET'])]
    public function resultat(Request $request): Response
    {
        $elements = [

            ['code' => 'WR310', 'titre' => 'Culture artistique', 'type' => 'Rendu', 'date_debut' => '10/09/2023', 'date_rendu' => '06/10/2023 23:59'],
            ['code' => 'WR303', 'titre' => 'UX Design d\'expérience', 'type' => 'Exercice', 'date_debut' => '20/09/2023', 'date_rendu' => '07/10/2023 23:59'],
            ['code' => 'WR309', 'titre' => 'UI Création et design', 'type' => 'Rendu', 'date_debut' => '15/09/2023', 'date_rendu' => '08/10/2023 23:59'],
            ['code' => 'WR312', 'titre' => 'Intégration web', 'type' => 'Partiel', 'date_debut' => '25/09/2023', 'date_rendu' => '08/10/2023 23:59'],
            ['code' => 'WR314', 'titre' => 'Projet de fin d\'études', 'type' => 'SAE', 'date_debut' => '01/09/2023', 'date_rendu' => '15/11/2023 23:59'],
            ['code' => 'WR302', 'titre' => 'Analyse de données', 'type' => 'Rendu', 'date_debut' => '10/09/2023', 'date_rendu' => '20/10/2023 23:59'],
            ['code' => 'WR305', 'titre' => 'Design graphique', 'type' => 'Exercice', 'date_debut' => '12/09/2023', 'date_rendu' => '30/09/2023 23:59'],
            ['code' => 'WR307', 'titre' => 'Sécurité informatique', 'type' => 'Rendu', 'date_debut' => '18/09/2023', 'date_rendu' => '25/10/2023 23:59'],
            ['code' => 'WR308', 'titre' => 'Algorithmique avancée', 'type' => 'Partiel', 'date_debut' => '20/09/2023', 'date_rendu' => '20/11/2023 23:59'],
            ['code' => 'WR311', 'titre' => 'Réseaux et télécommunications', 'type' => 'Exercice', 'date_debut' => '22/09/2023', 'date_rendu' => '15/10/2023 23:59'],
            ['code' => 'WR327', 'titre' => 'Développement mobile', 'type' => 'SAE', 'date_debut' => '25/09/2023', 'date_rendu' => '05/11/2023 23:59'],
            ['code' => 'WR326', 'titre' => 'Gestion de projet', 'type' => 'Rendu', 'date_debut' => '28/09/2023', 'date_rendu' => '12/11/2023 23:59'],
            ['code' => 'WR325', 'titre' => 'Intelligence artificielle', 'type' => 'Partiel', 'date_debut' => '01/10/2023', 'date_rendu' => '22/11/2023 23:59'],
            ['code' => 'WR324', 'titre' => 'Programmation fonctionnelle', 'type' => 'Exercice', 'date_debut' => '05/10/2023', 'date_rendu' => '25/10/2023 23:59'],
            ['code' => 'WR323', 'titre' => 'Conception de base de données', 'type' => 'Rendu', 'date_debut' => '10/10/2023', 'date_rendu' => '30/10/2023 23:59'],
            ['code' => 'WR322', 'titre' => 'Développement Front-End', 'type' => 'SAE', 'date_debut' => '15/10/2023', 'date_rendu' => '05/12/2023 23:59'],
            ['code' => 'WR321', 'titre' => 'Cloud computing', 'type' => 'Partiel', 'date_debut' => '20/10/2023', 'date_rendu' => '10/11/2023 23:59'],
            ['code' => 'WR319', 'titre' => 'Programmation orientée objet', 'type' => 'Exercice', 'date_debut' => '22/10/2023', 'date_rendu' => '15/11/2023 23:59'],
            ['code' => 'WR320', 'titre' => 'Machine learning', 'type' => 'Rendu', 'date_debut' => '25/10/2023', 'date_rendu' => '10/12/2023 23:59'],
        ];

        $rechercheTexte = $request->query->get('searchText', '');
        $typesRendu = $request->query->all()['type'] ?? [];

        if (!empty($rechercheTexte)) {
            $elements = array_filter($elements, function ($element) use ($rechercheTexte) {
                return stripos($element['titre'], $rechercheTexte) !== false ||
                    stripos($element['code'], $rechercheTexte) !== false;
            });
        }

        if (!empty($typesRendu)) {
            $elements = array_filter($elements, function ($element) use ($typesRendu) {
                return in_array($element['type'], $typesRendu);
            });
        }

        usort($elements, function ($a, $b) {
            $renduA = \DateTime::createFromFormat('d/m/Y H:i', $a['date_rendu']);
            $renduB = \DateTime::createFromFormat('d/m/Y H:i', $b['date_rendu']);
            if (!$renduA || !$renduB) {
                throw new \Exception('Format de date non valide pour la comparaison');
            }
            return $renduA <=> $renduB;
        });


        return $this->render('recherche/index.html.twig', [
            'elements' => $elements,
        ]);
    }
}
