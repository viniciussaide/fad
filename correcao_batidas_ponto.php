<?php
	require 'php-debug-bar/Kint.class.php';
	date_default_timezone_set("America/Sao_Paulo");
	define("DB_HOST", "localhost");
	define("DB_NAME", "fad");
	define("DB_USER", "");
	define("DB_PASS", "");
	define("DB_USER_ROOT", "root");
	define("DB_PASS_ROOT", "");
	
	
	$data_atual = new Datetime('2017-02-16');
	$inicio_periodo = new Datetime('2017-02-16');
	$fim_periodo = new Datetime('2017-03-15');

	
	$conn = mysqli_connect(DB_HOST, DB_USER_ROOT, DB_PASS_ROOT) or die ("Erro ao conectar");
	$bd = mysqli_select_db($conn, DB_NAME) or die("Não foi possível selecionar o banco de dados.");
	//mysqli_set_charset($conn, "utf8");
	$query = "SELECT * FROM efetivo";
	$efetivo = mysqli_query($conn, $query);
	if ($efetivo){
		while($row = mysqli_fetch_array($efetivo, MYSQL_ASSOC)){
			$matricula = $row['matricula'];
			$split_batidas = array();
			$data_atual = new Datetime('2017-02-16');

			while ($data_atual<=$fim_periodo){
				$batidas = array();
				$str_batidas = '';
				$query = "SELECT * FROM ponto_diario WHERE fk_matricula='$matricula' AND data='".$data_atual->format('Y-m-d')."'";
				$batidas_atuais = mysqli_query($conn, $query);
				if (mysqli_num_rows($batidas_atuais)>=2){
					while($row_2 = mysqli_fetch_array($batidas_atuais, MYSQL_ASSOC)){
						$id = $row_2['id_ponto'];
						if ($row_2['batidas']<>''){
							$str_batidas = $str_batidas."|".$row_2['batidas'];
						}
					}
					$split_batidas = explode("|",$str_batidas);
					foreach ($split_batidas as $str_batida) {
						if ($str_batida<>''){
							$batidas[] = new Datetime($data_atual->format('Y-m-d')." ".$str_batida);
						}
					}
					asort($batidas);
					print_r ($batidas);
					echo "<br><br>";
					$str_batida = '';
					foreach ($batidas as $batida) {
						if ($str_batida==''){
							$str_batida = $batida->format('H:i');
						}
						else {
							$str_batida = $str_batida."|".$batida->format('H:i');
						}
					}
					$query = "UPDATE ponto_diario SET batidas='$str_batida' WHERE id_ponto='$id'";
					$update = mysqli_query($conn, $query);
					$query = "DELETE FROM ponto_diario WHERE fk_matricula='$matricula' AND data='".$data_atual->format('Y-m-d')."' AND id_ponto<>'$id'";
					$delete = mysqli_query($conn, $query);
				}
				elseif (mysqli_num_rows($batidas_atuais)==0){
/*  					$query = "INSERT INTO ponto_diario (id_ponto,fk_matricula,hora_prev_1,hora_prev_2,hora_prev_3,hora_prev_4,data,batidas)
								VALUES ('','$matricula','','','','','".$data_atual->format('Y-m-d')."','')";
					$insert = mysqli_query($conn, $query);
					if ($insert){
						echo "---Inserção de valor vazio---";
					}
					else {
						echo "Erro na inserção";
					}  */
				}
				date_modify($data_atual, '+1 days');
			}
		}
	}
	else {
		echo $query;
	}