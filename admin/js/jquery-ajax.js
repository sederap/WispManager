(function(a){a.fn.validCampoFranz=function(b){a(this).on({keypress:function(a){var c=a.which,d=a.keyCode,e=String.fromCharCode(c).toLowerCase(),f=b;(-1!=f.indexOf(e)||9==d||37!=c&&37==d||39==d&&39!=c||8==d||46==d&&46!=c)&&161!=c||a.preventDefault()}})}})(jQuery);
function limpiaForm(miForm) {
// recorremos todos los campos que tiene el formulario
$(':input', miForm).each(function() {
var type = this.type;
var tag = this.tagName.toLowerCase();
//limpiamos los valores de los campos…
if (type == 'text' || type == 'password' || tag == 'textarea')
this.value = '';
// excepto de los checkboxes y radios, le quitamos el checked
// pero su valor no debe ser cambiado
else if (type == 'checkbox' || type == 'radio')
this.checked = false;
// los selects le ponesmos el indice a -
else if (tag == 'select')
this.selectedIndex = -1;
});
}

function procesar(url,form,act,div,envio){
$.ajax({
           type: "POST",
           url: url+".php"+act,
		   cache : false,
		   encoding:"UTF-8",
           data: $(form).serialize(),
		   beforeSend: function(data){
			$(div).html(envio); // Mostrar la respuestas del script PHP.  
		   },// Adjuntar los campos del formulario enviado.
           success: function(data)
           {
$(div).html(data); // Mostrar la respuestas del script PHP.
}
 });
    return false; // Evitar ejecutar el submit del formulario.
}
function html(url){
$('#contenido').empty();
$('#acciones').empty();
$('#msg').empty()
$('#contenido').html('<img src="images/cargando.gif"/>');
url=url+".php";
$('#contenido').load(url);$('.dropdownContain').hide();
}

function info_router(id){
procesar("info_router","#frmpri","?id="+id,"#acciones",'<img src="images/loading.gif"/> Conectando al equipo...');

 $(function() {
    $( "#acciones" ).dialog({
      resizable: true,
      width: 700,
	 position: ['center',50],
	  title:"Datos del Router",
      modal: true,
	  	  buttons: {
      },
   beforeClose: function(event, ui) {
window.clearTimeout(time_chart);
window.clearInterval(time_chart);

   }
    });
  });
}