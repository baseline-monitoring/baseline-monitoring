<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\BaselineConfiguration;
use App\Form\Admin\BaselineConfigurationFormType;
use App\Repository\Read\BaselineConfigurationRepository;
use App\Repository\Write\BaselineConfigurationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/baseline-configuration', name: 'admin_baseline_configuration_')]
class BaselineConfigurationController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function index(BaselineConfigurationRepository $baselineConfigurationRepository): Response
    {
        return $this->render('admin/baseline_configuration/index.html.twig', [
            'configurations' => $baselineConfigurationRepository->findAll(),
        ]);
    }

    #[Route(path: '/add', name: 'add')]
    public function add(Request $request, BaselineConfigurationManager $baselineConfigurationManager): Response
    {
        $baselineConfiguration = new BaselineConfiguration();
        $form = $this->createForm(BaselineConfigurationFormType::class, $baselineConfiguration);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $baselineConfigurationManager->persist($baselineConfiguration);

            $this->addFlash('success', 'Baseline configuration has been created');

            return $this->redirectToRoute('admin_baseline_configuration_index');
        }

        return $this->renderForm('admin/baseline_configuration/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/edit/{id}', name: 'edit')]
    public function edit(Request $request, BaselineConfiguration $baselineConfiguration, BaselineConfigurationManager $baselineConfigurationManager): Response
    {
        $form = $this->createForm(BaselineConfigurationFormType::class, $baselineConfiguration);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $baselineConfigurationManager->persist($baselineConfiguration);

            $this->addFlash('success', 'Baseline configuration sucessfully changed');

            return $this->redirectToRoute('admin_baseline_configuration_index');
        }

        return $this->renderForm('admin/baseline_configuration/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(BaselineConfiguration $baselineConfiguration, BaselineConfigurationManager $baselineConfigurationManager): RedirectResponse
    {
        $baselineConfigurationManager->remove($baselineConfiguration);

        $this->addFlash('success', 'Baseline configuration "' . $baselineConfiguration->getName() . '" deleted.');

        return $this->redirectToRoute('admin_baseline_configuration_index');
    }
}
