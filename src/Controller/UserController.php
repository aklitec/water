<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User\UserFinderType;
use App\Form\User\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UserController extends AbstractController {
	/**
	 * @Route("/", defaults={"page": "1"}, name="users_index", methods="GET")
	 * @Route("/page/{page<[1-9]\d*>}", name="user_index_paginated", methods="GET")
     * @param Request $request
	 * @param int $page
	 * @param UserRepository $repo
	 * @return Response
	 */
	public function index(Request $request, int $page, UserRepository $repo): Response {

        $form = $this->createForm(UserFinderType::class, new User());
        $form->handleRequest($request);

        $getArgs = $request->query->get('user_finder');
        $users = $getArgs ? $repo->findAllMatched($getArgs, $page) : $repo->findLatest($page);

		return $this->render('users/index.html.twig', ['users' => $users, 'form' => $form->createView()]);
	}

	/**
	 * @Route("/new", name="user_new", methods="GET|POST")
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @return Response
	 */
	public function new (Request $request, userPasswordEncoderInterface $passwordEncoder): Response{
		$user = new User();
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
			$user->setPassword($password);
			$user->setEnabled(true);
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
            $this->addFlash('success', 'users.flash.new.success');


            return $this->redirectToRoute('users_index');
		}

		return $this->render('users/new.html.twig', [
			'user' => $user,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}", name="user_show", methods="GET")
	 * @param User $user
	 * @return Response
	 */
	public function show(User $user, UserRepository $user_rep): Response {
		return $this->render('users/show.html.twig', ['user' => $user]);
	}

	/**
	 * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
	 * @param Request $request
	 * @param User $user
	 * @return Response
	 */
	public function edit(Request $request, User $user): Response{
		$form = $this->createForm(UserType::class, $user);
        $form->remove('plainPassword');
        $form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'users.flash.edit.success');

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
		}

		return $this->render('users/edit.html.twig', [
			'user' => $user,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/{id}", name="users_delete", methods="DELETE")
	 * @param User $user
	 * @return Response
	 */

	public function delete(User $user): Response{
		$UserId = $user->getId();
		$em = $this->getDoctrine()->getManager();
		$em->remove($user);
		$em->flush();
        $this->addFlash('success', 'users.flash.delete.success');

        return $this->redirectToRoute('users_index');
	}
}