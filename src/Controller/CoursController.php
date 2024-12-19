<?php

namespace App\Controller\Admin;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/cours")
 */
class CoursController extends AbstractController
{
    /**
     * @Route("/", name="admin_cours_index", methods={"GET"})
     */
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('admin/cours/index.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_cours_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $cours = new Cours();
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cours);
            $entityManager->flush();

            return $this->redirectToRoute('admin_cours_index');
        }

        return $this->render('admin/cours/new.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_cours_show", methods={"GET"})
     */
    public function show(Cours $cours): Response
    {
        return $this->render('admin/cours/show.html.twig', [
            'cours' => $cours,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_cours_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Cours $cours): Response
    {
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_cours_index');
        }

        return $this->render('admin/cours/edit.html.twig', [
            'cours' => $cours,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_cours_delete", methods={"POST"})
     */
    public function delete(Request $request, Cours $cours): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cours->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cours);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_cours_index');
    }
}
