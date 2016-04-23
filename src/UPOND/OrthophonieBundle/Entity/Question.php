<?php

namespace UPOND\OrthophonieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity
 */
class Question
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_question", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idQuestion;

    /**
     * @var string
     *
     * @ORM\Column(name="texteQuestion", type="string", nullable=true)
     */
    private $texteQuestion;

    /**
     * @ORM\OneToOne(targetEntity="UPOND\OrthophonieBundle\Entity\Multimedia")
     */
    private $multimedia;

    /**
     * Set idQuestion
     *
     * @param integer $idQuestion
     * @return Question
     */
    public function setIdQuestion($idQuestion)
    {
        $this->idQuestion = $idQuestion;

        return $this;
    }

    /**
     * Get idQuestion
     *
     * @return integer 
     */
    public function getIdQuestion()
    {
        return $this->idQuestion;
    }

    /**
     * Set idMultimedia
     *
     * @param \UPOND\OrthophonieBundle\Entity\Multimedia $idMultimedia
     * @return Question
     */
    public function setIdMultimedia(\UPOND\OrthophonieBundle\Entity\Multimedia $idMultimedia = null)
    {
        $this->idMultimedia = $idMultimedia;

        return $this;
    }

    /**
     * Get idMultimedia
     *
     * @return \UPOND\OrthophonieBundle\Entity\Multimedia 
     */
    public function getIdMultimedia()
    {
        return $this->idMultimedia;
    }

    /**
     * Set texteQuestion
     *
     * @param string $texteQuestion
     *
     * @return Question
     */
    public function setTexteQuestion($texteQuestion)
    {
        $this->texteQuestion = $texteQuestion;

        return $this;
    }

    /**
     * Get texteQuestion
     *
     * @return string
     */
    public function getTexteQuestion()
    {
        return $this->texteQuestion;
    }

    /**
     * Set multimedia
     *
     * @param \UPOND\OrthophonieBundle\Entity\Multimedia $multimedia
     *
     * @return Question
     */
    public function setMultimedia(\UPOND\OrthophonieBundle\Entity\Multimedia $multimedia = null)
    {
        $this->multimedia = $multimedia;

        return $this;
    }

    /**
     * Get multimedia
     *
     * @return \UPOND\OrthophonieBundle\Entity\Multimedia
     */
    public function getMultimedia()
    {
        return $this->multimedia;
    }
}
