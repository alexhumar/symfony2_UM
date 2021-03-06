<?php

namespace Salita\OtrosBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Salita\OtrosBundle\Repository\DiagnosticoRepository")
 * @ORM\Table(name="diagnostico")
 */

class Diagnostico
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=10, unique=true, nullable=false)
     */
    protected $codigo;

    /**
     * @ORM\Column(type="string", length=200, unique=true, nullable=false)
     */
    protected $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Salita\PacienteBundle\Entity\Consulta", mappedBy="diagnostico")
     */
    protected $consultas;

    public function __construct()
    {
        $this->consultas= new ArrayCollection();
    }


    /**
     * Get idDiagnostico
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
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
     * Add consultas
     *
     * @param Salita\PacienteBundle\Entity\Consulta $consultas
     */
    public function addConsulta(\Salita\PacienteBundle\Entity\Consulta $consultas)
    {
        $this->consultas[] = $consultas;
    }

    /**
     * Get consultas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getConsultas()
    {
        return $this->consultas;
    }
}
