<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\RemoteServer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemoteServerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'baseline_monitoring.baseline.admin.remote_server_configuration.edit.label.name',
            ])
            ->add('host', TextType::class, [
                'label' => 'baseline_monitoring.baseline.admin.remote_server_configuration.edit.label.host',
            ])
            ->add('privateKey', TextareaType::class, [
                'label' => 'baseline_monitoring.baseline.admin.remote_server_configuration.edit.label.private_key',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'baseline_monitoring.baseline.admin.remote_server_configuration.edit.label.save',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RemoteServer::class,
        ]);
    }
}
