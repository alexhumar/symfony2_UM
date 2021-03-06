<?php

namespace Salita\PlanBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Salita\PlanBundle\Repository\PlanProcRespRepository")
 * @ORM\Table(name="planprocresp")
 */

class PlanProcResp
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $periodicidad;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $finalizado;

    /**
     * @ORM\ManyToOne(targetEntity="Salita\PacienteBundle\Entity\Paciente", inversedBy="planesProcResp")
     * @ORM\JoinColumn(name="idPaciente", referencedColumnName="id")
     */
    protected $paciente;

    /**
     * @ORM\OneToMany(targetEntity="Salita\PlanBundle\Entity\EntregaPlanProcResp", mappedBy="plan")
     */
    protected $entregas;
    
    /**
     * @ORM\ManyToOne(targetEntity="Salita\PlanBundle\Entity\MetodoAnticonceptivo", inversedBy="planesProcResp")
     * @ORM\JoinColumn(name="idMetodoAnticonceptivo", referencedColumnName="id")
     */
    protected $metodoAnticonceptivo;
    
    public function __construct()
    {
        $this->entregas = new ArrayCollection();
    }

    /**
     * Get idPlanProcResp
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set periodicidad
     *
     * @param integer $periodicidad
     */
    public function setPeriodicidad($periodicidad)
    {
        $this->periodicidad = $periodicidad;
    }

    /**
     * Get periodicidad
     *
     * @return integer 
     */
    public function getPeriodicidad()
    {
        return $this->periodicidad;
    }

    /**
     * Set finalizado
     *
     * @param boolean $finalizado
     */
    public function setFinalizado($finalizado)
    {
        $this->finalizado = $finalizado;
    }

    /**
     * Get finalizado
     *
     * @return boolean 
     */
    public function getFinalizado()
    {
        return $this->finalizado;
    }

    /**
     * Set paciente
     *
     * @param Salita\PacienteBundle\Entity\Paciente $paciente
     */
    public function setPaciente(\Salita\PacienteBundle\Entity\Paciente $paciente)
    {
        $this->paciente = $paciente;
    }

    /**
     * Get paciente
     *
     * @return Salita\PacienteBundle\Entity\Paciente 
     */
    public function getPaciente()
    {
        return $this->paciente;
    }

    /**
     * Add entregas
     *
     * @param Salita\PlanBundle\Entity\EntregaPlanProcResp $entregas
     */
    public function addEntregaPlanProcResp(\Salita\PlanBundle\Entity\EntregaPlanProcResp $entregas)
    {
        $this->entregas[] = $entregas;
    }

    /**
     * Get entregas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEntregas()
    {
        return $this->entregas;
    }

    /**
     * Set metodoAnticonceptivo
     *
     * @param Salita\PlanBundle\Entity\MetodoAnticonceptivo $metodoAnticonceptivo
     */
    public function setMetodoAnticonceptivo(\Salita\PlanBundle\Entity\MetodoAnticonceptivo $metodoAnticonceptivo)
    {
        $this->metodoAnticonceptivo = $metodoAnticonceptivo;
    }

    /**
     * Get metodoAnticonceptivo
     *
     * @return Salita\PlanBundle\Entity\MetodoAnticonceptivo 
     */
    public function getMetodoAnticonceptivo()
    {
        return $this->metodoAnticonceptivo;
    }

    public function __toString()
    {
        return $this->getNombre();
    }
}
