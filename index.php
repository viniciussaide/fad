<?php
	date_default_timezone_set("America/Sao_Paulo");
	define("DB_HOST", "localhost");
	define("DB_NAME", "fad");
	define("DB_USER", "");
	define("DB_PASS", "");
	define("DB_USER_ROOT", "root");
	define("DB_PASS_ROOT", "");

	
	$hora_extra = 0;
	$hh_total = 0;
	$total_dias = 0;
	$horario_almoco = new Datetime("12:00");
	$horario_janta = new Datetime("20:00");	
	$flag_descanso = false;
	$flag_falta = false;
	$ultima_batida = "";
	$ultima_data = "";
	$matricula="";
	
	$conn = mysqli_connect(DB_HOST, DB_USER_ROOT, DB_PASS_ROOT) or die ("Erro ao conectar");
	$bd = mysqli_select_db($conn, DB_NAME) or die("Não foi possível selecionar o banco de dados.");
	mysqli_set_charset($conn, "utf8");
	$query = "SELECT * FROM ponto_diario JOIN efetivo ON efetivo.matricula=ponto_diario.fk_matricula WHERE efetivo.status='ativo'";
	$result = mysqli_query($conn, $query);

	
	if ($result){
		while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
			$hora_prev_1 = $row['hora_prev_1'];
			$hora_prev_2 = $row['hora_prev_2'];
			$hora_prev_3 = $row['hora_prev_3'];
			$hora_prev_4 = $row['hora_prev_4'];
			$batidas = explode("|",$row['batidas']);
			$qt_batidas = count($batidas);
			if ($batidas[0]==""){
				$qt_batidas = 0;
			}
			$hh1 = 0;
			$hh2 = 0;
			$almocou = false;
			$jantou = false;
			$flag_falta_batida = false;
			$primeira_batida = new Datetime($batidas[0]);
			$data_atual = new Datetime($row['data']);
			$flag_falta = false;
			$flag_demitido = false;		
			
			//Caso mude de matrícula
			if($matricula==""){
				$matricula = $row['fk_matricula'];
				echo $matricula."<br>";
			}
			elseif ($matricula<>$row['fk_matricula']){
				if ($total_dias>0){
					$media_hh = $hh_total / $total_dias;
				}
				else{
					$media_hh = 0;
					$flag_demitido = true;
				}
				
				echo "<b>Hora extra: ".round($hora_extra,2)." Média HH/dia trabalhado: ".round($media_hh,2)."</b>";
				if ($flag_demitido){
					echo "<b style='color: red;'> Provável demitido ou férias</b><br><br>";
				}
				else{
					echo "<br><br>";
				}
				
				$matricula = $row['fk_matricula'];
				echo $matricula."<br>";
				$hora_extra = 0;
				$hh_total = 0;
				$total_dias = 0;
				$ultima_batida = "";
				$flag_descanso = false;
				$flag_falta = false;
				$ultima_data = "";
			}
			
			//Verifica se foi cumprido as 11 horas de descanso		
			if (($ultima_batida<>"")AND($ultima_data<>"")){
				$dias_descanso = date_diff($data_atual, $ultima_data);
				$dias = $dias_descanso->format("%d");
				date_modify($primeira_batida, '+'.$dias.' days');
				//echo "---".$primeira_batida->format('d-m-Y H:i')."---";
				$hora_descanso = date_diff($primeira_batida, $ultima_batida);
				$h_descanso = $hora_descanso;
				$dias = $hora_descanso->format("%d")*24;
				$horas = $hora_descanso->format("%H");
				$minutos = $hora_descanso->format("%I")/60;
				$hora_descanso = $dias + $horas + $minutos;
				if ($hora_descanso<11){
					$flag_descanso = true;
				}
				else{
					$flag_descanso = false;
				}
			}
			
			if (($hora_prev_1<>"")OR($hora_prev_2<>"")OR($hora_prev_3<>"")OR($hora_prev_4<>"")){
				if ($qt_batidas==0){
					$flag_falta = true;
					$ultima_batida = "";
					//echo $hora_prev_1." - ".$hora_prev_2." - ".$hora_prev_3." - ".$hora_prev_4;
				}
				else{
					$ultima_batida = new Datetime($batidas[$qt_batidas-1]);
					$flag_falta = false;
				}
			}
			
			if ($qt_batidas==0){
				$hh_correto = 0;
				$ultima_batida = "";
			}
			elseif ($qt_batidas==1){
				$flag_falta_batida = true;
				$hh1 = 8.8;
				$hh_correto = $hh1;
				$total_dias +=1;
			}
			elseif(($qt_batidas % 2)==0){
				$total_dias +=1;
 				for ($i = 0; $i < $qt_batidas; $i+=2) {
					$batida1 = new Datetime($batidas[$i]);
					$batida2 = new Datetime($batidas[$i+1]);
///////////////////////////////////////////////////////////////////////
					if((date('H:i',strtotime('+5 minutes',strtotime($hora_prev_1)))>=date('H:i',strtotime($batidas[$i])))AND(date('H:i',strtotime('-5 minutes',strtotime($hora_prev_1)))<=date('H:i',strtotime($batidas[$i])))){
						$batida1 = new Datetime($hora_prev_1);
					}
					elseif((date('H:i',strtotime('+5 minutes',strtotime($hora_prev_2)))>=date('H:i',strtotime($batidas[$i])))AND(date('H:i',strtotime('-5 minutes',strtotime($hora_prev_2)))<=date('H:i',strtotime($batidas[$i])))){
						$batida1 = new Datetime($hora_prev_2);
					}
					elseif((date('H:i',strtotime('+5 minutes',strtotime($hora_prev_3)))>=date('H:i',strtotime($batidas[$i])))AND(date('H:i',strtotime('-5 minutes',strtotime($hora_prev_3)))<=date('H:i',strtotime($batidas[$i])))){
						$batida1 = new Datetime($hora_prev_3);
					}
					elseif((date('H:i',strtotime('+5 minutes',strtotime($hora_prev_4)))>=date('H:i',strtotime($batidas[$i])))AND(date('H:i',strtotime('-5 minutes',strtotime($hora_prev_4)))<=date('H:i',strtotime($batidas[$i])))){
						$batida1 = new Datetime($hora_prev_4);
					}
//////////////////////////////////////////////////////////////////////					
					if((date('H:i',strtotime('+5 minutes',strtotime($hora_prev_1)))>=date('H:i',strtotime($batidas[$i+1])))AND(date('H:i',strtotime($hora_prev_1))<=date('H:i',strtotime($batidas[$i+1])))){
						$batida2 = new Datetime($hora_prev_1);
					}
					elseif((date('H:i',strtotime('+5 minutes',strtotime($hora_prev_2)))>=date('H:i',strtotime($batidas[$i+1])))AND(date('H:i',strtotime($hora_prev_2))<=date('H:i',strtotime($batidas[$i+1])))){
						$batida2 = new Datetime($hora_prev_2);
					}
					elseif((date('H:i',strtotime('+5 minutes',strtotime($hora_prev_3)))>=date('H:i',strtotime($batidas[$i+1])))AND(date('H:i',strtotime($hora_prev_3))<=date('H:i',strtotime($batidas[$i+1])))){
						$batida2 = new Datetime($hora_prev_3);
					}
					elseif((date('H:i',strtotime('+5 minutes',strtotime($hora_prev_4)))>=date('H:i',strtotime($batidas[$i+1])))AND(date('H:i',strtotime($hora_prev_4))<=date('H:i',strtotime($batidas[$i+1])))){
						$batida2 = new Datetime($hora_prev_4);
					}
///////////////////////////////////////////////////////////////////////					
					if ($batida1>=$batida2){
						date_modify($batida2, '+1 day');
					}					
 					if (($horario_almoco>$batida1)AND($horario_almoco<$batida2)AND($almocou==false)){
						$almocou = true;
					}
					if (($horario_janta>$batida1)AND($horario_janta<$batida2)AND($jantou==false)){
						$jantou = true;
					}
/////////////////////////////////////////////////////////////////////////
					$hh = date_diff($batida1, $batida2);
					$horas = $hh->format("%H");
					$minutos = $hh->format("%I")/60;
					$hh = $horas + $minutos;
					$hh1 += $hh;
				}
				if ($almocou){
					$hh1 -= 1;
				}
				if ($jantou){
					$hh1 -= 1;
				}
				$hh_correto = $hh1;
			}
			else {
//////////////////////////////////////////////////////////////////////////
				for ($i = 0; $i < $qt_batidas-1; $i+=2) {
					$batida1 = new Datetime($batidas[$i]);
					$batida2 = new Datetime($batidas[$i+1]);
					if ($batida1>=$batida2){
						date_modify($batida2, '+1 day');
					}
					if (($horario_almoco>$batida1)AND($horario_almoco<$batida2)AND($almocou==false)){
						$almocou = true;
					}
					if (($horario_janta>$batida1)AND($horario_janta<$batida2)AND($jantou==false)){
						$jantou = true;
					}
					$hh = date_diff($batida1, $batida2);
					$horas = $hh->format("%H");
					$minutos = $hh->format("%I")/60;
					$hh = $horas + $minutos;
					$hh1 += $hh;
				}
				if ($almocou){
					$hh1 -= 1;
				}
				if ($jantou){
					$hh1 -= 1;
				}
////////////////////////////////////////////////////////////////////////
				for ($i = 1; $i < $qt_batidas; $i+=2) {
					$batida1 = new Datetime($batidas[$i]);
					$batida2 = new Datetime($batidas[$i+1]);
					if ($batida1>=$batida2){
						date_modify($batida2, '+1 day');
					}
					if (($horario_almoco>$batida1)AND($horario_almoco<$batida2)AND($almocou==false)){
						$almocou = true;
					}
					if (($horario_janta>$batida1)AND($horario_janta<$batida2)AND($jantou==false)){
						$jantou = true;
					}
					$hh = date_diff($batida1, $batida2);
					$horas = $hh->format("%H");
					$minutos = $hh->format("%I")/60;
					$hh = $horas + $minutos;
					$hh2 += $hh;
				}
				if ($almocou){
					$hh2 -= 1;
				}
				if ($jantou){
					$hh2 -= 1;
				}
				if ((abs($hh1-8.8))>=(abs($hh2-8.8))){
					$hh_correto = $hh2;
				}
				else {
					$hh_correto = $hh1;
				}
///////////////////////////////////////////////////////////////////////
			}
			if ($hh_correto>8.8){
				$hora_extra += $hh_correto - 8.8; 
			}
			if ($hh_correto>0){
				$total_dias +=1;
			}
			$hh_total += $hh_correto;
			$ultima_data = new Datetime($row['data']);
			echo $ultima_data->format('d-m-Y');
			
			foreach ($batidas as $ponto) {
				echo " - " . $ponto;
			}
			echo " Quantidade de batidas: $qt_batidas HH 1: ".round($hh1, 2)." HH 2: ".round($hh2, 2)." <b>HH mais correto: ".round($hh_correto, 2)."</b>";
			
			if ($flag_descanso){
				echo "<b style='color: red;'> (".round($hora_descanso,2).") Horas de descanso não cumpridas</b>";
				//echo "<b style='color: red;'> Horas de descanso não cumpridas</b> ".$primeira_batida->format('d-m-Y H:i')." ".$ultima_batida->format('d-m-Y H:i')." ".$h_descanso->format("%d")."<br>";
			}
			if ($flag_falta){
				echo "<b style='color: blue;'> Falta</b>";
			}
			if ($flag_falta_batida){
				echo "<b style='color: purple;'> Falta batida ponto</b>";
			}
			//Caso seja fim de semana
			if(date('w', strtotime($row['data'])) == 6) {
				echo "<b style='color: orange;'> Sábado</b>";
			}
			elseif(date('w', strtotime($row['data'])) == 0) {
				echo "<b style='color: orange;'> Domingo</b>";
			}
			
			echo "<br>";
		}
	}

?>