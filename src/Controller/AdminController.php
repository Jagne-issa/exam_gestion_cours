<?php
// src/Controller/AdminController.php
namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Niveau;
use App\Entity\Classe;
use App\Entity\Module;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use App\Repository\NiveauRepository;
use App\Repository\ClasseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/cours', name: 'admin_cours')]
    public function listCours(CoursRepository $coursRepository)
    {
        $cours = $coursRepository->findAll();
        return $this->render('admin/cours/list.html.twig', [
            'cours' => $cours
        ]);
    }

    #[Route('/admin/cours/new', name: 'admin_cours_new')]
    public function newCours(Request $request, EntityManagerInterface $em)
    {
        $cours = new Cours();
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($cours);
            $em->flush();
            return $this->redirectToRoute('admin_cours');
        }

        return $this->render('admin/cours/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/cours/{id}', name: 'admin_cours_show')]
    public function showCours(Cours $cours)
    {
        return $this->render('admin/cours/show.html.twig', [
            'cours' => $cours
        ]);
    }

    #[Route('/admin/cours/edit/{id}', name: 'admin_cours_edit')]
    public function editCours(Request $request, Cours $cours, EntityManagerInterface $em)
    {
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_cours');
        }

        return $this->render('admin/cours/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/cours/delete/{id}', name: 'admin_cours_delete')]
    public function deleteCours(Cours $cours, EntityManagerInterface $em)
    {
        $em->remove($cours);
        $em->flush();

        return $this->redirectToRoute('admin_cours');
    }
}
