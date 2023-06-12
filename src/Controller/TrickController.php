<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Entity\Media;
use App\Entity\Trick;
use App\Form\CommentaryType;
use App\Form\TrickType;
use App\Repository\CommentaryRepository;
use App\Repository\MediaRepository;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/trick')]
class TrickController extends AbstractController
{
    #[Route('/', name: 'app_trick_index', methods: ['GET'])]
    public function index(TrickRepository $trickRepository, MediaRepository $mediaRepository, UserRepository $userRepository): Response
    {
        //Envoi des données constituant la vue de l'index

        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
            'users'=> $userRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TrickRepository $trickRepository, MediaRepository $mediaRepository, UserRepository $userRepository, FileUploader $fileUploader): Response
    {
        // Récupération des Users
        $users = $userRepository->findAll();

        // L'utilisateur est-il connecté ?
            if ($this->isGranted('ROLE_USER')) {
                $trick = new Trick();

                $form = $this->createForm(TrickType::class, $trick);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    $trick->setUserId($this->getUser());
                    $trickRepository->save($trick, true);

                    $video=$form->get('video')->getData();
                    $photos=$form->get('media')->getData();

                    //utilisation du service créé FileUploader pour l'enregistrement des medias

                    foreach ($photos as $photo){
                        if ($photo) {
                            $media=new Media();
                            $fileName = $fileUploader->upload($photo);
                            $media->setPictures($fileName);
                            $media->setVideo($video);
                            $media->setTrickId($trick);
                            $mediaRepository->save($media, true);
                        } else if ($form->get('removeImage')->getData()) {
                            $media=new Media();
                            unlink($this->getParameter('uploads_path') . '/' . $media->getPictures());
                            $media->setPictures(null);
                        }
                    }

                    $this->addFlash('success', 'Ton trick à été correctement ajouté!');

                    //Faire attention à l'ordre dans lequel on envoie les données dans le code par exemple
                    //pour setIdTrick il faut envoyer le $trick dans le Repo avant tout.

                    return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
                }
                return $this->render('trick/new.html.twig', [
                    'trick' => $trick,
                    'form' => $form,
                    'users'=>$users
                ]);
            }else{
                return $this->redirectToRoute('app_login');
            }

    }

    #[Route('/{slug}', name: 'app_trick_show', methods: ['GET','POST'])]
    public function show(Trick $trick, MediaRepository $mediaRepository, TrickRepository $trickRepository,CommentaryRepository $commentaryRepository, Request $request): Response
    {
        $medias=$mediaRepository->findAll();
        $tricks=$trickRepository->findAll();
        $commentaries=$commentaryRepository->findAll();

        //Gestion de la partie commentaires

        //On instancie le nouveau commentaire
        $comment = new Commentary();

        //On instancie la date
        $date = new \DateTime();

        //attention de bien configurer la méthode 'POST' avant de soumettre un formulaire
        //on génère le commentaire

        $commentaryForm = $this->createForm(CommentaryType::class, $comment);
        $commentaryForm->handleRequest($request);
        $commentary=$commentaryForm->get('commentary')->getData();

        //traitement du formulaire

        if ($commentaryForm->isSubmitted() && $commentaryForm->isValid()){
            $comment->setCommentary($commentary);
            $comment->setDate($date);
            $comment->setTrickId($trick);
            $comment->setUserId($trick->getUserId());
            $commentaryRepository->save($comment,true);

            //on redirige sur la même page avec l'id pour le rafraichissement du commentaire
            return $this->redirectToRoute('app_trick_show', array('slug'=>$trick->getSlug()));

        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'medias'=>$medias,
            'tricks'=>$tricks,
            'commentaries'=>$commentaries,
            'commentaryForm'=>$commentaryForm->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, TrickRepository $trickRepository, MediaRepository $mediaRepository, FileUploader $fileUploader): Response
    {
        //Récupération des médias associés au trick
        $actualMedias= $mediaRepository->findBy(['trickId'=>$trick->getId()]);

        // création du formulaire
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        //Récupération des données du formulaire
        $video=$form->get('video')->getData();
        $photos=$form->get('media')->getData();

        //validation du formulaire et sauvegarde du trick et des médias + fileUpload
        if ($form->isSubmitted() && $form->isValid()) {

            //sauvegarde de la modification du trick
            $trickRepository->save($trick, true);

            //Boucle de suppression des médias attachés
            foreach($actualMedias as $media){
                $mediaRepository->remove($media);
            }

            //Set up des données de l'entité média
            foreach ($photos as $photo){
                if ($photo) {
                    $media=new Media();
                    $fileName = $fileUploader->upload($photo);
                    $media->setPictures($fileName);
                    $media->setVideo($video);
                    $media->setTrickId($trick);
                    $mediaRepository->save($media, true);
                } else if ($form->get('removeImage')->getData()) {
                    $media=new Media();
                    unlink($this->getParameter('uploads_path') . '/' . $media->getPictures());
                    $media->setPictures(null);
                }
            }

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        //Affichage du formulaire (création de la vue)
        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_trick_delete', methods: ['POST'])]
    public function delete(Request $request, Trick $trick, TrickRepository $trickRepository, MediaRepository $mediaRepository, CommentaryRepository $commentaryRepository): Response
    {
        //Suppression des commentaires associés au trick

        $comments=$commentaryRepository->findAll();
        foreach ($comments as $comment){
            if ($comment->getTrickId()->getId()===$trick->getId()){
                $commentaryRepository->remove($comment);
            }
        }

        //Suppression des medias associés au trick

        $medias=$mediaRepository->findAll();
        foreach ($medias as $media){
            if ($media->getTrickId()->getId()===$trick->getId()){
                $mediaRepository->remove($media);
            }
        }

        //Suppression du trick avec protection Token CSRF

        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {

            $trickRepository->remove($trick, true);
        }
        return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
    }
}
