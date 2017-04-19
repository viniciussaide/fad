<?php
	date_default_timezone_set("America/Sao_Paulo");
	define("DB_HOST", "localhost");
	define("DB_NAME", "fad");
	define("DB_USER", "");
	define("DB_PASS", "");
	define("DB_USER_ROOT", "root");
	define("DB_PASS_ROOT", "");
	$query2 = '';

	$conn = mysqli_connect(DB_HOST, DB_USER_ROOT, DB_PASS_ROOT) or die ("Erro ao conectar");
	$bd = mysqli_select_db($conn, DB_NAME) or die("Não foi possível selecionar o banco de dados.");
	
	$ip_relogios = array();
	$rep_relogios = array();
	
/*  	$ip_relogios[] = '192.168.1.26';
	$rep_relogios[] = '00010000280001359'; 

 	$ip_relogios[] = '192.168.1.28';
	$rep_relogios[] = '00010000280001362';
	
	$ip_relogios[] = '192.168.1.31';
	$rep_relogios[] = '00010000280003333';
	
	$ip_relogios[] = '192.168.1.34';
	$rep_relogios[] = '00010000280001365';
	
	$ip_relogios[] = '192.168.1.35';
	$rep_relogios[] = '00010000280001366';
	
	$ip_relogios[] = '192.168.1.40';
	$rep_relogios[] = '00010000280001363'; 
	
	$ip_relogios[] = '192.168.1.41';
	$rep_relogios[] = '00010000280001358';
	
	$ip_relogios[] = '192.168.1.53';
	$rep_relogios[] = 'RPONTO-AH10001301';
	
	$ip_relogios[] = '192.168.1.54';
	$rep_relogios[] = '00010000280001528';
	
	$ip_relogios[] = '192.168.1.86';
	$rep_relogios[] = '00010000280001371';
	
	$ip_relogios[] = '192.168.1.87';
	$rep_relogios[] = '00010000280001530';
	
	$ip_relogios[] = '192.168.1.158';
	$rep_relogios[] = '00010000280001287'; */
	
  	$ip_relogios[] = '192.168.1.225';
	$rep_relogios[] = '00010000280001526'; 
	
 	//data_afd=06%2F04%2F2017&data_fim_afd=07%2F04%2F2017&Comcabecalho=NAO&gerarAFDSince=Download%20de%20batidas%20do%20periodo%20acima	
/* 	$ip_relogios[] = '192.168.1.103';
	$rep_relogios[] = 'RPONTO-AH10003166'; */

	for ($i = 0; $i<count($ip_relogios); $i++){
		$curl = curl_init();
		$batidas = array();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://".$ip_relogios[$i]."/cgi-bin/logando",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"usuario\"\r\n\r\nadmin\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"senha\"\r\n\r\n12345\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
		  CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
		  ),
		));
		curl_setopt($curl, CURLOPT_COOKIESESSION, true);
		curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie-name');  //could be empty, but cause problems on some hosts
		curl_setopt($curl, CURLOPT_COOKIEFILE, 'test.txt');  //could be empty, but cause problems on some hosts

		$response = curl_exec($curl);
		$err = curl_error($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		}
		else {

			if ($ip_relogios[$i]<>'192.168.1.103'){
				curl_setopt_array($curl, array(
				  CURLOPT_URL => "http://".$ip_relogios[$i]."/cgi-bin/AFD".$rep_relogios[$i].".txt",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 10000000,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => "data_afd=16%2F02%2F2017&data_fim_afd=15%2F03%2F2017&Comcabecalho=NAO&gerarAFDSince=Download%20de%20batidas%20no%20per%C3%ADodo%20acima",
				  CURLOPT_HTTPHEADER => array(
					"cache-control: no-cache",
					"content-type: application/x-www-form-urlencoded",
				  ),
				));
			}
			else {
				curl_setopt_array($curl, array(
				  CURLOPT_URL => "http://".$ip_relogios[$i]."/cgi-bin/AFD".$rep_relogios[$i].".txt",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 10000000,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => "data_afd=16%2F02%2F2017&data_fim_afd=15%2F03%2F2017&Comcabecalho=NAO&gerarAFDSince=Download%20de%20batidas%20do%20periodo%20acima",
				  CURLOPT_HTTPHEADER => array(
					"cache-control: no-cache",
					"content-type: application/x-www-form-urlencoded",
				  ),
				));
			}


			$response = curl_exec($curl);
			$err = curl_error($curl);

			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
			  $batidas = $response;
			  echo $response;
			}
		}

		curl_close($curl);

		$batidas = explode(PHP_EOL,$batidas);
	
		echo "---".$ip_relogios[$i]."<br>";
		foreach ($batidas as $batida) {
			$pis = substr($batida,23,11);
			$data = substr($batida,14,4)."-".substr($batida,12,2)."-".substr($batida,10,2);
			$hora = substr($batida,18,2).":".substr($batida,20,2);
			if ($pis<>''){
				$query = "SELECT * FROM efetivo
							WHERE pis='".$pis."'";
				$result = mysqli_query($conn, $query);
				if ($result){
					while($row = mysqli_fetch_array($result, MYSQL_ASSOC)){
						$matricula = $row['matricula'];
						$nome = $row['nome'];
						$query2 = $query2."INSERT INTO ponto_diario (id_ponto,fk_matricula,hora_prev_1,hora_prev_2,hora_prev_3,hora_prev_4,data,batidas)
											VALUES ('','$matricula','','','','','$data','$hora');";
						echo $matricula." - ".$nome."<br>";
						echo "PIS: ".$pis."<br>"; 
						echo "Data: ".$data."<br>";
						echo "Hora: ".$hora."<br>";
					}
				}
			}
		}
	}
	$con2 = mysqli_connect(DB_HOST, DB_USER_ROOT, DB_PASS_ROOT) or die ("Erro ao conectar");
	$bd = mysqli_select_db($con2, DB_NAME) or die("Não foi possível selecionar o banco de dados.");
	//mysqli_set_charset($conn, "utf8");
	$insert = mysqli_multi_query($con2, $query2);
	if ($insert) {
		echo "---Foi";
	}
	else {
		echo "---Erro";
	}
	echo "<br>".$query2;