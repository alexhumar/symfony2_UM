<?php
namespace Salita\UsuarioBundle\Controller;

use Salita\UsuarioBundle\Form\Type\UsuarioType;
use Salita\UsuarioBundle\Entity\Usuario;
use Salita\UsuarioBundle\Entity\Rol;
use Salita\UsuarioBundle\Entity\Especialidad;
use Salita\UsuarioBundle\Form\Type\RolType;
use Salita\UsuarioBundle\Clases\RolTemporal;
use Salita\UsuarioBundle\Form\Type\EspecialidadUsuarioType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Salita\OtrosBundle\Clases\ConsultaRol;

class UsuarioFormController extends Controller
{
    private function getEntityManager()
    {
        return $this->getDoctrine()->getEntityManager();
    }
    
    private function getRolesRepo()
    {
    	$em = $this->getEntityManager();
    	return $em->getRepository('SalitaUsuarioBundle:Rol');
    }

	private function getUsersRepo()
	{
		$em = $this->getEntityManager();
        return $em->getRepository('SalitaUsuarioBundle:Usuario');
	}
	
    private function getRepoUserFromSessionUser($usuario)
	{
		$repoUsuarios = $this->getUsersRepo();
		$usuario = $repoUsuarios->findOneById($usuario->getId());
		return $usuario;
	}
	
	/*Esta funcion encapsula la logica de negocio relacionada a la asignacion de rol a un usuario.
	 * Retona en un boolean si la operacion fue exitosa o no, y almacena en la sesion el string especificando 
	 * el resultado exacto de la operacion.*/
	private function assignRoleToUser($usuario, $rol, $session)
	{
		$exito = false;
		$mensaje = "";
		
		if($usuario->hasRole($rol->getCodigo()))
		{
			$mensaje = 'El usuario ya tiene el rol que ha elegido';
		}
		else
		{
			if(($usuario->hasRole('ROLE_SECRETARIA')) && ($rol->getCodigo() == 'ROLE_ADMINISTRADOR'))
			{
				$mensaje = 'Un usuario con rol secretaria no puede ser administrador';
			}
			else
			{
				if(($usuario->hasRole('ROLE_ADMINISTRADOR')) && ($rol->getCodigo() == 'ROLE_SECRETARIA'))
				{
					$mensaje = 'Un usuario con rol administrador no puede ser secretaria';
				}
				else
				{
					if(($usuario->hasRole('ROLE_MEDICO')) && ($rol->getCodigo() == 'ROLE_SECRETARIA'))
					{
						$mensaje = 'Un usuario con rol medico no puede ser secretaria';
					}
					else
					{
						if(($usuario->hasRole('ROLE_SECRETARIA')) && ($rol->getCodigo() == 'ROLE_MEDICO'))
						{
							$mensaje = 'Un usuario con rol secretaria no puede ser medico';
						}
						else
						{
							$usuario->setEnabled(true);
							$usuario->agregarRol($rol);
							$em = $this->getEntityManager();
							$em->persist($usuario);
							$em->flush();
							/*Se "refresca" el usuario almacenado en la sesion*/
							$session->set('usuarioSeleccionado',$usuario);
							$exito = true;
							$mensaje = 'El rol se asigno exitosamente al usuario';
						}
					}
				}
			}
		}
		$session->set('mensaje_asignacion_rol', $mensaje);
		return $exito;
	}

    public function listUsuarioAction(Request $request)
    {
        $repoUsuarios = $this->getUsersRepo();
        $usuarios = $repoUsuarios->encontrarUsuariosOrdenadosPorNombre();
        return $this->render(
        			'SalitaUsuarioBundle:UsuarioForm:listado.html.twig',
        			array('usuarios' => $usuarios)
        		);
    }

    public function listSecretariaAction(Request $request)
    {
    	$repoUsuarios = $this->getUsersRepo();
        $secretarias = $repoUsuarios->encontrarSecretariasOrdenadasPorNombre();
        return $this->render(
        			'SalitaUsuarioBundle:UsuarioForm:listadoSecretarias.html.twig',
        			array('usuarios' => $secretarias)
        		);
    }

    public function listAdministradorAction(Request $request)
    {
        $repoUsuarios = $this->getUsersRepo();
        $administradores = $repoUsuarios->encontrarAdministradoresOrdenadosPorNombre();
        return $this->render(
        			'SalitaUsuarioBundle:UsuarioForm:listadoAdministradores.html.twig',
        			array('usuarios' => $administradores)
        		);
    }

    public function listMedicoAction(Request $request)
    {
        $repoUsuarios = $this->getUsersRepo();
        $medicos = $repoUsuarios->encontrarMedicosOrdenadosPorNombre();
        return $this->render(
        			'SalitaUsuarioBundle:UsuarioForm:listadoMedicos.html.twig',
        			array('usuarios' => $medicos)
        		);
    }
    
    /*Modificacion de algun usuario (fase GET)*/
    public function modifAction(Request $request, $id)
    {
    	$repoUsuarios = $this->getUsersRepo();
    	$usuario = $repoUsuarios->findOneById($id);
    	/*Si no existe el usuario*/
    	if(!$usuario)
    	{
    		throw $this->createNotFoundException("El usuario no existe");
    	}
    	$form = $this->createForm(new UsuarioType(),$usuario);
    	return $this->render(
    			'SalitaUsuarioBundle:UsuarioForm:modif.html.twig',
    			array('form' => $form->createView(),'id' => $id)
    	);
    }
    
    /*Modificacion de algun usuario (fase POST)*/
    public function modifProcessAction(Request $request, $id)
    {
    	$repoUsuarios = $this->getUsersRepo();
    	$usuario = $repoUsuarios->findOneById($id);
    	/*Si no existe el usuario*/
    	if(!$usuario)
    	{
    		throw $this->createNotFoundException("El usuario no existe");
    	}
    	$form = $this->createForm(new UsuarioType(),$usuario);
    	$form->handleRequest($request);
    	if ($form->isValid())
    	{
    		$em = $this->getEntityManager();
    		/*Ojo: esta linea puede que sea motivo de falla*/
    		$em->persist($usuario);
    		$em->flush();
    		return $this->render(
    				'SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig',
    				array('mensaje' => 'Los datos del usuario fueron modificados exitosamente')
    		);
    	}
    	else
    	{
    		return $this->render(
    				'SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig',
    				array('mensaje' => 'Se produjo un error al intentar modificar los datos del usuario')
    		);
    	}		
    }

    /*Modificacion de usuario propio (fase GET)*/
    public function modifPropioAction(Request $request)
    {
        $session = $request->getSession();
    	$rolSeleccionado = ConsultaRol::rolSeleccionado($session);
    	$usuario = $this->getRepoUserFromSessionUser($session->get('usuario'));
    	$form = $this->createForm(new UsuarioType(), $usuario);
    	return $this->render(
    				'SalitaUsuarioBundle:UsuarioForm:modifPropio.html.twig',
    				array('form' => $form->createView(),'rol' => $rolSeleccionado->getCodigo())
    			);
    }

    /*Modificacion de usuario propio (fase POST)*/
    public function modifPropioProcessAction(Request $request)
    {
        $em = $this->getEntityManager();
        $session = $request->getSession();
    	$usuario = $this->getRepoUserFromSessionUser($session->get('usuario'));
        $form = $this->createForm(new UsuarioType(), $usuario);
    	$form->handleRequest($request);
    	if ($form->isValid())
    	{
            $em = $this->getEntityManager();
            $em->flush();
            /*Se "refresca" el usuario almacenado en la sesion*/
    	    $session->set('usuario', $usuario);
            return $this->render(
    				'SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig',
    				array('mensaje' => 'Sus datos fueron modificados exitosamente')
    			  );
    	}
    	else
    	{
    		return $this->render(
    				'SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig',
    				array('mensaje' => 'Se produjo un error al intentar modificar sus datos')
    			);
    	}
    }

    public function delSecretariaAction(Request $request, $id)
    {
    	$em = $this->getEntityManager();
        $repoUsuarios = $this->getUsersRepo();
        $usuario = $repoUsuarios->find($id);
        /*Si no existe el usuario*/
        if (!$usuario)
        {
        	throw $this->createNotFoundException("El usuario no existe");
        }
        $em->remove($usuario);
        $em->flush();
        return $this->redirect($this->generateUrl('listado_secretaria'));
    }

    public function delMedicoAction(Request $request, $id)
    {
        $em = $this->getEntityManager();
        $repoUsuarios = $this->getUsersRepo();
        $repoRoles = $this->getRolesRepo();
        $usuario = $repoUsuarios->find($id);
        /*Si no existe el usuario*/
        if(!$usuario)
        {
        	throw $this->createNotFoundException("El usuario no existe");
        }
        /*Si el usuario solamente es medico, lo deshabilita*/
        if(!$usuario->hasRole('ROLE_ADMINISTRADOR'))
        {
        	$usuario->setEnabled(false);
        }
        $rol = $repoRoles->findOneByCodigo("'ROLE_MEDICO'");
        $usuario->quitarRol($rol);
        /*Guarda con esta linea de codigo...*/
        $em->persist($usuario);
        $em->flush();
        return $this->redirect($this->generateUrl('listado_medico'));   
    }

    public function delAdministradorAction(Request $request, $id)
    {
        $em = $this->getEntityManager();
        $repoUsuarios = $this->getUsersRepo();
        $repoRoles = $this->getRolesRepo();
        $usuario = $repoUsuarios->find($id);
        if(!$usuario)
        {
        	throw $this->createNotFoundException("El usuario no existe");
        }
        /*Si el usuario es solamente administrador*/
        if(!$usuario->hasRole('ROLE_MEDICO'))
        {
            $usuario->setEnabled(false);
        }
        $rol = $repoRoles->findOneByCodigo("'ROLE_ADMINISTRADOR'");
        $usuario->quitarRol($rol);
        /*Guarda con esta linea de codigo...*/
        $em->persist($usuario);    
        $em->flush();
        return $this->redirect($this->generateUrl('listado_administrador')); 
    }
    
    /*Asignacion de rol a usuario (fase GET)*/
    public function asignarRolAction(Request $request)
    {
    	$repoRoles = $this->getRolesRepo();
    	$roles = $repoRoles->findAll();
    	$rolTemp = new RolTemporal();
    	/*Crea un formulario con un combo box con los roles existentes para asignar al usuario.
    	 *El rol seleccionado queda cargado en un objeto de clase RolTemporal.*/
    	$form = $this->createForm(new RolType($roles), $rolTemp);
    	return $this->render(
    				'SalitaUsuarioBundle:UsuarioForm:asignacionRol.html.twig',
    				array('form' => $form->createView())
    			);
    }
    
    /*Asignacion de rol a usuario (fase POST)*/
    public function asignarRolProcessAction(Request $request)
    {
    	$repoRoles = $this->getRolesRepo();
    	$roles = $repoRoles->findAll();
    	$rolTemp = new RolTemporal();
    	$form = $this->createForm(new RolType($roles), $rolTemp);
   		$form->handleRequest($request);
   		if ($form->isValid())
   		{
   			$session = $request->getSession();
   			$rolElegido = $repoRoles->findOneByCodigo($rolTemp->getNombre());
   			if (!$session->has('usuarioSeleccionado')) 
   			{
   				return $this->redirect($this->generateUrl('listado_usuario'));
   			}
   			$usuario = $this->getRepoUserFromSessionUser($session->get('usuarioSeleccionado'));
   			/*Asigna el rol elegido al usuario y retorna un mensaje en base al resultado de las validaciones*/
			if ($this->assignRoleToUser($usuario, $rolElegido, $session))
			{
				/*Si se asigno exitosamente el rol y el rol elejido fue el de medico, debe asignarse la especialidad*/
				if($rolElegido->getCodigo() == 'ROLE_MEDICO')
				{
					return $this->redirect($this->generateUrl('asignacion_especialidad'));
				}
			}
			$mensaje = $session->get('mensaje_asignacion_rol');
   			return $this->render(
   						'SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig',
   						array('mensaje' => $mensaje)
   					);
    	}
   		else
   		{
   			$mensaje = 'Se produjo un error al intentar asignar un rol al usuario';
   			return $this->render(
   						'SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig',
   						array('mensaje' => $mensaje)
   					);
   		}
    }
    
 
    
    /*Asignacion de especialidad a usuario medico (fase GET)*/
    public function asignarEspecialidadAction(Request $request)
    {
    	$session = $request->getSession();
    	if($session->has('usuarioSeleccionado'))
    	{
    		$usuario = $this->getRepoUserFromSessionUser($session->get('usuarioSeleccionado'));
    	}
    	else
    	{
    		return $this->redirect($this->generateUrl('listado_usuario'));
    	}
    	if ($usuario->hasRole('ROLE_MEDICO'))
    	{
    		$form = $this->createForm(new EspecialidadUsuarioType(), $usuario);
   			return $this->render(
   						'SalitaUsuarioBundle:UsuarioForm:asignacionEspecialidad.html.twig',
   						array('form' => $form->createView())
   					);
    	}
    	else
    	{
    		$mensaje = 'El usuario no es un medico';
    		return $this->render(
    					'SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig',
    					array('mensaje' => $mensaje)
    				);
    	}
    }
    
    /*Asignacion de especialidad a usuario medico (fase POST)*/
    public function asignarEspecialidadProcessAction(Request $request)
    {
    	$session = $request->getSession();
    	$em = $this->getEntityManager();
    	if($session->has('usuarioSeleccionado'))
    	{
    		$usuario = $this->getRepoUserFromSessionUser($session->get('usuarioSeleccionado'));
    	}
    	else
    	{
    		return $this->redirect($this->generateUrl('listado_usuario'));
    	}
    	if ($usuario->hasRole('ROLE_MEDICO'))
    	{
    		$form = $this->createForm(new EspecialidadUsuarioType(), $usuario);
    		$form->handleRequest($request);
    		if ($form->isValid())
    		{
    			$em->persist($usuario);
    			$em->flush();
    			$session->remove('usuarioSeleccionado');
    			$mensaje = 'La especialidad fue asignada exitosamente al medico';
    			return $this->render(
    						'SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig',
    						array('mensaje' => $mensaje)
    					);
    		}
    		else
    		{
    			$mensaje = 'Se produjo un error al intentar asignar una especialidad al medico';
    			return $this->render(
    						'SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig',
    						array('mensaje' => $mensaje)
    					);
    		}
    	}
    	else
    	{
    		$mensaje = 'El usuario no es un medico';
    		return $this->render(
    					'SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig',
    					array('mensaje' => $mensaje)
    				);
    	}
    }

    public function seleccionarAction(Request $request, $id)
    {
        $session = $request->getSession();
        $repoUsuarios = $this->getUsersRepo();
        $usuario = $repoUsuarios->findOneById($id);
        if(!$usuario)
        {
        	throw $this->createNotFoundException("El usuario no existe");
        }
        /*Usuario seleccionado contiene el usuario al cual se le asignara un rol (y posiblemente especialidad).
         * Se mantiene seteado en la variable de sesion mientras dura el proceso de asignacion de rol.*/
        $session->set('usuarioSeleccionado', $usuario);
        return $this->redirect($this->generateUrl('asignacion_rol'));
    }
}
