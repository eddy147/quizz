<?php

namespace IndyDutch\QuizzBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="IndyDutch\QuizzBundle\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var PossibleAnswer[]
     *
     * @ORM\OneToMany(targetEntity="IndyDutch\QuizzBundle\Entity\PossibleAnswer", mappedBy="question"))
     */
    private $possibleAnswers;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="IndyDutch\QuizzBundle\Entity\Category")
     */
    private $category;

    /**
     * Question constructor.
     */
    public function __construct()
    {
        $this->possibleAnswers = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Question
     */
    public function setCategory(Category $category): Question
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPossibleAnswers(): ArrayCollection
    {
        return $this->possibleAnswers;
    }

    /**
     * @param ArrayCollection $possibleAnswers
     * @return Question
     */
    public function setPossibleAnswers(ArrayCollection $possibleAnswers): Question
    {
        $this->possibleAnswers = $possibleAnswers;

        return $this;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Question
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}

