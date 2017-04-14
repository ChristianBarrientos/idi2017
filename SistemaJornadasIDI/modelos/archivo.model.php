<?php
class archivo {

	private $id;
	private $nombre;
	private $titulo;
	private $ubicacion;

	private $size;
	private $tipo;
	private $archivo_tmp_name;
	
	
	function  cargar_datos ($nom, $size, $tipo, $archivo_tmp_name, $titulo,$resu_trabajo){
		//global $baseDatos;
		if (!$size== 0) {
            $ruta = $archivo_tmp_name;
            $tipo_a = substr(strrchr($nom, "."), 1);
            $autor = $_SESSION["nombre"].$_SESSION["apellido"];
            $destino = "resumenes/" . $autor . "_" . "$resu_trabajo" . "_" . $titulo . "." . $tipo_a;
            copy($ruta, $destino);
            $this->ubicacion = $destino;
        }

		$this->nombre = $nom;
		$this->size = $size;
		$this->tipo = substr(strrchr($nom, "."), 1);
		$this->archivo_tmp_name = $archivo_tmp_name;
		$this->titulo = $titulo;
		

		//$sql = "SELECT MAX(id) AS id FROM archivos";
		//$this -> id = $sql +1;
	}

	function  cargar_datos_devolucion ($nom, $size, $tipo, $archivo_tmp_name, $titulo,$resu_trabajo){
		//global $baseDatos;
		if (!$size== 0) {
            $ruta = $archivo_tmp_name;
            $tipo_a = substr(strrchr($nom, "."), 1);
            $autor = $_SESSION["nombre"].$_SESSION["apellido"];
            $destino = "devoluciones/" . $autor . "_" . "$resu_trabajo" . "_" . $titulo . "." . $tipo_a;
            copy($ruta, $destino);
            $this->ubicacion = $destino;
        }

		$this->nombre = $nom;
		$this->size = $size;
		$this->tipo = substr(strrchr($nom, "."), 1);
		$this->archivo_tmp_name = $archivo_tmp_name;
		$this->titulo = $titulo;
		

		//$sql = "SELECT MAX(id) AS id FROM archivos";
		//$this -> id = $sql +1;
	}



	function  cargar_datos2 ($nom, $titulo, $tipo, $id){
		$this-> nombre = $nom;
		$this-> tipo = $tipo;
		$this-> titulo = $titulo;
		$this -> id = $id;

	}
	function  cargar_datos3 ($nom,$tipo, $contenido){
		$this-> nombre = $nom;
		$this-> tipo = $tipo;
		$this -> contenido = $contenido;

	}



	function  return_contenido ($ruta_temp,$size){
		$fp = fopen($ruta_temp, "rb");
    	$contenido = fread($fp, $size);
    	$contenido = addslashes($contenido);
    	fclose($fp); 
    	return $contenido;

	}

	function  buscar_tipo($tipo){
		$tipo_s;
		foreach ($tipo as $key => $value) {
			if($key == "post"){
				

			}
			if($key == "comunicacion"){
				
			}
		}

	}

	function  contar_resumenes($user_id,$id_titulo){
		global $baseDatos;
		
		$sql = "SELECT COUNT(*) AS cant FROM `resumen` WHERE `id_usuario` = '$user_id'	";
		$res = $baseDatos->query($sql);
		$resul =$res->fetch_assoc();
		if($resul['cant'] < 2){
			
			return true;
		}
		else {
			
			return false;
		}
	}


	/*function cargar_archivo_bd ($archivo){
		global $baseDatos;
		if ( $archivo-> archivo_tmp_name != "none" and $archivo-> archivo_tmp_name != null ){

    		$fp = fopen($archivo-> archivo_tmp_name, "rb");
    		$contenido = fread($fp, $archivo-> size);
    		$contenido = addslashes($contenido);
    		fclose($fp); 
    		try{
    			$sql = "INSERT INTO archivo (id_archivo, nombre, contenido, tipo) 
    		VALUES (0,'$archivo->nombre','$contenido','$archivo->tipo')";
    		$resultado = $baseDatos->query($sql);
    		}
    		catch (Exception $e) {
    		echo 'Excepción capturada: ';
      		header('Location: index.php');
			}
    		return $resultado;
    	}
    	else{
    		
    		return false;
    		//echo "NOMBREDEL ARCHIVOO-------$archivo-> archivo_tmp_name-------";
    	}
	}*/

	function  cargar_archivo_bd ($archivo){
		global $baseDatos;
		
		$nom_ar = $archivo -> getnombre();
		$ubi_ar = $archivo -> getUbicacion();
		$tip_ar = $archivo -> gettipo();
		$sql = "INSERT INTO archivo (id_archivo, nombre, ubicacion, tipo) 
    		VALUES (0,'$nom_ar','$ubi_ar','$tip_ar')";
    	$resultado = $baseDatos->query($sql);

    	return $resultado;



	}
	/*function consulta_id_archivo ($ruta_temp,$size){
		$contenido = $this-> return_contenido($ruta_temp,$size);
		global $baseDatos;
		$sql = "SELECT id_archivo FROM archivo WHERE contenido = '$contenido'";
		$resul = $baseDatos->query($sql);
		//$arrayresult = $resul->fetch_all();
		$fila = mysqli_fetch_array ($resul);
		return $fila;

	}*/
	function  consulta_id_archivo ($archivo){
		$ubicacion = $archivo->getUbicacion();
		global $baseDatos;
		$sql = "SELECT id_archivo FROM archivo WHERE ubicacion = '$ubicacion'";
		$resul = $baseDatos->query($sql);
		$fila = mysqli_fetch_array ($resul);
		
		return $fila[0];
	

	}


	


	function  existen_archivos (){

		global $baseDatos;
        $results = $baseDatos->query("SELECT COUNT(*) AS cant FROM `archivos`");
        $res = $results->fetch_assoc();
        if ($res['cant'] != 0){
            return true;
        }else{
            return false;
        }
	}

	function  mostrar_archivos_bd (){

		global $baseDatos;
       
        if($this -> existen_archivos() ){
        		$sql = "SELECT id, nombre, titulo, tipo FROM `archivos`";
        		$listaarchivos = array();
        		$resultado = $baseDatos->query($sql);
        		$arrayConsulta = $resultado->fetch_all(MYSQLI_ASSOC);

        		foreach ($arrayConsulta as $res) {
        		    $archi = new archivo();
        		    $archi->cargar_datos2($res['nombre'],$res['titulo'],$res['tipo'],$res['id']);
        		    $listaarchivos[] = $archi;
        		}
        		return $listaarchivos;
        }
        else {
        	return false;
        }
        

	}

	function  descargar_archivo_bs($id){

		global $baseDatos;
		$sql = "SELECT * FROM `archivos` WHERE `id` = $id";
		$resultado = $baseDatos->query($sql);
		$res = $resultado -> fetch_assoc();
		$archivo = new archivo();
		$archivo->cargar_datos3($res['nombre'],$res['tipo'],$res['contenido']);
		return $archivo;
		
	}	

	function consultar_ubicacion($id_archivo){
		global $baseDatos;
		$sql = "SELECT * FROM archivo WHERE id_archivo = $id_archivo";
		$res = $baseDatos->query($sql);
		$resultado = $res->fetch_all(MYSQLI_ASSOC);
		
		return $resultado[0];

	}

	function consultar_ubicacion_dos($id_archivo){
		global $baseDatos;
		$sql = "SELECT * FROM archivo WHERE id_archivo = $id_archivo";
		$res = $baseDatos->query($sql);
		$resultado = $res->fetch_all(MYSQLI_ASSOC);
		
		return $resultado[0]['ubicacion'] ;

	}




	function getnombre (){
		return $this -> nombre;
	}
	function getsize (){
		return $this -> size;
	}
	function gettipo (){
		return $this -> tipo;
	}
	function getTitulo (){
		return $this -> titulo;
	}
	function getarchivo_tmp_name (){
		return $this -> archivo_tmp_name;
	}
	function getId (){
		return $this -> id;
	}

	function getUbicacion (){
		return $this -> ubicacion;
	}
	


}
?>