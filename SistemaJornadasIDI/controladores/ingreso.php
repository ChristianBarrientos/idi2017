<?php

class Ingreso_Controller{


	function login (){
        
		$tpl = new TemplatePower("templates/login.html");
		$tpl->prepare();
		return $tpl->getOutputContent();

	}
/*----------------------------ADMIN----------------------------------------------------------------*/
	function verUsuarios(){
		$tp1 = new TemplatePower("templates/admin_usuarios.html");
        $tp1->prepare();
        //$tp1->gotoBlock("_ROOT");
        $us = new Usuario();
        $lista = $us->all_usuarios();
        if ($lista != null) {
            foreach ($lista as $res) {

                $tp1->newBlock("body");
                
                $tp1->assign("apellido", $res->getApeUs());
                $tp1->assign("nombre", $res->getNomUs());
                $tp1->assign("dni", $res->getDNI());
                $tp1->assign("correo", $res->getCorreo());
                $cantidad_titulos = titulo::consultar_cantidad_titulos($res->getUserId());
                //$tp1->assign("titulo", $cantidad_titulos);
                $permi_usr = $res->getPermisos();
                $tp1->assign("permiso",$permi_usr );
                if($permi_usr == 'EVALUADOR'){
                    $tp1->newBlock("boton_administrador");
                    $tp1->assign("id_usuario",$res->getUserId());
                }
                if($permi_usr == 'USUARIO'){
                    $tp1->newBlock("boton_evaluador");
                    $tp1->assign("id_usuario",$res->getUserId());
                    
                }
                $tp1->newBlock("demas_opciones");
                //$user_id_raro = ; //noce xq no lo plasma en el html
                $tp1->assign("id_usuario",$res->getUserId());
               //$tp1->assign("id_usuario",$res->getUserId());
               
                
                
            }
        }
        return $tp1->getOutputContent();

	}


function elevar_privilegio(){
    $id_usuario = $_GET['id'];
    $cantidad_titulos = titulo::consultar_cantidad_titulos($id_usuario);
    $evaluador_existe_devolucion = devolucion::consulta_existencia_devolucion($id_usuario);
    if($cantidad_titulos == 0 && $evaluador_existe_devolucion == null){
        $usuario = usuario::consultar_datos_usuario($id_usuario);
        $permiso = $usuario->getPermisos();
        $tp1 = new TemplatePower("templates/privilegios_elevar.html");
        $tp1->prepare();
        $tp1->gotoBlock("_ROOT");
        if($permiso == 'USUARIO'){
            $elevar = usuario::elevar_privilegio_evaluador($id_usuario);
            
        }
        if ($permiso == 'EVALUADOR') {
            $elevar = usuario::elevar_privilegio_admin($id_usuario);
            
        }
        if($elevar){
            $usuario_new = usuario::consultar_datos_usuario($id_usuario);
            $tp1->newBlock("ok_permiso");
            $tp1->assign("apellido", $usuario_new->getApeUs());
            $tp1->assign("nombre", $usuario_new->getNomUs());
            $tp1->assign("dni", $usuario_new->getDNI());
            $tp1->assign("permiso", $usuario_new->getPermisos());
        }
        else{
             $tp1->newBlock("no_ok_permiso");
        }


    }
    else{
        $tp1 = new TemplatePower("templates/con_titulos_evaluador.html");
        $tp1->prepare();
        $tp1->gotoBlock("_ROOT");

    }
        
    return $tp1->getOutputContent();

    //}
    }

function baja_usuario(){
   
    $id_usuario = $_GET['id'];
    $tp1 = new TemplatePower("templates/baja_usuario.html");
    $tp1->prepare();
    $usuario = usuario::consultar_datos_usuario($id_usuario);   
    $evaluador_existe_devolucion = devolucion::consultar_existencia_devoluciones_realizadas($id_usuario);
    $titulos_usuario_baja = titulo::consultar_titulos_totales($usuario->getUserId());
    if(count($titulos_usuario_baja) == 0  && $evaluador_existe_devolucion == null && $_SESSION["permiso"] = 'ADMIN'){

         foreach ($titulos_usuario_baja as $key => $id_titulo) {
            $ok_eliminacion_titulo = titulo::procedimiento_eliminar_titulo($id_titulo['id_titulo']);
            if($ok_eliminacion_titulo){
                $SI_SE_ELIMINO = TRUE;
               
            }
            else{
                $SI_SE_ELIMINO = FALSE;
                
            }
        }

    }
    
    if(isset($SI_SE_ELIMINO)){

        if($SI_SE_ELIMINO){
        $baja_usuario_ = usuario::baja_usuario($id_usuario);
    }
    }
    else{
        if(count($titulos_usuario_baja) == 0)
        {
            $baja_usuario_ = usuario::baja_usuario($id_usuario);
        }
        else{
            $baja_usuario_ = FALSE;    
        }
        
    }
    
    if ($baja_usuario_ == TRUE AND isset($baja_usuario_)) {
        $tp1->newBlock("ok_baja");
        $tp1->assign("apellido", $usuario->getApeUs());
        $tp1->assign("nombre", $usuario->getNomUs());
        $tp1->assign("dni", $usuario->getDNI());
        $tp1->assign("permiso", $usuario->getPermisos());
        $tp1->assign("titulos", $titulos_usuario_baja);
        
     }
    else {
        $tp1->newBlock("no_ok_baja");
    }

    return $tp1->getOutputContent();
    }

function resetear_contraseña(){
    $id_usuario = $_GET['id'];
    $usuario = usuario::consultar_datos_usuario($id_usuario);
    $ok_reseteo = usuario::resetear_contraseña($id_usuario,$usuario->getDNI());
    $tp1 = new TemplatePower("templates/reseteo_contrasena.html");
    $tp1->prepare();
    if($ok_reseteo){
        $tp1->newBlock("ok_contraseña");
        $tp1->assign("apellido", $usuario->getApeUs());
        $tp1->assign("nombre", $usuario->getNomUs());
        $tp1->assign("correo", $usuario->getCorreo());
        $tp1->assign("pass", $usuario->getDNI());
        

    }
    else{
         $tp1->newBlock("no_ok_contraseña");

    }
    return $tp1->getOutputContent();
}

/*----------------------------FIN ADMINISTRACION USUARIOS----------------------------------------------------------------*/

/*----------------------------ADMINISTRACION ARCHIVOS----------------------------------------------------------------*/
function listar_archivos(){
    $tp1 = new TemplatePower("templates/listar_archivos_admin.html");
    $tp1->prepare();
    
    $tp1->gotoBlock("_ROOT");
    $us = new Usuario();
    $lista = $us->all_usuarios();
    $sin_usuarios = 0;
    $sin_evaluadores = 0;

    if ($lista != null) {
        foreach ($lista as $res) {
            if ($res->getPermisos() != 'ADMIN') {
                # code...
                    
                if ($res->getPermisos() == 'USUARIO') {
                        $tp1->newBlock("usuarios");
                        $tp1->assign("apellido", $res->getApeUs());
                        $tp1->assign("nombre", $res->getNomUs());
                        $tp1->assign("correo", $res->getCorreo());
                        $titulos = titulo::consultar_titulos_totales($res->getUserId());
                        $tp1->assign("titulo", count($titulos));
                        if(count($titulos) != 0){             
                            $tp1->newBlock("con_titulo");
                            $tp1->assign("id_usuario", $res->getUserId());
                            }
                        $evaluadores_cantidad = titulo::evaluador_titulo($titulos);
                        if($evaluadores_cantidad == FALSE){
                            $tp1->newBlock("evaluador_cantidad");
                            $tp1->assign("evaluadorcantidad", 0);
                        }
                        else{
                            $tp1->newBlock("evaluador_cantidad");
                            $tp1->assign("evaluadorcantidad", $evaluadores_cantidad[0]['cant']);
                        }
                      
                        if($evaluadores_cantidad[0]['cant'] != count($titulos)) {
                            
                            $tp1->newBlock("sin_evaluador");

                        }     
                        else{

                            if($evaluadores_cantidad[0]['cant'] == 0) {

                            }
                            else{
                                 $tp1->newBlock("con_evaluador_boton_ver");
                             
                            }
                           

                        }              
                        $tp1->assign("id_usuario",$res->getUserId());
                    }
                    
                    
                    if ($res->getPermisos() == 'EVALUADOR') {
                        
                        $sin_evaluadores = 1;
                        $tp1->newBlock("usuarios_evaluadores");
                        $tp1->assign("apellido", $res->getApeUs());
                        $tp1->assign("nombre", $res->getNomUs());
                        $tp1->assign("correo", $res->getCorreo());
                        
                        $titulos_asignados = titulo::consultar_titulos_asignados_evaluador($res->getUserId());
                        
                        $tp1->assign("titulo", count($titulos_asignados));
                        if(count($titulos_asignados) != 0){            
                            $tp1->newBlock("con_titulo_evaluador");
                            $tp1->assign("id_evaluador", $res->getUserId());
                            
                            }
                        $devolucion_cantidad = devolucion::cantidad_devoluciones_por_evaluador($titulos_asignados);
                        
                        if($devolucion_cantidad == FALSE){
                            $tp1->assign("devoluciones", 0);
                        }
                       
                        if($devolucion_cantidad[0]['cant'] != 0){

                            $tp1->newBlock("sin_evaluador_evaluador");
                            $tp1->assign("devoluciones", $devolucion_cantidad[0]['cant']);

                        }
                        else{
                            $tp1->newBlock("habilita_devolucion_0");
                            $tp1->assign("devoluciones", $devolucion_cantidad[0]['cant']);
                        }
                        $tp1->assign("id_usuario",$res->getUserId());
                        
                    }
                    
                       


                    $sin_usuarios = 1;
                }
            }
        }
        
    if($sin_usuarios == 0){
        $tp1 = new TemplatePower("templates/sin_usuarios_registrados.html");   
        $tp1->prepare();
        $tp1->gotoBlock("_ROOT");

    }
    if($sin_evaluadores == 0){
        
        $tp1->newBlock("sin_evaluadores_asignados");
    }

    return $tp1->getOutputContent();


}


function ver_titulos(){
    $id_usuario = $_GET['id'];
    $dat_usuario = usuario::consultar_datos_usuario($id_usuario);
    $titulos_totales = titulo::consultar_titulos_totales($id_usuario);
    $tp1 = new TemplatePower("templates/titulos_por_usuario.html");
    $tp1->prepare();
    $tp1->gotoBlock("_ROOT");
    $tp1->assign("nombre_inscripto", $dat_usuario->getNomUs().' '.$dat_usuario->getApeUs());
    $titulos_ = new titulo();
    $lista = $titulos_->todos_los_titulos($id_usuario);
    //echo "ESTA LISTA POSEE";
    //print_r($lista);
   
    foreach ($lista as $res) {

        //$tp1->newBlock("body");
        $devolucion_titulo = devolucion::consultar_devolucion($res->getTituloId());
        $tp1->newBlock("cuerpo_lista");
        $tp1->assign("nombre", $res->gettituloTitulo());
        $tp1->assign("area", $res->getareaTitulo());
        $tp1->assign("subarea", $res->getsubareaTitulo());
        $tp1->assign("autores", $res->getAutores());
        $tp1->assign("tipo", $res->getTipo());
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
        //echo "OK_aRCHIVO_RESUMEN____";
        //print_r($ok_archivos_resumen);
        //echo $ok_archivos_resumen[0]['id_archivo'];   
        //echo $ok_archivos_resumen[1]['id_archivo'];
        //echo "CONTENIDO_COOUNTE____";
        //echo count($ok_archivos_resumen);
       
        //echo  $ok_archivos_resumen[0];
        //echo  $ok_archivos_resumen[1];
        
        for ($i=0; $i < count($ok_archivos_resumen) ; $i++) { 
           
           if($i == 0){

                $tp1->newBlock("con_descarga_i");
                $tp1->assign("id_archivo", $ok_archivos_resumen_ubicacion[$i]['ubicacion']);   
           }
           else{
                $tp1->newBlock("con_descarga_ii");
                $tp1->assign("id_archivo", $ok_archivos_resumen_ubicacion[$i]['ubicacion']);
           }
        }

        if(count(($ok_archivos_resumen)) == 0){
            $tp1->newBlock("con_descarga_i");
            $tp1->assign("sin_archivo", '---');
            $tp1->newBlock("con_descarga_ii");
            $tp1->assign("sin_archivo", '---');

        }

        $ok_trabajo_final = titulo::consultar_id_trabajoFinal_2($res->getTituloId());
        //echo "TRABAJO_FINAL";
       // echo $ok_trabajo_final;
        //print_r($ok_trabajo_final);
        if($ok_trabajo_final[0]['id_trabajoFinal'] != null){
           
            $id_archivo_trabajo_final = titulo::consultar_archivo_trabajoFinal_2($ok_trabajo_final[0]['id_trabajoFinal']);
            $tp1->newBlock("con_descarga_iii");
            $tp1->assign("id_archivo", $id_archivo_trabajo_final[0]['ubicacion']);
        }
        else{
            //$tp1->newBlock("con_descarga_iii");
            $tp1->assign("sin_archivo", '---');

        }
       
        if($devolucion_titulo['condicion'] != null){
            $tp1->newBlock("condicion");
            $condicion_devuelta = devolucion::condicion_devuelta_por_titulo($res->getTituloId());
            if ($condicion_devuelta['OK'] == 3) {
                $tp1->assign("condicion",  $condicion_devuelta['OK'].'APROBADOS');
            }
            
            
        }
        else{
            $tp1->newBlock("condicion");
            $tp1->assign("condicion", 'Sin Evaluar');
        }

       
        }
      return $tp1->getOutputContent();


}

function asigna_evaluador(){

    $id_usuario = $_GET['id'];
    $dat_usuario = usuario::consultar_datos_usuario($id_usuario);
    $titulos_totales = titulo::consultar_titulos_totales($id_usuario);
    $tp1 = new TemplatePower("templates/asignar_evaluador_admin.html");
    $tp1->prepare();
    $tp1->gotoBlock("_ROOT");
    $tp1->assign("nombre_inscripto", $dat_usuario->getNomUs().' '.$dat_usuario->getApeUs());
    $titulos_ = new titulo();
    $lista = $titulos_->todos_los_titulos($id_usuario);
    //echo "ESTA LISTA POSEE";
    //print_r($lista);
   
    foreach ($lista as $res) {

        //$tp1->newBlock("body");
        
        $tp1->newBlock("cuerpo_lista");
        $tp1->assign("nombre", $res->gettituloTitulo());
        $tp1->assign("area", $res->getareaTitulo());
        $tp1->assign("autores", $res->getAutores());
        $tp1->assign("tipo", $res->getTipo());
        $titulos_asignados = titulo::evaluador_titulo_2($res->getTituloId());
       
        if($titulos_asignados != null){
            $tp1->assign("evaluador_ok_nook", 'SI');
            $tp1->newBlock("con_evaluador_boton_ver");
            $tp1->assign("id_usuario", $dat_usuario->getUserId());

        }
        else
        {
            $tp1->newBlock("boton_activa_asigna");
            $tp1->assign("id_titulo", $res->getTituloId());
        }
           
        }
      return $tp1->getOutputContent();

}

function asigna_evaluador_elegir_evaluador(){

    $id_titulo = $_GET['id'];
    echo "``````````````";
    print_r($id_titulo);
    echo "''''''''''''''''''''''''''''''''''";
    $tp1 = new TemplatePower("templates/seleccionar_evaluador_admin.html");
    $tp1->prepare();
    $tp1->gotoBlock("_ROOT");
    $dat_titulo = titulo::consultart_datos_titulos($id_titulo);
    $titulo_nuevo = new titulo();
    $titulo_nuevo->constructor_titulo($dat_titulo[0]);
    $tp1->assign("nombre_titulo", $titulo_nuevo->gettituloTitulo());
    $tp1->assign("tipo_titulo", $titulo_nuevo->getTipo());
    $tp1->assign("autor_titulo", $titulo_nuevo->getAutores());
    $tp1->assign("area_titulo", $titulo_nuevo->getareaTitulo());
    $tp1->newBlock("id_t");
    echo "**";
    print_r($titulo_nuevo);
    echo "***";
    $tp1->assign("id_titulo", $titulo_nuevo->getTituloId());
    global $id_titulo_super;
    
    $id_titulo_super  = $titulo_nuevo->getTituloId();

    $us = new Usuario();
    $lista = $us->all_usuarios();
    $sin_evaluadores = 0;
    if ($lista != null) {
        foreach ($lista as $res) {
            if ($res->getPermisos() != 'ADMIN') {
                    if ($res->getPermisos() == 'EVALUADOR') {
                        $sin_evaluadores = 1;
                        $tp1->newBlock("usuarios_evaluadores");
                        $tp1->assign("nombre", $res->getNomUs());
                        $tp1->assign("apellido", $res->getApeUs());
                        $tp1->assign("correo", $res->getCorreo());
                        $tp1->assign("dni", $res->getDNI());
                        $titulos_asignados = titulo::consultar_titulos_asignados_evaluador($res->getUserId());
                        $tp1->assign("cantidad_trabajos_asignados", count($titulos_asignados));

                        $tp1->assign("id_usuario",$res->getUserId());            
                    }    
                }
            }
        if($sin_evaluadores == 0){
             $tp1->newBlock("sin_evaluadores_asignados");
        }
        }
    return $tp1->getOutputContent();
    }

function asignar_evaluador_final(){
    //$id_usuario_evaluador = $_POST['evaluador_radio'] ;
    if ($_GET['id_t'] < 0 OR $_GET['id_t'] == null) {
        echo "nonon";
      header('Location: index.php');
     # code...
   }
    $id_titulo = $_GET['id_t'];
    $numero=$_POST["evaluador_check_"];
    $count = count($numero);   
        
    $i = 0;
    for ($i = 0; $i < $count; $i++) {
        if($numero[$i] != null){
            if ($i == 0) {
                 $evaluador_1 = $numero[$i];  
            }
            if ($i == 1) {
                 $evaluador_2 = $numero[$i];  
            }
            if ($i == 2) {
                 $evaluador_3 = $numero[$i];  
            }
                  
       }

     }
  
    if (isset($evaluador_1)) {
        $OK_ASIGNA= $this->verificar_titulos_evaluador($evaluador_1,$id_titulo);
        if ($OK_ASIGNA == null) {
            $ok_evaluador_1 = evaluador::cargar_evaluador_bd($evaluador_1,$id_titulo);
        }
        
        
    }
    if (isset($evaluador_2)) {
        $OK_ASIGNA= $this->verificar_titulos_evaluador($evaluador_2,$id_titulo);
        if ($OK_ASIGNA == null) {
            $ok_evaluador_2 = evaluador::cargar_evaluador_bd($evaluador_2,$id_titulo);
        }
        
        
    }
    if (isset($evaluador_3)) {
        $OK_ASIGNA= $this->verificar_titulos_evaluador($evaluador_3,$id_titulo);
        if ($OK_ASIGNA == null) {
            $ok_evaluador_3 = evaluador::cargar_evaluador_bd($evaluador_3,$id_titulo);
        }
        
        
    }
    //$ok_evaluador = evaluador::cargar_evaluador_bd($id_usuario_evaluador,$id_titulo);
    $evaluador_datos_titulo = titulo::consultart_datos_titulos($id_titulo);
    $tp1 = new TemplatePower("templates/alta_evaluador.html");
    $tp1->prepare();
    if($ok_evaluador_1 ){
        $dat_eva1 = usuario::dat_usuario($evaluador_1);
        $tp1->newBlock("ok_alta");
        $tp1->assign("apellido",$dat_eva1['apellido']);
        $tp1->assign("nombre",$dat_eva1['nombre']);
        $tp1->assign("titulo",$evaluador_datos_titulo['titulo']);
        $tp1->assign("area",$evaluador_datos_titulo['area']);
        $tp1->assign("tipo",$evaluador_datos_titulo['tipo']);
        //for ($i = 0; $i < $count; $i++) {
         //}   
    }
    elseif ($ok_evaluador_2) {
        $dat_eva2 = usuario::dat_usuario($evaluador_2);
        $tp1->newBlock("ok_alta");
        $tp1->assign("apellido",$dat_eva2['apellido']);
        $tp1->assign("nombre",$dat_eva2['nombre']);
        $tp1->assign("titulo",$evaluador_datos_titulo[0]['titulo']);
        $tp1->assign("area",$evaluador_datos_titulo[0]['area']);
        $tp1->assign("tipo",$evaluador_datos_titulo[0]['tipo']);
    }
    elseif ($ok_evaluador_3) {
        $dat_eva3 = usuario::dat_usuario($evaluador_3);
        $tp1->newBlock("ok_alta");
        $tp1->assign("apellido",$dat_eva3['apellido']);
        $tp1->assign("nombre",$dat_eva3['nombre']);
        $tp1->assign("titulo",$evaluador_datos_titulo['titulo']);
        $tp1->assign("area",$evaluador_datos_titulo['area']);
        $tp1->assign("tipo",$evaluador_datos_titulo['tipo']);
    }
    else{
        echo "ERROR";
    }
    return $tp1->getOutputContent();

}


function verificar_titulos_evaluador($id_evaluador,$id_titulo)
{
    $consulta_eva = evaluador::consultar_titulo_evaluaodr_para_tres($id_evaluador,$id_titulo);
    return $consulta_eva;

}
/*----------------------------EVALUADOR----------------------------------------------------------------*/

function ver_evaluador(){
    $id_usuario = $_GET['id'];
    $tp1 = new TemplatePower("templates/ver_evaluador_admin.html");
    $tp1->prepare();
    $dat_usuario = usuario::consultar_datos_usuario($id_usuario);
    $tp1->assign("usuario_nombre", $dat_usuario->getNomUs());
    $tp1->assign("usuario_apellido", $dat_usuario->getApeUs());
    $tp1->assign("usuario_correo", $dat_usuario->getCorreo());

    $titulos_totales = titulo::consultar_titulos_totales($id_usuario);
    $titulos_ = new titulo();
    $lista = $titulos_->todos_los_titulos($id_usuario);
   
    $numero_titulo = 1;
    foreach ($lista as $titulo_nuevo) {
        $tp1->newBlock("bloque_completo");
        $tp1->assign("numero", $numero_titulo);
        $numero_titulo = $numero_titulo + 1;
        $tp1->assign("nombre_titulo", $titulo_nuevo->gettituloTitulo());
        $tp1->assign("tipo_titulo", $titulo_nuevo->getTipo());
        $tp1->assign("area_titulo", $titulo_nuevo->getareaTitulo());
        $tp1->assign("autor_titulo", $titulo_nuevo->getAutores());
        
        $evaluador = evaluador::consultar_evaluador($titulo_nuevo->getTituloId());
        if($evaluador != null){
            $tp1->newBlock("usuarios_evaluadores");
            $tp1->assign("nombre", $evaluador['nombre']);
            $tp1->assign("apellido", $evaluador['apellido']);
            $tp1->assign("correo", $evaluador['correo']);
            $tp1->assign("dni", $evaluador['dni_usuario']);
        }
        else{
            $tp1->newBlock("sin_usuarios_evaluadores");
        }
       
    }  
    return $tp1->getOutputContent();
}

function ver_titulos_evaluador(){
    $id_evaluador = $_GET['id_ev'];

    $dat_usuario = usuario::consultar_datos_usuario($id_evaluador);
    $titulos_asignados = evaluador::consultar_titulos_asignados($id_evaluador);
    $tp1 = new TemplatePower("templates/titulos_asignados_evaluador.html");
    $tp1->prepare();
    $tp1->gotoBlock("_ROOT");
    $tp1->assign("nombre_evaluador", $dat_usuario->getNomUs().'  '.$dat_usuario->getApeUs());
    $titulos_evaluador = evaluador::todos_los_titulos_asignados($id_evaluador);
    //echo "****_____asdasd__";
    //print_r($titulos_evaluador);

    foreach ($titulos_evaluador as $res) {

        //$tp1->newBlock("body");
        
        $tp1->newBlock("cuerpo_lista");
        $tp1->assign("nombre", $res->gettituloTitulo());
        $tp1->assign("area", $res->getareaTitulo());
        $tp1->assign("autores", $res->getAutores());
        $tp1->assign("tipo", $res->getTipo());
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
        //echo "OK_aRCHIVO_RESUMEN____";
        //print_r($ok_archivos_resumen);
        //echo $ok_archivos_resumen[0]['id_archivo'];   
        //echo $ok_archivos_resumen[1]['id_archivo'];
        //echo "CONTENIDO_COOUNTE____";
        //echo count($ok_archivos_resumen);
       
        //echo  $ok_archivos_resumen[0];
        //echo  $ok_archivos_resumen[1];
        
        for ($i=0; $i < count($ok_archivos_resumen) ; $i++) { 
           
           if($i == 0){

                $tp1->newBlock("con_descarga_i");
                $tp1->assign("id_archivo", $ok_archivos_resumen_ubicacion[$i]['ubicacion']);   
           }
           else{
                $tp1->newBlock("con_descarga_ii");
                $tp1->assign("id_archivo", $ok_archivos_resumen_ubicacion[$i]['ubicacion']);
           }
        }

        if(count(($ok_archivos_resumen)) == 0){
            $tp1->newBlock("con_descarga_i");
            $tp1->assign("sin_archivo", '---');
            $tp1->newBlock("con_descarga_ii");
            $tp1->assign("sin_archivo", '---');

        }

        $ok_trabajo_final = titulo::consultar_id_trabajoFinal_2($res->getTituloId());
        //echo "TRABAJO_FINAL";
       // echo $ok_trabajo_final;
        //print_r($ok_trabajo_final);
        if($ok_trabajo_final[0]['id_trabajoFinal'] != null){
           
            $id_archivo_trabajo_final = titulo::consultar_archivo_trabajoFinal_2($ok_trabajo_final[0]['id_trabajoFinal']);
            $tp1->newBlock("con_descarga_iii");
            $tp1->assign("id_archivo", $id_archivo_trabajo_final[0]['ubicacion']);
        }
        else{
            //$tp1->newBlock("con_descarga_iii");
            $tp1->assign("sin_archivo", '---');

        }
        
                
        }
      return $tp1->getOutputContent();

}


function ver_devoluciones_evaluador(){
        if($this->verificar_usuario_registrado() == 0){
            header('Location: index.php');
           }
        $id_evaluador = $_GET['id'];
        $dat_usuario = usuario::consultar_datos_usuario($id_evaluador);
        //$titulos_asignados = evaluador::consultar_titulos_asignados($id_evaluador);
        $tp1 = new TemplatePower("templates/devoluciones_realisadas__evaluador_ver-admin.html");
        $tp1->prepare();
        $tp1->gotoBlock("_ROOT");
        $tp1->assign("nombre_evaluador", $dat_usuario->getNomUs().'  '.$dat_usuario->getApeUs());
        $titulos_evaluador = evaluador::todos_los_titulos_asignados_dos($id_evaluador);
        //echo "****_____asdasd__";
        //print_r($titulos_evaluador);
        $i = 0;
      
        foreach ($titulos_evaluador as $res) {

            //$tp1->newBlock("body");
           
            $tp1->newBlock("cuerpo_lista");
            
            $titulo_datos = titulo::consultart_datos_titulos($res['id_titulo']);
            $datos_devolucion = devolucion::consultar_devolucion($res['id_titulo']);
            echo "CONTROLADOR*";
            print_r($titulo_datos);
        
            $tp1->assign("nombre", $titulo_datos['titulo']);
            $tp1->assign("area", $titulo_datos['area']);
            $tp1->assign("autores", $titulo_datos['autores']);
            $tp1->assign("tipo", $titulo_datos['tipo']);
            $tp1->assign("condicion", $datos_devolucion['condicion']);
            //////////////////////
           
            $ok_devoluciones_evaluador = devolucion::titulo_devuelto__($titulo_datos['id_titulo']); 
          
           if ($ok_devoluciones_evaluador == 0 || $ok_devoluciones_evaluador == null) {
               # code...
             $tp1->assign("archivo_i", 0);
           }
           if($ok_devoluciones_evaluador != 0 || $ok_devoluciones_evaluador != null){
            
             $tp1->newBlock("con_descarga_");
             $ubicacion_archivo = archivo::consultar_ubicacion_dos($ok_devoluciones_evaluador['id_archivo']);
             $tp1->assign("id_archivo",$ubicacion_archivo );
           }
          
                $i = $i +1;  
            }
          return $tp1->getOutputContent();
}

function verificar_usuario_registrado(){
    if(isset($_SESSION["nombre"])){
        return 1;
    }
    else{
        return 0;
    }
}

function listar_pendientes(){
    $tp1 = new TemplatePower("templates/lista_pendientes.html");
        $tp1->prepare();
        //$tp1->gotoBlock("_ROOT");
        $us = new Usuario();
        $lista = $us->all_pendientes();

        if ($lista != null) {
            foreach ($lista as $res) {

                $tp1->newBlock("body");
                
                $ok = usuario::consultar_pendiente_en_usuarios($res->getDNI());
                if($ok == null){
                    
                    $tp1->assign("apellido", $res->getApeUs());
                    $tp1->assign("nombre", $res->getNomUs());
                    $tp1->assign("dni", $res->getDNI());
                    $tp1->assign("correo", $res->getCorreo());
                    $tp1->newBlock("aceptado");
                    $tp1->assign("id_pendiente", $res->getUserId());
                    $tp1->newBlock("rechazado");
                    $tp1->assign("id_pendiente", $res->getUserId());

               
                }
                else{
                    //$tp1->newBlock("estado_aceptado");
                    
                }
              
            }
        }
        return $tp1->getOutputContent();

}

function rechazar_pendiente(){
    $id_pendiente = $_GET['id'];
    $tp1 = new TemplatePower("templates/condicion_pendiente.html");
    $tp1->prepare();
    $dat_pendiente = evaluador::consultar_datos_pendiente($id_pendiente);
    $ok_rechazo = evaluador::rechazar_pendiente($id_pendiente);
    if($ok_rechazo){
        $tp1->newBlock("rechazado");
        $tp1->assign("apellido", $dat_pendiente['apellido']);
        $tp1->assign("nombre", $dat_pendiente['nombre']);
        $tp1->assign("dni", $dat_pendiente['dni_usuario']);
        $tp1->assign("correo", $dat_pendiente['correo']);
    }
    else
    {
        $tp1->newBlock("error_pendiente");
    }
    return $tp1->getOutputContent();

}

function aceptar_pendiente(){
    $id_pendiente = $_GET['id'];
    $tp1 = new TemplatePower("templates/usuario_evaluador_pendiente.html");
    $tp1->prepare();
   
    $dat_pendiente = evaluador::consultar_datos_pendiente($id_pendiente);
    //$ok_rechazo = evaluador::rechazar_pendiente($id_pendiente);

    //if($ok_rechazo){
        $tp1->assign("id_pendiente", $dat_pendiente['id_pendiente']);
        $tp1->assign("apellido", $dat_pendiente['apellido']);
        $tp1->assign("nombre", $dat_pendiente['nombre']);
        $tp1->assign("dni", $dat_pendiente['dni_usuario']);
        $tp1->assign("correo", $dat_pendiente['correo']);
    //}
  
    return $tp1->getOutputContent();

}




}

?>

