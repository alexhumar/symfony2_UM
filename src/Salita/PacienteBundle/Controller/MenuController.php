<?php
namespace Salita\PacienteBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Salita\OtrosBundle\Clases\ConsultaRol;
use Salita\OtrosBundle\Clases\ConsultaEspecialidad;
use Salita\UsuarioBundle\Entity\Especialidad;

class MenuController extends Controller
{

    public function principalAction(Request $request)
    {
       $session = $request->getSession();
       if(!$session->has('paciente'))
       {
           return $this->redirect($this->generateUrl('busqueda_paciente'));
       }
       else
       {
           $rolSeleccionado = ConsultaRol::rolSeleccionado($session);
           /*if ($rolSeleccionado->getCodigo() == 'ROLE_MEDICO')*/
           if ($rolSeleccionado->isRoleMedico())
           {
               $especialidad = ConsultaEspecialidad::especialidadSeleccionada();
               $codigoEspecialidad = $especialidad->getCodigo();
           }
           else 
           {
               //$codigoEspecialidad = 'NO TIENE';
               $codigoEspecialidad = Especialidad::getCodigoNoTieneEspecialidad();
           }
           $paciente = $session->get('paciente');
           return $this->render(
           			'SalitaPacienteBundle:Menu:principal.html.twig',
           			array('paciente' => $paciente, 'rol' =>$rolSeleccionado->getCodigo(), 
           				  'nombreRol' =>$rolSeleccionado->getNombre(),'especialidad' => $codigoEspecialidad)
           		);
       }
    }
}
