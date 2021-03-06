<?php

namespace Salita\OtrosBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tipodocumento")
 */

class TipoDocumento
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     */
    protected $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Salita\PacienteBundle\Entity\Paciente", mappedBy="tipoDocumento")
     */
    protected $pacientes;

    public function __construct()
    {
        $this->pacientes= new ArrayCollection();
    }


    /**
     * Get idTipoDocumento
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add pacientes
     *
     * @param Salita\PacienteBundle\Entity\Paciente $pacientes
     */
    public function addPaciente(\Salita\PacienteBundle\Entity\Paciente $pacientes)
    {
        $this->pacientes[] = $pacientes;
    }

    /**
     * Get pacientes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPacientes()
    {
        return $this->pacientes;
    }

    public function __toString()
    {
        return $this->getNombre();
    }
}
