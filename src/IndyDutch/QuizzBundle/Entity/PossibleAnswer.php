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
     * @var Choice
     *
     * @ORM\ManyToOne(targetEntity="IndyDutch\QuizzBundle\Entity\Choice", inversedBy="possibleAnswers")
     * @ORM\JoinColumn(name="choice_id", referencedColumnName="id", nullable=false)
     */
    private $choice;

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
     * @return Choice
     */
    public function getChoice(): Choice
    {
        return $this->choice;
    }

    /**
     * @param Choice $choice
     * @return PossibleAnswer
     */
    public function setChoice(Choice $choice): PossibleAnswer
    {
        $this->choice = $choice;

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
        return $this->choice->getContent();
    }
}