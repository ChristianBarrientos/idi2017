<?php
class evaluador {

	function  cargar_evaluador_bd ($id_usuario_evaluador, $id_titulo){

		global $baseDatos;
		$sql = "INSERT INTO evaluador_titulo VALUES (0,$id_titulo, $id_usuario_evaluador,' ')";
		$result = $baseDatos->query($sql);
		return $result;

	}

	function  consultar_evaluador($id_titulo){
		
		global $baseDatos;
		$sql = "SELECT id_usuario FROM evaluador_titulo WHERE id_titulo = $id_titulo";
		$resul = $baseDatos->query($sql);
		$fila = mysqli_fetch_array ($resul);
		
		$id_usuario_evaluador =  $fila['id_usuario'];
		$sql2 = "SELECT * FROM usuarios WHERE id_usuario = $id_usuario_evaluador";
		$resul2 = $baseDatos->query($sql2);
		//SOLUCION AL FET_ARRAY(MYSQL)!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$fila2 = mysqli_fetch_array ($resul2);
		
		return $fila2;

	}

    function consultar_titulo_evaluaodr_para_tres($id_evaluador,$id_titulo){
        global $baseDatos;
        $sql = "SELECT * FROM evaluador_titulo WHERE id_usuario = $id_evaluador AND id_titulo = $id_titulo";
        $res = $baseDatos->query($sql);
        $fila = $res->fetch_all(MYSQLI_ASSOC);
        return $fila;
    }

	function consultar_titulos_asignados($id_evaluador){
		global $baseDatos;
		$sql = "SELECT * FROM evaluador_titulo WHERE id_usuario = $id_evaluador";
		$res = $baseDatos->query($sql);
		$fila = $res->fetch_all(MYSQLI_ASSOC);
		return $fila;
	}

	public function todos_los_titulos_asignados($id_usuario)
    {
        global $baseDatos;
        
        $arrayTitulos = array();
        //if ($this->existeTitulo_asignado($id_usuario)) {//
            $sql = "SELECT * FROM evaluador_titulo WHERE id_usuario = $id_usuario ORDER BY id_titulo ASC";
            $resultado = $baseDatos->query($sql);
            $arrayConsulta = $resultado->fetch_all(MYSQLI_ASSOC);
            foreach ($arrayConsulta as $res) {
                $titulo = new titulo();
                //echo "valor_res___";
                //print_r($res);
                $titulo_datos = titulo::consultart_datos_titulos($res['id_titulo']);
                //echo "**";
                //print_r($titulo_datos);
                $titulo->constructor_titulo($titulo_datos[0]);
                $arrayTitulos[] = $titulo;
            }
        
            return $arrayTitulos;
        //} else {
        //    return null;
        //}

    }

   

    public function todos_los_titulos_asignados_dos($id_usuario)
    {
        global $baseDatos;
        
        $arrayTitulos = array();
        //if ($this->existeTitulo_asignado($id_usuario)) {//
            $sql = "SELECT * FROM evaluador_titulo WHERE id_usuario = $id_usuario ORDER BY id_titulo ASC";
            $resultado = $baseDatos->query($sql);
            $arrayConsulta = $resultado->fetch_all(MYSQLI_ASSOC);
         
            return $arrayConsulta;
        //} else {
        //    return null;
        //}

    }

    function existeTitulo_asignado($id_usuario){

        global $baseDatos;
        $results = $baseDatos->query("SELECT COUNT(*) AS cant FROM `evaluador_titulo` WHERE id_usuario = $id_usuario");
        $res = $results->fetch_assoc();
        if ($res["cant"] != 0) {
            return true;
        } else {
            return false;
        }
    }

    function consultar_titulo_asignado($id_titulo){
    	
        global $baseDatos;
    }

    function existe_evaluador_titulo($id_titulo){
        global $baseDatos;
        $sql = "SELECT * FROM evaluador_titulo WHERE id_titulo = $id_titulo";
        $res = $baseDatos->query($sql);
        $resultado = $res->fetch_all(MYSQLI_ASSOC);
        return $resultado;

    }

    function agregar_pendiente($nombre,$dni, $apellido, $correo){//,
        
        global $baseDatos;
        $sql = "INSERT INTO pendientes VALUES 
            (0,'$dni','$nombre','$apellido','$correo')";
        $res = $baseDatos->query($sql);
        return $res;
    }

     function rechazar_pendiente($id_pendiente){
        
        global $baseDatos;
        $sql = "DELETE FROM pendientes WHERE id_pendiente = $id_pendiente"; 
        $res = $baseDatos->query($sql);
        return $res;
    }

    function consultar_datos_pendiente ($id_pendiente){
        global $baseDatos;
        $sql = "SELECT * FROM pendientes WHERE id_pendiente = $id_pendiente";
        $res = $baseDatos->query($sql);
        $resultado = $res->fetch_all(MYSQLI_ASSOC);
        return $resultado[0];
    }

    function ok_nuevo_usuario($us){
        global $baseDatos;
        $sql = "SELECT * FROM usuarios WHERE correo = '$us'";
        $res = $baseDatos->query($sql);
        $resultado = $res->fetch_all(MYSQLI_ASSOC);
        return $resultado[0];
    }

    function ok_nuevo_usuario_dni($dni){
        global $baseDatos;
        $sql = "SELECT * FROM usuarios WHERE dni_usuario = '$dni'";
        $res = $baseDatos->query($sql);
        $resultado = $res->fetch_all(MYSQLI_ASSOC);
        return $resultado[0];
    }

    function ok_nuevo_usuario_correo($correo){
        global $baseDatos;
        $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
        $res = $baseDatos->query($sql);
        $resultado = $res->fetch_all(MYSQLI_ASSOC);
        return $resultado[0];
    }



}
?>