<?php

namespace IndyDutch\QuizzBundle\Controller\Admin;

use IndyDutch\QuizzBundle\Form\PossibleAnswerType;
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

        return $this->render('@Quizz/admin/questions.new.html.twig', [
            'questionForm' => $questionForm->createView(),
            'possibleAnswerForm' => $possibleAnswerForm->createView(),
        ]);
    }
}