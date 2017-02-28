<?php

namespace IndyDutch\QuizBundle\Controller;

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
            ->getRepository('QuizBundle:Question')
            ->findAll();

        return $this->render(
            '@Quiz/default/questions.html.twig',
            array(
                'questions' => $questions,
            )
        );
    }
}
