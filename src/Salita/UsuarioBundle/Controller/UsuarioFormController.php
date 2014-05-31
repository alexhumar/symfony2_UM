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

    function listUsuarioAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repoUsuarios = $em->getRepository('SalitaUsuarioBundle:Usuario');
        $usuarios = $repoUsuarios->encontrarUsuariosOrdenadosPorNombre();
        return $this->render('SalitaUsuarioBundle:UsuarioForm:listado.html.twig', array('usuarios' => $usuarios,
            ));
    }

    function listSecretariaAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repoUsuarios = $em->getRepository('SalitaUsuarioBundle:Usuario');
        $secretarias = $repoUsuarios->encontrarSecretariasOrdenadasPorNombre();
        return $this->render('SalitaUsuarioBundle:UsuarioForm:listadoSecretarias.html.twig', array('usuarios' => $secretarias,
            ));
    }

    function listAdministradorAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repoUsuarios = $em->getRepository('SalitaUsuarioBundle:Usuario');
        $administradores = $repoUsuarios->encontrarAdministradoresOrdenadosPorNombre();
        return $this->render('SalitaUsuarioBundle:UsuarioForm:listadoAdministradores.html.twig', array('usuarios' => $administradores,
            ));
    }

    function listMedicoAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repoUsuarios = $em->getRepository('SalitaUsuarioBundle:Usuario');
        $medicos = $repoUsuarios->encontrarMedicosOrdenadosPorNombre();
        return $this->render('SalitaUsuarioBundle:UsuarioForm:listadoMedicos.html.twig', array('usuarios' => $medicos,
            ));
    }
          
    public function modifAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repoUsuarios = $em->getRepository('SalitaUsuarioBundle:Usuario');
        $usuario = $repoUsuarios->findOneById($id);
        $rol = $request->getSession()->get('rolSeleccionado');
        $form = $this->createForm(new UsuarioType($rol),$usuario);
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);   
            if ($form->isValid())
            {
                $em->persist($usuario);
                $em->flush();
                return $this->render('SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig', array('mensaje' => 'Los datos del usuario fueron modificados exitosamente',
            ));
            }
            else 
            {
                return $this->render('SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig', array('mensaje' => 'Se produjo un error al intentar modificar los datos del usuario',
            ));
            }            
        }
        else
        {
            return $this->render('SalitaUsuarioBundle:UsuarioForm:modif.html.twig', array('form' => $form->createView(),'id' => $id,
            ));
        }
    }

    public function modifPropioAction(Request $request)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getEntityManager();
        $usuario = $session->get('usuario');
        $form = $this->createForm(new UsuarioType(), $usuario);
        $rolSeleccionado = ConsultaRol::rolSeleccionado($session);
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);     
            if ($form->isValid())
            {
                //$em->update($usuario);
                $em->flush();
                $session->set('usuario', $usuario);
                return $this->render('SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig', array('mensaje' => 'Sus datos fueron modificados exitosamente',
            ));
            }
            else 
            {
                return $this->render('SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig', array('mensaje' => 'Se produjo un error al intentar modificar sus datos',
            ));
            }            
        }
        else
        {
            return $this->render('SalitaUsuarioBundle:UsuarioForm:modifPropio.html.twig', array('form' => $form->createView(),'rol' => $rolSeleccionado->getCodigo(),
            ));
        }
    }
    
    public function delSecretariaAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repoUsuarios = $em->getRepository('SalitaUsuarioBundle:Usuario');
        $usuario = $repoUsuarios->find($id);
        $em->remove($usuario);     
        $em->flush();
        return $this->redirect($this->generateUrl('listado_secretaria')); 
    }

    public function delMedicoAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repoUsuarios = $em->getRepository('SalitaUsuarioBundle:Usuario');
        $repoRoles = $em->getRepository('SalitaUsuarioBundle:Rol');
        $usuario = $repoUsuarios->find($id);
        
        if(!$usuario->hasRole('ROLE_ADMINISTRADOR'))
        {
            $usuario->setEnabled(false);
        }
        $rol = $repoRoles->findOneByCodigo("'ROLE_MEDICO'");
        $usuario->quitarRol($rol);
        $em->persist($usuario);    
        $em->flush();
        return $this->redirect($this->generateUrl('listado_medico'));
    }

    public function delAdministradorAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repoUsuarios = $em->getRepository('SalitaUsuarioBundle:Usuario');
        $repoRoles = $em->getRepository('SalitaUsuarioBundle:Rol');
        $usuario = $repoUsuarios->find($id);
        if(!$usuario->hasRole('ROLE_MEDICO'))
        {
            $usuario->setEnabled(false);
        }
        $rol = $repoRoles->findOneByCodigo("'ROLE_ADMINISTRADOR'");
        $usuario->quitarRol($rol);
        $em->persist($usuario);    
        $em->flush();
        return $this->redirect($this->generateUrl('listado_administrador')); 
    }

    public function asignarRolAction(Request $request)
    {
        //$session = $this->container->get('session');
        $session = $this->container->get('request')->getSession();
        $em = $this->getDoctrine()->getEntityManager();
        $repoRoles = $em->getRepository('SalitaUsuarioBundle:Rol');
        $roles = $repoRoles->findAll();
        $rolTemp = new RolTemporal();  
        $form = $this->createForm(new RolType($roles), $rolTemp);
        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);
            if ($form->isValid())
            {
                $rolElegido = $repoRoles->findOneByCodigo($rolTemp->getNombre());
                $usuario = $session->get('usuarioSeleccionado');
                if($usuario->hasRole($rolElegido->getCodigo()))
                {
                    $mensaje = 'El usuario ya tiene el rol que ha elegido';
                }
                else
                {
                    if(($usuario->hasRole('ROLE_SECRETARIA')) && ($rolElegido->getCodigo() == 'ROLE_ADMINISTRADOR'))
                    {
                        $mensaje = 'Un usuario con rol secretaria no puede ser administrador';
                    }
                    else
                    {
                        if(($usuario->hasRole('ROLE_ADMINISTRADOR')) && ($rolElegido->getCodigo() == 'ROLE_SECRETARIA'))
                        {
                            $mensaje = 'Un usuario con rol administrador no puede ser secretaria';
                        }
                        else
                        {
                            if(($usuario->hasRole('ROLE_MEDICO')) && ($rolElegido->getCodigo() == 'ROLE_SECRETARIA'))
                            {
                                $mensaje = 'Un usuario con rol medico no puede ser secretaria'; 
                            }
                            else
                            {
                                if(($usuario->hasRole('ROLE_SECRETARIA')) && ($rolElegido->getCodigo() == 'ROLE_MEDICO'))
                                {
                                    $mensaje = 'Un usuario con rol secretaria no puede ser medico';
                                }
                                else
                                {
                                    $mensaje = 'El rol se asigno exitosamente al usuario';
                                    $usuario->setEnabled(true);
                                    $em->persist($usuario);
                                    $em->flush();
                                    if($rolElegido->getCodigo() == 'ROLE_MEDICO')
                                    {
                                        return $this->redirect($this->generateUrl('asignacion_especialidad')); 
                                    }
                                }
                            }
                        }
                    }    
                }   

                return $this->render('SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig', array('mensaje' => $mensaje,
            ));
            }
            else 
            {
                $mensaje = 'Se produjo un error al intentar asignar un rol al usuario';
                return $this->render('SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig', array('mensaje' => $mensaje,
            ));
            }            
        }
        else
        {
            return $this->render('SalitaUsuarioBundle:UsuarioForm:asignacionRol.html.twig', array('form' => $form->createView(),));
        }
    }

    public function asignarEspecialidadAction(Request $request)
    {
        //$session = $this->container->get('session');
        $session = $this->container->get('request')->getSession();
        $em = $this->getDoctrine()->getEntityManager();
        if($session->has('usuarioSeleccionado'))
        {
            $usuario = $session->get('usuarioSeleccionado');
        }
        else
        {
            return $this->redirect($this->generateUrl('listado_usuario'));
        }
        if ($usuario->hasRole('ROLE_MEDICO'))
        {
            $form = $this->createForm(new EspecialidadUsuarioType(), $usuario);
            if ($request->getMethod() == 'POST')
            {
                $form->bindRequest($request);
                if ($form->isValid())
                {
                    $em->persist($usuario);
                    $em->flush();
                    $session->remove('usuarioSeleccionado');
                    $mensaje = 'La especialidad fue asignada exitosamente al medico';
                    return $this->render('SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig', array('mensaje' => $mensaje,
                    ));
                }
                else 
                {
                    $mensaje = 'Se produjo un error al intentar asignar una especialidad al medico';
                    return $this->render('SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig', array('mensaje' => $mensaje,
                    ));
                }            
            }
            else
            {
                return $this->render('SalitaUsuarioBundle:UsuarioForm:asignacionEspecialidad.html.twig', array('form' => $form->createView(),));
            }
        }
        else
        {
            $mensaje = 'El usuario no es un medico';
            return $this->render('SalitaUsuarioBundle:UsuarioForm:mensaje.html.twig', array('mensaje' => $mensaje,
            ));           
        }
    }

    public function seleccionarAction(Request $request, $id)
    {
        //$session = $this->container->get('session');
        $session = $this->container->get('request')->getSession();
        $em = $this->getDoctrine()->getEntityManager();
        $repoUsuarios = $em->getRepository('SalitaUsuarioBundle:Usuario');
        $usuario = $repoUsuarios->findOneById($id);
        $session->set('usuarioSeleccionado', $usuario);
        return $this->redirect($this->generateUrl('asignacion_rol'));
    }
}
