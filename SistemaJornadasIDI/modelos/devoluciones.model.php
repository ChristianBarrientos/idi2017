<?php
class devolucion{


	function cantidad_devoluciones_por_evaluador($titulos){
	global $baseDatos;
	if(count($titulos) == 0){

		return false;

	}
	elseif (count($titulos) == 1)  {
		$id_a = $titulos[0]['id_titulo'];
		$sql = "SELECT COUNT(*) AS cant  FROM devolucion_titulo WHERE id_titulo = $id_a ";
	}
	else{
		$id_a = $titulos[0]['id_titulo'];
		$id_b = $titulos[1]['id_titulo'];
		$sql = "SELECT COUNT(*) AS cant  FROM devolucion_titulo 
				WHERE id_titulo = $id_a OR id_titulo = $id_b ";
	}
	

	$res = $baseDatos->query($sql);
	$result = $res->fetch_all(MYSQLI_ASSOC);
	
	return $result;

	}

	function cantidad_devoluciones_por_evaluador_datos($titulos){
	global $baseDatos;

	if(count($titulos) == 0){

		return false;

	}
	elseif (count($titulos) == 1)  {
		$id_a = $titulos[0]['id_titulo'];
		$sql = "SELECT * AS cant  FROM devolucion_titulo WHERE id_titulo = $id_a ";
	}
	else{
		$id_a = $titulos[0]['id_titulo'];
		$id_b = $titulos[1]['id_titulo'];
		$sql = "SELECT * AS cant  FROM devolucion_titulo 
				WHERE id_titulo = $id_a OR id_titulo = $id_b ";
	}
	

	$res = $baseDatos->query($sql);
	$result = $res->fetch_all(MYSQLI_ASSOC);
	
	return $result;

	}
	function cantidad_devoluciones_por_evaluador_datos_dos($titulos){
		global $baseDatos;
		
		if(count($titulos) == 0){

			return false;

		}
		
		$id_a = $titulos[0]['id_titulo'];
		$sql = "SELECT * FROM devolucion_titulo WHERE id_titulo = $id_a ";
		
		

		$res = $baseDatos->query($sql);
		$result = $res->fetch_all(MYSQLI_ASSOC);
		
		return $result;

	}

	function titulo_devuelto__($id_titulo){
		global $baseDatos;
		
		$sql = "SELECT * FROM devolucion_titulo WHERE id_titulo = $id_titulo";
		$res = $baseDatos->query($sql);
		$result = $res->fetch_all(MYSQLI_ASSOC);
		
		return $result[0];

	}

	function consultar_devolucion($id_titulo){
		global $baseDatos;
		
		$sql = "SELECT * FROM devolucion_titulo WHERE id_titulo = $id_titulo";
		$res = $baseDatos->query($sql);
		$result =  mysqli_fetch_array($res);
		
		
		return $result;
	}

	function consultar_devolucion_2($id_titulo,$id_evaluador){
		global $baseDatos;
		$sql = "SELECT * FROM devolucion_titulo WHERE id_titulo = $id_titulo AND id_evaluador = $id_evaluador";
		$res = $baseDatos->query($sql);
		$result =  mysqli_fetch_array($res);
		return $result;
	}

	function consultar_devolucion_condicion_($id_titulo,$id_evaluador){
		global $baseDatos;
		$sql = "SELECT condicion FROM evaluador_titulo WHERE id_titulo = $id_titulo AND id_usuario = $id_evaluador";
		$res = $baseDatos->query($sql);
		$result =  mysqli_fetch_array($res);
		echo "***";
		print_r($result);
		return $result;
	}

	

	function cargar_devolucion($id_titulo,$id_archivo,$condicion,$id_usuario){
		global $baseDatos;
		$sql = "INSERT INTO devolucion_titulo VALUES (0,'$condicion',$id_titulo,$id_archivo,$id_usuario)";
		$res = $baseDatos->query($sql);
		return $res;
	}

	function consulta_existencia_devolucion($id_usuario){
		global $baseDatos;
		$sql ="SELECT evaluador_titulo.id_usuario 
				FROM evaluador_titulo, devolucion_titulo 
				WHERE evaluador_titulo.id_titulo = devolucion_titulo.id_titulo  
						AND evaluador_titulo.id_usuario = $id_usuario";
		$res = $baseDatos->query($sql);
		$result =  mysqli_fetch_array($res);
		
		return $result;
	}

	function consultar_existencia_devoluciones_realizadas ($id_usuario){
		global $baseDatos;
		$sql ="SELECT devolucion_titulo.id_titulo FROM evaluador_titulo, devolucion_titulo WHERE evaluador_titulo.id_titulo = devolucion_titulo.id_titulo AND evaluador_titulo.id_usuario = $id_usuario";
		$res = $baseDatos->query($sql);
		$result =  mysqli_fetch_array($res);
		
		return $result;
	}


	function consultar_condicion($id_titulo,$id_evaluador){
		global $baseDatos;
		$sql = "SELECT condicion FROM devolucion_titulo WHERE id_titulo = $id_titulo AND id_evaluador = $id_evaluador";
		$res = $baseDatos->query($sql);
		$result =  mysqli_fetch_array($res);
		return $result;
	}

	function agragar_condicion_titulo_evaluador($condicion,$id_titulo,$id_usuario){
		global $baseDatos;
		$sql="UPDATE evaluador_titulo SET condicion = '$condicion' WHERE id_titulo=$id_titulo AND id_usuario=$id_usuario";
		$res = $baseDatos->query($sql);
		return $res;
	}

	function carga_puntaje_condicion ($condicion,$id_titulo,$id_usuario){
		global $baseDatos;
		$sql="UPDATE devolucion_titulo SET condicion = $condicion WHERE id_titulo=$id_titulo AND id_evaluador=$id_usuario";

		$res = $baseDatos->query($sql);
		return $res;
	}
	function condicion_devuelta_por_titulo($id_titulo){

		global $baseDatos;
		$sql = "SELECT COUNT(*) AS OK FROM devolucion_titulo WHERE id_titulo = $id_titulo AND condicion = 'aprobado'";
		$res = $baseDatos->query($sql);
		$result =  mysqli_fetch_array($res);
		return $result;

	}








	}
?>