<?php
class Archivo_Controller {
	function guardar_archivo (){

//if (isset($_SESSION["nombre"])){
  

 
   	$archivo = new archivo();
   
  if ($_FILES["archivito"]["size"] == 0) {
    //echo "SOLUCIONADOOOOOOOOO";
    header('Location: templates/archivo_demasiado_grande.html');
    exit("Archivo Demasiado Grande");

    # code...
  }
   
   

    try {
        $archivo -> cargar_datos ($_FILES["archivito"]["name"], $_FILES["archivito"]["size"],$_FILES["archivito"]["type"],$_FILES["archivito"]["tmp_name"], $_POST['titulo'],"RESUMEN1_");
      
    } catch (Exception $e) {
        echo 'Excepción capturada: ';
          header('Location: index.php');
    }

      $cantidad_titulos = usuario::contar_titulos($_SESSION['id_usuario']);
      if($cantidad_titulos <= 2){
        //array_push($archivo,"RESUMEN");
        
        //echo "$archivo";
        $res =$archivo -> cargar_archivo_bd($archivo);

          
          if($res == true){
              $tpl = new TemplatePower("templates/subir_archivo.html");
              $tpl->prepare();
              
              $numero=$_POST["numero"];
              $count = count($numero);
              //$tipo_os = ".";
              
              for ($i = 0; $i < $count; $i++) {
                  if($numero[$i] != null){
                    if($i == 0){
                      $tipo_os = $numero[$i];
                    }
                    else{
                      $tipo_os = $tipo_os.'  '.'y'.'  '.$numero[$i];
                    }
                    
                   
                    
                  }
                }
              $autores_todos = null;
              for ($i=1; $i < 10; $i++) { 
              
                # code...
                if(isset($_POST["autor_nombre".$i] ) AND isset($_POST["autor_apellido".$i]) ){
                  if($_POST["autor_nombre".$i] != null AND $_POST["autor_apellido".$i] != null){
                    $filia = $_POST["filiacion_".$i];
                    
                    $apellido_nombre = $_POST["autor_apellido".$i]." ".$_POST["autor_nombre".$i].'('.$filia.')';
                    if($_POST["expone_".$i] != null){
                      $apellido_nombre = $apellido_nombre."(EXPOSITOR)";
                    
                    }
                    if ($autores_todos == null) {
                      $autores_todos = $apellido_nombre;
                    }
                    else
                    {
                      $autores_todos = $autores_todos."<br>".$apellido_nombre;
                    }
                    
                  }
                }
                  
              }
             

             
                
                $okok_titulo = titulo:: cargar_titulo_bd( $_POST['area'],$_POST['subarea'], $_POST['titulo'], $autores_todos,$tipo_os,$_SESSION['id_usuario']);
                
                if($okok_titulo){

                    $id_archivo_subido = $archivo->consulta_id_archivo($archivo);
                    $id_titulo_nuevo = titulo:: consultar_id_titulo($_SESSION['id_usuario']);
                    $ok_cargar_resumen = resumen:: cargar_resumen_bd($id_archivo_subido);
                    $id_resumen_cargado = resumen:: consultar_id_resumen($id_archivo_subido);
                    $relacion_titulo_resumen = titulo:: enlazar_resumen_titulo_id($id_titulo_nuevo,$id_resumen_cargado);
                    if($relacion_titulo_resumen){
                         
                       //$resumen2 = $this->subir_resumen2($tpl);
                       
                       
                       $trabajofinal = $this->subir_trabajoFinal($tpl);
                      
                      $tpl->newBlock("archivo_ok");
                      $tpl->assign("nombre", $archivo->getnombre());
                      $tpl->assign("titulo", $archivo->getTitulo());
                      $tpl->assign("area", $_POST['area']);
                        

                    }
                    else{
                      echo "erroralcargar_resUMENES";
                      $tpl = new TemplatePower("templates/error_intente_masd_tarde.html");
                      $tpl->prepare();

                    }
                  
                    //$usr = new usuario();
                    //$usr ->realizar_entrega($_SESSION['id_usuario']);
                    //echo "pasounamvez";
                   
                }
                else{
                  echo"ERRORALCARGARTITULOOOOS!";
                  $tpl = new TemplatePower("templates/error_intente_masd_tarde.html");
                  $tpl->prepare();

                }

              }
              else {
                echo "ERROR";
                $tpl = new TemplatePower("templates/subir_archivo.html");
                $tpl->prepare();
                $tpl->newBlock("archivo_no_ok");

        }
        $webapp = $tpl->getOutputContent();
              
      return $webapp;

      }
      else{
        
        $webapp = Registrar_Controller::mostrar_titulos_user();
        return $webapp;

      }
     
        
        
        //}
        //else{
          //header('Location: index.php');
        //}
           
    
      

}

	
  function subir_resumen2(&$tpl){
    //echo "subirresumen2!";
    
    //echo isset($_FILES["archivito2"]["name"]);
    if ($_FILES["archivito2"]["name"] == null) {
      # code...
      
      return false;
    }
    else{
      
      $archivo = new archivo();
      $archivo -> cargar_datos ($_FILES["archivito2"]["name"], $_FILES["archivito2"]["size"],$_FILES["archivito2"]["type"],$_FILES["archivito2"]["tmp_name"], $_POST['titulo'],"RESUMEN2_");
       $res =$archivo -> cargar_archivo_bd($archivo);
       if($res == true){
                      $id_archivo_subido = $archivo->consulta_id_archivo($archivo);
                      $id_titulo_nuevo = titulo:: consultar_id_titulo($_SESSION['id_usuario']);
                      $ok_cargar_resumen = resumen:: cargar_resumen_bd($id_archivo_subido);
                      $id_resumen_cargado = resumen:: consultar_id_resumen($id_archivo_subido);
                      $relacion_titulo_resumen = titulo:: enlazar_resumen_titulo_id($id_titulo_nuevo,$id_resumen_cargado);
                      if($relacion_titulo_resumen){

                          //$tpl = new TemplatePower("templates/subir_archivo.html");
                          //$tpl->prepare(); 
                          $tpl->newBlock("archivo_ok");
                          $tpl->assign("nombre", $archivo->getnombre());
                          $tpl->assign("titulo", $archivo->getTitulo());
                          $tpl->assign("area", $_POST['area']);

                      }
                      else{
                        
                        $tpl = new TemplatePower("templates/error_intente_masd_tarde.html");
                        $tpl->prepare();


                      }

                      return $tpl;
       }
       else{
          return false;
       }
    }



  }

  function subir_trabajoFinal(&$tpl){

    if ($_FILES["trabajofinal"]["name"] == null) {
      # code...
      
      return false;
    }
    else{
   
    $archivo = new archivo();
    $archivo -> cargar_datos ($_FILES["trabajofinal"]["name"], $_FILES["trabajofinal"]["size"],$_FILES["trabajofinal"]["type"],$_FILES["trabajofinal"]["tmp_name"], $_POST['titulo'],"TRABAJO_FINAL");
    $res =$archivo -> cargar_archivo_bd($archivo);
    if($res == true){
      $id_archivo_subido = $archivo->consulta_id_archivo($archivo);
      $id_titulo_nuevo = titulo:: consultar_id_titulo($_SESSION['id_usuario']);

      $enlazar_trabajoFinal_archivo = trabajofinal::enlazar_trabajoFinal_archivo($id_archivo_subido,$id_titulo_nuevo);
      $id_trabajo_final_del_titulo = trabajofinal::consultar_id($id_archivo_subido);
      $agrega_trabajoFinal_titulo = titulo::agregar_trabajo_final($id_trabajo_final_del_titulo,$id_titulo_nuevo);
      
      if($enlazar_trabajoFinal_archivo){
       

          //$tpl = new TemplatePower("templates/subir_archivo.html");
          //$tpl->prepare(); 
          $tpl->newBlock("trabajofinal_ok");
          $tpl->assign("titulo", $_POST['titulo']);
          $tpl->assign("nombre", $archivo->getnombre());
          }
          else{
            
            
            $tpl = new TemplatePower("templates/error_intente_masd_tarde.html");
            $tpl->prepare();
          }
          return $tpl;
      }
    else{
      
      return false;
    }
  }
  }


	function mostrar_archivos (){

		$archivo = new archivo();
		$lista = $archivo -> mostrar_archivos_bd();

		$tpl = new TemplatePower("template/listar_archivos.html");
    $tpl->prepare();  
    
    if ($lista != false)
    {
      foreach ($lista as $res) {
                $tpl->newBlock("listaarchivos");
                $tpl->assign("id_archivo", $res->getId());
                $tpl->assign("nombre", $res->getnombre());
                $tpl->assign("titulo",$res->getTitulo());  
                //$tpl->assign("id_descarga",$res->getId());  
            }

        $webapp = $tpl->getOutputContent();
       
        return $webapp;
      }
   
    else{
      $tpl -> newBlock("listarnoarchivos");
    }
   }

		  

  function descargar_archivo (){
  
   if (!isset($_GET['id']) ) {
      header('Location: templates/archivo_demasiado_grande.html');
     # code...
   }
   $id = $_GET["id"];

   $archivo = new archivo();
   $res = $archivo -> descargar_archivo_bs($id);
   $tipo = $res -> gettipo();
   $contenido = $res -> getContenido();
   $nombre = $res -> getnombre();
   header("Content-type: $tipo");
   header("Content-disposition: attachment; filename='$nombre'");
   echo $contenido;

  }

  

  function eliminar_titulo(){
     
     if (!isset($_GET['id']) ) {
      header('Location: templates/archivo_demasiado_grande.html');
     # code...
   }
   $id = $_GET["id"];
     $dat_titulo = titulo::consultart_datos_titulos($id);
     $ok_eliminacion = titulo::procedimiento_eliminar_titulo($id);
     $tpl = new TemplatePower("templates/eliminar_titulo.html");
     $tpl->prepare();
     if($ok_eliminacion){
     
      $tpl->newBlock("ok_eliminado");
      $tpl->assign("autores",$dat_titulo['autores']);
      $tpl->assign("titulo",$dat_titulo['titulo']);
      $tpl->assign("area",$dat_titulo['area']);
      $tpl->assign("tipo",$dat_titulo['tipo']);

     }
     else{
      $tpl->newBlock("no_ok_eliminado");
      $tpl->assign("autores",$dat_titulo['autores']);
      $tpl->assign("titulo",$dat_titulo['titulo']);
      $tpl->assign("area",$dat_titulo['area']);
      $tpl->assign("tipo",$dat_titulo['tipo']);

     }
     
     return $tpl->getOutputContent();

  }


}
?>