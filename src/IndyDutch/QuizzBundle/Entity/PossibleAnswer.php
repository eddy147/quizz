<?php


namespace IndyDutch\QuizzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @package IndyDutch\QuizzBundle\Entity
 *
 * @ORM\Entity(repositoryClass="IndyDutch\QuizzBundle\Repository\PossibleAnswerRepository")
 * @ORM\Table(name="possibleanswer")
 */
class PossibleAnswer
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
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="IndyDutch\QuizzBundle\Entity\Question", inversedBy="possibleAnswers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=false)
     */
    private $question;

    /**
     * @var Answer
     *
     * @ORM\ManyToOne(targetEntity="IndyDutch\QuizzBundle\Entity\Answer", inversedBy="possibleAnswers")
     * @ORM\JoinColumn(name="answer_id", referencedColumnName="id", nullable=false)
     */
    private $answer;

    /**
     * @var bool
     */
    private $correctAnswer;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return PossibleAnswer
     */
    public function setId(int $id): PossibleAnswer
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Question
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    /**
     * @param Question $question
     * @return PossibleAnswer
     */
    public function setQuestion(Question $question): PossibleAnswer
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Answer
     */
    public function getAnswer(): Answer
    {
        return $this->answer;
    }

    /**
     * @param Answer $answer
     * @return PossibleAnswer
     */
    public function setAnswer(Answer $answer): PossibleAnswer
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCorrectAnswer(): bool
    {
        return $this->correctAnswer;
    }

    /**
     * @param bool $correctAnswer
     * @return PossibleAnswer
     */
    public function setCorrectAnswer(bool $correctAnswer): PossibleAnswer
    {
        $this->correctAnswer = $correctAnswer;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->answer->getContent();
    }
}