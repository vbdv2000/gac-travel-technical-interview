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

        return $this->render('sign-up.html.twig', [
            'controller_name' => 'UsersController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_users_new', methods: ['POST'])]
    public function new(Request $request, UsersRepository $usersRepository): Response
    {
        $user = new Users();

        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request); 

        dump('$form->isSubmitted()', $form->isSubmitted());
        dump('$form->isValid()', $form->isValid());

        if ($form->isSubmitted() && $form->isValid()) {
            //dd('$form->getData()', $form->getData());        
            $usersRepository->add($user);
            return $this->redirectToRoute('success_register', [], Response::HTTP_SEE_OTHER);
        } else {
            dd($form->getErrors());
        }

        return $this->render('sign-up.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    public function show(): Response
    {
        $users = $this->getDoctrine()->getRepository(Users::class)->findAll();
        return $this->render('users.html.twig', [
            'users' => $users,
        ]);
    }

    public function update(Request $request, $id) {

        $user = $this->getDoctrine()->getRepository(Users::class);
        $user = $user->find($id);

        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $user = $form->getData();
            //$user->setActive($form->get('active')->getData());
            $user->setPassword($form->get('password')->getData());
            $em->persist($user);
            $em->flush();

            //$this->addFlash('alert','Usuario modificado con éxito');

            return $this->redirectToRoute('users');
        }

        return $this->render('edit-user.html.twig',[
            'form' => $form->createView(),
            'user' => $user,
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
