<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Exception;
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

        $wishForm = $this->createForm(WishType::class,$wish);
        $wishForm -> handleRequest($request);

        if($wishForm->isSubmitted() && $wishForm->isValid()){

            try{
                $wish ->setDateCreated(new \DateTime());
                $wish ->setIsPublished(true);
                $entityManager ->persist($wish);
                $entityManager ->flush();
                $this -> addFlash('succes','Votre souhait a été enregistré');
                return  $this->redirectToRoute('wish_list');
            }catch (\Exception $exception){
                return $this->redirectToRoute('wish_ajouterWish');
            }
        }
        return $this->render('wish/ajouterWish.html.twig',
        compact('wishForm'));
    }

    #[Route('/delete/{wish}', name: '_deleteWish')]
    public function deleteWish( Wish $wish,
        EntityManagerInterface  $entityManager
    ): Response
    {
        try{
            $entityManager->remove($wish);
            $entityManager->flush();
        }catch(\Exception $exception ){
            $this -> addFlash($exception->getMessage());
            return $this->redirectToRoute('wish_list');
        }


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
