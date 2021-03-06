<?php

namespace Salita\PacienteBundle\Entity;

use Salita\PacienteBundle\Entity\AntecedenteFamiliar;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Salita\PacienteBundle\Repository\AntecedenteFamiliarClinicoRepository")
 */
class AntecedenteFamiliarClinico extends AntecedenteFamiliar
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $cardiovascularMenorA55;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $asma;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $trastornosMentales;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $alergias;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $adiccionesTabaquismo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $infectoContagiosas;

    /**
     * @var boolean $tuberculosis
     */
    protected $tuberculosis;

    /**
     * @var boolean $diabetes
     */
    protected $diabetes;

    /**
     * @var boolean $hipertensionArterial
     */
    protected $hipertensionArterial;

    /**
     * @var text $otros
     */
    protected $otros;


    /**
     * Get idAntecedenteFamiliarClinico
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cardiovascularMenorA55
     *
     * @param boolean $cardiovascularMenorA55
     */
    public function setCardiovascularMenorA55($cardiovascularMenorA55)
    {
        $this->cardiovascularMenorA55 = $cardiovascularMenorA55;
    }

    /**
     * Get cardiovascularMenorA55
     *
     * @return boolean 
     */
    public function getCardiovascularMenorA55()
    {
        return $this->cardiovascularMenorA55;
    }

    /**
     * Set asma
     *
     * @param boolean $asma
     */
    public function setAsma($asma)
    {
        $this->asma = $asma;
    }

    /**
     * Get asma
     *
     * @return boolean 
     */
    public function getAsma()
    {
        return $this->asma;
    }

    /**
     * Set trastornosMentales
     *
     * @param boolean $trastornosMentales
     */
    public function setTrastornosMentales($trastornosMentales)
    {
        $this->trastornosMentales = $trastornosMentales;
    }

    /**
     * Get trastornosMentales
     *
     * @return boolean 
     */
    public function getTrastornosMentales()
    {
        return $this->trastornosMentales;
    }

    /**
     * Set alergias
     *
     * @param boolean $alergias
     */
    public function setAlergias($alergias)
    {
        $this->alergias = $alergias;
    }

    /**
     * Get alergias
     *
     * @return boolean 
     */
    public function getAlergias()
    {
        return $this->alergias;
    }

    /**
     * Set adiccionesTabaquismo
     *
     * @param boolean $adiccionesTabaquismo
     */
    public function setAdiccionesTabaquismo($adiccionesTabaquismo)
    {
        $this->adiccionesTabaquismo = $adiccionesTabaquismo;
    }

    /**
     * Get adiccionesTabaquismo
     *
     * @return boolean 
     */
    public function getAdiccionesTabaquismo()
    {
        return $this->adiccionesTabaquismo;
    }

    /**
     * Set infectoContagiosas
     *
     * @param boolean $infectoContagiosas
     */
    public function setInfectoContagiosas($infectoContagiosas)
    {
        $this->infectoContagiosas = $infectoContagiosas;
    }

    /**
     * Get infectoContagiosas
     *
     * @return boolean 
     */
    public function getInfectoContagiosas()
    {
        return $this->infectoContagiosas;
    }

    /**
     * Set tuberculosis
     *
     * @param boolean $tuberculosis
     */
    public function setTuberculosis($tuberculosis)
    {
        $this->tuberculosis = $tuberculosis;
    }

    /**
     * Get tuberculosis
     *
     * @return boolean 
     */
    public function getTuberculosis()
    {
        return $this->tuberculosis;
    }

    /**
     * Set diabetes
     *
     * @param boolean $diabetes
     */
    public function setDiabetes($diabetes)
    {
        $this->diabetes = $diabetes;
    }

    /**
     * Get diabetes
     *
     * @return boolean 
     */
    public function getDiabetes()
    {
        return $this->diabetes;
    }

    /**
     * Set hipertensionArterial
     *
     * @param boolean $hipertensionArterial
     */
    public function setHipertensionArterial($hipertensionArterial)
    {
        $this->hipertensionArterial = $hipertensionArterial;
    }

    /**
     * Get hipertensionArterial
     *
     * @return boolean 
     */
    public function getHipertensionArterial()
    {
        return $this->hipertensionArterial;
    }

    /**
     * Set otros
     *
     * @param text $otros
     */
    public function setOtros($otros)
    {
        $this->otros = $otros;
    }

    /**
     * Get otros
     *
     * @return text 
     */
    public function getOtros()
    {
        return $this->otros;
    }
}
