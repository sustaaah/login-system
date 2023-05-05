<?php
class auth{
	private $response = array();

	public function responseDataUpdate($location, $newData) {
		// Aggiunge i nuovi dati all'array
		$this->response[$location] = $newData;
	}

	public function responseAddError($newData){
		
	}

	public function responseDataPrint(){
		// Codifica l'array in formato JSON
		$json = json_encode($this->response);

		// Imposta l'header per indicare il tipo di contenuto
		header('Content-Type: application/json');

		// Stampa il JSON
		echo $json;
	}



}