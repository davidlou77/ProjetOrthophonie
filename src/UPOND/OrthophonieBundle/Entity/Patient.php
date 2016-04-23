<?php

namespace UPOND\OrthophonieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Patient
 *
 * @ORM\Table(name="patient", indexes={@ORM\Index(name="id_medecin", columns={"id_medecin"})})
 * @ORM\Entity
 */
class Patient
{
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=20, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=20, nullable=true)
     */
    private $prenom;

    /**
     * @var \Utilisateur
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_patient", referencedColumnName="id")
     * })
     */
    private $idPatient;

    /**
     * @var \Medecin
     *
     * @ORM\ManyToOne(targetEntity="Medecin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_medecin", referencedColumnName="id_medecin")
     * })
     */
    private $idMedecin;



    /**
     * Set nom
     *
     * @param string $nom
     * @return Patient
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Patient
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set idPatient
     *
     * @param \UPOND\OrthophonieBundle\Entity\Utilisateur $idPatient
     * @return Patient
     */
    public function setIdPatient(\UPOND\OrthophonieBundle\Entity\Utilisateur $idPatient)
    {
        $this->idPatient = $idPatient;

        return $this;
    }

    /**
     * Get idPatient
     *
     * @return \UPOND\OrthophonieBundle\Entity\Utilisateur 
     */
    public function getIdPatient()
    {
        return $this->idPatient;
    }

    /**
     * Set idMedecin
     *
     * @param \UPOND\OrthophonieBundle\Entity\Medecin $idMedecin
     * @return Patient
     */
    public function setIdMedecin(\UPOND\OrthophonieBundle\Entity\Medecin $idMedecin = null)
    {
        $this->idMedecin = $idMedecin;

        return $this;
    }

    /**
     * Get idMedecin
     *
     * @return \UPOND\OrthophonieBundle\Entity\Medecin 
     */
    public function getIdMedecin()
    {
        return $this->idMedecin;
    }
}
