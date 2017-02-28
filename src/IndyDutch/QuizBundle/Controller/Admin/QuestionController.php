<?php

namespace IndyDutch\QuizBundle\Controller\Admin;

use IndyDutch\QuizBundle\Form\PossibleAnswerType;
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
        $questionForm = $this->createForm(QuestionType::class);

        //only handles POST requests
        $questionForm->handleRequest($request);
        if ($questionForm->isSubmitted() && $questionForm->isValid()) {
            $question = $questionForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();

            $this->addFlash('success', 'Question added.');

            return $this->redirectToRoute('questions_list');
        }

        $possibleAnswerForm = $this->createForm(PossibleAnswerType::class);

        //only handles POST requests
        $possibleAnswerForm->handleRequest($request);
        if ($possibleAnswerForm->isSubmitted() && $possibleAnswerForm->isValid()) {
            $question = $possibleAnswerForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();

            $this->addFlash('success', 'Question added.');

            return $this->redirectToRoute('questions_list');
        }

        return $this->render('@Quiz/admin/questions.new.html.twig', [
            'questionForm' => $questionForm->createView(),
            'possibleAnswerForm' => $possibleAnswerForm->createView(),
        ]);
    }
}
