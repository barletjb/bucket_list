<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WishController extends AbstractController
{
    #[Route('/list', name: 'wish_list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy(['isPublished' => 1], ['dateCreated' => 'DESC']);

        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes,
        ]);
    }

    #[Route('/wish/detail/{id}', name: 'wish_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);

        if (!$wish) {
            throw $this->createNotFoundException('This wish does not exist, sorry!');
        }

        return $this->render('wish/detail.html.twig', [
            'wish' => $wish,
        ]);
    }

    #[Route('/wish/create', name: 'create_wish')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wish = new Wish();
        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash('success', 'Idea successfully added!');
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }


        return $this->render('wish/newWish.html.twig',[
            'new_form' => $form

        ]);
    }

    #[Route('/wish/update/{id}', name: 'update_wish', requirements: ['id' => '\d+'])]
    public function update(Wish $wish, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WishType::class, $wish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Idea successfully updated!');
            return $this->redirectToRoute('wish_detail', ['id' => $wish->getId()]);
        }


        return $this->render('wish/newWish.html.twig',[
            'new_form' => $form
        ]);

    }

    #[Route('/wish/delete/{id}', name: 'delete_wish', requirements: ['id' => '\d+'])]
    public function delete(Wish $wish, EntityManagerInterface $entityManager): Response{

        $entityManager->remove($wish);
        $entityManager->flush();
        $this->addFlash('success', 'Idea successfully deleted!');

        return $this->redirectToRoute('wish_list');
    }






}
