<?php

namespace IndyDutch\QuizzBundle\Controller;

use IndyDutch\QuizzBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class QuestionController extends Controller
{
    /**
     * @Route("/questions/categories/{id}", name="questions_list")
     * @Method({"GET"})
     */
    public function getListAction(Category $category)
    {
        $questions = $this->getDoctrine()
            ->getRepository('QuizzBundle:Question')
            ->findBy(["category" => $category]);

        return $this->render(
            '@Quizz/default/questions.html.twig',
            array(
                'questions' => $questions,
            )
        );
    }
}