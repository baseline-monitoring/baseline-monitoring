<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\RemoteServer;
use App\Form\Admin\RemoteServerFormType;
use App\Repository\Read\RemoteServerRepository;
use App\Repository\Write\RemoteServerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/remote-server-configuration', name: 'admin_remote_server_configuration_')]
class RemoteServerConfigurationController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function index(RemoteServerRepository $remoteServerRepository): Response
    {
        return $this->render('admin/remote_server_configuration/index.html.twig', [
            'configurations' => $remoteServerRepository->findAll(),
        ]);
    }

    #[Route(path: '/add', name: 'add')]
    public function add(Request $request, RemoteServerManager $remoteServerManager): Response
    {
        $remoteServer = new RemoteServer();
        $form = $this->createForm(RemoteServerFormType::class, $remoteServer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $remoteServerManager->persist($remoteServer);

            $this->addFlash('success', 'Remote server has been created');

            return $this->redirectToRoute('admin_remote_server_configuration_index');
        }

        return $this->renderForm('admin/remote_server_configuration/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/edit/{id}', name: 'edit')]
    public function edit(Request $request, RemoteServer $remoteServer, RemoteServerManager $remoteServerManager): Response
    {
        $form = $this->createForm(RemoteServerFormType::class, $remoteServer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $remoteServerManager->persist($remoteServer);

            $this->addFlash('success', 'Remote server sucessfully changed');

            return $this->redirectToRoute('admin_remote_server_configuration_index');
        }

        return $this->renderForm('admin/remote_server_configuration/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(RemoteServer $remoteServer, RemoteServerManager $remoteServerManager): RedirectResponse
    {
        $remoteServerManager->remove($remoteServer);

        $this->addFlash('success', 'Remote server configuration "' . $remoteServer->getName() . '" deleted.');

        return $this->redirectToRoute('admin_remote_server_configuration_index');
    }
}
