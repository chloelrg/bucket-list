<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wish', name: 'wish')] //prefixe
class WishController extends AbstractController
{
    #[Route('/', name: '_list')]
    public function list( WishRepository $wishRepository): Response
    {
        $whishes = $wishRepository-> findBy(["isPublished"=>true]);
        return $this->render('wish/list.html.twig',
            [
                'whishes'=>$whishes
        ]);
    }

    #[Route('/ajouter', name: '_ajouterWish')]
    public function ajouterWish(
        Request                 $request,
        EntityManagerInterface  $entityManager
    ): Response
    {
        $wish = new Wish();
        $wish ->setDateCreated(new \DateTime());
        $wish ->setIsPublished(true);

        $wishForm = $this->createForm(WishType::class,$wish);
        $wishForm -> handleRequest($request);

        if($wishForm->isSubmitted() && $wishForm->isValid()){
            $entityManager ->persist($wish);
            $entityManager ->flush();
            return  $this->redirectToRoute('wish_list');
        }
        return $this->render('wish/ajouterWish.html.twig',
        compact('wishForm'));
    }

    #[Route('/delete/{wish}', name: '_deleteWish')]
    public function deleteWish( Wish $wish,
        EntityManagerInterface  $entityManager
    ): Response
    {
        $entityManager->remove($wish);
        $entityManager->flush();

        return $this->redirectToRoute('wish_list');
    }


    #[Route('/detail/{wish}',
        name: '_wishdetail',
        requirements: ['wish' => '\d+']
    )]
    public function wishdetail(Wish $wish): Response
    {
        //Ne pas afficher le twig si il y a une erreur
        if(!$wish){
            throw $this->createNotFoundException('Ce wish n\'existe pas');
        }
        return $this->render('wish/wishdetail.html.twig', [
            'wish' => $wish
        ]);
    }
}
