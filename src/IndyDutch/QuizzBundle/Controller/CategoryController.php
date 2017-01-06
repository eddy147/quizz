<?php

namespace IndyDutch\QuizzBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="categories_list")
     * @Method({"GET"})
     */
    public function getListAction()
    {
        $categories = $this->getDoctrine()
            ->getRepository('QuizzBundle:Category')
            ->findAll();

        return $this->render(
            '@Quizz/default/categories.html.twig',
            array(
                'categories' => $categories,
            )
        );
    }
}
