<?php

namespace IndyDutch\QuizzBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use IndyDutch\QuizzBundle\Form\QuestionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/admin")
 */
class QuestionController extends Controller
{
    /**
     * @Route("/questions", name="admin_questions_list")
     */
    public function indexAction()
    {
        $questions = $this->getDoctrine()
            ->getRepository('QuizzBundle:Question')
            ->findAll();

        return $this->render(
            '@Quizz/default/questions.html.twig',
            array(
                'questions' => $questions,
            )
        );
    }

    /**
     * @Route("/questions/new", name="admin_questions_new")
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

            return $this->redirectToRoute('admin_questions_list');
        }

        return $this->render('@Quizz/admin/questions.new.html.twig', [
            'questionForm' => $form->createView()
        ]);
    }
}