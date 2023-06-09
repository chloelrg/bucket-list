<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use App\Services\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/wish', name: 'wish')] // prefixe
class WishController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/', name: '_list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy(['user' => $this->getUser()]);

        // $wishes = $wishRepository->findAllWithCategory();
        return $this->render('wish/list.html.twig',
            compact('wishes'));
    }

    #[Route('/ajouter', name: '_ajouterWish')]
    public function ajouterWish(
        Request $request,
        EntityManagerInterface $entityManager,
        Censurator $censurator
    ): Response {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);

        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            try {
                $wish->setDateCreated(new \DateTime());
                $wish->setIsPublished(true);
                $wish->setUser($this->getUser());

                $wish->setTitle($censurator->purify($wish->getTitle()));
                $wish->setDescription($censurator->purify($wish->getDescription()));

                $entityManager->persist($wish);
                $entityManager->flush();
                $this->addFlash('succes', 'Votre souhait a été enregistré');

                return $this->redirectToRoute('wish_list');
            } catch (\Exception $exception) {
                return $this->redirectToRoute('wish_ajouterWish');
            }
        }

        return $this->render('wish/ajouterWish.html.twig',
            compact('wishForm'));
    }

    #[Route('/delete/{wish}', name: '_deleteWish')]
    public function deleteWish(Wish $wish,
        EntityManagerInterface $entityManager
    ): Response {
        try {
            $entityManager->remove($wish);
            $entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash($exception->getMessage(),'Votre message n\'a pas pu être supprimé');

            return $this->redirectToRoute('wish_list');
        }

        return $this->redirectToRoute('wish_list');
    }

    #[Route('/update/{wish}', name: '_updateWish')]
    public function updateWish(Wish $wish,
                                EntityManagerInterface $entityManager,
                                Request $request
    ): Response {
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $entityManager->persist($wish);
            $entityManager->flush();
        }

        return $this->render('wish/updateWish.html.twig',
            compact('wishForm'));
    }

    #[Route('/detail/{wish}',
        name: '_wishdetail',
        requirements: ['wish' => '\d+']
    )]
    public function wishdetail(Wish $wish): Response
    {
        // Ne pas afficher le twig si il y a une erreur
        if (!$wish) {
            throw $this->createNotFoundException('Ce wish n\'existe pas');
        }

        return $this->render('wish/wishdetail.html.twig', [
            'wish' => $wish,
        ]);
    }
}
