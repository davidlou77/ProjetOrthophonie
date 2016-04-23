<?php

namespace UPOND\OrthophonieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resultat
 *
 * @ORM\Table(name="resultat", indexes={@ORM\Index(name="id_patient", columns={"id_patient"}), @ORM\Index(name="id_partie", columns={"id_partie"})})
 * @ORM\Entity
 */
class Resultat
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_resultat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idResultat;

    /**
     * @var float
     *
     * @ORM\Column(name="taux_reussite", type="float", precision=10, scale=0, nullable=true)
     */
    private $tauxReussite;

    /**
     * @var integer
     *
     * @ORM\Column(name="temps_total", type="integer", nullable=true)
     */
    private $tempsTotal;

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
     * Get idResultat
     *
     * @return integer 
     */
    public function getIdResultat()
    {
        return $this->idResultat;
    }

    /**
     * Set tauxReussite
     *
     * @param float $tauxReussite
     * @return Resultat
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
     * Set tempsTotal
     *
     * @param integer $tempsTotal
     * @return Resultat
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
     * @return Resultat
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
     * @return Resultat
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
}
