<?php

namespace UPOND\OrthophonieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Partie
 *
 * @ORM\Table(name="partie", indexes={@ORM\Index(name="id_patient", columns={"id_patient"})})
 * @ORM\Entity
 */
class Partie
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_partie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPartie;

    /**
     * @var boolean
     *
     * @ORM\Column(name="partie_fini", type="boolean", nullable=false)
     */
    private $partieFini;

    /**
     * @var float
     *
     * @ORM\Column(name="taux_reussite", type="float", precision=10, scale=0, nullable=true)
     */
    private $tauxReussite;

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
     * @ORM\OneToMany(targetEntity="UPOND\OrthophonieBundle\Entity\Exercice", mappedBy="partie")
     */
    private $exercices;

    /**
     * @var Date
     *
     * @ORM\Column(name="dateCreation", type="date", nullable=true)
     */
    private $dateCreation;

    public function __construct()
    {
        $this->exercices = new ArrayCollection();
    }

    /**
     * Get idPartie
     *
     * @return integer 
     */
    public function getIdPartie()
    {
        return $this->idPartie;
    }

    /**
     * Set tempsTotal
     *
     * @param integer $tempsTotal
     * @return Partie
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
     * Set partieFini
     *
     * @param boolean $partieFini
     * @return Partie
     */
    public function setPartieFini($partieFini)
    {
        $this->partieFini = $partieFini;

        return $this;
    }

    /**
     * Get partieFini
     *
     * @return boolean 
     */
    public function getPartieFini()
    {
        return $this->partieFini;
    }

    /**
     * Set tauxReussite
     *
     * @param float $tauxReussite
     * @return Partie
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
     * Set idPatient
     *
     * @param \UPOND\OrthophonieBundle\Entity\Patient $idPatient
     * @return Partie
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Partie
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Add exercice
     *
     * @param \UPOND\OrthophonieBundle\Entity\Exercice $exercice
     *
     * @return Partie
     */
    public function addExercice(\UPOND\OrthophonieBundle\Entity\Exercice $exercice)
    {
        $this->exercices[] = $exercice;

        return $this;
    }

    /**
     * Remove exercice
     *
     * @param \UPOND\OrthophonieBundle\Entity\Exercice $exercice
     */
    public function removeExercice(\UPOND\OrthophonieBundle\Entity\Exercice $exercice)
    {
        $this->exercices->removeElement($exercice);
    }

    /**
     * Get exercices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExercices()
    {
        return $this->exercices;
    }
}
