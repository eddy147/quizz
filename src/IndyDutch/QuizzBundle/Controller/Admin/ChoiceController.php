<?php

namespace IndyDutch\QuizzBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use IndyDutch\QuizzBundle\Form\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
/**
 * @Route("/admin")
 */
class ChoiceController extends Controller
{
    /**
     * @Route("/choices", name="admin_choices_new")
     * @Method({"POST"})
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

            return $this->redirectToRoute('choices_list');
        }

        return $this->render('@Quizz/admin/choices.new.html.twig', [
            'choiceForm' => $form->createView()
        ]);
    }
}