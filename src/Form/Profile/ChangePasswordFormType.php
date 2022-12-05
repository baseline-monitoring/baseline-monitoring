<?php

declare(strict_types=1);

namespace App\Form\Profile;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'baseline_monitoring.user_management.change_password.current_password',
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'baseline_monitoring.user_management.change_password.current_password'],
                'second_options' => ['label' => 'baseline_monitoring.user_management.change_password.repeat_password'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'baseline_monitoring.user_management.change_password.action',
            ])
        ;
    }
}
