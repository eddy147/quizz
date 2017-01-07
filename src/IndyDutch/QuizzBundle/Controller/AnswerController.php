<?php

namespace IndyDutch\QuizzBundle\Controller;

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
            ->getRepository('QuizzBundle:Answer')
            ->findAll();

        return $this->render(
            '@Quizz/default/answers.html.twig',
            array(
                'answers' => $answers,
            )
        );
    }
}