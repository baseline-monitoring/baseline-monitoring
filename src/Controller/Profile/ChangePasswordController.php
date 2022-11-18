<?php

namespace App\Controller\Profile;

use App\Entity\User;
use App\Form\Profile\ChangePasswordFormType;
use App\Repository\Write\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/profile/change-password', name: 'profile_change_password_')]
class ChangePasswordController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, UserManager $userManager, TranslatorInterface $translator): Response
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedException('User not logged in');
        }
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $currentPassword */
            $currentPassword = $form->get('currentPassword')->getData();
            /** @var string $newPassword */
            $newPassword = $form->get('newPassword')->getData();

            // The Symfony method getUser returns an interface which has not predefined every method we use in the following code
            assert($user instanceof User);

            if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('danger', $translator->trans('baseline_monitoring.layout.flash_messages.change_password.password_wrong'));

                return $this->render('profile/change_password.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $userManager->upgradePassword($user, $passwordHasher->hashPassword($user, $newPassword));
            $this->addFlash('success', $translator->trans('baseline_monitoring.layout.flash_messages.change_password.success'));
        }

        return $this->render('profile/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
