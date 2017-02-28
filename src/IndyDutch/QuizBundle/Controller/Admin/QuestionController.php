<?php

namespace IndyDutch\QuizBundle\Controller\Admin;

use IndyDutch\QuizBundle\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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

        return $this->render('@Quiz/admin/questions.new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
