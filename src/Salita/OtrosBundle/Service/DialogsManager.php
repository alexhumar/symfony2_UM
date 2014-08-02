<?php
namespace Salita\OtrosBundle\Service;

/*Esta clase contiene todos los mensajes del sistema. Deberia ser util a la hora de aplicarle i18n al sistema*/
class DialogsManager
{
	/*Mensajes de OtrosBundle*/
	
	public function cargaBarrioExitoMsg()
	{
		return 'otrosbundle.barrioForm.new.exito';
	}
	
	public function cargaLocalidadExitoMsg()
	{
		return 'otrosbundle.localidadForm.new.exito';
	}
	
	public function cargaMetodoEstudioExitoMsg()
	{
		return 'otrosbundle.metodoEstudioForm.new.exito';
	}
	
	public function cargaPaisExitoMsg()
	{
		return 'otrosbundle.paisForm.new.exito';
	}
	
	public function cargaPartidoExitoMsg()
	{
		return 'otrosbundle.partidoForm.new.exito';
	}

	public function modificacionPartidoExitoMsg()
	{
		return 'otrosbundle.partidoForm.modif.exito';
	}
	
	public function getDiagnosticoInexistenteMsg()
	{
		return 'otrosbundle.notFoundException.diagnostico';
	}
	
	public function getPartidoInexistenteMsg()
	{
		return 	'otrosbundle.notFoundException.partido';
	}
	
	public function getVacunaInexistenteMsg()
	{
		return 	'otrosbundle.notFoundException.vacuna';
	}
	
	/*Mensajes de PacienteBundle*/
	
	public function getAntecedentesInexistentesMsg()
	{
		return 	'pacientebundle.notFoundException.antecedentes';
	}
	
    public function modificacionAntecedentesExitoMsg()
	{
		return 'pacientebundle.antecedentes.modif.exito';
	}
	
	public function cargaAplicacionVacunaExitoMsg()
	{
		return 'pacientebundle.aplicacionVacuna.new.exito';
	}
	
	public function cargaConsultaExitoMsg()
	{
		return 'pacientebundle.consulta.new.exito';
	}
	
	public function cargaEstudioExitoMsg()
	{
		return 'pacientebundle.estudio.new.exito';
	}
	
	public function modificacionDatosPacienteExitoMsg()
	{
		return 'pacientebundle.paciente.modif.exito';
	}
	
	public function cargaPacienteExitoMsg()
	{
		return 'pacientebundle.paciente.new.exito';
	}
	
	
}