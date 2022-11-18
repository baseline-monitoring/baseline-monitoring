<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\BaselineConfiguration;
use App\Entity\RemoteServer;
use App\Repository\Read\RemoteServerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaselineConfigurationFormType extends AbstractType
{
    public function __construct(private readonly RemoteServerRepository $remoteServerRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('remoteServer', EntityType::class, [
                'class' => RemoteServer::class,
                'query_builder' => function () {
                    return $this->remoteServerRepository->createQueryBuilder('r')
                        ->orderBy('r.name', 'ASC');
                },
                'choice_label' => 'name',
                'label' => 'baseline_monitoring.baseline.admin.baseline_configuration.edit.label.remote_server',
            ])
            ->add('repositoryUrl', UrlType::class, [
                'label' => 'baseline_monitoring.baseline.admin.baseline_configuration.edit.label.repository_url',
            ])
            ->add('name', TextType::class, [
                'label' => 'baseline_monitoring.baseline.admin.baseline_configuration.edit.label.name',
            ])
            ->add('pathToConfiguration', TextType::class, [
                'label' => 'baseline_monitoring.baseline.admin.baseline_configuration.edit.label.configuration_file_path',
            ])
            ->add('pathToBaseline', TextType::class, [
                'label' => 'baseline_monitoring.baseline.admin.baseline_configuration.edit.label.baseline_file_path',
            ])
            ->add('mainBranch', TextType::class, [
                'label' => 'baseline_monitoring.baseline.admin.baseline_configuration.edit.label.branch',
            ])
            ->add('baselineConfigurationGoals', CollectionType::class, [
                'entry_type' => BaselineConfigurationGoalFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'label' => 'baseline_monitoring.baseline.admin.baseline_configuration.edit.label.goals',
            ])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success',
                ],
                'label' => 'baseline_monitoring.baseline.admin.baseline_configuration.edit.label.save',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BaselineConfiguration::class,
        ]);
    }
}
