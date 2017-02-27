<?php

namespace IndyDutch\QuizBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use IndyDutch\QuizBundle\Form\AnswerType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class AnswerController extends Controller
{
    /**
     * @Route("/answers", name="admin_answers_new")
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(AnswerType::class);

        //only handles POST requests
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $answer = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($answer);
            $em->flush();

            $this->addFlash('success', 'answer added.');

            return $this->redirectToRoute('answers_list');
        }

        return $this->render('@Quiz/admin/answers.new.html.twig', [
            'answerForm' => $form->createView()
        ]);
    }
}