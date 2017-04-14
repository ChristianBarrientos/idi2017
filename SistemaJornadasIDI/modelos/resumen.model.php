<?php
class resumen {

	function  cargar_resumen_bd ($id_archivo){

		global $baseDatos;
		$sql = "INSERT INTO resumen VALUES (0,$id_archivo)";
		$result = $baseDatos->query($sql);
		return $result;

	}

	function  consultar_id_resumen ($id_archivo){
		
		global $baseDatos;
		$sql = "SELECT id_resumen FROM resumen WHERE id_archivo = '$id_archivo'";
		$resul = $baseDatos->query($sql);
		$fila = mysqli_fetch_array ($resul);
		return $fila[0];
	

	}



}
?>