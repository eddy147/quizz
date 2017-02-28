<?php

namespace IndyDutch\QuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question.
 *
 * @ORM\Table(name="questions_answers")
 * @ORM\Entity(repositoryClass="IndyDutch\QuizBundle\Repository\QuestionAnswersRepository")
 */
class QuestionAnswers
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
     * @var \IndyDutch\QuizBundle\Entity\Question
     * @ORM\ManyToOne(targetEntity="IndyDutch\QuizBundle\Entity\Question", inversedBy="questionAnswers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;

    /**
     * @var \IndyDutch\QuizBundle\Entity\Answer
     * @ORM\ManyToOne(targetEntity="IndyDutch\QuizBundle\Entity\Answer")
     * @ORM\JoinColumn(name="answer_id", referencedColumnName="id")
     */
    private $answer;

    /**
     * @var bool
     */
    private $correctAnswer;

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
     * @return bool
     */
    public function isCorrectAnswer()
    {
        return $this->correctAnswer;
    }

    /**
     * @param bool
     *
     * @return $this
     */
    public function setCorrectAnswer($bool)
    {
        $this->correctAnswer = $bool;

        return $this;
    }

    /**
     * @return $this
     */
    public function getQuestion(): Question
    {
        return $this->question;
    }

    /**
     * @param Question $question
     *
     * @return $this
     */
    public function setQuestion(Question $question): QuestionAnswers
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
     *
     * @return QuestionAnswers
     */
    public function setAnswer(Answer $answer): QuestionAnswers
    {
        $this->answer = $answer;

        return $this;
    }
}
