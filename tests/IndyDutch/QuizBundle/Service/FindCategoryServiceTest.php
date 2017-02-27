<?php

namespace Tests\IndyDutch\QuizBundle\Service;

use IndyDutch\QuizBundle\Service\FindCategoryService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FindCategoryServiceTest extends WebTestCase
{
    /**
     * @testdox Test that if you give a valid searchstring, meaning, equal or longer than 2 characters,
     * it will call a real search.
     */
    public function testSearchWithValidSearchString()
    {
        $expected = array(
            array('id' => 3, 'name' => 'geography'),
            array('id' => 4, 'name' => 'science'),
        );
        $repository = \Mockery::mock('IndyDutch\QuizBundle\Repository\CategoryRepository')->makePartial();
        $repository
            ->shouldReceive('search')
            ->once()
            ->andReturn($expected);
        $service = new FindCategoryService($repository);
        $this->assertSame($expected, $service->search('sc'));
    }

    /**
     * @testdox Test that if you give a not a valid searchstring, meaning, shorter than 2 characters,
     * it will not call a real search.
     */
    public function testSearchWithInValidSearchString()
    {
        $repository = \Mockery::mock('IndyDutch\QuizBundle\Repository\CategoryRepository')->makePartial();
        $repository->shouldNotReceive('search');
        $service = new FindCategoryService($repository);
        $this->assertSame(array(), $service->search('s'));
    }
}
