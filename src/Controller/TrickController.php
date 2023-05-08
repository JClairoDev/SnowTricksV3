<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Trick;
use App\Form\PhotoType;
use App\Form\TrickType;
use App\Repository\MediaRepository;
use App\Repository\TrickRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/trick')]
class TrickController extends AbstractController
{
    #[Route('/', name: 'app_trick_index', methods: ['GET'])]
    public function index(TrickRepository $trickRepository, MediaRepository $mediaRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
            'medias' => $mediaRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TrickRepository $trickRepository, MediaRepository $mediaRepository, FileUploader $fileUploader): Response
    {

        //$userId=$this->getUser()->getUserIdentifier();

        $trick = new Trick();
        //$formMedia=$this->createForm(PhotoType::class, $media);
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

            //bien faire attention à l'ordre dans lequel on envoi les données dans le code par exemple
            //pour setIdTrick il faut envoyer le $trick dans le Repo avant tout.


            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trick_show', methods: ['GET'])]
    public function show(Trick $trick, MediaRepository $mediaRepository, TrickRepository $trickRepository): Response
    {
        $medias=$mediaRepository->findAll();
        $tricks=$trickRepository->findAll();
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'medias'=>$medias,
            'tricks'=>$tricks
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, TrickRepository $trickRepository): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickRepository->save($trick, true);

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trick_delete', methods: ['POST'])]
    public function delete(Request $request, Trick $trick, TrickRepository $trickRepository, MediaRepository $mediaRepository): Response
    {
            $medias=$mediaRepository->findAll();

            foreach ($medias as $media){
                if ($media->getTrickId()->getId()===$trick->getId()){
                    $mediaRepository->remove($media);
                }
            }


        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {

            $trickRepository->remove($trick, true);
        }

        return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
    }
}
