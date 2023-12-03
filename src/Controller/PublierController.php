<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

class PublierController extends AbstractController
{
    #[Route('/publier', name: 'app_publier')]
    #[Route('/publier', name: 'app_publier', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $data = [
                'assignment_type' => $request->request->get('assignment_type'),
                'subject' => $request->request->get('subject'),
                'title' => $request->request->get('title'),
                'start_date' => $request->request->get('start_date'),
                'due_date' => $request->request->get('due_date'),
                'due_time' => $request->request->get('due_time'),
                'description' => $request->request->get('description'),
                'file_paths' => [], // Pour stocker les chemins des fichiers téléchargés
            ];

            // Gestion des fichiers téléchargés
            $files = $request->files->get('file_upload');
            $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/telechargement/';
            if ($files) {
                foreach ($files as $file) {
                    if ($file instanceof UploadedFile && $file->isValid()) {
                        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                        try {
                            $file->move($uploadDirectory, $newFilename);
                            $data['file_paths'][] = 'telechargement/' . $newFilename;
                        } catch (FileException $e) {
                            $this->addFlash('error', 'Erreur lors de l\'enregistrement du fichier : '.$e->getMessage());
                        }
                    }
                }
            }

            // Création d'un nom de fichier unique pour chaque soumission
            $uniqueFileName = 'formulaire_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.txt';
            $filePath = $this->getParameter('kernel.project_dir') . '/public/info/' . $uniqueFileName;

            // Conversion des données en chaîne JSON et écriture dans le fichier
            $jsonData = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents($filePath, $jsonData . PHP_EOL);

            // Ajout d'un message flash
            $this->addFlash('success', 'Devoir publié avec succès !');

            // Préparation d'une réponse avec redirection après 3 secondes
            $response = new Response();
            $response->headers->set('Refresh', '0; url='.$this->generateUrl('app_publier'));
            return $response;
        }

        return $this->render('publier/index.html.twig');
    }
}
