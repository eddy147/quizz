<?php

namespace IndyDutch\QuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Score
 *
 * @ORM\Table(name="score")
 */
class Score
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
     * @var User
     * @ORM\
     */
    private $user;

    /**
     * @var Question
     */
    private $question;

    /** @var Answer */
    private $givenAnswer;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Score
     */
    public function setUser(User $user): Score
    {
        $this->user = $user;

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
     * @return Score
     */
    public function setQuestion(Question $question): Score
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Answer
     */
    public function getGivenAnswer(): Answer
    {
        return $this->givenAnswer;
    }

    /**
     * @param Answer $givenAnswer
     * @return Score
     */
    public function setGivenAnswer(Answer $givenAnswer): Score
    {
        $this->givenAnswer = $givenAnswer;

        return $this;
    }
}