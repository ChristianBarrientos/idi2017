<?php

class trabajofinal {

	function  enlazar_trabajoFinal_archivo($id_archivo, $id_trabajoFinal){
		global $baseDatos;
		$sql = "INSERT INTO trabajofinal VALUES ($id_trabajoFinal,$id_archivo)";
		$result = $baseDatos->query($sql);
		return $result;


	}

	function  consultar_id($id_archivo){
		global $baseDatos;
		$sql="SELECT id_trabajoFinal FROM trabajofinal WHERE id_archivo=$id_archivo";
		$res = $baseDatos->query($sql);
		$fila = $res->fetch_all(MYSQLI_ASSOC);
		return $fila[0]['id_trabajoFinal'];

	}
}
?>