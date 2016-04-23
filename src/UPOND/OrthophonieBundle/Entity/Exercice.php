<?php

namespace UPOND\OrthophonieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Exercice
 *
 * @ORM\Table(name="exercice")
 * @ORM\Entity
 */
class Exercice
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_exercice", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idExercice;

    /**
     * @var integer
     *
     * @ORM\Column(name="temps", type="integer", nullable=true)
     */
    private $temps;

    /**
     * @var boolean
     *
     * @ORM\Column(name="exercice_fini", type="boolean", nullable=true)
     */
    private $exerciceFini;

    /**
     * @var string
     *
     * @ORM\Column(name="strategie", type="string", nullable=true)
     */
    private $strategie;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_bonne_reponse", type="integer", nullable=true)
     */
    private $nbBonneReponse;

    /**
     * @var float
     *
     * @ORM\Column(name="taux_reussite", type="float", precision=10, scale=0, nullable=true)
     */
    private $tauxReussite;

    /**
     * @var string
     *
     * @ORM\Column(name="phase", type="string", length=20, nullable=true)
     */
    private $phase;

    /**
     * @ORM\ManyToMany(targetEntity="UPOND\OrthophonieBundle\Entity\Question")
     * @ORM\JoinTable(name="Exercice_Questions",
     *      joinColumns={@JoinColumn(name="id_exercice", referencedColumnName="id_exercice")},
     *      inverseJoinColumns={@JoinColumn(name="id_question", referencedColumnName="id_question")}
     *      )
     */
    private $questions;

    /**
     * @ORM\ManyToOne(targetEntity="UPOND\OrthophonieBundle\Entity\Partie", inversedBy="exercices")
     * @ORM\JoinColumn(name="id_partie", referencedColumnName="id_partie")
     */
    private $partie;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    /**
     * Get idExercice
     *
     * @return integer 
     */
    public function getIdExercice()
    {
        return $this->idExercice;
    }

    /**
     * Set temps
     *
     * @param integer $temps
     * @return Exercice
     */
    public function setTemps($temps)
    {
        $this->temps = $temps;

        return $this;
    }

    /**
     * Get temps
     *
     * @return integer 
     */
    public function getTemps()
    {
        return $this->temps;
    }

    /**
     * Set exerciceFini
     *
     * @param boolean $exerciceFini
     * @return Exercice
     */
    public function setExerciceFini($exerciceFini)
    {
        $this->exerciceFini = $exerciceFini;

        return $this;
    }

    /**
     * Get exerciceFini
     *
     * @return boolean 
     */
    public function getExerciceFini()
    {
        return $this->exerciceFini;
    }

    /**
     * Set nbBonneReponse
     *
     * @param integer $nbBonneReponse
     * @return Exercice
     */
    public function setNbBonneReponse($nbBonneReponse)
    {
        $this->nbBonneReponse = $nbBonneReponse;

        return $this;
    }

    /**
     * Get nbBonneReponse
     *
     * @return integer 
     */
    public function getNbBonneReponse()
    {
        return $this->nbBonneReponse;
    }

    /**
     * Set tauxReussite
     *
     * @param float $tauxReussite
     * @return Exercice
     */
    public function setTauxReussite($tauxReussite)
    {
        $this->tauxReussite = $tauxReussite;

        return $this;
    }

    /**
     * Get tauxReussite
     *
     * @return float 
     */
    public function getTauxReussite()
    {
        return $this->tauxReussite;
    }

    /**
     * Set idQuestion
     *
     * @param integer $idQuestion
     * @return Exercice
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
     * Set phase
     *
     * @param string $phase
     * @return Exercice
     */
    public function setPhase($phase)
    {
        $this->phase = $phase;

        return $this;
    }

    /**
     * Get phase
     *
     * @return string 
     */
    public function getPhase()
    {
        return $this->phase;
    }

    /**
     * Set strategie
     *
     * @param string $strategie
     *
     * @return Exercice
     */
    public function setStrategie($strategie)
    {
        $this->strategie = $strategie;

        return $this;
    }

    /**
     * Get strategie
     *
     * @return string
     */
    public function getStrategie()
    {
        return $this->strategie;
    }

    /**
     * Add question
     *
     * @param \UPOND\OrthophonieBundle\Entity\Question $question
     *
     * @return Exercice
     */
    public function addQuestion(\UPOND\OrthophonieBundle\Entity\Question $question)
    {
        $this->questions[] = $question;

        return $this;
    }

    /**
     * Remove question
     *
     * @param \UPOND\OrthophonieBundle\Entity\Question $question
     */
    public function removeQuestion(\UPOND\OrthophonieBundle\Entity\Question $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set partie
     *
     * @param \UPOND\OrthophonieBundle\Entity\Partie $partie
     *
     * @return Exercice
     */
    public function setPartie(\UPOND\OrthophonieBundle\Entity\Partie $partie = null)
    {
        $this->partie = $partie;

        return $this;
    }

    /**
     * Get partie
     *
     * @return \UPOND\OrthophonieBundle\Entity\Partie
     */
    public function getPartie()
    {
        return $this->partie;
    }
}
