<?php

namespace IndyDutch\QuizzBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Choice
 *
 * @ORM\Table(name="choice")
 * @ORM\Entity(repositoryClass="IndyDutch\QuizzBundle\Repository\ChoiceRepository")
 */
class Choice
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
     * @ORM\OneToMany(targetEntity="IndyDutch\QuizzBundle\Entity\PossibleAnswer", mappedBy="choice"))
     */
    private $possibleAnswers;

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
     * @return Choice
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

    public function __toString()
    {
        return $this->content;
    }

}

