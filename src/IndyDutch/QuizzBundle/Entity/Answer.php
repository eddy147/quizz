<?php

namespace IndyDutch\QuizzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineExtensions\Taggable\Taggable;

/**
 * Answer
 *
 * @ORM\Table(name="answer")
 * @ORM\Entity(repositoryClass="IndyDutch\QuizzBundle\Repository\AnswerRepository")
 */
class Answer implements Taggable
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
     * @ORM\OneToMany(targetEntity="IndyDutch\QuizzBundle\Entity\PossibleAnswer", mappedBy="answer"))
     */
    private $possibleAnswers;

    private $tags;

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
     * Set content
     *
     * @param string $content
     *
     * @return Answer
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

    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    public function getTaggableType()
    {
        return 'quizz_tag';
    }

    public function getTaggableId()
    {
        return $this->getId();
    }

    public function __toString()
    {
        return $this->content;
    }

}

