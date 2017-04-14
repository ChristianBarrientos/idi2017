<?php
class Registrar_Controller{

	function registrar_usuario_formulario (){
		if (!isset($_SESSION["nombre"])){		
			$tpl = new TemplatePower("templates/formulario_registro.html");
			$tpl->prepare();
			//$tpl->newBlock("correo_ingreso");
			//$tpl->gotoBlock('_ROOT');
			
		}
		else
		{
			$this->cerrar_sesion();
			header('Location: index.php');
		}
		return $tpl->getOutputContent();

	}

	function registrar_usuario_bd (){
		global $baseDatos;
		//echo "REGISTRA_USUARIO_BD";
		$nombre = $baseDatos->real_escape_string($_POST['nombre']);
		$apellido = $baseDatos->real_escape_string($_POST['apellido']);
		$correo = $baseDatos->real_escape_string($_POST['correo']);
		$pass = $baseDatos->real_escape_string($_POST['pass']);
		$dni = $baseDatos->real_escape_string($_POST['dni']);

		if($nombre != null and $apellido != null and $correo != null and $pass != null and $dni !=null){
			//echo "PrimerIF";
			$usuario = new usuario();
			if($usuario -> verificar_pass($_POST['pass'], $_POST['passrepite'])){
				//echo "SegundoIF";
				//if($usuario -> existecorreo($correo)){
					//echo "TEercerIF";
					$verificarinsert = $usuario->insert_usuario($nombre,$dni,$apellido,$correo,$pass);
					if($verificarinsert == true){
						//echo "CArgarEnLABAse";
						if (!isset($_SESSION["nombre"]) and  $_POST['nombre'] != null ){	
							$tpl = new TemplatePower("templates/bienvenida_usuario_registrado.html");
							$tpl->prepare();
							return $tpl->getOutputContent();
						}
						else{
							//echo "asdasdasdsa";
							  header('Location: index.php');
						}

					}
					else{
						$tpl = new TemplatePower("templates/error_intente_masd_tarde.html");
						$tpl->prepare();
						return $tpl->getOutputContent();
					}
				/*}
				else{

					$tpl = new TemplatePower("templates/formulario_registro.html");
					$tpl->prepare();
					$tpl->newBlock("correo_en_uso");
					return $tpl->getOutputContent();

				}*/

			}
			else{
				$tpl = new TemplatePower("templates/formulario_registro.html");
					$tpl->prepare();
					$tpl->newBlock("mal_pass");
					return $tpl->getOutputContent();


			}
				
		}
		else{
			//echo "ESTAMOSACA";
			header('Location: index.php');
		}



	}

	/*
	$ok_cantidad_resumenes = archivo::contar_resumenes($_SESSION['id_usuario']);
  				if($ok_cantidad_resumenes){
						$tpl = new TemplatePower("templates/subir_archivo.html");
						$tpl->prepare();
						$tpl->newBlock("subir_archivo");
						
					}
				else{
					    $tpl = new TemplatePower("templates/subir_archivo.html");
					    $tpl->prepare();
					    $tpl->newBlock("archivo_YaSubido");
				
  					}
	*/

  	function menu_titulos(){

  if (isset($_SESSION["nombre"]) and $_SESSION["permiso"] == 'USUARIO'){

  		$tpl = new TemplatePower("templates/menu_titulos.html");
		$tpl->prepare();
		//$tpl->newBlock("subir_archivo");

		
	}else{
		header('Location: index.php');
	}
 	
 	return $tpl->getOutputContent();

  	}

  	function agregar_titulo(){

  	if (isset($_SESSION["nombre"]) and $_SESSION["permiso"] == 'USUARIO'){
  		
  		$tpl = new TemplatePower("templates/subir_archivo.html");
			$tpl->prepare();
			$tpl->newBlock("subir_archivo");
			$tpl->newBlock("informacion_archivo");

  		/*$ok_cantidad_resumenes = archivo::contar_resumenes($_SESSION['id_usuario']);
	  		if($ok_cantidad_resumenes){
					$tpl = new TemplatePower("templates/subir_archivo.html");
					$tpl->prepare();
					$tpl->newBlock("subir_archivo");
							
				}
			else{
						  $tpl = new TemplatePower("templates/subir_archivo.html");
						  $tpl->prepare();
						  $tpl->newBlock("archivo_YaSubido");
					
	  			}*/
		
	}else{
		
		header('Location: index.php');
	}
	
  		return $tpl->getOutputContent();
  	}

	function verificar_usuario(){
		$correo = $_POST['correo'];
		$pass = md5($_POST['pass']);
		$usuario = new usuario();
		$_user = $usuario -> verificar_user($correo,$pass);
	
		if($_user){
			$regis = new Registrar_Controller();
			$regis->iniciar_session($_user);
			
			//$regis->mostrar_titulos_user();

		}
		else{

			$tpl = new TemplatePower("templates/login.html");
			$tpl->prepare();
			$tpl->newBlock("error_");
			return $tpl->getOutputContent();

  		
			}

		}

		function mostrar_titulos_user(){


			if ($_SESSION["permiso"] == 'USUARIO'){

				//echo "VERIFICAR USUARIO-SI PERMISO";
				$tpl = new TemplatePower("templates/menu_bienvenida_usuario.html");
				$tpl->prepare();
				$tpl->gotoBlock("_ROOT");
				$cant_titulos = usuario::contar_titulos($_SESSION['id_usuario']);
				
				//$tpl->newBlock("menu_bienvenida_agrega_titulo_usuario");
				
				if($cant_titulos == 0){

					
				$tpl->newBlock("boton_agregar_titulo_sintitulo");

				}
				else {


					$titulo = usuario::consultart_datos_titulos($_SESSION['id_usuario']);
					
					
					$tpl = new TemplatePower("templates/menu_bienvenida_usuario.html");
					$tpl->prepare();
					$tpl->gotoBlock("_ROOT");
					$i=1;

					foreach ($titulo as $clave ) {
						if($clave['autores'] != null and $clave['titulo'] != null and $clave['area'] != null ){

							
							$tpl->newBlock("menu_bienvenida_con_2_titulos");
							$tpl->assign("orden",$i);
							$tpl->assign("autores",$clave['autores']);
							
							$tpl->assign("titulo",$clave['titulo']);
							$tpl->assign("area",$clave['area']);
							$tpl->assign("subarea",$clave['subarea']);
							$tpl->assign("tipo",$clave['tipo']);
							//$tpl->assign("condicion",$clave['condicion']);
							$devolucion_titulo = devolucion::consultar_devolucion($clave['id_titulo']);
							if ($devolucion_titulo != null) {
								$archivo_descargar = archivo::consultar_ubicacion($devolucion_titulo['id_archivo']);
							}
							
							
							$evaluador_ok = evaluador::existe_evaluador_titulo($clave['id_titulo']);
							
							
							if(count($evaluador_ok) != null){
								$tpl->newBlock("titulo_ya_asignado");
								if($devolucion_titulo['condicion'] != null){
									$tpl->newBlock("condicion");
									$tpl->assign("condicion",$devolucion_titulo['condicion']);
									if ($devolucion_titulo != null) {
									$tpl->assign("id_archivo",$archivo_descargar['ubicacion']);
									}
									$tpl->assign("titulo_descargar",$clave['titulo']);
								}
							}
							else
							{
								$tpl->newBlock("eliminar_boton");
								$tpl->assign("id_titulo_eliminar",$clave['id_titulo']);
								}
							$i=$i+1;
							}

						}
						if($cant_titulos >= 2){
							$tpl->newBlock("Alert_Info_YaNoMas");
						}
						else{
							$tpl->newBlock("boton_agregar_titulo");	
						}

			}
			}
			else{
				if ($_SESSION["permiso"] == 'ADMIN'){
					
					$tpl = new TemplatePower("templates/menu_admin.html");
					$tpl->prepare();
					$tpl->gotoBlock("_ROOT");
				}
				if ($_SESSION["permiso"] == 'EVALUADOR'){
					$tpl = new TemplatePower("templates/menu_evaluador.html");
					$tpl->prepare();
					$tpl->gotoBlock("_ROOT");
					$id_evaluador = $_SESSION['id_usuario'];
    				$dat_usuario = usuario::consultar_datos_usuario($id_evaluador);
  
    				$titulos_asignados = evaluador::consultar_titulos_asignados($id_evaluador);
					$titulos_evaluador = evaluador::todos_los_titulos_asignados($id_evaluador);
					$tpl->assign("nombre_evaluador", $dat_usuario->getNomUs().' '.$dat_usuario->getApeUs());
					$bandera = 0;
					$salto_inecesario = 0;
					foreach ($titulos_evaluador as $res) {

					        //$tp1->newBlock("body");
						
					    $posee_devolucion = devolucion::consultar_devolucion_2($res->getTituloId(),$id_evaluador);
					   
					    /*if($posee_devolucion != null){
					    	$salto_inecesario = $salto_inecesario +1;

					    	continue;
					    }*/
					   
					    $salto_inecesario = $salto_inecesario +1;    
						        $tpl->newBlock("cuerpo_lista");
						        $tpl->assign("nombre", $res->gettituloTitulo());
						        $tpl->assign("area", $res->getareaTitulo());
						        $tpl->assign("subarea", $res->getsubareaTitulo());
						        //$tp1->assign("autores", $res->getAutores());
						        $tpl->assign("tipo", $res->getTipo());
						         $tpl->assign("id_input", $bandera.'a');
						        $bandera = $bandera +1;
						        $ok_resumen_ = titulo::consultar_resumenes_por_titulo($res->getTituloId());
						        //echo "OK_RESUMEN___";
						        //print_r($ok_resumen_);
						        $ok_archivos_resumen = array();
						        $ok_archivos_resumen_ubicacion = array();
						        $i=0;
						        foreach ($ok_resumen_ as $id_resu) {
						            //echo "VALOR_";
						            //echo $id_resu['id_resumen'];
						            $ok_archivos_resumen[]=titulo::consultar_archivos_por_resumen_2($id_resu['id_resumen']);
						            $ok_archivos_resumen_ubicacion[$i] = archivo::consultar_ubicacion( $ok_archivos_resumen[$i]['id_archivo']);
						            $i = $i +1;
						        }
						    

						        for ($i=0; $i < count($ok_archivos_resumen) ; $i++) { 
						           
						           if($i == 0){

						                $tpl->newBlock("con_descarga_i");
						                $nombre_descargar = $res->gettituloTitulo().'_RESUMEN_I'.'.'.$ok_archivos_resumen_ubicacion[$i]['tipo'];
						                $tpl->assign("id_archivo", $ok_archivos_resumen_ubicacion[$i]['ubicacion']);   
						                $tpl->assign("titulo_descargar", $nombre_descargar);
						           }
						           else{
						                $tpl->newBlock("con_descarga_ii");
						                $nombre_descargar = $res->gettituloTitulo().'_RESUMEN_II'.'.'.$ok_archivos_resumen_ubicacion[$i]['tipo'];
						                $tpl->assign("id_archivo", $ok_archivos_resumen_ubicacion[$i]['ubicacion']);
						                $tpl->assign("titulo_descargar", $nombre_descargar);
						           }
						        }

						        if(count(($ok_archivos_resumen)) == 0){
						            $tpl->newBlock("con_descarga_i");
						            $tpl->assign("sin_archivo", '---');
						            $tpl->newBlock("con_descarga_ii");
						            $tpl->assign("sin_archivo", '---');

						        }

						        $ok_trabajo_final = titulo::consultar_id_trabajoFinal_2($res->getTituloId());
						        //echo "TRABAJO_FINAL";
						       // echo $ok_trabajo_final;
						        //print_r($ok_trabajo_final);
						        if($ok_trabajo_final[0]['id_trabajoFinal'] != null){
						           
						            $id_archivo_trabajo_final = titulo::consultar_archivo_trabajoFinal_2($ok_trabajo_final[0]['id_trabajoFinal']);

						            $nombre_descargar = $res->gettituloTitulo().'_TRABAJO_FINAL'.'.'.$id_archivo_trabajo_final[0]['tipo'];
						            $tpl->newBlock("con_descarga_iii");
						            $tpl->assign("id_archivo", $id_archivo_trabajo_final[0]['ubicacion']);
						            $tpl->assign("titulo_descargar", $nombre_descargar);
						        }
						        else{
						            //$tp1->newBlock("con_descarga_iii");
						            $tpl->assign("sin_archivo", '---');

						        	}
						      

						      
						       $ok_devolucion = devolucion::consultar_devolucion_2($res->getTituloId(),$id_evaluador);
						       
						       
						       if($ok_devolucion != null){
						       		$tpl->newBlock("ver_devolucion");
						       		$tpl->assign("id_titulo", $res->getTituloId());
						       		$tpl->assign("id_evaluador", $id_evaluador);
						       }
						       else{
						       		$tpl->newBlock("realiza_devolucion");
						       		$tpl->assign("id_titulo", $res->getTituloId());
						       		$tpl->assign("id_evaluador", $id_evaluador);


						       }
					        
					                
					        }
      


				}


			}
			
  		return $tpl->getOutputContent();
	
	}

	function iniciar_session ($_user){
		$_SESSION["nombre"] = $_user["nombre"];
		$_SESSION["apellido"] = $_user["apellido"];      
		$_SESSION["correo"] = $_user["correo"];
	    $_SESSION["id_usuario"] = $_user["id_usuario"];
	    //echo "idusuario--->$_user['id_usuario']";
	    $_SESSION["permiso"] = $_user["permiso"];
	    
	}

	
	function cerrar_sesion(){
		session_destroy();
        header('Location: index.php');
	}



	function formulario_evaluador(){
		$tpl = new TemplatePower("templates/formulario_evaluador.html");
		$tpl->prepare();
		$tpl->gotoBlock("_ROOT");
		return $tpl->getOutputContent();

	}

	function tramite_evaluador(){
		global $baseDatos;
		$nombre = $baseDatos->real_escape_string($_POST['nombre']);
		$apellido = $baseDatos->real_escape_string($_POST['apellido']);
		$correo = $baseDatos->real_escape_string($_POST['correo']);
		
		$dni = $baseDatos->real_escape_string($_POST['dni']);

		if($nombre != null and $apellido != null and $correo != null ){
			$usuario = new usuario();

				//if($usuario -> existecorreo($correo)){
					$ok_nuevo_usuario_dni = evaluador::ok_nuevo_usuario_dni($dni);
					/*if ($ok_nuevo_usuario_dni != null) {
					$tpl = new TemplatePower("templates/formulario_evaluador.html");
					$tpl->prepare();
					$tpl->newBlock("dni_en_uso");
					return $tpl->getOutputContent();
						}*/
					$tpl = new TemplatePower("templates/datos_evaluador_recibidos.html");
					$tpl->prepare();
					

					$agregar_pendiente = evaluador::agregar_pendiente($nombre,$dni,$apellido,$correo);
					return $tpl->getOutputContent();
					
				/*}
				else{

					$tpl = new TemplatePower("templates/formulario_evaluador.html");
					$tpl->prepare();
					$tpl->newBlock("correo_en_uso");
					return $tpl->getOutputContent();
				}	*/
		}
		else{
			//echo "ESTAMOSACA";
			header('Location: index.php');
		}



	}

	function registrar_evaluador_pendiente(){
		$id_pendiente = $_GET['id_pendiente'];
		if ($id_pendiente < 0 OR $id_pendiente == null) {
			header('Location: index.php');
			# code...
		}
		$dat_pendiente = evaluador::consultar_datos_pendiente($id_pendiente);
		$usuario = $_POST['usuario_nuevo'];
		$pass = $_POST['pass_nuevo'];

		$ok_nuevo_usuario = evaluador::ok_nuevo_usuario($usuario);
		//
		//$ok_nuevo_usuario_correo = evaluador::ok_nuevo_usuario_dni($dat_pendiente['correo']);
		if ($ok_nuevo_usuario != null) {
			$tpl = new TemplatePower("templates/usuario_evaluador_pendiente.html");
				$tpl->prepare();
				
				$tpl->assign("apellido", $apellido);
        		$tpl->assign("nombre", $nombre);
        		$tpl->assign("dni", $dni);
        		$tpl->assign("correo", $dat_pendiente['correo']);
        		$tpl->newBlock("no_user");
        		$tpl->assign("usuario", $correo);
        		$tpl->assign("pass", $pass);
				return $tpl->getOutputContent();
		}

		global $baseDatos;
		//echo "REGISTRA_USUARIO_BD";
		$nombre = $baseDatos->real_escape_string($dat_pendiente['nombre']);
		$apellido = $baseDatos->real_escape_string($dat_pendiente['apellido']);
		$correo = $baseDatos->real_escape_string($usuario);
		$pass = $baseDatos->real_escape_string($pass);
		$dni = $baseDatos->real_escape_string($dat_pendiente['dni_usuario']);

		if($nombre != null and $apellido != null and $correo != null and $pass != null and $dni !=null){
			//echo "PrimerIF";
			$usuario = new usuario();
					$verificarinsert = $usuario->insert_usuario_pendiente($nombre,$dni,$apellido,$correo,$pass);
					if($verificarinsert == true){
						//echo "CArgarEnLABAse";
						//$ok_rechazo = evaluador::rechazar_pendiente($id_pendiente);
							//AND $ok_rechazo
							$tpl = new TemplatePower("templates/condicion_pendiente.html");
							$tpl->prepare();
							$tpl->newBlock("aceptado");
							$tpl->assign("apellido", $apellido);
        					$tpl->assign("nombre", $nombre);
        					$tpl->assign("dni", $dni);
        					$tpl->assign("correo", $dat_pendiente['correo']);
        					$tpl->assign("usuario", $correo);
        					$tpl->assign("pass", $pass);
							return $tpl->getOutputContent();
						

					}
					else{
						$tpl = new TemplatePower("templates/error_intente_masd_tarde.html");
						$tpl->prepare();
						return $tpl->getOutputContent();
					}
				

		
				
		}
		else{
			//echo "ESTAMOSACA";
			header('Location: index.php');
		}


	}

	function genera_usuario($nom, $ap){
    //Variables
		
		$usuario_name = substr($nom, 0, 1).$ap;

		
		$ok_user_name = usuario::consultar_usuario_2($usuario_name);
		if ($ok_user_name == null) {
			# code...
			
			return $usuario_name;
		}
		else{
			
			$DesdeLetra = "a";
			$HastaLetra = "z";
			$DesdeNumero = 1;
			$HastaNumero = 10000;
			$letraAleatoria = chr(rand(ord($DesdeLetra), ord($HastaLetra)));
			$numeroAleatorio = rand($DesdeNumero, $HastaNumero);
			return $this->genera_usuario($nom.$letraAleatoria, $ap.$numeroAleatorio);
		}
		
		}

		function generar_pass(){
			$DesdeLetra = "a";
			$HastaLetra = "z";
			$DesdeNumero = 1;
			$HastaNumero = 10000;
			$pass = null;
			for ($i=0; $i < 4; $i++) { 
				$letraAleatoria = chr(rand(ord($DesdeLetra), ord($HastaLetra)));
				$letraAleatoria2 = chr(rand(ord($DesdeLetra), ord($HastaLetra)));
				$numeroAleatorio = rand($DesdeNumero, $HastaNumero);
				$numeroAleatorio2 = rand($DesdeNumero, $HastaNumero);
				$letraAleatoria = strtoupper($letraAleatoria);
				$letraAleatoria2 = strtoupper($letraAleatoria2);
				$pass = $letraAleatoria.$numeroAleatorio.$letraAleatoria2;
			}
			
			
			return $pass;
		}

		function mandar_mail($usuario,$pass,$correo_destino){
			$mail = "Prueba de mensaje";
			//Titulo
			$titulo = "PRUEBA DE TITULO";
			//cabecera
			$headers = "MIME-Version: 1.0\r\n"; 
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
			//dirección del remitente 
			$headers .= "From: Geeky Theory < tu_dirección_email >\r\n";
			//Enviamos el mensaje a tu_dirección_email 
			$bool = mail($correo_destino,$titulo,$mail,$headers);
			if($bool){
			    echo "Mensaje enviado";
			}else{
			    echo "Mensaje no enviado";
			}
		}



	function tramite_evaluador_2(){
		global $baseDatos;
		$nombre = $baseDatos->real_escape_string($_POST['nombre']);
		$apellido = $baseDatos->real_escape_string($_POST['apellido']);
		$correo = $baseDatos->real_escape_string($_POST['correo']);
		$dni = $baseDatos->real_escape_string($_POST['dni']);
		$dni = $dni.'.';

		$usuario_ = $this->genera_usuario($nombre, $apellido);
		$pass = $this->generar_pass();
		

		$verificarinsert = usuario::insert_usuario_2($nombre,$dni,$apellido,$correo,$pass,$usuario_);
		if($verificarinsert == true){
			

			$ok_mail = $this->mandar_mail ($usuario_,$pass,$correo);
			if (!isset($_SESSION["nombre"]) and  $_POST['nombre'] != null ){	
				$tpl = new TemplatePower("templates/bienvenida_usuario_registrado.html");
				$tpl->prepare();
				return $tpl->getOutputContent();
				}
			else{
							//echo "asdasdasdsa";
				header('Location: index.php');
				}

			}
			else{
				$tpl = new TemplatePower("templates/error_intente_masd_tarde.html");
				$tpl->prepare();
				return $tpl->getOutputContent();
					}


	}


	
		


}

?>