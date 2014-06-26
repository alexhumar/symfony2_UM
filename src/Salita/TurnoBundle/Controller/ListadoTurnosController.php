<?php
namespace Salita\TurnoBundle\Controller;

//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Salita\OtrosBundle\Clases\MyController;
use Salita\OtrosBundle\Clases\ConsultaRol;

class ListadoTurnosController extends MyController
{

    public function listarAction()
    {
        $session = $this->getSession();
        $repoTurnos = $this->getReposManager()->getTurnosRepo();
        $rolSeleccionado = ConsultaRol::rolSeleccionado($session);
        $turnos = $repoTurnos->turnosDelDia();
        return $this->render(
        			'SalitaTurnoBundle:Listados:turnosDelDia.html.twig',
        			array('turnos' => $turnos, 'rol' => $rolSeleccionado->getCodigo())
        		);
    }

    public function listarEspecialidadAction()
    {
        $session = $this->getSession();
        $usuario = $this->getPersistenceManager()->getRepoUserFromSessionUser($session->get('usuario'), $this);
        $repoTurnos = $this->getReposManager()->getTurnosRepo();
        $rolSeleccionado = ConsultaRol::rolSeleccionado($session);
        $turnos = $repoTurnos->turnosDelDiaDeEspecialidad($usuario->getEspecialidad());
        return $this->render(
        			'SalitaTurnoBundle:Listados:turnosDelDiaEspecialidad.html.twig',
        			array('turnos' => $turnos, 'rol' => $rolSeleccionado->getCodigo())
        		);
    }
}