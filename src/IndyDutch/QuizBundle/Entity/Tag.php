<?php
/**
 * Created by PhpStorm.
 * User: eddy
 * Date: 1/7/17
 * Time: 9:42 AM
 */

namespace IndyDutch\QuizBundle\Entity;

use \FPN\TagBundle\Entity\Tag as BaseTag;
use Doctrine\ORM\Mapping as ORM;

/**
 * Acme\TagBundle\Entity\Tag
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Tag extends BaseTag
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Tagging", mappedBy="tag", fetch="EAGER")
     **/
    protected $tagging;
}