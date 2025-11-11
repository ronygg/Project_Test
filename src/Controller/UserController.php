<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\Password\PasswordNotFoundException;
use App\Exception\Password\PasswordPersistanceException;
use App\Exception\User\UserPersistenceException;
use App\Form\UserPasswordType;
use App\Form\UserType;
use App\Manager\FlashBagMessageManager;
use App\Manager\PasswordManager;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
final class UserController extends AbstractController
{
    const ENTITY = 'Usuario';
    const ENTITIES = 'Usuarios';
    const INDEX_ROUTE = 'app_user_index';

    public function __construct(
        private readonly FlashBagMessageManager $flashBagMessageManager,
        private readonly UserManager $userManager,
        private readonly PasswordManager $passwordManager,
    ) {
    }


    #[Route(name: 'app_user_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig'
            , [
                'users' => $this->userManager->getAllUsers(),
                'entities' => self::ENTITIES,
            ]
        );
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class,
            $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $plainPassord = $form->get('plainPassword')->getData();
                $this->userManager->setUserData($user, $plainPassord);
                $message = $this->flashBagMessageManager->createFlashMessage(
                    'user.created.success'
                );
                $this->addFlash('success', $message);
            } catch (UserPersistenceException $e) {
                $message = $this->flashBagMessageManager->createFlashMessage(
                    'user.created.failure'
                );
                $this->addFlash('error', $message);
            }

            return $this->redirectToRoute(self::INDEX_ROUTE);
        }

        return $this->render('user/insert.html.twig', [
            'action' => 'Nuevo',
            'entity' => self::ENTITY,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $excludeRole = $request->query->getBoolean('exclude', false);
        $form = $this->createForm(UserType::class,
            $user,
            ['include_password' => false, 'exclude_role' => $excludeRole]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->userManager->setUserData($user, null);
                $message = $this->flashBagMessageManager->createFlashMessage(
                    'user.updated.success'
                );
                $this->addFlash('error', $message);

            } catch (UserPersistenceException $e) {
                $message = $this->flashBagMessageManager->createFlashMessage(
                    'user.updated.failure'
                );
                $this->addFlash('error', $message);
            }
            return $this->redirectToRoute(self::INDEX_ROUTE);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'action' => 'Editar',
            'entity' => self::ENTITY,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        try {
            $this->userManager->remove($user);
            $message = $this->flashBagMessageManager
                ->createFlashMessage('user.deleted.success');
            $this->addFlash('success', $message);
        } catch (ForeignKeyConstraintViolationException $e) {
            $message = $this->flashBagMessageManager
                ->createFlashMessage('user.deleted.usedElement');
            $this->addFlash('error', $message);
        } catch (\Exception $e) {
            $message = $this->flashBagMessageManager
                ->createFlashMessage('user.deleted.failure');
            $this->addFlash('error', $message);
        }

        return $this->redirectToRoute(self::INDEX_ROUTE);
    }

    #[Route('/{id}/update_password', name: 'app_password_update', methods: ['GET', 'POST'])]
    public function updatePassword(Request $request, User $user): Response
    {
        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $oldPassword = $form->get('currentPassword')->getData();
                $plainPassword = $form->get('plainPassword')->getData();
                $this->userManager->changePassword(
                    $user,
                    $plainPassword,
                    $oldPassword);
                $message = $this->flashBagMessageManager->createFlashMessage(
                    'password.updated.success',
                );
                $this->addFlash('success', $message);
            } catch (PasswordNotFoundException $e) {
                $message = $this->flashBagMessageManager->createFlashMessage(
                    'password.not_found',
                );
                $this->addFlash('error', $message);
            } catch (PasswordPersistanceException $e) {
                $message = $this->flashBagMessageManager->createFlashMessage(
                    'password.updated.failure'
                );
                $this->addFlash('error', $message);
            }

            return $this->redirectToRoute(self::INDEX_ROUTE);

        }

        return $this->render('password/form.html.twig', [
            'form' => $form,
        ]);

    }
}
