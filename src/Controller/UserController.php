<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/', name: 'read')]
    public function read(EntityManagerInterface $em): Response
    {

        $users = $em->getRepository(User::class)->findAll();

//        $form = $this->createForm(UserType::class, $users);


        return $this->render('user/read.html.twig', [
//            'form' => $form,
            'users' => $users,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $req, EntityManagerInterface $em): Response
    {

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('read');

        }

        return $this->render('user/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function update($id, Request $req, EntityManagerInterface $em): Response
    {

        $user = $em->getRepository(User::class)->find($id);


        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('read');

        }
        return $this->render('user/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id, EntityManagerInterface $em): Response
    {

        $user = $em->getRepository(User::class)->find($id);


        $em->remove($user);
        $em->flush();

        $this->addFlash(
            'notice',
            'Your changes were saved!'
        );

        return $this->redirectToRoute('read');
    }
}


