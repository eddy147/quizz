<?php

namespace IndyDutch\QuizzBundle\Controller\Admin;

use IndyDutch\QuizzBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="admin_categories_new", options = { "expose" = true })
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(CategoryType::class);

        //only handles POST requests
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'category added.');

            return $this->redirectToRoute('categories_list');
        }

        return $this->render('@Quizz/admin/categories.new.html.twig', [
            'categoryForm' => $form->createView()
        ]);
    }
}
