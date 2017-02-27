<?php

namespace IndyDutch\QuizBundle\Service;

use IndyDutch\QuizBundle\Repository\CategoryRepository;

class FindCategoryService
{
    const MIN_SEARCH_LENGTH = 2;

    /** @var CategoryRepository */
    private $repository;

    /**
     * @param CategoryRepository $repository
     */
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $searchString
     *
     * @return array
     */
    public function search($searchString)
    {
        if (mb_strlen($searchString) >= static::MIN_SEARCH_LENGTH) {
            return $this->repository->search($searchString);
        }

        return array();
    }
}