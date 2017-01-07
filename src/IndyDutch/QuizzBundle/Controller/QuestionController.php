<?php

namespace IndyDutch\QuizzBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class QuestionController extends Controller
{
    /**
     * @Route("/questions", name="questions_list")
     * @Method({"GET"})
     */
    public function getListAction()
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
}