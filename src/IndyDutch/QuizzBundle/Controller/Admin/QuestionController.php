<?php

namespace IndyDutch\QuizzBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use IndyDutch\QuizzBundle\Form\QuestionType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * @Route("/admin")
 */
class QuestionController extends Controller
{
    /**
     * @Route("/questions", name="admin_questions_new")
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(QuestionType::class);

        //only handles POST requests
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();

            $this->addFlash('success', 'Question added.');

            return $this->redirectToRoute('questions_list');
        }

        return $this->render('@Quizz/admin/questions.new.html.twig', [
            'questionForm' => $form->createView()
        ]);
    }
}