//window.onload =function(){alert("hola");}
var numero_autores = 1;
function comprobar_pass(){
  
	alert("asdasdasdas");
    var pass1 = document.getElementById('pass');
    var pass2 = document.getElementById('pass_repite');
 
    if(pass2 != pass1){
      alert("Repitio Mal la Contraseña");
      pass1.value= ' ';
      pass2.value= ' ';
      alert("okko");
      
    }
}

function captura_area(){

	var area=document.getElementById("areas").selectedIndex;
  //alert(area);
  //var subarea= document.getElementById('subarea').selectedIndex;
  var porNombre=document.getElementsByName("subarea")[0].value;
  //alert(porNombre);
}

function agregar_autor(){
  var id_div_autores = document.getElementById("autores");
  var salto_br = document.createElement("br");
  var salto_br2 = document.createElement("br");
  var salto_br3 = document.createElement("br");
  var salto_br4 = document.createElement("br");
  var salto_br5 = document.createElement("br");
  var salto_br6 = document.createElement("br");
  id_div_autores.appendChild(salto_br);
  id_div_autores.appendChild(salto_br2);
  var nw_label = document.createElement("label");
  var nw_label_nombre = document.createElement("label");
  var nw_label_apellido = document.createElement("label");
  var nw_label_filiacion = document.createElement("label");
  var nw_label_expone = document.createElement("label");
  var nw_input_expone = document.createElement("input");
  var nw_input_filiacion = document.createElement("input");
  numero_autores = numero_autores +1;
  var numero_autor = "Autor N°".concat(numero_autores);
  var nombre_autor = "Nombre*";
  var apellido_autor = "Apellido*";
  var exponee = 'Expositor';
  var filia = 'Filiacion*';
  var texto_label = document.createTextNode(numero_autor);
  var texto_label_nombre = document.createTextNode(nombre_autor);
  var texto_label_apellido = document.createTextNode(apellido_autor);
  var expone = document.createTextNode(exponee);
  var texto_filia = document.createTextNode(filia);

  nw_label.appendChild(texto_label);
  nw_label_nombre.appendChild(texto_label_nombre);
  nw_label_apellido.appendChild(texto_label_apellido);
  nw_label_filiacion.appendChild(texto_filia);

  var nw_input_nombre = document.createElement("input");
  var nw_input_apellido = document.createElement("input");
  id_div_autores.appendChild(nw_label);
  nw_input_nombre.setAttribute('name','autor_nombre'.concat(numero_autores));
  nw_input_nombre.setAttribute('id','autor_nombre'.concat(numero_autores));
  nw_input_apellido.setAttribute('name','autor_apellido'.concat(numero_autores));
  nw_input_apellido.setAttribute('id','autor_apellido'.concat(numero_autores));
 
  nw_input_nombre.setAttribute('placeholder','Ingrese su Nombre');
  nw_input_apellido.setAttribute('placeholder','Ingrese su Apellido');

  nw_input_expone.setAttribute('type','checkbox');
  nw_input_expone.setAttribute('name','expone_'.concat(numero_autores));
  nw_input_filiacion.setAttribute('name','filiacion_'.concat(numero_autores));
  nw_input_filiacion.setAttribute('id','filiacion_'.concat(numero_autores));
  nw_input_filiacion.setAttribute('placeholder','Ingrese su Filiacion');

  nw_label_expone.appendChild(nw_input_expone);
  nw_label_expone.appendChild(expone);
  
  id_div_autores.appendChild(salto_br3);
  id_div_autores.appendChild(salto_br4);
  id_div_autores.appendChild(nw_label_nombre);
  id_div_autores.appendChild(nw_input_nombre);
  id_div_autores.appendChild(nw_label_apellido);
  id_div_autores.appendChild(nw_input_apellido);
  id_div_autores.appendChild(salto_br5);
  id_div_autores.appendChild(salto_br6);
  id_div_autores.appendChild(nw_label_filiacion);
  id_div_autores.appendChild(nw_input_filiacion);
  id_div_autores.appendChild(nw_label_expone);
  var input_filia = document.getElementById("filiacion_".concat(numero_autores));
  var input_nombre = document.getElementById("autor_nombre".concat(numero_autores));
  var input_apellido = document.getElementById("autor_apellido".concat(numero_autores));
  input_apellido.required = true;
  input_nombre.required = true;
  input_filia.required = true;


}