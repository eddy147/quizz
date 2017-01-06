<?php

namespace IndyDutch\QuizzBundle\Controller;

use IndyDutch\QuizzBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ChoiceController extends Controller
{
    /**
     * @Route("/choices/categories/{id}", name="choices_list")
     * @Method({"GET"})
     */
    public function getListAction(Category $category)
    {
        $choices = $this->getDoctrine()
            ->getRepository('QuizzBundle:Choice')
            ->findBy(["category" => $category]);

        return $this->render(
            '@Quizz/default/choices.html.twig',
            array(
                'choices' => $choices,
            )
        );
    }
}