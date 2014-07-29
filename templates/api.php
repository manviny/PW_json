<?php
if ($input->urlSegment2 != "") { //Si viene por GET
		//Para coger los segmentos hay que poner $input->urlSegment1, $input->urlSegment2 o $input->urlSegment3 
	$pageBuscar = wire('pages')->get('/'.$input->urlSegment1.'/'.$input->urlSegment2.'/');
	if(!$pageBuscar->id) { 
				echo header('HTTP/1.0 404 Not Found');
		} else {
		$getData = wire('pages')->get('/'.$input->urlSegment1.'/'.$input->urlSegment2.'/')->apiData;
		//echo json_encode($getData);
		echo $getData;
		//echo json_decode($getData);	
		}
		
} else{ //Si viene por POST
		// Datos recibidos
		$data = file_get_contents("php://input");
		$objData = json_decode($data);
		$modelo = $input->urlSegment1;
		$id = $objData->id;	//Esta variable tiene que ser texto (Convertir desde AngularJS)
		$data = $objData->data; 
		$data = json_encode($data); 
		$username = $objData->username;
		$token = $objData->token;

		/**
		*	Templates necesarios:
		*	api 		-> Dispone de Archivo PHP
		*	dat 		-> NO Dispone de Archivo PHP
		*
		*/
		/*
		*	Comprobacion y/o creacion de la pagina modelo
		*
		*/
		$pageModelo = $pages->get('/'.$modelo.'/');

		if(!$pageModelo->id) { 
				// create new page, if not already there
				$pageModelo = new Page();
  				$pageModelo->template = 'basic-page';
  				$pageModelo->parent = '/';
  				$pageModelo->name = $modelo; // URL name
		}
		$pageModelo->of(false); // turns off output formatting
		$pageModelo->title = $modelo;
		$pageModelo->save();

		/*
		*	Comprobacion y/o creacion de la pagina de Datos
		*
		*/
		$pageData = $pages->get('/'.$modelo.'/'.$id.'/');
		if(!$pageData->id) { 
  				// create new page, if not already there
  				$pageData = new Page();
  				$pageData->template = 'data';
  				$pageData->parent = '/'.$modelo.'/';
  				$pageData->name = $id; // URL name
		}
		$pageData->of(false); // turns off output formatting
		$pageData->title = $id;
		$pageData->apiData = $data;
		$pageData->save();
		//echo "Finalizado Correctamente";
		echo $data;
		//NOTA:
		//Convertir la fecha en string para pasar las variables a process --> variable.toString();
}; //FIN IF/ELSE
?>