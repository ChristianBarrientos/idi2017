<?php
class Devolucion_Controller{

function realiza_devolucion(){
  if ($_GET['id_evaluador'] != $_SESSION['id_usuario'] OR !isset($_GET['id_evaluador'])) {
    header('Location: index.php');
  }

  if ($_GET['id'] == null OR $_GET['id'] < 0) {
    header('Location: index.php');
  }
	$id_titulo = $_GET['id'];
  $id_evaluador = $_GET['id_evaluador'];
	$tpl = new TemplatePower("templates/cargar_devolucion_evaluador.html");
	$tpl->prepare();
	$tpl->gotoBlock("_ROOT");
	$tpl->assign("id_titulo", $id_titulo);
  $tpl->assign("id_evaluador", $id_evaluador);
	return $tpl->getOutputContent();
}

function realizar_entraga_devolucion(){

  if (!isset($_GET['id']) AND ($_GET['id'] == null OR $_GET['id'] < 0)) {
     header('Location: index.php');
     # code...
   }
	$id_titulo = $_GET['id'];
  if (!isset($_GET['id_evaluador']) OR ($_GET['id_evaluador'] == null OR $_GET['id_evaluador'] < 0) OR $_GET['id_evaluador'] != $_SESSION['id_usuario'] ) {
     header('Location: index.php');
     # code...
   }
  $id_evaluador = $_GET['id_evaluador'];
   	$archivo = new archivo();
    
  if ($_FILES["archivito"]["size"] == 0) {
    //echo "SOLUCIONADOOOOOOOOO";
    header('Location: templates/archivo_demasiado_grande.html');
    exit("Archivo Demasiado Grande");
  }
   	$dat_titulo = titulo::consultart_datos_titulos($id_titulo);
   
    $archivo -> cargar_datos_devolucion ($_FILES["archivito"]["name"], $_FILES["archivito"]["size"],$_FILES["archivito"]["type"],$_FILES["archivito"]["tmp_name"], $dat_titulo[0]['titulo'],"DEVOLUCION");
   
    $res =$archivo -> cargar_archivo_bd($archivo);
  
        if($res == true){
               $condicion = $_POST['condicion'];
              
              $id_archivo_subido = $archivo->consulta_id_archivo($archivo);

              $titulo_ya_devuelto = devolucion::consultar_devolucion($id_titulo);
              /*$condicion_quorum = devolucion::consultar_condicion($id_titulo,$id_evaluador);
              echo "**";
              print_r($condicion_quorum);
              echo "--";
              print_r($id_titulo);
              echo "ççç";
              print_r($id_evaluador);
              echo "ççç";
              $condicion = $_POST['condicion'];
              if ($condicion == 'aprobado') {
                if ($condicion_quorum == null) {
                  $condicion_quorum = 1;
                }
                else{
                    $condicion_quorum = $condicion_quorum + 1;
                }
                 
                echo "string";
                $cargada_condicion_puntaje = devolucion::carga_puntaje_condicion($condicion_quorum,$id_titulo,$id_evaluador);
               
                $agrega_condicion_titulo_evaluador = devolucion::agragar_condicion_titulo_evaluador('Aprobado',$id_titulo,$id_evaluador);

                }
              else{
                echo "aa";
                $agrega_condicion_titulo_evaluador = devoluciones::agragar_condicion_titulo_evaluador('Desaprobado',$id_titulo,$id_evaluador);

              }
              if (!$agrega_condicion_titulo_evaluador) {
                  $tpl = new TemplatePower("templates/error_intente_masd_tarde.html");
                  $tpl->prepare();
                  return $tpl->getOutputContent();
              }

              $id_archivo_subido = $archivo->consulta_id_archivo($archivo);

              $titulo_ya_devuelto = devolucion::consultar_devolucion($id_titulo);*/
              
              /*if($titulo_ya_devuelto == null){*/
                    $carga_devolucion = devolucion::cargar_devolucion($id_titulo,$id_archivo_subido,$condicion,$id_evaluador);

                    if($carga_devolucion){
                      $tpl = new TemplatePower("templates/archivo_devolucion_evaluador_ok.html");
                      $tpl->prepare();
                    }
                    else{
                      $tpl = new TemplatePower("templates/subir_archivo.html");
                      $tpl->prepare();
                      $tpl->newBlock("archivo_no_ok");
                    }
              /*}
              else{

                  $tpl = new TemplatePower("templates/subir_archivo.html");
                  $tpl->prepare();
                  $tpl->newBlock("titulo_ya_devuelto");
                  $tpl->assign("nr_titulo", $id_titulo);
              
              }*/
              }
        else {
          
            $tpl = new TemplatePower("templates/subir_archivo.html");
            $tpl->prepare();
            $tpl->newBlock("archivo_no_ok");

        }
      

        return $tpl->getOutputContent();
}


function ver_devolucion(){
	$id_titulo = $_GET['id'];
  $id_evaluador = $_GET['id_evaluador'];
  $dat_titulo = titulo::consultart_datos_titulos($id_titulo);
  $tpl = new TemplatePower("templates/ver_devolucion_evaluador.html");
  $tpl->prepare();
  $tpl->assign("nombre_titulo",$dat_titulo[0]['titulo']);
  $dat_titulos_devolucion = devolucion::consultar_devolucion($id_titulo);

  $condicion = devolucion::consultar_devolucion_condicion_($id_titulo,$id_evaluador);
  $tpl->newBlock("cuerpo_lista");
  $tpl->assign("Condicion",$condicion['condicion']);
  $ubicacion_devolucion = archivo::consultar_ubicacion($dat_titulos_devolucion['id_archivo']);
  $tpl->assign("id_archivo",$ubicacion_devolucion['ubicacion']);
  return $tpl->getOutputContent();
  

}



}


?>