<?php
//===========================================================================================================
// OPEN SESSION |
//---------------
	session_start();

//===========================================================================================================
// INCLUDES |
//-----------

include("include_config.php");

global $config;
if ($config["dbEngine"]=="MYSQL"){
	$baseDatos = new mysqli($config["dbhost"],$config["dbuser"],$config["dbpass"],$config["db"]);
	
	
}



//===========================================================================================================
// INSTANCIA CLASES Y METODOS |
//-----------------------------

	if ((!isset($_REQUEST["action"])) || ($_REQUEST["action"]=="")) {
        $_REQUEST["action"] = "Ingreso::login"; 
    }
	if ($_REQUEST["action"]=="") {
        $html = "";
    }
	else{
		if (!strpos($_REQUEST["action"],"::")) {
            $_REQUEST["action"].="::login";
        }
		list($classParam,$method) = explode('::',$_REQUEST["action"]);
		if ($method=="") {
		    $method="login";// AGREGAR Condición PARA SABER SI YA INICIO Sesión
        }
		$classToInstaciate = $classParam."_Controller";
		if (class_exists($classToInstaciate)){
			if (method_exists($classToInstaciate,$method)) {
				$claseTemp = new $classToInstaciate;
				$html=call_user_func_array(array($claseTemp, $method),array());
			}
			else{
				echo "ERROR";
				$html="No tiene permitido acceder a ese contenido.";
			}
		}
		else{
			$html="La página solicitada no está disponible.";
		}
	}
	
//===========================================================================================================
// INSTANCIA TEMPLATE |
//---------------------

	$tpl = new TemplatePower("templates/index.html");
	$tpl->prepare();
	
//===========================================================================================================
// LEVANTA TEMPLATE	|
//-------------------		

	$tpl->gotoBlock("_ROOT");
	
	
	if (isset($_SESSION["nombre"])){
		
		if(!isset($_FILES["archivito"]) ){
		
			if($html == null){
				//echo "htmnull";
				$tpl->assign("contenido",Registrar_Controller::mostrar_titulos_user());
				$tpl->printToScreen();
			}
			else{

				$tpl->assign("contenido",$html);
    			$tpl->printToScreen();
			}
			
		}
		else{
			//echo "sientra";
			//echo "estamosaca";
			$tpl->assign("contenido",$html);
			$tpl->printToScreen();
		}
    	
    	//
    	//$webapp=$tpl->getOutputContent();
		//echo $webapp;
    	//$tpl->printToScreen();

    }
    else
    {
    	//$tpl->newBlock("loginBlock");
    	
    	$tpl->assign("contenido",$html);
    	$tpl->printToScreen();
    	//$tpl->assign("menu_usuari",Ingreso_Controller::menu());
    	
    }
    


?>
