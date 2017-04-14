<?php
/*Incluir Configuracions Base de Datos */
include('inc.configuration.php');
/*Controladores */
include('controladores/ingreso.php');
include('controladores/registrar.php');
include('controladores/archivo.php');
include('controladores/devolucion.php');
/*Modelos*/
include('modelos/usuario.model.php');
include('modelos/archivo.model.php');
include('modelos/resumen.model.php');
include('modelos/titulo.model.php');
include('modelos/trabajofinal.model.php');
include('modelos/devoluciones.model.php');
include('modelos/evaluador.model.php');
/*Templates-Vistas*/
include('php_recurso/class.TemplatePower.inc.php');


?>