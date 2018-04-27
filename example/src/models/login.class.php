<?php session_start();



/******************************************************************************************************
   login.class.php : Contiene el los scope y directivas de funcionamiento, llamados desde las vistas
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




$_SESSION['id_usuario'] = "";

require_once('conector.php');
require_once('funciones.php');





switch($_GET['action']){
	
	case 'consultarLogin' :
	
			$JSON       = file_get_contents("php://input");
			$request    = json_decode($JSON);
			
			$rut    		= quitarPuntosGuion($request->rut);
			$rut_sindv 	= quitarVerificador($rut);
			$contrasena = $request->contrasena;




			//$sql = "SELECT rut_usua FROM ta_usuario WHERE AES_DECRYPT(rut_usua,8090) = '".$rut."' AND AES_DECRYPT(password_usua,8090) = '".$contrasena."'";	
			//session_unset();			
			//$con = conecta();
			//$con2 = conectadbpersonal();
			
			//if(!$con){exit;}
			//if(!$con2){exit;}	
			
			
			
			
			try{
			 	
				$ip = obtenerIP();
				
				$sql = "CALL spr_login_usuario('".quitarCeroIzquierda($rut_sindv)."','".$contrasena."', '".$ip."', @id_usuario, @nombre_perfil, @tipo_perfil, @id_centro_operacion, @nombre_centro_operacion, @estado_usuario, @id_usuario_perfil, @perfil_id_perfil)";
				//echo $sql;
				//exit;		
				
				$Result = mysqli_query($con,$sql);				
				
				/*
				if(!$Result){
					throw new Exception("MySQL ERROR : " . mysqli_error($con));
				}
				*/
				
				$sql = "SELECT @id_usuario as id_usuario
							  ,@nombre_perfil AS nombre_perfil
							  ,@tipo_perfil AS tipo_perfil
							  ,@id_centro_operacion AS id_centro_operacion
							  ,@nombre_centro_operacion AS nombre_centro_operacion
							  ,@estado_usuario as estado_usuario
							  ,@id_usuario_perfil as id_usuario_perfil
							  ,@perfil_id_perfil as perfil_id_perfil";
				//echo $sql;
				//exit;
							   
				$Result = mysqli_query($con,$sql);
				if(!$Result){
					throw new Exception("MySQL ERROR : ". mysqli_error($con));
					/*VALIDAR LOS ERRORES RESULTADOS NULL POR FALLAS DEL PARAMETRO DE RESPUESTA*/
				}else{
					while($row = mysqli_fetch_object($Result)){
						//list($rut, $rutdv) = validar_rut($rut);
						//echo $rut;
				    	//exit;
						
						/*
						$_SESSION['id_usuario'] = $row[0];
						
						$_SESSION['rut'] = formateaRut($rut);
						
						$_SESSION['perfil'] = $row[1];
						$_SESSION['tipo_perfil'] = $row[2];
						$_SESSION['id_centro_operacion'] = $row[3];
						$_SESSION['nombre_centro_operacion'] = $row[4];
						*/
						
						
						$_SESSION['id_usuario'] = $row->id_usuario;
						
						$_SESSION['rut'] = formateaRut($rut);
						
						$_SESSION['perfil'] = $row->nombre_perfil;
						$_SESSION['tipo_perfil'] = $row->tipo_perfil;
						$_SESSION['id_centro_operacion'] = $row->id_centro_operacion;
						$_SESSION['nombre_centro_operacion'] = $row->nombre_centro_operacion;
						
						$_SESSION['id_usuario_perfil'] = $row->id_usuario_perfil;
						$_SESSION['perfil_id_perfil'] = $row->perfil_id_perfil;
						
						
						/*
						//saco el numero de elementos
						$longitud = count($retornorut);
						
						//Recorro todos los elementos
						for($i=0; $i<$longitud; $i++){
							//saco el valor de cada elemento
							echo $retornorut[1];
							echo "<br>";
						}
						*/
						
						/* llamada al db personal para obtener codigo de funcioario para fotografia */
		
						
						$sql2 = "SELECT * FROM pesbasi WHERE TRIM(LEADING '0' FROM PEFBRUT) = '".quitarCeroIzquierda(quitarPuntosGuion($rut))."' GROUP BY PEFBCOD";
						//echo $sql2;
						//exit;
						
						$Result2 = mysqli_query($con2,$sql2);
						if($Result2){
								while($row2 = mysqli_fetch_object($Result2)){
									$_SESSION['codigo'] = $row2->PEFBCOD;							
									$_SESSION['rut'] = formateaRut($row2->PEFBRUT);							
									$_SESSION['nombres'] = $row2->PEFBNOM1." ".$row2->PEFBNOM2;
									$_SESSION['ap_paterno'] = $row2->PEFBAPEP;
									$_SESSION['ap_materno'] = $row2->PEFBAPEM;											
								}
								
						}else{
							throw new Exception("MySQL ERROR : " . mysqli_error($con2));
						}				
						
						$obj = array('tipo' => 'ok', 'mensaje' => $row->estado_usuario);
						echo json_encode($obj);
					}
				}
			}catch(Exception $e){
			 	$obj = array('tipo' => 'error', 'mensaje' => utf8_encode($e->getMessage()));
				echo json_encode($obj);	
			}
	
	break;	
	
	
	
	case 'cerrarSesion' :

			session_destroy();
	
	break;	
	
	
	
	
	
	
	
	
	
}

	

?>