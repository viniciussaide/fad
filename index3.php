<?php
ini_set("soap.wsdl_cache_enabled", "0");

$SoapClient = new SoapClient("http://www.ahgora.com.br/ws/pontoweb.php?wsdl");

$arguments = array (
       "empresa"=> "dfd039d748afaeb06f14f39a840ccacc",
	   "datai"=>"16022017",
	   "dataf"=>"15032017",
	   "nrep_filtro"=>"RPONTO-AH10001301",
	   "nsr"=>"0"
	   );

$result = $SoapClient->__soapCall("obterBatidas", $arguments, array('location' => 'http://www.ahgora.com.br/ws/pontoweb.php'));

if (isset($result->DadosBatida)){
	foreach($result->DadosBatida as $key => $batida){
		$data = substr($batida->Data , 0 , 2)."/".substr($batida->Data , 2 , 2)."/".substr($batida->Data , 4 , 4);
		$hora = substr($batida->Hora , 0,2).":".substr($batida->Hora , 2,2);
		
		echo "Número do PIS: ". $batida->PIS ."--- Data: ".$data."--- Hora: ".$hora ."<br>";
	}
}

?>