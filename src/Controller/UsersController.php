<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

#[Route('/users')]
class UsersController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        $form = $this->createForm(UsersType::class, new Users());

        return $this->render('sign-up.html', [
            'controller_name' => 'UsersController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_users_new', methods: ['POST'])]
    public function new(Request $request, UsersRepository $usersRepository): Response
    {
        $user = new Users();
        //$user->setActive(true);
        //$now = new \DateTime();
        //$user->setCreatedAt($now);


        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request); 

        dump('$form->isSubmitted()', $form->isSubmitted());
        //dump('$form->isValid()', $form->isValid());

        if ($form->isSubmitted() && $form->isValid()) {
            dd('$form->getData()', $form->getData());
            $usersRepository->add($user);
            return $this->redirectToRoute('success_register', [], Response::HTTP_SEE_OTHER);
        } else {
            dd($form->getErrors());
        }

        return $this->render('sign-up.html', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_users_show', methods: ['GET'])]
    public function show(Users $user): Response
    {
        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Users $user, UsersRepository $usersRepository): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersRepository->add($user);
            return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_users_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, UsersRepository $usersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $usersRepository->remove($user);
        }

        return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
    }
}
