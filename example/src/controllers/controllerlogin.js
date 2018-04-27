'use strict';



/******************************************************************************************************
   controllerLogin.js : Contiene el los scope y directivas de funcionamiento, llamados desde las vistas
                        al controlador y este se comunica con los archivos *.class.php
    
   Fecha de creación		: 08/03/2018
   Autor            		: Jorge Gacitúa B. 
    
   Control de Actualizaciones 
   --------------------------
   
   Referencia  Fecha        Autor                Descripción 
   ---------   ----------   ---------------      ---------------------
   <00>        08/03/2018   Jorge Gacitúa B      Se crea el archivo.
   
   		  
  ************* P R O C E D I M I E N T O S ********************************************************* 
   <01>        08/03/2018   Jorge Gacitúa B      Se crea el scope verificarRut
   <02>        08/03/2018   Jorge Gacitúa B      Se crea el scope entrar
   
   
   
   
   
   <03>        08/03/2018   Jorge Gacitúa B      Se crea la directiva showValidation
   
******************************************************************************************************/




app.controller('controllerLogin', function($scope,$http, $state, $location){
	
$("#txtRut").focus();



//<01> scope de función verificarRut, para verificar RUT ingresado.
$scope.verificarRut = function(){
		$('#txtRut').Rut({
			  on_error: function()
			  {
				$(swal("Mensaje!!!", "El RUT ingresado es Incorrecto...", "warning"));
				$("#txtRut").val("");
				$("#txtRut").focus();
			  },
			  format_on: 'keyup'
		});
}





	
//<02> scope de función entrar, para validar los datos del usuario que acceden al sitio.
$scope.entrar = function(form){	
		
	$scope.$broadcast('show-errors-check-validity');
	//$scope.$broadcast('show-errors-reset');
  
  //bloquear boton entrar
    
  if($scope.frmLogueo.$valid) {
  	
  	//alert("ingresando...");	
  	//return false;
  	
		$('body').addClass('waitMe_body');
			
		var elem = $("<div class='waitMe_container img' ng-hide='eventos'><div class='fa-spin fa-5x'></div></div>");
		$('body').prepend(elem);		
		
		
		setTimeout(function(){ 								
		
			/*		
			$http.post('src/models/login.class.php',{ rut : $scope.txtRut , contrasena : $scope.txtPassword })
			
			.success(function(data){
				
				console.log(data);				
				console.log(String(data.tipo));
					
				if(String(data.tipo) == "error"){
					data.mensaje=data.mensaje.replace('\r\n',' ');
					
					$scope.rsJSON = data.mensaje;
				
					$("#mensaje").html("<div class='alert alert-danger alert-dismissible fade in' role='alert' ng-hide='alertaError'>" +
										"<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>×</span><span class='sr-only'>Cerrar</span></button>" +
										"<strong>Alerta!</strong> " + data.mensaje +"</div>");
										
					$scope.txtRut    = '';
					$scope.txtPassword = '';
						
					$("#txtRut").focus();
											
					
					return false;			
				}else{
					if((String(data.mensaje) == "null")){
						
						//$("#mensaje").html("<div class='alert alert-warning alert-dismissible fade in' role='alert' ng-hide='alertaLoginError'>" +
					  //						"<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>×</span><span class='sr-only'>Cerrar</span></button>" +
						//					"<strong>Lo sentimos!</strong> Su Usuario o Contraseña estan incorrectos.</div>");
											
						swal("Mensaje!!!", "Lo sentimos! Su Usuario o Contraseña estan incorrectos.", "warning");					
											
						$scope.txtRut = "";
						$scope.txtPassword = "";
						
						$("#txtRut").focus();						
						
					}
					
					if((String(data.mensaje) == "0")){
						swal("Mensaje!!!", "El Usuario se encuentra Inactivo...!!!", "warning");						
					}
					
					if((String(data.mensaje) == "1")){						
						localStorage.clear();							
						$state.go('eventos');
					}
				}		
				
			})							
			*/
			
			
			$state.go('inicio');
			
			$('body.waitMe_body').find('.waitMe_container:not([data-waitme_id])').remove();
			$('body.waitMe_body').removeClass('waitMe_body hideMe');
			
				
		},1000);			
		
		
	}
	
};




	

	




	
});



















//<03> directiva de validacion showValidation, para validar objetos en el DOOM o formularios.
app.directive("showValidation", ["$timeout", function($timeout) {
  return {
    restrict: 'A',
    require: '^form',
    link: function(scope, el, attrs, formCtrl) {
      var inputEl = el[0].querySelector("[name]");
      var inputNgEl = angular.element(inputEl);
      var inputName = inputNgEl.attr("name");			
			
      var blurred = false;      
      
      inputNgEl.bind("blur", function() {
        blurred = true;
        el.toggleClass("has-error", formCtrl[inputName].$invalid && formCtrl[inputName].$dirty);
      });
			
      scope.$watch(function() {
        return formCtrl[inputName].$invalid;
      }, function(invalid) {
        if (!blurred && invalid) {
          return
        }
        el.toggleClass("has-error", invalid);
      });
            			
      scope.$on("show-errors-check-validity", function() {
        el.toggleClass("has-error", formCtrl[inputName].$invalid);
      });
      
      scope.$on("show-errors-reset", function() {
        $timeout(function() {
          el.removeClass("has-error");
        }, 0, false);
      });
      
            
    }
  };
}]);
