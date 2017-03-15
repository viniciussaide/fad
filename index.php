<?php
require 'php-debug-bar/Kint.class.php';


function dias_feriados($ano = null){
  if ($ano === null)
  {
    $ano = intval(date('Y'));
  }
 
  $pascoa     = easter_date($ano); // Limite de 1970 ou após 2037 da easter_date PHP consulta http://www.php.net/manual/pt_BR/function.easter-date.php
  $dia_pascoa = date('j', $pascoa);
  $mes_pascoa = date('n', $pascoa);
  $ano_pascoa = date('Y', $pascoa);
 
  $feriados = array(
    // Datas Fixas dos feriados Nacionais Basileiros
    mktime(0, 0, 0, 1,  1,   $ano), // Confraternização Universal - Lei nº 662, de 06/04/49
    mktime(0, 0, 0, 4,  21,  $ano), // Tiradentes - Lei nº 662, de 06/04/49
    mktime(0, 0, 0, 5,  1,   $ano), // Dia do Trabalhador - Lei nº 662, de 06/04/49
    mktime(0, 0, 0, 9,  7,   $ano), // Dia da Independência - Lei nº 662, de 06/04/49
    mktime(0, 0, 0, 10,  12, $ano), // N. S. Aparecida - Lei nº 6802, de 30/06/80
    mktime(0, 0, 0, 11,  2,  $ano), // Todos os santos - Lei nº 662, de 06/04/49
    mktime(0, 0, 0, 11, 15,  $ano), // Proclamação da republica - Lei nº 662, de 06/04/49
    mktime(0, 0, 0, 12, 25,  $ano), // Natal - Lei nº 662, de 06/04/49
	mktime(0, 0, 0, 7, 17,  $ano), // Aniversário da cidade de Volta Redonda 
	mktime(0, 0, 0, 4, 23,  $ano), // Dia de São Jorge
 
    // Datas variáveis dependentes da páscoa
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 48,  $ano_pascoa),//2ºferia Carnaval
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47,  $ano_pascoa),//3ºferia Carnaval
	mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 46,  $ano_pascoa),//3ºferia Carnaval
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2 ,  $ano_pascoa),//6ºfeira Santa
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa     ,  $ano_pascoa),//Pascoa
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60,  $ano_pascoa),//Corpus Christ
  );
 
  sort($feriados);
  
  return $feriados;
}

function tolerancia_horario($str_batida,$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,$mais_minutos,$menos_minutos,$data){
	if((date('H:i',strtotime($mais_minutos,date_timestamp_get($hora_prev_1)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,date_timestamp_get($hora_prev_1)))<=date('H:i',strtotime($str_batida)))){
		return $hora_prev_1;
	}
/* 	elseif((date('H:i',strtotime($mais_minutos,strtotime($hora_prev_2)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,strtotime($hora_prev_2)))<=date('H:i',strtotime($str_batida)))){
		//return new Datetime($data." ".$hora_prev_2);
	}
	elseif((date('H:i',strtotime($mais_minutos,strtotime($hora_prev_3)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,strtotime($hora_prev_3)))<=date('H:i',strtotime($str_batida)))){
		//return new Datetime($data." ".$hora_prev_3);
	} */
	elseif((date('H:i',strtotime($mais_minutos,date_timestamp_get($hora_prev_4)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,date_timestamp_get($hora_prev_4)))<=date('H:i',strtotime($str_batida)))){
		return $hora_prev_4;
	}
	else {
		return new Datetime($data." ".$str_batida);
	}
}

function plano_horario_trabalho($str_batida,$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,$mais_minutos,$menos_minutos,$flag_ES){
	if ($flag_ES){
		return true;
	}else {
		if((date('H:i',strtotime($mais_minutos,date_timestamp_get($hora_prev_1)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,date_timestamp_get($hora_prev_1)))<=date('H:i',strtotime($str_batida)))){
			return true;
		}
/* 		elseif((date('H:i',strtotime($mais_minutos,strtotime($hora_prev_2)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,strtotime($hora_prev_2)))<=date('H:i',strtotime($str_batida)))){
			return true;
		}
		elseif((date('H:i',strtotime($mais_minutos,strtotime($hora_prev_3)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,strtotime($hora_prev_3)))<=date('H:i',strtotime($str_batida)))){
			return true;
		} */
		elseif((date('H:i',strtotime($mais_minutos,date_timestamp_get($hora_prev_4)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,date_timestamp_get($hora_prev_4)))<=date('H:i',strtotime($str_batida)))){
			return true;
		}
		else {
			return false;
		}
	}

}

function desconta_hora_alimentacao($hora,$batida1,$batida2,$almocou){
	if ($almocou==true){
		return true;
	}
	if (($hora>$batida1)AND($hora<$batida2)){
		return true;
	}
	else {
		return false;
	}
}

function virou_dia($batida1,$batida2){
	if ($batida1>=$batida2){
		date_modify($batida2, '+1 day');
		return true;
	}
	else {
		return false;
	}
}

	date_default_timezone_set("America/Sao_Paulo");
	define("DB_HOST", "localhost");
	define("DB_NAME", "fad");
	define("DB_USER", "");
	define("DB_PASS", "");
	define("DB_USER_ROOT", "root");
	define("DB_PASS_ROOT", "");

	
	$hora_extra = 0;
	$hh_total = 0;
	$hh50 = 0;
	$hh75 = 0;
	$hh100 = 0;
	$pht = 0;
	$total_dias = 0;
	$hora_incompleta = 0;
	$flag_descanso = false;
	$flag_falta = false;
	$flag_horario_verao = false;
	$flag_trabalho_emergencial = false;
	$ultima_batida = "";
	$matricula="";
	$vetor_batidas = array();
	
	$ano_atual=date("Y");
	$feriados = dias_feriados($ano_atual);
	
	$conn = mysqli_connect(DB_HOST, DB_USER_ROOT, DB_PASS_ROOT) or die ("Erro ao conectar");
	$bd = mysqli_select_db($conn, DB_NAME) or die("Não foi possível selecionar o banco de dados.");
	//mysqli_set_charset($conn, "utf8");
	$query = "SELECT * FROM ponto_diario 
				JOIN efetivo ON efetivo.matricula=ponto_diario.fk_matricula 
				JOIN custo ON efetivo.fk_custo=custo.custo
				WHERE efetivo.status='ativo' OR efetivo.status LIKE '%Ferias%'
				ORDER BY efetivo.matricula, ponto_diario.data";
	$result = mysqli_query($conn, $query);

	
	if ($result){
		while($row = mysqli_fetch_array($result, MYSQL_ASSOC)){
			$almocou1 = false;
			$jantou1 = false;
			$almocou2 = false;
			$jantou2 = false;
			$flag_falta_batida = false;
			$flag_falta = false;
			$flag_demitido = false;
			$flag_virou_dia = false;
			$flag_entrada_prevista = false;
			$flag_saida_prevista = false;
			$flag_ferias = false;
			$flag_trabalho_emergencial = false;
			$flag_horario_verao = false;
			$flag_fim_horario_verao = false;
			$flag_inicio_horario_verao = false;
			
			
			$hora_prev_1 = new Datetime($row['data']." ".$row['hora_prev_1']);
			$hora_prev_2 = new Datetime($row['data']." ".$row['hora_prev_2']);
			$hora_prev_3 = new Datetime($row['data']." ".$row['hora_prev_3']);
			$hora_prev_4 = new Datetime($row['data']." ".$row['hora_prev_4']);
			$data_atual = new Datetime($row['data']);
			$horario_almoco = new Datetime($row['data']." 12:00");
			$horario_janta = new Datetime($row['data']." 20:00");
			
			//Verifica Horario de Verão > inicio, meio e fim
			$data_seguinte = new Datetime($row['data']);
			date_modify($data_seguinte, '+1 days');
			
 			if ((date_offset_get($data_atual)/3600)==-2 AND (date_offset_get($data_seguinte)/3600)==-2){
				$flag_horario_verao = true;
			}
			elseif ((date_offset_get($data_atual)/3600)==-2 AND (date_offset_get($data_seguinte)/3600)==-3){
				$flag_fim_horario_verao = true;
			}
			elseif ((date_offset_get($data_atual)/3600)==-3 AND (date_offset_get($data_seguinte)/3600)==-2){
				$flag_inicio_horario_verao = true;
			}
			elseif ((date_offset_get($data_atual)/3600)==-3 AND (date_offset_get($data_seguinte)/3600)==-3){
				$flag_horario_verao = false;
			}
			else {
				$flag_horario_verao = false;
			}
			
			if ($hora_prev_4<$hora_prev_1){
				date_modify($hora_prev_4, '+1 days');
			}
			
			$almocou1 = desconta_hora_alimentacao($horario_almoco,$hora_prev_1,$hora_prev_4,$almocou1);
			$jantou1 = desconta_hora_alimentacao($horario_janta,$hora_prev_1,$hora_prev_4,$jantou1);
			
			$pht = date_diff($hora_prev_1, $hora_prev_4);
			$dias = $pht->format("%d")*24;
			$horas = $pht->format("%H");
			$minutos = $pht->format("%I")/60;
			$pht = $dias + $horas + $minutos;
			
			if ($almocou1){
				$pht -= 1;
			}
			if ($jantou1){
				$pht -= 1;
			}
			
			$batidas = explode("|",$row['batidas']);
			$qt_batidas = count($batidas);
			if ($batidas[0]==""){
				$qt_batidas = 0;
			}
			$hh1 = 0;
			$hh2 = 0;
			$hora_extra_atual = 0;
			$hora_incompleta_atual = 0;	

			$primeira_batida = new Datetime($row['data']." ".$batidas[0]);
			

			
			//Caso mude de matrícula
			if($matricula==""){
				$matricula = $row['fk_matricula'];
				$status = $row['status'];
				$inicio_ferias = new Datetime($row['inicio_ferias']);
				$fim_ferias = new Datetime($row['fim_ferias']);
				if ($inicio_ferias>new Datetime("0000-00-00") AND $fim_ferias>new Datetime("0000-00-00")){
					echo $matricula." - ".$status.": ".date_format($inicio_ferias, 'd/m/Y').">>>".date_format($fim_ferias, 'd/m/Y')."<br>";
				}
				else {
					echo $matricula." - ".$status."<br>";
				}
			}
			elseif ($matricula<>$row['fk_matricula']){
				if ($total_dias>0){
					$media_hh = $hh_total / $total_dias;
				}
				else{
					$media_hh = 0;
				}
				
				echo "<b>Dias trabalhados: ".$total_dias." HH Total: ".round($hh_total,2)." <b style='color: cornflowerblue;'>Hora extra: ".round($hora_extra,2)." HH50: ".round($hh50,2)." HH75: ".round($hh75,2)." HH100: ".round($hh100,2)."</b><b style='color: red;'> Déficit de horas: ".round($hora_incompleta,2)."</b> Média HH/dia trabalhado: ".round($media_hh,2)."</b><br>";
				//print_r($vetor_batidas);
				
				for ($i = 0; $i < count($vetor_batidas)-1; $i++) {
					if ($vetor_batidas[$i]>$vetor_batidas[$i+1]){
						echo "Possível Erro!!! ";
						echo $vetor_batidas[$i+1]->format ('d-m-Y H:i');
						date_modify($vetor_batidas[$i+1], '+1 days');
						echo " Alterado para: ".$vetor_batidas[$i+1]->format ('d-m-Y H:i')."<br>";
					}
				}
				echo "<br><br>";
				
				
				$matricula = $row['fk_matricula'];
				$status = $row['status'];
				$inicio_ferias = new Datetime($row['inicio_ferias']);
				$fim_ferias = new Datetime($row['fim_ferias']);
				$hora_extra = 0;
				$hh_total = 0;
				$hh50 = 0;
				$hh75 = 0;
				$hh100 = 0;
				$total_dias = 0;
				$hh_correto = 0;
				unset ($hora_descanso);
				$vetor_batidas = array();
				$primeira_batida = "";
				$ultima_batida = "";
				$flag_descanso = false;
				//$flag_falta = false;
				$ultima_batida = "";
				$hora_incompleta = 0;
				
				if ($inicio_ferias>new Datetime("0000-00-00") AND $fim_ferias>new Datetime("0000-00-00")){
					echo $matricula." - ".$status.": ".date_format($inicio_ferias, 'd/m/Y').">>>".date_format($fim_ferias, 'd/m/Y')."<br>";
				}
				else {
					echo $matricula." - ".$status."<br>";
				}
			}
			//Férias
			if ($inicio_ferias>new Datetime("0000-00-00") AND $fim_ferias>new Datetime("0000-00-00")){
				if ($inicio_ferias<=$data_atual AND $fim_ferias>=$data_atual){
					$flag_ferias = true;
				}
				else {
					$flag_ferias = false;
				}
			}
			//Verifica se data atual é feriado
			if (in_array(date_timestamp_get($data_atual), $feriados)) { 
				$flag_feriado = true;
			}
			else {
				$flag_feriado = false;
			}
			//Verifica se foi cumprido as 11 horas de descanso
			if (($primeira_batida<>"")AND($ultima_batida<>"")){
				$horas_descanso = date_diff($primeira_batida, $ultima_batida);
				if ($qt_batidas==0){
					date_modify($primeira_batida, '+1 days');
				}
				$hora_descanso = date_diff($primeira_batida, $ultima_batida);
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
			
			//Verifica se faltou e qual a ultima batida de ponto
			if (($hora_prev_1<>"")OR($hora_prev_2<>"")OR($hora_prev_3<>"")OR($hora_prev_4<>"")){
				if ($qt_batidas==0){
					$flag_falta = true;
					$ultima_batida = "";
				}
				else{
					$ultima_batida = new Datetime($batidas[$qt_batidas-1]);
					$flag_falta = false;
				}
			}
			
			if ($qt_batidas>0){
				//Verifica trabalho em dias não programados que geram hora extra
				if ($hora_prev_1->format('H:i')=="00:00" AND $hora_prev_4->format('H:i')=="00:00"){
					$flag_trabalho_emergencial = true;
				}
			}
			
			//Verifica perídos, hh, hora extra, etc
			//PROCESSO PRINCIPAL
			if ($qt_batidas==0){
				$hh_correto = 0;
				$ultima_batida = "";
			}
			elseif ($qt_batidas==1){
				$flag_entrada_prevista = plano_horario_trabalho($batidas[0],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-5 minutes",$flag_entrada_prevista);
				$flag_falta_batida = true;
				$hh1 = 0;
				$hh_correto = $hh1;
				$total_dias +=1;
			}
			elseif(($qt_batidas % 2)==0){
				$total_dias +=1;
 				for ($i = 0; $i < $qt_batidas; $i+=2) {
					$batida1 = tolerancia_horario($batidas[$i],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-5 minutes",$row['data']);
					$batida2 = tolerancia_horario($batidas[$i+1],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-0 minutes",$row['data']);
					$flag_entrada_prevista = plano_horario_trabalho($batidas[$i],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-5 minutes",$flag_entrada_prevista);
					$flag_saida_prevista = plano_horario_trabalho($batidas[$i+1],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-0 minutes",$flag_saida_prevista);
 /*					echo "---".$batida1->format("d-m-Y H:i")."---";
					echo "---".$batida2->format("d-m-Y H:i")."---";
					echo "---".$horario_almoco->format("d-m-Y H:i")."---";
					echo "---".$horario_janta->format("d-m-Y H:i")."---"; */
					$flag_virou_dia = virou_dia($batida1,$batida2);
					$almocou1 = desconta_hora_alimentacao($horario_almoco,$batida1,$batida2,$almocou1);
					$jantou1 = desconta_hora_alimentacao($horario_janta,$batida1,$batida2,$jantou1);
					//echo "Alimentação: ".$almocou1 . $jantou1;
					$hh = date_diff($batida1, $batida2);
					$horas = $hh->format("%H");
					$minutos = $hh->format("%I")/60;
					$hh = $horas + $minutos;
					$hh1 += $hh;
				}
				if ($almocou1){
					$hh1 -= 1;
				}
				if ($jantou1){
					$hh1 -= 1;
				}
				$hh_correto = $hh1;
			}
			else {
				for ($i = 0; $i < $qt_batidas-1; $i+=2) {
					//Entrada - Saída - ... - Entrada - Saída - Erro
					$batida1 = tolerancia_horario($batidas[$i],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-5 minutes",$row['data']);
					$batida2 = tolerancia_horario($batidas[$i+1],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-0 minutes",$row['data']);
					$flag_entrada_prevista = plano_horario_trabalho($batidas[$i],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-5 minutes",$flag_entrada_prevista);
					$flag_saida_prevista = plano_horario_trabalho($batidas[$i+1],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-0 minutes",$flag_saida_prevista);
					$flag_virou_dia = virou_dia($batida1,$batida2);
					$almocou1 = desconta_hora_alimentacao($horario_almoco,$batida1,$batida2,$almocou1);
					$jantou1 = desconta_hora_alimentacao($horario_janta,$batida1,$batida2,$jantou1);
					$hh = date_diff($batida1, $batida2);
					$horas = $hh->format("%H");
					$minutos = $hh->format("%I")/60;
					$hh = $horas + $minutos;
					$hh1 += $hh;
					
					//Erro - Entrada - Saída - ... - Entrada - Saída
					$batida1 = tolerancia_horario($batidas[$i+1],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-5 minutes",$row['data']);
					$batida2 = tolerancia_horario($batidas[$i+2],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-0 minutes",$row['data']);
					$flag_entrada_prevista = plano_horario_trabalho($batidas[$i+1],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-5 minutes",$flag_entrada_prevista);
					$flag_saida_prevista = plano_horario_trabalho($batidas[$i+2],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-0 minutes",$flag_saida_prevista);
					$flag_virou_dia = virou_dia($batida1,$batida2);
					$almocou2 = desconta_hora_alimentacao($horario_almoco,$batida1,$batida2,$almocou2);
					$jantou2 = desconta_hora_alimentacao($horario_janta,$batida1,$batida2,$jantou2);
					$hh = date_diff($batida1, $batida2);
					$horas = $hh->format("%H");
					$minutos = $hh->format("%I")/60;
					$hh = $horas + $minutos;
					$hh2 += $hh;
				}
				if ($almocou1){
					$hh1 -= 1;
				}
				if ($jantou1){
					$hh1 -= 1;
				}
				if ($almocou2){
					$hh2 -= 1;
				}
				if ($jantou2){
					$hh2 -= 1;
				}
				if ((abs($hh1-$pht))>=(abs($hh2-$pht))){
					$hh_correto = $hh2;
				}
				else {
					$hh_correto = $hh1;
				}
			}
			//Reduçao e aumento de PHT de acordo com o horário de verão
			if ($flag_fim_horario_verao AND $flag_virou_dia){
				$pht -= 1;
				$hh_correto -=1;
			}
			elseif ($flag_inicio_horario_verao AND $flag_virou_dia){
				$pht += 1;
				$hh_correto +=1;
			}
			
			//Se horas descanso forem mais próximas do PHT que HH1 e HH2 porém menor que 11 horas
			if (isset($hora_descanso)){
				if (abs($hh_correto-$pht)>=abs($hora_descanso-$pht) AND $hora_descanso<=11 AND $hh_correto<>0){
					$temp = $hh_correto;
					
					$hh_correto = $hora_descanso;
					$hora_descanso = $temp;
					if ($hora_descanso<11){
						$flag_descanso = true;
					}
					else{
						$flag_descanso = false;
					}
				}
			}

			//Hora extra ou horas incompletas
			if (($hora_prev_1<>"")OR($hora_prev_2<>"")OR($hora_prev_3<>"")OR($hora_prev_4<>"")){
				if ($hh_correto>$pht){
					$hora_extra_atual = $hh_correto - $pht; 
					$hora_extra += $hora_extra_atual;
					if ($flag_feriado OR (date('w', strtotime($row['data'])) == 0)){
						$hh100 += $hora_extra_atual;
					}
					elseif((date('w', strtotime($row['data'])) == 6)) {
						$hh75 += $hora_extra_atual;
					}
					else {
						$hh50 += $hora_extra_atual;
					}
				}
				elseif ($hh_correto<$pht){
					if (($hora_prev_1<>"")OR($hora_prev_2<>"")OR($hora_prev_3<>"")OR($hora_prev_4<>"")){
						if ($flag_entrada_prevista AND $flag_saida_prevista){
							$hora_incompleta_atual = 0;
						}
						elseif(!$flag_ferias) {
							$hora_incompleta_atual = $pht - $hh_correto;
							$hora_incompleta += $hora_incompleta_atual;
						}
					}
				}
			}
			else {
				$hora_extra_atual = $hh_correto; 
				$hora_extra += $hora_extra_atual;
				if ($flag_feriado OR (date('w', strtotime($row['data'])) == 0)){
					$hh100 += $hora_extra_atual;
				}
				elseif((date('w', strtotime($row['data'])) == 6)) {
					$hh75 += $hora_extra_atual;
				}
				else {
					$hh50 += $hora_extra_atual;
				}
			}
			
			//Salva última batida de ponto para verificar trabalho na virada de um dia
			$hh_total += $hh_correto;
			if ($qt_batidas>=1) {
				$ultima_batida = new Datetime($row['data']." ".$batidas[$qt_batidas-1]);
			}
			else {
				//$ultima_batida = new Datetime($row['data']);
				$ultima_batida = "";
			}
			echo $data_atual->format('d-m-Y');
			if ($hora_prev_1->format('H:i')=="00:00" AND $hora_prev_4->format('H:i')=="00:00"){
				echo "<b style='color:red'> Horário não previsto </b>";
			}
			elseif ($hora_prev_1<>"" AND $hora_prev_4<>""){
				echo "<b> Hora prevista: ".$hora_prev_1->format('H:i')." - ".$hora_prev_4->format('H:i')." PHT: ".$pht."</b>";
			}
			else {
				echo "<b style='color:red'> Horário não previsto </b>";
			}
			
			//Batidas de ponto
			if ($qt_batidas>1){
				echo " Batidas: ".$qt_batidas." ";
				foreach ($batidas as $ponto) {
					$vetor_batidas[] = new Datetime ($row['data']." ".$ponto);
					echo $ponto. " * ";
				}
			}
			elseif ($qt_batidas=1 AND $batidas[0]<>""){
				echo " Batidas: ".$qt_batidas." ".$batidas[0];
			}
			//HH mais correto
			echo " HH 1: ".round($hh1, 2)." HH 2: ".round($hh2, 2)." <b>HH mais correto: ".round($hh_correto, 2)."</b>";
			//Férias
			if ($flag_ferias){
				echo "<b style='color: green;'> Período de Férias</b>";
			}
			//Apenas 1 batida no ponto
			if ($flag_falta_batida){
				echo "<b style='color: purple;'> Apenas uma batida de ponto</b>";
			}
			//Hora extra ou hora incompleta
			if ($hora_extra_atual>0){
				echo "<b style='color: cornflowerblue;'> Hora extra: ".round($hora_extra_atual,2)."</b>";
			}
			elseif ($hora_incompleta_atual>0) {
				//Nenhuma batida num dia programado
				if ($flag_falta AND !$flag_feriado AND !$flag_ferias){
					echo "<b style='color: deeppink;'> Nenhuma batida de ponto</b>";
				}
				else {
					echo "<b style='color: red;'> Déficit de horas: ".round($hora_incompleta_atual,2)."</b>";
				}
			}
			//Menos de 11 horas de descanso
			if ($flag_descanso){
				echo "<b style='color: red;'> (".round($hora_descanso,2).") Horas de descanso</b>";
			}
			//Virou o dia
			if ($flag_virou_dia){
				echo "<b style='color: brown;'> Virou o dia!</b>";
			}
			//Trabalho Emergencial
			if ($flag_trabalho_emergencial){
				echo "<b style='color: brown;'> Batida em dia não programado!</b>";
			}
			//Entrada ou saídas Previstas ou não
			if ($flag_entrada_prevista AND $flag_saida_prevista){
				echo "<b style='color: green;'> Entrada e Saída Previstas!</b>";
			}
			elseif ($flag_entrada_prevista){
				echo "<b style='color: green;'> Entrada Prevista!</b>";
			}
			elseif ($flag_saida_prevista){
				echo "<b style='color: green;'> Saída Prevista!</b>";
			}
			elseif (!$flag_feriado AND !(date('w', strtotime($row['data'])) == 6)AND !(date('w', strtotime($row['data'])) == 0)){
				if (!$flag_ferias AND !$flag_falta AND !$flag_falta_batida){
					echo "<b style='color: darkviolet;'> Entrada e Saída Incorretas!</b>".$flag_falta;
				}
			}
			//Caso seja fim de semana
			if(date('w', strtotime($row['data'])) == 6) {
				echo "<b style='color: orange;'> Sábado</b>";
			}
			elseif(date('w', strtotime($row['data'])) == 0) {
				echo "<b style='color: orange;'> Domingo</b>";
			}
			//Feriados
			if ($flag_feriado){
				echo "<b style='color: greenyellow;'> Feriado!</b>";
			}
			if ($flag_horario_verao){
				echo "<b style='color: orange;'> Horário de Verão</b>";
			}
			elseif ($flag_fim_horario_verao){
				echo "<b style='color: orange;'> Fim do Horário de Verão</b>";
			}
			elseif ($flag_inicio_horario_verao){
				echo "<b style='color: orange;'> Início do Horário de Verão</b>";
			}
			echo "<br>";
		}
	}

?>