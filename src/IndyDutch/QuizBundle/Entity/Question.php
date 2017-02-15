<?php

namespace IndyDutch\QuizBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Question
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
     * @var Answer[]
     *
     * @ORM\ManyToMany(targetEntity="IndyDutch\QuizBundle\Entity\Answer")
     * @ORM\JoinTable(name="questions_answers",
     *      joinColumns={@ORM\JoinColumn(name="question_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="answer_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $answers;

    /**
     * @var Answer
     *
     * @ORM\OneToOne(targetEntity="IndyDutch\QuizBundle\Entity\Answer")
     * @ORM\JoinColumn(name="correct_answer_id", referencedColumnName="id")
     */
    private $correctAnswer;

    /**
     * @var Tag[]
     */
    private $tags;

    /**
     * Question constructor.
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getAnswers(): ArrayCollection
    {
        return $this->answers;
    }

    /**
     * @param ArrayCollection $answers
     * @return Question
     */
    public function setAnswers(ArrayCollection $answers): Question
    {
        $this->answers = $answers;

        return $this;
    }

    /**
     * Set QuestionText
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
     * Get QuestionText
     *
     * @return string
     */
    public function getQuestionText()
    {
        return $this->questionText;
    }

    /**
     * @return Answer
     */
    public function getCorrectAnswer(): Answer
    {
        return $this->correctAnswer;
    }

    /**
     * @param Answer $correctAnswer
     * @return Question
     */
    public function setCorrectAnswer(Answer $correctAnswer): Question
    {
        $this->correctAnswer = $correctAnswer;

        return $this;
    }

    /**
     * @return ArrayCollection|Tag[]
     */
    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    /**
     * @return string
     */
    public function getTaggableType()
    {
        return 'quiz_tag';
    }

    /**
     * @return int
     */
    public function getTaggableId()
    {
        return $this->getId();
    }
}

