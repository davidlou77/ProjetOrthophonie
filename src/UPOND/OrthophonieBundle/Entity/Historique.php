<?php

namespace UPOND\OrthophonieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Historique
 *
 * @ORM\Table(name="historique", indexes={@ORM\Index(name="foreign_key01", columns={"id_question"}), @ORM\Index(name="foreign_key02", columns={"id_exercice"}), @ORM\Index(name="foreign_key03", columns={"id_partie"}), @ORM\Index(name="foreign_key04", columns={"id_patient"})})
 * @ORM\Entity
 */
class Historique
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_historique", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idHistorique;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordre", type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="bonne_reponse", type="boolean", nullable=true)
     */
    private $bonneReponse;

    /**
     * @var \Question
     *
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_question", referencedColumnName="id_question")
     * })
     */
    private $idQuestion;

    /**
     * @var \Partie
     *
     * @ORM\ManyToOne(targetEntity="Partie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_partie", referencedColumnName="id_partie")
     * })
     */
    private $idPartie;

    /**
     * @var \Patient
     *
     * @ORM\ManyToOne(targetEntity="Patient")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_patient", referencedColumnName="id_patient")
     * })
     */
    private $idPatient;

    /**
     * @var \Exercice
     *
     * @ORM\ManyToOne(targetEntity="Exercice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_exercice", referencedColumnName="id_exercice")
     * })
     */
    private $idExercice;

    /**
     * @var integer
     *
     * @ORM\Column(name="tempsTotal", type="integer", nullable=true)
     */
    private $tempsTotal;

    /**
     * Get idHistorique
     *
     * @return integer 
     */
    public function getIdHistorique()
    {
        return $this->idHistorique;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return Historique
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set reponseDonne
     *
     * @param integer $reponseDonne
     * @return Historique
     */
    public function setReponseDonne($reponseDonne)
    {
        $this->reponseDonne = $reponseDonne;

        return $this;
    }

    /**
     * Get reponseDonne
     *
     * @return integer 
     */
    public function getReponseDonne()
    {
        return $this->reponseDonne;
    }

    /**
     * Set bonneReponse
     *
     * @param boolean $bonneReponse
     * @return Historique
     */
    public function setBonneReponse($bonneReponse)
    {
        $this->bonneReponse = $bonneReponse;

        return $this;
    }

    /**
     * Get bonneReponse
     *
     * @return boolean 
     */
    public function getBonneReponse()
    {
        return $this->bonneReponse;
    }

    /**
     * Set idQuestion
     *
     * @param \UPOND\OrthophonieBundle\Entity\Question $idQuestion
     * @return Historique
     */
    public function setIdQuestion(\UPOND\OrthophonieBundle\Entity\Question $idQuestion = null)
    {
        $this->idQuestion = $idQuestion;

        return $this;
    }

    /**
     * Get idQuestion
     *
     * @return \UPOND\OrthophonieBundle\Entity\Question 
     */
    public function getIdQuestion()
    {
        return $this->idQuestion;
    }

    /**
     * Set idMultimedia
     *
     * @param \UPOND\OrthophonieBundle\Entity\Multimedia $idMultimedia
     * @return Historique
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
     * Set tempsTotal
     *
     * @param integer $tempsTotal
     *
     * @return Historique
     */
    public function setTempsTotal($tempsTotal)
    {
        $this->tempsTotal = $tempsTotal;

        return $this;
    }

    /**
     * Get tempsTotal
     *
     * @return integer
     */
    public function getTempsTotal()
    {
        return $this->tempsTotal;
    }

    /**
     * Set idPartie
     *
     * @param \UPOND\OrthophonieBundle\Entity\Partie $idPartie
     *
     * @return Historique
     */
    public function setIdPartie(\UPOND\OrthophonieBundle\Entity\Partie $idPartie = null)
    {
        $this->idPartie = $idPartie;

        return $this;
    }

    /**
     * Get idPartie
     *
     * @return \UPOND\OrthophonieBundle\Entity\Partie
     */
    public function getIdPartie()
    {
        return $this->idPartie;
    }

    /**
     * Set idPatient
     *
     * @param \UPOND\OrthophonieBundle\Entity\Patient $idPatient
     *
     * @return Historique
     */
    public function setIdPatient(\UPOND\OrthophonieBundle\Entity\Patient $idPatient = null)
    {
        $this->idPatient = $idPatient;

        return $this;
    }

    /**
     * Get idPatient
     *
     * @return \UPOND\OrthophonieBundle\Entity\Patient
     */
    public function getIdPatient()
    {
        return $this->idPatient;
    }

    /**
     * Set idExercice
     *
     * @param \UPOND\OrthophonieBundle\Entity\Exercice $idExercice
     *
     * @return Historique
     */
    public function setIdExercice(\UPOND\OrthophonieBundle\Entity\Exercice $idExercice = null)
    {
        $this->idExercice = $idExercice;

        return $this;
    }

    /**
     * Get idExercice
     *
     * @return \UPOND\OrthophonieBundle\Entity\Exercice
     */
    public function getIdExercice()
    {
        return $this->idExercice;
    }
}
