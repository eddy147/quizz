<?php

namespace IndyDutch\QuizzBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use IndyDutch\QuizzBundle\Form\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class ChoiceController extends Controller
{
    /**
     * @Route("/choices", name="admin_choices_list")
     */
    public function indexAction()
    {
        $choices = $this->getDoctrine()
            ->getRepository('QuizzBundle:Choice')
            ->findAll();

        return $this->render(
            '@Quizz/default/choices.html.twig',
            array(
                'choices' => $choices,
            )
        );
    }

    /**
     * @Route("/choices/new", name="admin_choices_new")
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(ChoiceType::class);

        //only handles POST requests
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $choice = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($choice);
            $em->flush();

            $this->addFlash('success', 'choice added.');

            return $this->redirectToRoute('admin_choices_list');
        }

        return $this->render('@Quizz/admin/choices.new.html.twig', [
            'choiceForm' => $form->createView()
        ]);
    }
}