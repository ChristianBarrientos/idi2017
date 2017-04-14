<?php
class usuario {
	private $id_usuario;
    private $dni;
	private $nombre;
	private $apellido;
	private $correo;
	private $permiso;
	private $pass;
    private $bandera = false;

	
     public function constructorUsuario($array)
    {
        $this->id_usuario = $array["id_usuario"];
        $this->dni = $array["dni_usuario"];
        $this->nombre = $array["nombre"];
        $this->apellido = $array["apellido"];
        $this->correo = $array["correo"];
        $this->permiso = $array["permiso"];
    }

    public function constructorUsuario_pendiente($array){
        $this->id_usuario = $array["id_pendiente"];
        $this->dni = $array["dni_usuario"];
        $this->nombre = $array["nombre"];
        $this->apellido = $array["apellido"];
        $this->correo = $array["correo"];
    }

	public function    dat_usuario($id_usuario){
        global $baseDatos;
        $sql = "SELECT * FROM usuarios WHERE id_usuario = $id_usuario";    //PONER COMO SQL
        $resultado = $baseDatos->query($sql);
        return $resultado->fetch_assoc();
    }


    public function    existecorreo($correo)
    {
        global $baseDatos;
        $results = $baseDatos->query("SELECT COUNT(*) AS cant FROM `usuarios` WHERE `correo` = '$correo'");
        $res = $results->fetch_assoc();
        //$res=$results->fetch_all(MYSQLI_ASSOC);
       
        if ($res["cant"] == 0) {
            return true;
        } else {
            return false;
        }
    }

    



	 public function    insert_usuario($nombre,$dni, $apellido, $correo,$pass)
    {
        global $baseDatos;
        $passmd5 = md5($pass);
        //echo "NOMBRE->$nombre APELLIDO->$apellido CORREO->$correo PASS->$pass";
        $sql = "INSERT INTO usuarios(id_usuario,dni_usuario,nombre,apellido,correo,permiso,pass) VALUES 
            (0,'$dni','$nombre','$apellido','$correo','USUARIO','$passmd5')";
        $res = $baseDatos->query($sql);

        return $res;


        
       
        
    }


     public function insert_usuario_2($nombre,$dni, $apellido, $correo,$pass,$usuario)
    {
        global $baseDatos;
        $passmd5 = md5($pass);
        //echo "NOMBRE->$nombre APELLIDO->$apellido CORREO->$correo PASS->$pass";
        $sql = "INSERT INTO usuarios(id_usuario,dni_usuario,nombre,apellido,usuario,correo,permiso,pass) VALUES 
            (0,'$dni','$nombre','$apellido','$usuario','$correo','EVALUADOR','$passmd5')";
        $res = $baseDatos->query($sql);

        return $res;


        
       
        
    }

  
     public function insert_usuario_pendiente($nombre,$dni, $apellido, $correo,$pass)
    {
        global $baseDatos;
        $passmd5 = md5($pass);
        //echo "NOMBRE->$nombre APELLIDO->$apellido CORREO->$correo PASS->$pass";
        $sql = "INSERT INTO usuarios(id_usuario,dni_usuario,nombre,apellido,correo,permiso,pass) VALUES 
            (0,'$dni','$nombre','$apellido','$correo','EVALUADOR','$passmd5')";
        $res = $baseDatos->query($sql);

        return $res; 
    }

    

    function    verificar_user ($correo, $pass){
    global $baseDatos;
    //$passmd5 = md5($pass);
    $resultsc = $baseDatos->query("SELECT * FROM usuarios WHERE correo = '$correo' and pass = '$pass' ");
        
    return $res = $resultsc->fetch_assoc();


    }

    function    verificar_pass($pass1 , $pass2){

        if ($pass1 == $pass2){
            return true;
        }else{
            return false;
        }
    }

    function    realizar_entrega ($id_usuario){
        global $baseDatos;
        $sql = "UPDATE usuarios SET cantidad_trabajos +=  1 WHERE id_usuario = $id_usuario";
        $res = $baseDatos -> query($sql);
        return $res;
       
    }

    function    contar_titulos ($id_usuario){
        global $baseDatos;
        $sql ="SELECT COUNT(*) AS cant FROM titulo WHERE id_usuario = '$id_usuario'";
        $res = $baseDatos->query($sql);
        $result = $res->fetch_assoc();
        return $result['cant'];
    }

    function consultart_datos_titulos ($id_usuario){

        global $baseDatos;
        $sql = "SELECT * FROM titulo WHERE id_usuario = $id_usuario";

        $res = $baseDatos->query($sql);
       
        //print_r($res);
        
        $result = $res->fetch_all(MYSQLI_ASSOC);
       
        /*$result = array();
        $a = $res->fetch_all();
        while ($row = $a) {
          $result = $row;
        }^*/

        ;
        return $result;
    }

    function consultar_datos_usuario($id_usuario){
        global $baseDatos;
        $sql = "SELECT * FROM `usuarios` WHERE id_usuario = $id_usuario";
        $resultado = $baseDatos->query($sql);
        $res = $resultado->fetch_all(MYSQLI_ASSOC);
        $usuario = new Usuario();
        $usuario->constructorUsuario($res[0]);
        return $usuario;
    }


    public function all_usuarios()
    {
        global $baseDatos;
        $arrayUsuarios = array();
        if ($this->existeUsuario()) {
            $sql = "SELECT * FROM `usuarios` ORDER BY apellido ASC";
            $resultado = $baseDatos->query($sql);
            $arrayConsulta = $resultado->fetch_all(MYSQLI_ASSOC);
            foreach ($arrayConsulta as $res) {
                $usuario = new Usuario();
                $usuario->constructorUsuario($res);
                $arrayUsuarios[] = $usuario;
            }
            return $arrayUsuarios;
        } else {
            return null;
        }

    }

     public function all_pendientes()
    {
        global $baseDatos;
        $arrayUsuarios = array();
        
        $sql = "SELECT * FROM `pendientes` ORDER BY apellido ASC";
        $resultado = $baseDatos->query($sql);
        $arrayConsulta = $resultado->fetch_all(MYSQLI_ASSOC);

        foreach ($arrayConsulta as $res) {
            $usuario = new Usuario();
            $usuario->constructorUsuario_pendiente($res);
            $arrayUsuarios[] = $usuario;
        }
      
        return $arrayUsuarios;
       

    }

    function existeUsuario_pendientes(){
         global $baseDatos;
        $results = $baseDatos->query("SELECT COUNT(*) AS cant FROM `pendientes`");
        $res = $results->fetch_assoc();
        if ($res["cant"] != 0) {
            return true;
        } else {
            return false;
        }
        }

     public function existeUsuario()
    
    {
        global $baseDatos;
        $results = $baseDatos->query("SELECT COUNT(*) AS cant FROM `usuarios`");
        $res = $results->fetch_assoc();
        if ($res["cant"] != 0) {
            return true;
        } else {
            return false;
        }
    }

    /*SELECT tbl1.idEmpleado, tbl1.fecha, tbl1.HoraIngreso, tbl1.HoraEgreso, tbl2.motivo 
    FROM tbl1 
    LEFT JOIN tbl2 ON tbl1.idEmpleado=tbl2.idEmpleado
*/

    function elevar_privilegio_admin($id_usuario){
        global $baseDatos;

        $sql = "UPDATE usuarios SET permiso = 'ADMIN' WHERE id_usuario = $id_usuario";
        $res = $baseDatos->query($sql);
        return $res;
    }
    function elevar_privilegio_evaluador($id_usuario){
        global $baseDatos;
        $sql = "UPDATE usuarios SET permiso = 'EVALUADOR' WHERE id_usuario = $id_usuario";
        $res = $baseDatos->query($sql);
        return $res;
    }

    function baja_usuario($id_usuario){
        global $baseDatos;
        $sql = "DELETE FROM usuarios WHERE id_usuario = $id_usuario";
        $res = $baseDatos->query($sql);
        return $res;

    }

    function resetear_contraseña($id_usuario,$nueva_contraseña){
        global $baseDatos;
        $contra_md5 = MD5($nueva_contraseña);
        $sql = "UPDATE usuarios SET pass = '$contra_md5' WHERE id_usuario = $id_usuario";
        $res = $baseDatos->query($sql);
        return $res;
    }


    function consultar_pendiente_en_usuarios ($dni){
        global $baseDatos;
        $sql ="SELECT * FROM usuarios WHERE dni_usuario = $dni";
        $res = $baseDatos->query($sql);
        $result = $res->fetch_all(MYSQLI_ASSOC);
        return $result;
    }


      function consultar_usuario_2($usuario_name){
         global $baseDatos;
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario_name'";

        $res = $baseDatos->query($sql);
       
        
        $result = $res->fetch_all(MYSQLI_ASSOC);

        
        return $result;

    }



public function getUserId()
    {
        return $this->id_usuario;
    }

    public function getNomUs()
    {
        return $this->nombre;
    }

    public function getApeUs()
    {
        return $this->apellido;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function getPermisos()
    {
        return $this->permiso;
    }

      public function getDNI()
    {
        return $this->dni;
    }
   

   
   
  
}

?>