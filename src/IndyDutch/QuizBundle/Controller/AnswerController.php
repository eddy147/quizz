<?php

namespace IndyDutch\QuizBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AnswerController extends Controller
{
    /**
     * @Route("/answers", name="answers_list")
     * @Method({"GET"})
     */
    public function getListAction()
    {
        $answers = $this->getDoctrine()
            ->getRepository('QuizBundle:Answer')
            ->findAll();

        return $this->render(
            '@Quiz/default/answers.html.twig',
            array(
                'answers' => $answers,
            )
        );
    }
}
