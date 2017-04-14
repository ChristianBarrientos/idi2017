<?php
class titulo{

	private $id_titulo;
	private $area;
	private $subarea;
	private $titulo;
	private $autores;
	private $id_trabajoFinal;
	private $id_usuario;
	private $tipo;


	public function constructor_titulo($array)
    {
        $this->id_titulo = $array["id_titulo"];
        $this->area = $array["area"];
        $this->subarea = $array["subarea"];
        $this->titulo = $array["titulo"];
        $this->autores = $array["autores"];
        $this->id_trabajoFinal = $array["id_trabajoFinal"];
        $this->id_usuario = $array["id_usuario"];
        $this->tipo = $array["tipo"];
    }


	function  cargar_titulo_bd ( $area, $subarea, $titulo, $autores,$tipo,$id_usuario){
		
		global $baseDatos;
		$sql = "INSERT INTO titulo(id_titulo,area,subarea,titulo,autores,id_usuario,id_trabajoFinal,tipo) VALUES (0,'$area','$subarea', '$titulo', '$autores', $id_usuario, null, '$tipo')";
		$result = $baseDatos->query($sql);
		return $result;

	}
	//obtiene el ULTIMO titulo cargado
	function  consultar_id_titulo($id_usuario){
		global $baseDatos;
		$sql = "SELECT id_titulo FROM titulo WHERE id_usuario = $id_usuario";
		$resul = $baseDatos->query($sql);
		//$fila = mysqli_fetch_array ($resul);
		$fila = $resul->fetch_all(MYSQLI_ASSOC);
		
		if(count($fila) == 2){
			
			
			return $fila[1]['id_titulo'];
		}
		else{
			
			return $fila[0] ['id_titulo'];
		}
		

	}

	function consultar_titulos_totales ($id_usuario){
		global $baseDatos;
		$sql = "SELECT id_titulo FROM titulo WHERE id_usuario = $id_usuario";
		$resul = $baseDatos->query($sql);
		//$fila = mysqli_fetch_array ($resul);
		$fila = $resul->fetch_all(MYSQLI_ASSOC);

		return $fila;
	}

	function consultar_titulos_asignados_evaluador ($id_usuario){
		global $baseDatos;
		$sql = "SELECT id_titulo FROM evaluador_titulo WHERE id_usuario = $id_usuario";
		$resul = $baseDatos->query($sql);
		//$fila = mysqli_fetch_array ($resul);
		$fila = $resul->fetch_all(MYSQLI_ASSOC);

		return $fila;
	}

	function consultar_cantidad_titulos($id_usuario){
		global $baseDatos;
		$sql = "SELECT id_titulo FROM titulo WHERE id_usuario = $id_usuario";
		$resul = $baseDatos->query($sql);
		//$fila = mysqli_fetch_array ($resul);
		$fila = $resul->fetch_all(MYSQLI_ASSOC);
		
		return count($fila);
	}
	function  enlazar_resumen_titulo_id($id_titulo, $id_resumen){
		global $baseDatos;
		$sql = "INSERT INTO titulo_resumen VALUES (0,$id_titulo,$id_resumen)";
		$result = $baseDatos->query($sql);
		return $result;

	}

	function  procedimiento_eliminar_titulo($id_titulo){
		//$id_titulo = $_GET['id'];
		$id_resumenes = array();
		$id_archivos = array();
		//$ok_trabajo_final = titulo::eliminar_trabajo_final($id_trabajo_final);
		$id_trabajo_final = titulo::consultar_id_trabajoFinal($id_titulo);
		$id_archivo_trabajoFinal = titulo::consultar_archivo_trabajoFinal($id_trabajo_final);
		$id_resumenes = titulo::consultar_resumenes_por_titulo($id_titulo);
		
		for($i=0 ; $i< count($id_resumenes);$i++){
			$id_archivos[$i] = titulo::consultar_archivos_por_resumen($id_resumenes[$i]['id_resumen']);
			
			
		}
		

		$ok_titulo_resumen = titulo::eliminar_titulo_resumen($id_titulo);
		for($i=0;$i<count($id_resumenes);$i++){
			//echo "Eliminar Resumenes";
			//echo $id_resumenes[$i];
			//print_r($id_resumenes);
			
			$ok_resumen = titulo::eliminar_resumen($id_resumenes[$i]['id_resumen']);
		}
		
		for($i=0;$i<count($id_archivos);$i++){
			$ok_archivo = titulo::eliminar_archivo($id_archivos[$i]);
		}

		$ok_titulo = titulo::eliminar_titulo($id_titulo);
		
		if($id_archivo_trabajoFinal == false){
			$ok_archivo_trabajoFinal_ = true;
			$ok_trabajo_final = true;
		}
		else{
			$ok_trabajo_final = titulo::eliminar_trabajo_final($id_trabajo_final);
			$ok_archivo_trabajoFinal_ = titulo::eliminar_archivo($id_archivo_trabajoFinal);
		}
		
		
		
		if($ok_titulo_resumen == true AND $ok_resumen==true AND $ok_archivo == true AND $ok_trabajo_final == true  AND $ok_archivo_trabajoFinal_ == true ){
			//ok_trabajo_final == true
			return true;
		}
		else{
			return false;
		}

		

		/*COMENZAMOS ELIMINANDO EL ARCHIVO
		SELECT archivo.id_archivo FROM archivo,titulo_resumen,titulo,resumen WHERE titulo.id_titulo = 6 AND titulo_resumen.id_titulo = titulo.id_titulo AND titulo_resumen.id_resumen = resumen.id_resumen AND resumen.id_archivo = archivo.id_archivo

		PROCEDIMIENTO PARA ELIMINAR UN TITULO COMPLETAMENTE
		**ELIMINAMOS DE TITULO_RESUMEN
		DELETE FROM titulo_resumen where id_resumen = 5 OR id_resumen = 8
		**ELIMINAMOS DE RESUMEN
		DELETE FROM resumen where id_archivo = 5 OR id_archivo = 8 OR id_resumen=5 OR id_resumen=8;
		**ELIMINAMOS ARCHIVO
		DELETE FROM archivo where id_archivo = 5 OR id_archivo = 8;
		**ELIMINAMOS TITULO
		DELETE FROM titulo where id_titulo = 6
		*/

	}

	function  eliminar_titulo_resumen($id_titulo){
		global $baseDatos;
		$sql = "DELETE FROM titulo_resumen WHERE id_titulo = $id_titulo";
		$res = $baseDatos->query($sql);
		return $res;
	}

	function  eliminar_resumen($id_resumen){
		global $baseDatos;
		$sql = "DELETE FROM resumen WHERE id_resumen = $id_resumen";
		$res = $baseDatos->query($sql);
		return $res;
	}

	//esta funcion debemos tocar para cambiar los archivos guardadnos, debemos cambiar elnombre
	// debemos concatenar la palabra ELIMINADO!!!
	function  eliminar_archivo($id_archivo){
		
		$archivo_ =titulo::consultar_datos_archivo($id_archivo);
		global $baseDatos;
		//rename("/tmp/archivo_tmp.txt", "/home/user/login/docs/mi_archivo.txt"); RENOMBRAR
		//MUUUUY IMPORTANTEE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$ubicacion_carpeta = 'C:/xampp/htdocs/idi22/SistemaJornadasIDI/';
		//$ubicacion_carpeta_ok = $ubicacion_carpeta.;
		$dir = $archivo_[0]['ubicacion'];
		$dir_absoluto = $ubicacion_carpeta.$dir;
		
		unlink($dir_absoluto);  //BORRAR
		$sql = "DELETE FROM archivo WHERE id_archivo = $id_archivo ";
		$res = $baseDatos->query($sql);
		return $res;
	}

	function  eliminar_titulo($id_titulo){
		global $baseDatos;
		$sql = "DELETE FROM titulo where id_titulo = $id_titulo";
		$res = $baseDatos->query($sql);
		return $res;
	}

	function  eliminar_trabajo_final($id_trabajo_final){
		global $baseDatos;
		//$id_trabajo_final = titulo::consultar_id_trabajoFinal($id_titulo);
		//echo "ELIMINARTRABAJOFINAÑ\n";
		//echo $id_trabajo_final;
		$sql = "DELETE FROM trabajofinal WHERE id_trabajoFinal = $id_trabajo_final ";
		$res = $baseDatos->query($sql);
		return $res;
	}
	

	function  consultar_id_trabajoFinal($id_titulo){
		global $baseDatos;
		$sql = "SELECT id_trabajoFinal FROM titulo WHERE id_titulo = $id_titulo";
		$res = $baseDatos->query($sql);
		
		$fila = $res->fetch_all(MYSQLI_ASSOC);
		return $fila[0]['id_trabajoFinal'];
		//$fila = $res->fetch_array(MYSQLI_ASSOC);
		//return $fila['id_trabajoFinal'];
	}
	function  consultar_id_trabajoFinal_2($id_titulo){
		global $baseDatos;
		$sql = "SELECT id_trabajoFinal FROM titulo WHERE id_titulo = $id_titulo";
		$res = $baseDatos->query($sql);
		
		$fila = $res->fetch_all(MYSQLI_ASSOC);
		return $fila;
		//$fila = $res->fetch_array(MYSQLI_ASSOC);
		//return $fila['id_trabajoFinal'];
	}



	function  consultar_archivo_trabajoFinal($id_trabajoFinal){

		global $baseDatos;
		$sql = "SELECT id_archivo FROM trabajofinal WHERE id_trabajoFinal = $id_trabajoFinal";
		$res = $baseDatos->query($sql);
		//$fila = $res->fetch_all(MYSQLI_ASSOC);

		if ($res == false) {
			return false;
			# code...
		}
		else{
			//$fila = $res->fetch_array(MYSQLI_ASSOC);
			$fila = $res->fetch_all(MYSQLI_ASSOC);
			
			return $fila[0]['id_archivo'];	
		}
		

	}

	function  consultar_archivo_trabajoFinal_2($id_trabajoFinal){
		
		global $baseDatos;
		$sql = "SELECT id_archivo FROM trabajofinal WHERE id_trabajoFinal = $id_trabajoFinal";
		$res = $baseDatos->query($sql);
		//$fila = $res->fetch_all(MYSQLI_ASSOC);
		$id_archivo_tf = $res->fetch_all(MYSQLI_ASSOC);
		$id_archivo_tf_buscar = $id_archivo_tf[0]['id_archivo'];
		$sql = "SELECT * FROM archivo WHERE id_archivo = $id_archivo_tf_buscar";
		$res = $baseDatos->query($sql);
		$fila = $res->fetch_all(MYSQLI_ASSOC);
		return $fila;	
	}


	function  consultar_resumenes_por_titulo($id_titulo){
		//OBTENEMOS LOS RESUMENES POR TITULO
		//SELECT DISTINCT id_resumen FROM titulo_resumen WHERE id_titulo = 7
		//CON LO ANTERIOR OBTENEMOS EL ID_ARCHIVO DE CADA RESUMEN
		//SELECT DISTINCT id_archivo FROM resumen WHERE id_resumen = 12 OR id_resumen = 13
		global $baseDatos;
		$sql = "SELECT DISTINCT id_resumen FROM titulo_resumen WHERE id_titulo = $id_titulo";
		$res = $baseDatos->query($sql);
		//echo "CONSULTAR_RESUMENES_POR_TI";
		$fila = $res->fetch_all(MYSQLI_ASSOC);
		//$fila = $res->fetch_array(MYSQLI_ASSOC);
		//print_r($fila);
		return $fila;
	}

	function  consultar_archivos_por_resumen($id_resumen){

		global $baseDatos;
		$sql = "SELECT DISTINCT id_archivo FROM resumen WHERE id_resumen = $id_resumen";
		$res = $baseDatos->query($sql);
		$fila = mysqli_fetch_array ($res);
		
		return $fila[0];
	}

	function  consultar_archivos_por_resumen_2($id_resumen){

		global $baseDatos;
		$sql = "SELECT * FROM resumen WHERE id_resumen = $id_resumen";
		$res = $baseDatos->query($sql);
		$fila = mysqli_fetch_array ($res);
		return $fila;
	}

	 function  consultart_datos_titulos ($id_titulo){

        global $baseDatos;
        //$sql = "SELECT area,titulo,autores,tipo FROM titulo WHERE id_titulo = $id_titulo";
        $sql = "SELECT * FROM titulo WHERE id_titulo = $id_titulo";
        $res = $baseDatos->query($sql);
        $result = $res->fetch_all(MYSQLI_ASSOC);
        return $result;
    }


	/*INSERT INTO Tabla1(Titulo) VALUES(“dddd”);
	SELECT LAST_INSERT_ID();
*/
function  agregar_trabajo_final($id_titulo,$id_trabajoFinal){
	global $baseDatos;
	$sql="UPDATE titulo SET id_trabajoFinal = $id_trabajoFinal WHERE id_titulo=$id_titulo";
	$res = $baseDatos->query($sql);
	return $res;

}

function  consultar_datos_archivo($id_archivo){
	global $baseDatos;
	$sql="SELECT * FROM archivo WHERE id_archivo = $id_archivo";
	$res = $baseDatos->query($sql);
	$result = $res->fetch_all(MYSQLI_ASSOC);
	
	return $result;


}

function evaluador_titulo($titulos){
	global $baseDatos;
	
	if(count($titulos) == 0){
		return false;

	}
	elseif (count($titulos) == 1)  {
		$id_a = $titulos[0]['id_titulo'];
		$sql = "SELECT COUNT(*) AS cant  FROM evaluador_titulo WHERE id_titulo = $id_a ";
	}
	else{
		$id_a = $titulos[0]['id_titulo'];
		$id_b = $titulos[1]['id_titulo'];
		$sql = "SELECT COUNT(*) AS cant  FROM evaluador_titulo 
				WHERE id_titulo = $id_a OR id_titulo = $id_b ";
	}
	

	$res = $baseDatos->query($sql);
	$result = $res->fetch_all(MYSQLI_ASSOC);
	return $result;

}

function evaluador_titulo_2($id_titulo){
	global $baseDatos;
	$sql = "SELECT * FROM evaluador_titulo WHERE id_titulo = $id_titulo";
	$res = $baseDatos->query($sql);
	$result = $res->fetch_all(MYSQLI_ASSOC);
	return $result;

}


public function todos_los_titulos($id_usuario)
    {
        global $baseDatos;
        $arrayTitulos = array();
        if ($this->existeTitulo($id_usuario)) {
            $sql = "SELECT * FROM titulo WHERE id_usuario = $id_usuario ORDER BY id_titulo ASC";
            $resultado = $baseDatos->query($sql);
            $arrayConsulta = $resultado->fetch_all(MYSQLI_ASSOC);
            foreach ($arrayConsulta as $res) {
                $titulo = new titulo();
                $titulo->constructor_titulo($res);
                $arrayTitulos[] = $titulo;
            }
            return $arrayTitulos;
        } else {

            return null;
        }

    }



public function existeTitulo($id_usuario)
    {
        global $baseDatos;
        $results = $baseDatos->query("SELECT COUNT(*) AS cant FROM `titulo` WHERE id_usuario = $id_usuario");
        $res = $results->fetch_assoc();
        if ($res["cant"] != 0) {
        	
            return true;
        } else {
        	
            return false;
        }
    }

	
public function getTituloId()
    {
        return $this->id_titulo;
    }

    public function getareaTitulo()
    {
        return $this->area;
    }

    public function gettituloTitulo()
    {
        return $this->titulo;
    }

    public function getAutores()
    {
        return $this->autores;
    }

    public function getId_trabajoFinal()
    {
        return $this->id_trabajoFinal;
    }

      public function getId_usuario()
    {
        return $this->id_usuario;
    }
       public function getTipo()
    {
        return $this->tipo;
    }

       public function getsubareaTitulo()
    {
        return $this->subarea;
    }
    

}
?>