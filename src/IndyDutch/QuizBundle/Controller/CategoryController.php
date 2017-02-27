<?php

namespace IndyDutch\QuizBundle\Controller;

use IndyDutch\QuizBundle\Entity\Category;
use IndyDutch\QuizBundle\Service\FindCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/categories/{search}", name="categories_autocompletion")
     * @Method({"GET"})
     */
    public function autoCompleteAction(Request $request)
    {
        $data = array();
        $searchString = trim(strip_tags($request->get('search')));

        $categories = $this->get('indy_dutch_quiz.service.find_category_service')
            ->search($searchString);

        foreach ($categories as $category) {
            $data[] = array('id' => $category->getId(), 'name' => $category->getName());
        }

        $response = new JsonResponse();
        $response->setData($data);

        return $response;
    }
}