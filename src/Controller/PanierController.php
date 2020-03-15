<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\AddprodType;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/", name="panier")
     */
    public function index()
    {
        $PanierRepo= $this->getDoctrine()
            ->getRepository(Panier::class)
            ->findAll();
        $quantotal=0;
        $montotal=0;
        foreach ($PanierRepo as $value){
            $quantotal+= $value->getQuantite();
            $montotal+=$value->getProduit()->getPrix()*$value->getQuantite();
        }

        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'paniers'=>$PanierRepo,
            'montotal'=>$montotal,
            'quantotal'=>$quantotal
        ]);
    }
    /**
     * @Route("/remove/{id}", name="removepanier")
     */
    public function removepanier($id,EntityManagerInterface $entityManager )
    {
        $PanierRepo= $this->getDoctrine()
            ->getRepository(Panier::class)
            ->find($id);
        $entityManager->remove($PanierRepo);
        $entityManager->flush();
        return $this->redirectToRoute('panier');
    }
    /**
     * @Route("/produits", name="produits")
     */
    public function produits(EntityManagerInterface $entityManager, Request $request)
    {
        $produits = new Produit();



        $form = $this->createForm(ProduitType::class, $produits);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $produit= $form->getData();
            $image = $produit->getPhoto();

            $imageName = md5(uniqid()).'.'.$image->guessExtension();

            $image->move($this->getParameter('upload_files'),$imageName);
            $produits ->setPhoto($imageName);

            $entityManager->persist($produit);
            $entityManager->flush();


        }

        $ProduitRepo = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->findAll();

        return $this->render('panier/produits.html.twig', [
            'formprod'=> $form->createView(),
            'produits'=> $ProduitRepo
        ]);

    }
    /**
     * @Route("/produits/{id}", name="produit")
     */
    public function produit($id,EntityManagerInterface $entityManager, Request $request)
    {

      $paniers =new Panier();

        $ProduitRepo = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->find($id);


        $form = $this->createForm(AddprodType::class, $paniers);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $panier= $form->getData();
            $panier->setEtat(false);
            $panier->setProduit($ProduitRepo);


            $entityManager->persist($panier);
            $entityManager->flush();

        }

        return $this->render('panier/produit.html.twig', [
            'formpanier'=> $form->createView(),
            'produit'=> $ProduitRepo
        ]);

    }
    /**
     * @Route("/produits/remove/{id}", name="removeproduit")
     */
    public function removeproduit($id,EntityManagerInterface $entityManager)
    {
        $ProduitRepo = $this->getDoctrine()
            ->getRepository(Produit::class)
            ->find($id);

        $ProduitRepo->deleteFile();

        $entityManager->remove($ProduitRepo);
        $entityManager->flush();

        return $this->redirectToRoute('produits');
    }
}
