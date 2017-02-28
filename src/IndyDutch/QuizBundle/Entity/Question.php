<?php

namespace IndyDutch\QuizBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Question.
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="IndyDutch\QuizBundle\Repository\QuestionRepository")
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
     * @ORM\Column(name="questionText", type="string", length=255)
     */
    private $questionText;

    /**
     * @var QuestionAnswers[]
     * @ORM\OneToMany(targetEntity="IndyDutch\QuizBundle\Entity\QuestionAnswers", mappedBy="question")
     */
    private $questionAnswers;

    /**
     * @var Category[]
     *
     * @ORM\ManyToMany(targetEntity="IndyDutch\QuizBundle\Entity\Category")
     * @ORM\JoinTable(name="questions_categories",
     *      joinColumns={@ORM\JoinColumn(name="question_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $categories;

    /**
     * Question constructor.
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getAnswers(): ArrayCollection
    {
        return $this->answers;
    }

    /**
     * @param ArrayCollection $answers
     *
     * @return Question
     */
    public function setAnswers(ArrayCollection $answers): Question
    {
        $this->answers = $answers;

        return $this;
    }

    /**
     * Set QuestionText.
     *
     * @param string $questionText
     *
     * @return Question
     */
    public function setQuestionText($questionText)
    {
        $this->questionText = $questionText;

        return $this;
    }

    /**
     * Get QuestionText.
     *
     * @return string
     */
    public function getQuestionText()
    {
        return $this->questionText;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     *
     * @return Question
     */
    public function setCategories(array $categories): Question
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return QuestionAnswers[]
     */
    public function getQuestionAnswers(): array
    {
        return $this->questionAnswers;
    }
}
