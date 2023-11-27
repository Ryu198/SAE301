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
            $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/telechargement';
            if ($files) {
                foreach ($files as $file) {
                    if ($file instanceof UploadedFile && $file->isValid()) {
                        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                        try {
                            $file->move($uploadDirectory, $newFilename);
                            $data['file_paths'][] = '/telechargement' . $newFilename;
                        } catch (FileException $e) {
                            $this->addFlash('error', 'Erreur lors de l\'enregistrement du fichier : '.$e->getMessage());
                            // Si vous souhaitez arrêter l'exécution ici, décommentez la ligne suivante:
                            // return $this->redirectToRoute('app_publier');
                        }
                    }
                }
            }

            // Convertissez les données en chaîne JSON et écrivez-les dans un fichier
            $jsonData = json_encode($data, JSON_PRETTY_PRINT);
            $filePath = $this->getParameter('kernel.project_dir') . '/public/info/formulaire.txt';
            file_put_contents($filePath, $jsonData . PHP_EOL, FILE_APPEND);

            // Ajoutez un message flash
            $this->addFlash('success', 'Devoir publié avec succès !');

            // Préparez une réponse qui inclut une métadonnée Refresh pour la redirection
            $response = new Response();
            $response->headers->set('Refresh', '5; url='.$this->generateUrl('app_profil'));
            return $response;
        }

        return $this->render('publier/index.html.twig');
    }
}
