<head>
	<style>
	body{
		background-color: beige;
	}
	</style>
</head>
<?php
require 'php-debug-bar/Kint.class.php';

//Gera um vetor com todos os feriados do ano dado
function dias_feriados($ano = null){
  if ($ano === null){
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
	//mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 48,  $ano_pascoa),//2ºfeira Carnaval
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47,  $ano_pascoa),//3ºfeira Carnaval
	//mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 46,  $ano_pascoa),//4ºfeira Carnaval
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2 ,  $ano_pascoa),//6ºfeira Santa
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa     ,  $ano_pascoa),//Pascoa
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60,  $ano_pascoa),//Corpus Christ
  );
  sort($feriados);
  return $feriados;
}

//Altera os horário de entrada e saída de acordo com a tolerância atual
function tolerancia_horario($str_batida,$hora_prev,$mais_minutos,$menos_minutos,$data){
	if((date('H:i',strtotime($mais_minutos,date_timestamp_get($hora_prev)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,date_timestamp_get($hora_prev)))<=date('H:i',strtotime($str_batida)))){
		return $hora_prev;
	}
/* 	elseif((date('H:i',strtotime($mais_minutos,strtotime($hora_prev_2)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,strtotime($hora_prev_2)))<=date('H:i',strtotime($str_batida)))){
		//return new Datetime($data." ".$hora_prev_2);
	}
	elseif((date('H:i',strtotime($mais_minutos,strtotime($hora_prev_3)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,strtotime($hora_prev_3)))<=date('H:i',strtotime($str_batida)))){
		//return new Datetime($data." ".$hora_prev_3);
	} 
	elseif((date('H:i',strtotime($mais_minutos,date_timestamp_get($hora_prev_4)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,date_timestamp_get($hora_prev_4)))<=date('H:i',strtotime($str_batida)))){
		return $hora_prev_4;
	}*/
	else {
		return new Datetime($data." ".$str_batida);
	}
}

//Verifica se a entrada ou a saída esta prevista
function plano_horario_trabalho($str_batida,$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,$mais_minutos,$menos_minutos,$flag_ES){
	if ($flag_ES=='Entrada' OR $flag_ES=='Saida'){
		return $flag_ES;
	}
	else {
		if((date('H:i',strtotime($mais_minutos,date_timestamp_get($hora_prev_1)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,date_timestamp_get($hora_prev_1)))<=date('H:i',strtotime($str_batida)))){
			return 'Entrada';
		}
/* 		elseif((date('H:i',strtotime($mais_minutos,strtotime($hora_prev_2)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,strtotime($hora_prev_2)))<=date('H:i',strtotime($str_batida)))){
			return true;
		}
		elseif((date('H:i',strtotime($mais_minutos,strtotime($hora_prev_3)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,strtotime($hora_prev_3)))<=date('H:i',strtotime($str_batida)))){
			return true;
		} */
		elseif((date('H:i',strtotime($mais_minutos,date_timestamp_get($hora_prev_4)))>=date('H:i',strtotime($str_batida)))AND(date('H:i',strtotime($menos_minutos,date_timestamp_get($hora_prev_4)))<=date('H:i',strtotime($str_batida)))){
			return 'Saída';
		}
		else {
			return false;
		}
	}

}

function desconta_hora_alimentacao($hora,$batida1,$batida2,$almocou){
	if ($almocou==true){
		return true;
		echo "---Foi1";
	}
	if (($hora>$batida1)AND($hora<$batida2)){
		return true;
	}
	else {
		return false;
	}
}

function verifica_hora_pertence_periodo($hora,$inicio_periodo,$fim_periodo,$flag_ES){
	if ($flag_ES==true){
		return true;
	}
	if (($hora>=$inicio_periodo)AND($hora<=$fim_periodo)){
		return true;
	}
	else {
		return false;
	}
}

function hh_noturno($flag_inicio_noturno,$flag_fim_noturno,$batida1,$batida2,$data_atual){
	$hora_inicio_noturno = new Datetime($data_atual." 22:00");
	$hora_fim_noturno = new Datetime($data_atual." 05:00");
	date_modify($hora_fim_noturno, '+1 days');
	if ($flag_inicio_noturno AND $flag_fim_noturno){
		$hh_noturno_atual = date_diff($hora_inicio_noturno, $hora_fim_noturno);
		$horas = $hh_noturno_atual->format("%H");
		$minutos = $hh_noturno_atual->format("%I")/60;
		return $horas + $minutos;
	}
	elseif ($flag_inicio_noturno){
		$hh_noturno_atual = date_diff($hora_inicio_noturno, $batida2);
		$horas = $hh_noturno_atual->format("%H");
		$minutos = $hh_noturno_atual->format("%I")/60;
		return $horas + $minutos;
	}
	elseif ($flag_fim_noturno){
		$hh_noturno_atual = date_diff($batida1, $hora_fim_noturno);
		$horas = $hh_noturno_atual->format("%H");
		$minutos = $hh_noturno_atual->format("%I")/60;
		return $horas + $minutos;
	}
	else{
		return 0;
	}
}

function virou_dia($batida1,$batida2){
	if ($batida1>$batida2){
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

	//Variáveis iniciais resetadas apenas quando matricula é alterada
	$hora_extra = 0; //Total de horas extra
	$hh_total = 0; //Total de HH
	$hh50 = 0; //Hora extra 50%
	$hh75 = 0; //Hora extra 75%
	$hh100 = 0; //Hora extra 100%
	$hh_noturno_atual = 0; //Hora noturna atual caso batidas pares
	$hh_noturno = 0; //Total Hora noturna
	$hh_noturno_atual_1 = 0; //Hora noturna atual caso batidas impares considerando Entrada - Saída - ... - Entrada - Saída - Erro
	$hh_noturno_1 = 0; //Total atual Hora noturna caso batidas impares considerando Entrada - Saída - ... - Entrada - Saída - Erro
	$hh_noturno_atual_2 = 0; //Hora noturna atual caso batidas impares considerando Erro - Entrada - Saída - ... - Entrada - Saída
	$hh_noturno_2 = 0; //Total atual Hora noturna caso batidas impares considerando Erro - Entrada - Saída - ... - Entrada - Saída
	$total_dias = 0; //Total de dias trabalhados
	$hora_incompleta = 0; //Total de déficit de horas
	$ultima_batida = ""; //Última batida do dia anterior
	$matricula = ""; //Matrícula atual
	$vetor_batidas = array(); //Vetor com todas as batidas da matricula
	$pht_semanal = 0;
	$pht_mensal = 0;
	
	$inicio_periodo = new Datetime('2017-02-16');
	$fim_periodo = new Datetime('2017-03-16');
	$intervalo = DateInterval::createFromDateString('1 day');

	$ano_atual=date("Y"); //Ano atual para verificar feriados
	$feriados = dias_feriados($ano_atual); //Vetor com todos os feriados do ano atual
	
	//Conexão com bando de dados + SELECT
	$conn = mysqli_connect(DB_HOST, DB_USER_ROOT, DB_PASS_ROOT) or die ("Erro ao conectar");
	$bd = mysqli_select_db($conn, DB_NAME) or die("Não foi possível selecionar o banco de dados.");
	//mysqli_set_charset($conn, "utf8");
	$query = "SELECT 
				efetivo.matricula as matricula,
				efetivo.status as status,
				efetivo.inicio_ferias as inicio_ferias,
				efetivo.fim_ferias as fim_ferias,
				pht_programado.id_pht_programado as id_pht_programado,
				pht_1.id_pht as id_pht_1,
				pht_1.hora_entrada as hora_entrada_1,
				pht_1.hora_saida as hora_saida_1,
				pht_programado.dias_phtd_1 as dias_phtd_1,
				pht_2.id_pht as id_pht_2,
				pht_2.hora_entrada as hora_entrada_2,
				pht_2.hora_saida as hora_saida_2,
				pht_programado.dias_phtd_2 as dias_phtd_2 
				FROM efetivo 
					JOIN pht_programado ON efetivo.fk_pht_programado=pht_programado.id_pht_programado 
					JOIN pht_diario as pht_1 ON pht_programado.fk_phtd_1=pht_1.id_pht 
					LEFT JOIN pht_diario as pht_2 ON pht_programado.fk_phtd_2=pht_2.id_pht 
					WHERE (status='ativo' OR status LIKE '%Ferias%') 
					ORDER BY efetivo.matricula";
					
	$efetivo = mysqli_query($conn, $query);
	if ($efetivo){
		while($row_efetivo = mysqli_fetch_array($efetivo, MYSQL_ASSOC)){		
			//Zerar ou seta novas variáveis gerais
			$matricula = $row_efetivo['matricula'];
			$status = $row_efetivo['status'];
			$inicio_ferias = new Datetime($row_efetivo['inicio_ferias']);
			$fim_ferias = new Datetime($row_efetivo['fim_ferias']);
			$id_pht_programado = $row_efetivo['id_pht_programado'];
			$id_pht_1 = $row_efetivo['id_pht_1'];
			$id_pht_2 = $row_efetivo['id_pht_2'];
			$hora_entrada_1 = $row_efetivo['hora_entrada_1'];
			$hora_entrada_2 = $row_efetivo['hora_entrada_2'];
			$hora_saida_1 = $row_efetivo['hora_saida_1'];
			$hora_saida_2 = $row_efetivo['hora_saida_2'];
			$dias_phtd_1 = $row_efetivo['dias_phtd_1'];
			$dias_phtd_2 = $row_efetivo['dias_phtd_2'];
			$dias_phtd_1 = str_split ($dias_phtd_1);
			$dias_phtd_2 = str_split ($dias_phtd_2);
			$hora_extra = 0;
			$hh_total = 0;
			$hh50 = 0;
			$hh75 = 0;
			$hh100 = 0;
			$hh_noturno_atual = 0;
			$hh_noturno = 0;
			$hh_noturno_atual_1 = 0;
			$hh_noturno_1 = 0;
			$hh_noturno_atual_2 = 0;
			$hh_noturno_2 = 0;
			$total_dias = 0;
			$hh_correto = 0;
			unset ($hora_descanso);
			$vetor_batidas = array();
			$primeira_batida = "";
			$ultima_batida = "";
			$flag_descanso = false;
			$hora_incompleta = 0;
			$pht_semanal = 0;
			$pht_mensal = 0;
			
			if ($inicio_ferias>new Datetime("0000-00-00") AND $fim_ferias>new Datetime("0000-00-00")){
				echo $matricula." - ".$status.": ".date_format($inicio_ferias, 'd/m/Y').">>>".date_format($fim_ferias, 'd/m/Y')."<br>";
			}
			else {
				echo $matricula." - ".$status."<br>";
			}
			echo "Plano de horário: ". $id_pht_programado."<br>";
			
			$periodo = new DatePeriod($inicio_periodo, $intervalo, $fim_periodo);
			foreach ($periodo as $data_atual){
				$query = "SELECT * FROM ponto_diario
							WHERE fk_matricula='$matricula' AND data='".$data_atual->format('Y-m-d')."'";
				$ponto_diario = mysqli_query($conn, $query);
				$row = mysqli_fetch_array($ponto_diario, MYSQL_ASSOC);
				
				//Split das batidas atuais
				if (mysqli_num_rows($ponto_diario)==0){
					$batidas = explode("|","");
					$qt_batidas = count($batidas);
					$primeira_batida = new Datetime($row['data']);
				}
				else {
					$batidas = explode("|",$row['batidas']);
					$qt_batidas = count($batidas);
					$primeira_batida = new Datetime($row['data']." ".$batidas[0]);
				}
				
				$hh1 = 0;
				$hh2 = 0;
				$hora_extra_atual = 0;
				$hora_incompleta_atual = 0;	
				$hh_noturno_atual = 0;
				$hora_extra_dia_seguinte = 0;
				$pht = 0;
				$almocou1 = false;
				$jantou1 = false;
				$almocou2 = false;
				$jantou2 = false;
				$almocou3 = false;
				$jantou3 = false;
				$pht_almocou = false;
				$pht_jantou = false;
				$flag_falta_batida = false;
				$flag_falta = false;
				$flag_demitido = false;
				$flag_virou_dia = false;
				$flag_entrada_prevista = false;
				$flag_saida_prevista = false;
				$flag_ferias = false;
				$flag_feriado = false;
				$flag_trabalho_emergencial = false;
				$flag_horario_verao = false;
				$flag_inicio_horario_verao = false;
				$flag_fim_horario_verao = false;
				$flag_pht_virada = false;
				$flag_batidas_extras = false;
				$flag_hh_dia_anterior = false;
				$flag_inicio_noturno = false;
				$flag_fim_noturno = false;
				$flag_hora_noturna = false;
				$flag_possivel_inversao = false;
				$flag_hora_extra_proibida = false;
				$flag_batida1_pertence_periodo = false;
				$flag_batida2_pertence_periodo = false;
				$flag_possivel_troca_horario = false;
				$flag_hora_nao_programada = false;
				$flag_possivel_compensacao = false;
				$flag_x_y = false;
				
				$horario_almoco = new Datetime($row['data']." 12:00");
				$horario_janta = new Datetime($row['data']." 20:00");
				$hora_inicio_noturno = new Datetime($row['data']." 22:00");
				$hora_fim_noturno = new Datetime($row['data']." 05:00");
				date_modify($hora_fim_noturno, '+1 days');
				
				//Horas previstas baseados no PHT de contrato
				if ($id_pht_programado=='P066' OR $id_pht_programado=='P067' OR $id_pht_programado=='P359-A' OR $id_pht_programado=='P359-B'){
					if ((date('W', strtotime($data_atual->format('Y-m-d'))) % 2 == 0) AND (in_array(date('w', strtotime($data_atual->format('Y-m-d'))),$dias_phtd_1))){
						$hora_prev_1 = new Datetime($row['data']." ".$hora_entrada_1);
						$hora_prev_2 = new Datetime($row['data']);
						$hora_prev_3 = new Datetime($row['data']);
						$hora_prev_4 = new Datetime($row['data']." ".$hora_saida_1);
					}
					elseif ((date('W', strtotime($data_atual->format('Y-m-d'))) % 2 == 1) AND (in_array(date('w', strtotime($data_atual->format('Y-m-d'))),$dias_phtd_2))) {
						$hora_prev_1 = new Datetime($row['data']." ".$hora_entrada_2);
						$hora_prev_2 = new Datetime($row['data']);
						$hora_prev_3 = new Datetime($row['data']);
						$hora_prev_4 = new Datetime($row['data']." ".$hora_saida_2);
					}
					else {
						$hora_prev_1 = new Datetime($row['data']." 00:00");
						$hora_prev_2 = new Datetime($row['data']." 00:00");
						$hora_prev_3 = new Datetime($row['data']." 00:00");
						$hora_prev_4 = new Datetime($row['data']." 00:00");
					}
					$flag_x_y = true;
				}
				else {
					if (isset($id_pht_1) AND (in_array(date('w', strtotime($data_atual->format('Y-m-d'))),$dias_phtd_1))){
						$hora_prev_1 = new Datetime($row['data']." ".$hora_entrada_1);
						$hora_prev_2 = new Datetime($row['data']);
						$hora_prev_3 = new Datetime($row['data']);
						$hora_prev_4 = new Datetime($row['data']." ".$hora_saida_1);
					}
					elseif (isset($id_pht_2) AND (in_array(date('w', strtotime($data_atual->format('Y-m-d'))),$dias_phtd_2))){
						$hora_prev_1 = new Datetime($row['data']." ".$hora_entrada_2);
						$hora_prev_2 = new Datetime($row['data']);
						$hora_prev_3 = new Datetime($row['data']);
						$hora_prev_4 = new Datetime($row['data']." ".$hora_saida_2);
					}
					else {
						$hora_prev_1 = new Datetime($row['data']." 00:00");
						$hora_prev_2 = new Datetime($row['data']." 00:00");
						$hora_prev_3 = new Datetime($row['data']." 00:00");
						$hora_prev_4 = new Datetime($row['data']." 00:00");
					}
					$flag_x_y = false;
				}

				
				//Verifica se PHT é de virada de dia
				if ($hora_prev_4<$hora_prev_1){
					date_modify($hora_prev_4, '+1 days');
					$flag_pht_virada = true;
				}
				
				//Ativa flag caso Não exista hora prevista, ou caso seja X-Y
				if ($hora_prev_1->format('H:i')=="00:00" AND $hora_prev_4->format('H:i')=="00:00"){
					$flag_hora_nao_programada = true;
				}

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
			
				//Desconta almoço e janta do PHT
				$pht_almocou = desconta_hora_alimentacao($horario_almoco,$hora_prev_1,$hora_prev_4,$almocou1);
				$pht_jantou = desconta_hora_alimentacao($horario_janta,$hora_prev_1,$hora_prev_4,$jantou1);
				$pht = date_diff($hora_prev_1, $hora_prev_4);
				$dias = $pht->format("%d")*24;
				$horas = $pht->format("%H");
				$minutos = $pht->format("%I")/60;
				$pht = $dias + $horas + $minutos;
				if ($pht_almocou){ //Desconta almoço
					$pht -= 1;
				}
				if ($pht_jantou){ //Desconta janta
					$pht -= 1;
				}
				$pht_semanal += $pht;
				$pht_mensal += $pht;
				//Reduçao e aumento de PHT de acordo com o horário de verão se virada de dia
				if ($flag_fim_horario_verao AND $flag_pht_virada){
					$pht -=1;
				}
				elseif ($flag_inicio_horario_verao AND $flag_pht_virada){
					$pht +=1;
				}
				//Se a primeira batida é vazia, total de batidas é zero
				if ($batidas[0]==""){
					$qt_batidas = 0;
				}
				//Se mais de três batidas ativa flag de batidas extras
				if ($qt_batidas>=3){
					$flag_batidas_extras = true;
				}
				//Ativa flag Férias caso data atual está entre o período dado
				if ($inicio_ferias>new Datetime("0000-00-00") AND $fim_ferias>new Datetime("0000-00-00")){
					if ($inicio_ferias<=$data_atual AND $fim_ferias>=$data_atual){
						$flag_ferias = true;
					}
					else {
						$flag_ferias = false;
					}
				}
				//Ativa flag feriado caso data atual seja feriado
				if (in_array(date_timestamp_get($data_atual), $feriados)) { 
					$flag_feriado = true;
				}
				//Ativa flag descanso se não foi cumprido as 11 horas de descanso do dia anterior
				if (($primeira_batida<>"")AND($ultima_batida<>"")){
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
				//Ativa flag falta caso não tenha batida no dia
				if (($hora_prev_1<>"")OR($hora_prev_2<>"")OR($hora_prev_3<>"")OR($hora_prev_4<>"")){
					if ($qt_batidas==0){
						$flag_falta = true;
					}
					else{
						$flag_falta = false;
					}
				}
				//Ativa flag trabalho emergencial caso possua batida em dias não programados que geram hora extra
				if ($qt_batidas>0){
					if ($hora_prev_1->format('H:i')=="00:00" AND $hora_prev_4->format('H:i')=="00:00"){
						$flag_trabalho_emergencial = true;
					}
				}
			
				//Verifica perídos de trabalho, hh, hora extra, etc
				//INICIO PROCESSO PRINCIPAL
				if ($qt_batidas==0){
					$hh_correto = 0;
					$ultima_batida = "";
					if (($hora_prev_1<>"")OR($hora_prev_2<>"")OR($hora_prev_3<>"")OR($hora_prev_4<>"")){
						if (!$flag_ferias AND !$flag_feriado){
							$hora_incompleta_atual = $pht;
							$hora_incompleta += $hora_incompleta_atual;
						}
					}
				}
				elseif ($qt_batidas==1){
					$flag_entrada_prevista = plano_horario_trabalho($batidas[0],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-5 minutes",$flag_entrada_prevista);
					$flag_saida_prevista = plano_horario_trabalho($batidas[0],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-0 minutes",$flag_saida_prevista);
					$flag_falta_batida = true;
					$hh1 = 0;
					$hh_correto = $hh1;
					$total_dias +=1;
					if (($hora_prev_1<>"")OR($hora_prev_2<>"")OR($hora_prev_3<>"")OR($hora_prev_4<>"")){
						if (!$flag_ferias AND !$flag_feriado){
							$hora_incompleta_atual = $pht;
							$hora_incompleta += $hora_incompleta_atual;
						}
					}
				}
				elseif(($qt_batidas % 2)==0){
					$total_dias +=1;
					for ($i = 0; $i < $qt_batidas; $i+=2) {
						$flag_batida1_pertence_periodo = false;
						$flag_batida2_pertence_periodo = false;
						
						$almocou1 = false;
						$jantou1 = false;
						
						$batida1 = new Datetime($row['data']." ".$batidas[$i]);
						$batida2 = new Datetime($row['data']." ".$batidas[$i+1]);
						
						$flag_virou_dia = virou_dia($batida1,$batida2);
						
						$flag_entrada_prevista = plano_horario_trabalho($batidas[$i],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-5 minutes",$flag_entrada_prevista);
						$flag_saida_prevista = plano_horario_trabalho($batidas[$i+1],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-0 minutes",$flag_saida_prevista);
						
						if ($flag_entrada_prevista){
							$batida1 = tolerancia_horario($batidas[$i],$hora_prev_1,"+5 minutes","-5 minutes",$row['data']);
						}
						if ($flag_saida_prevista){
							$batida2 = tolerancia_horario($batidas[$i+1],$hora_prev_4,"+5 minutes","-0 minutes",$row['data']);
							if ($flag_virou_dia){
								date_modify($batida2, '+1 days');
							}
						}
						
						if ($flag_pht_virada AND ($batida2>$data_seguinte)){
							$flag_virou_dia = true;
						}
						
						$flag_inicio_noturno = verifica_hora_pertence_periodo($hora_inicio_noturno,$batida1,$batida2,$flag_inicio_noturno);
						$flag_fim_noturno = verifica_hora_pertence_periodo($hora_fim_noturno,$batida1,$batida2,$flag_fim_noturno);
						
						$hh_noturno_atual = hh_noturno($flag_inicio_noturno,$flag_fim_noturno,$batida1,$batida2,$row['data']);
						$hh_noturno += $hh_noturno_atual;
						
						$hh = date_diff($batida1, $batida2);
						$horas = $hh->format("%H");
						$minutos = $hh->format("%I")/60;
						$hh = $horas + $minutos;
						
						$horario_almoco = new Datetime($row['data']." 12:00");
						$horario_janta = new Datetime($row['data']." 20:00");
						
						$almocou1 = desconta_hora_alimentacao($horario_almoco,$batida1,$batida2,$almocou1);
						date_modify($horario_almoco, '-1 days');
						$almocou1 = desconta_hora_alimentacao($horario_almoco,$batida1,$batida2,$almocou1);
						
						$jantou1 = desconta_hora_alimentacao($horario_janta,$batida1,$batida2,$jantou1);
						date_modify($horario_janta, '-1 days');
						$jantou1 = desconta_hora_alimentacao($horario_janta,$batida1,$batida2,$jantou1);
						if ($almocou1){$hh -= 1;}
						if ($jantou1){$hh -= 1;}
						$hh1 += $hh;
						//Cálculos de hora extra e deficit de horas

						if ((date('w', date_timestamp_get($data_atual)) > 0) AND (date('w', date_timestamp_get($data_atual)) < 6) AND !(in_array(date_timestamp_get($data_atual), $feriados))){
							$flag_batida1_pertence_periodo = verifica_hora_pertence_periodo($batida1,$hora_prev_1,$hora_prev_4,$flag_batida1_pertence_periodo);
							$flag_batida2_pertence_periodo = verifica_hora_pertence_periodo($batida2,$hora_prev_1,$hora_prev_4,$flag_batida2_pertence_periodo);
							if ($flag_batida1_pertence_periodo AND $flag_batida2_pertence_periodo){
								if(isset($batidas[$i+2]) AND !isset($batidas[$i-1])){
									$batida3 = tolerancia_horario($batidas[$i+2],$hora_prev_1,"+5 minutes","-5 minutes",$row['data']);
									if ($batida1>$hora_prev_1){
										$hh = date_diff($hora_prev_1, $batida1);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_incompleta_atual += $hh;
									}
									if ($batida2<$batida3){
										$hh = date_diff($batida2, $batida3);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_incompleta_atual += $hh;
									}
								}
								elseif(isset($batidas[$i+2]) AND isset($batidas[$i-1])){
									$batida3 = tolerancia_horario($batidas[$i+2],$hora_prev_4,"+5 minutes","-5 minutes",$row['data']);
									if ($batida2<$batida3){
										$hh = date_diff($batida2, $batida3);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_incompleta_atual += $hh;
									}
								}
								elseif(!isset($batidas[$i+2]) AND isset($batidas[$i-1])){
									if ($batida2<$hora_prev_4){
										$hh = date_diff($batida2, $hora_prev_4);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_incompleta_atual += $hh;
									}
								}
								elseif(!isset($batidas[$i+2]) AND !isset($batidas[$i-1])){
									if ($batida1>$hora_prev_1){
										$hh = date_diff($hora_prev_1, $batida1);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_incompleta_atual += $hh;
									}
									if ($batida2<$hora_prev_4){
										$hh = date_diff($batida2, $hora_prev_4);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_incompleta_atual += $hh;
									}
								}
							}
							elseif (!$flag_batida1_pertence_periodo AND $flag_batida2_pertence_periodo){
								if(isset($batidas[$i+2]) AND !isset($batidas[$i-1])){
									$batida3 = tolerancia_horario($batidas[$i+2],$hora_prev_1,"+5 minutes","-5 minutes",$row['data']);
									
									$hh = date_diff($hora_prev_1, $batida1);
									$horas = $hh->format("%H");
									$minutos = $hh->format("%I")/60;
									$hh = $horas + $minutos;
									$hora_extra_atual += $hh;
									
									if ($batida2<$batida3 AND $batida3<=$hora_prev_4){
										$hh = date_diff($batida2, $batida3);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_incompleta_atual += $hh;
									}
								}
								elseif(isset($batidas[$i+2]) AND isset($batidas[$i-1])){
									
								}
								elseif(!isset($batidas[$i+2]) AND isset($batidas[$i-1])){

								}
								elseif(!isset($batidas[$i+2]) AND !isset($batidas[$i-1])){
									$hh = date_diff($hora_prev_1, $batida1);
									$horas = $hh->format("%H");
									$minutos = $hh->format("%I")/60;
									$hh = $horas + $minutos;
									$hora_extra_atual += $hh;
									
									if ($batida2<$hora_prev_4){
										$hh = date_diff($batida2, $hora_prev_4);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_incompleta_atual += $hh;
									}
								}
							}
							elseif ($flag_batida1_pertence_periodo AND !$flag_batida2_pertence_periodo){
								$horario_almoco = new Datetime($row['data']." 12:00");
								$horario_janta = new Datetime($row['data']." 20:00");
								$almocou1 = false;
								$jantou1 = false;
								
								if(isset($batidas[$i+2]) AND !isset($batidas[$i-1])){
									$batida3 = tolerancia_horario($batidas[$i+2],$hora_prev_1,"+5 minutes","-5 minutes",$row['data']);
									if ($batida1>$hora_prev_1){
										$hh = date_diff($hora_prev_1, $batida1);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_incompleta_atual += $hh;
									}
									$hh = date_diff($hora_prev_4, $batida2);
									$horas = $hh->format("%H");
									$minutos = $hh->format("%I")/60;
									$hh = $horas + $minutos;
									$hora_extra_atual += $hh;
								}
								elseif(isset($batidas[$i+2]) AND isset($batidas[$i-1])){

								}
								elseif(!isset($batidas[$i+2]) AND isset($batidas[$i-1])){
									$hh = date_diff($hora_prev_4, $batida2);
									$horas = $hh->format("%H");
									$minutos = $hh->format("%I")/60;
									$hh = $horas + $minutos;
									$hora_extra_atual += $hh;
									if ($flag_virou_dia){
										$hora_extra_dia_seguinte = $hh;
									}
								}
								elseif(!isset($batidas[$i+2]) AND !isset($batidas[$i-1])){
									if ($batida1>$hora_prev_1){
										$hh = date_diff($hora_prev_1, $batida1);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_incompleta_atual += $hh;
									}
									$almocou1 = desconta_hora_alimentacao($horario_almoco,$hora_prev_4,$batida2,$almocou1);
									$jantou1 = desconta_hora_alimentacao($horario_janta,$hora_prev_4,$batida2,$jantou1);
									$hh = date_diff($hora_prev_4, $batida2);
									$horas = $hh->format("%H");
									$minutos = $hh->format("%I")/60;
									$hh = $horas + $minutos;
									$hora_extra_atual += $hh;
									if ($flag_virou_dia AND $flag_pht_virada){
										$hora_extra_dia_seguinte = $hh;
									}
									elseif ($flag_virou_dia){
										$hh = date_diff($data_seguinte, $batida2);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_extra_dia_seguinte = $hh;
									}
								}
								if ($hora_extra_atual>1){
									if ($almocou1){
										$hora_extra_atual -= 1;
									}
									if ($jantou1){
										$hora_extra_atual -= 1;
									}
								}
							}
							elseif (!$flag_batida1_pertence_periodo AND !$flag_batida2_pertence_periodo){
								if (($batida1<=$hora_prev_1 AND $batida2<=$hora_prev_1)OR($batida1>=$hora_prev_4 AND $batida2>=$hora_prev_4)){
									if ($qt_batidas==2){
										$hora_incompleta_atual += $pht;
									}
									$horario_almoco = new Datetime($row['data']." 12:00");
									$horario_janta = new Datetime($row['data']." 20:00");
									$almocou1 = desconta_hora_alimentacao($horario_almoco,$batida1,$batida2,$almocou1);
									$jantou1 = desconta_hora_alimentacao($horario_janta,$batida1,$batida2,$jantou1);

									$hh = date_diff($batida1, $batida2);
									$horas = $hh->format("%H");
									$minutos = $hh->format("%I")/60;
									$hh = $horas + $minutos;
									$hora_extra_atual += $hh;
									
									if ($almocou1){
										$hora_extra_atual -= 1;
									}
									if ($jantou1){
										$hora_extra_atual -= 1;
									}
									if ($flag_virou_dia AND $flag_pht_virada){
										$hora_extra_dia_seguinte = $hh;
									}
									elseif ($flag_virou_dia){
										$hh = date_diff($data_seguinte, $batida2);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_extra_dia_seguinte = $hh;
									}
								}
								elseif($batida1<=$hora_prev_1 AND $batida2>=$hora_prev_4){
									
									//Hora extra no inicio do expediente
									$hh = date_diff($batida1, $hora_prev_1);
									$horas = $hh->format("%H");
									$minutos = $hh->format("%I")/60;
									$hh = $horas + $minutos;
									$hora_extra_atual += $hh;
									
									//Hora extra no fim do expediente
									$hh = date_diff($batida2, $hora_prev_4);
									$horas = $hh->format("%H");
									$minutos = $hh->format("%I")/60;
									$hh = $horas + $minutos;
									$hora_extra_atual += $hh;
									if ($flag_virou_dia){
										$hora_extra_dia_seguinte = $hh;
									}
								}
							}
						}
						else {
							//Caso virada de dia com dia seguinte sendo feriado ou domingo gera hora extra mesmo estando programado
							if ($flag_virou_dia AND ((date('w', date_timestamp_get($data_seguinte)) == 0) OR in_array(date_timestamp_get($data_seguinte), $feriados))){
								if (($batida1->format('d')==$data_seguinte->format('d'))AND($batida2->format('d')==$data_seguinte->format('d'))){
								}
								elseif ($batida1->format('d')==$data_seguinte->format('d')){
								}
								elseif ($batida2->format('d')==$data_seguinte->format('d')){
									if ($flag_hora_nao_programada){
										$hh = date_diff($batida1, $data_seguinte);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_extra_atual += $hh;
										if ($jantou1){
											$hora_extra_atual -=1;
										}
									}
									else {
										if ($flag_x_y){
											$almocou1 = false;
											$jantou1 = false;
											$horario_almoco = new Datetime($row['data']." 12:00");
											$horario_janta = new Datetime($row['data']." 20:00");
											$almocou1 = desconta_hora_alimentacao($horario_almoco,$hora_prev_4, $data_seguinte,$almocou1);
											$jantou1 = desconta_hora_alimentacao($horario_janta,$hora_prev_4, $data_seguinte,$jantou1);
											$hh = date_diff($hora_prev_4, $data_seguinte);
											$horas = $hh->format("%H");
											$minutos = $hh->format("%I")/60;
											$hh = $horas + $minutos;
											$hora_extra_atual += $hh;
											if ($almocou1){
												$hora_extra_atual -=1;
											}
											if ($jantou1){
												$hora_extra_atual -=1;
											}
											if ($flag_fim_horario_verao){
												$hora_extra_atual -=1;
											}
										}
										else {
											if ($batida1>$hora_prev_1){
												$hh = date_diff($batida1, $hora_prev_1);
												$horas = $hh->format("%H");
												$minutos = $hh->format("%I")/60;
												$hh = $horas + $minutos;
												$hora_incompleta_atual += $hh;
											}
											else {
												$hh = date_diff($batida1, $data_seguinte);
												$horas = $hh->format("%H");
												$minutos = $hh->format("%I")/60;
												$hh = $horas + $minutos;
												$hora_extra_atual += $hh;
												if ($almocou1){
													$hora_extra_atual -=1;
												}
												if ($jantou1){
													$hora_extra_atual -=1;
												}
												if ($flag_fim_horario_verao){
													$hora_extra_atual -=1;
												}
											}
										}
									}
									if ($batida2>=$data_seguinte){
										$hh = date_diff($data_seguinte, $batida2);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_extra_dia_seguinte = $hh;
										$hora_extra_atual += $hora_extra_dia_seguinte;
									}
									else {
										$hh = date_diff($batida2, $data_seguinte);
										$horas = $hh->format("%H");
										$minutos = $hh->format("%I")/60;
										$hh = $horas + $minutos;
										$hora_extra_atual += $hh;
									}
								}
								else {
								}
							}
							elseif ($flag_virou_dia AND ((date('w', date_timestamp_get($data_seguinte)) == 1))){
								$hora_extra_atual = $hh;
								
								$hh = date_diff($data_seguinte,$batida2);
								$horas = $hh->format("%H");
								$minutos = $hh->format("%I")/60;
								$hh = $horas + $minutos;
								$hora_extra_dia_seguinte += $hh;
							}
							elseif ($flag_hora_nao_programada){
								$hora_extra_atual += $hh;
							}
							elseif ($flag_x_y AND (date('w',date_timestamp_get($data_seguinte)) == 0)){
								$almocou1 = false;
								$jantou1 = false;
								$horario_almoco = new Datetime($row['data']." 12:00");
								$horario_janta = new Datetime($row['data']." 20:00");
								$almocou1 = desconta_hora_alimentacao($horario_almoco,$hora_prev_4,$batida2,$almocou1);
								$jantou1 = desconta_hora_alimentacao($horario_janta,$hora_prev_4,$batida2,$jantou1);
								$hh = date_diff($hora_prev_4, $batida2);
								$horas = $hh->format("%H");
								$minutos = $hh->format("%I")/60;
								$hh = $horas + $minutos;
								$hora_extra_atual += $hh;
								if ($almocou1){
									$hora_extra_atual -=1;
								}
								if ($jantou1){
									$hora_extra_atual -=1;
								}
							}
						}
					}
					//Cálculo de hora extra do dia seguinte
					if ($hora_extra_dia_seguinte==0){
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
					else {					
						if (in_array(date_timestamp_get($data_seguinte), $feriados) OR (date('w',date_timestamp_get($data_seguinte)) == 0)) { 
							$hh100 += $hora_extra_dia_seguinte;
						}
						elseif((date('w', date_timestamp_get($data_seguinte)) == 6)) {
							$hh75 += $hora_extra_dia_seguinte;
						}
						else {
							$hh50 += $hora_extra_dia_seguinte;
						}
					
					
						if ($flag_feriado OR (date('w', strtotime($row['data'])) == 0)){
							$hh100 += $hora_extra_atual - $hora_extra_dia_seguinte;
						}
						elseif((date('w', strtotime($row['data'])) == 6)) {
							$hh75 += $hora_extra_atual - $hora_extra_dia_seguinte;
						}
						else {
							$hh50 += $hora_extra_atual - $hora_extra_dia_seguinte;
						}
					}
					
					$hora_extra += $hora_extra_atual;
					$hora_incompleta += $hora_incompleta_atual;
					$hh_correto = $hh1;
				}
				else {
					for ($i = 0; $i < $qt_batidas-1; $i+=2) {
						//Entrada - Saída - ... - Entrada - Saída - Erro
						$batida1 = tolerancia_horario($batidas[$i],$hora_prev_1,"+5 minutes","-5 minutes",$row['data']);
						$batida2 = tolerancia_horario($batidas[$i+1],$hora_prev_4,"+5 minutes","-0 minutes",$row['data']);
						
						$flag_entrada_prevista = plano_horario_trabalho($batidas[$i],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-5 minutes",$flag_entrada_prevista);
						$flag_saida_prevista = plano_horario_trabalho($batidas[$i+1],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-0 minutes",$flag_saida_prevista);
						
						$flag_virou_dia = virou_dia($batida1,$batida2);
						
						$flag_inicio_noturno = verifica_hora_pertence_periodo($hora_inicio_noturno,$batida1,$batida2,$flag_inicio_noturno);
						$flag_fim_noturno = verifica_hora_pertence_periodo($hora_fim_noturno,$batida1,$batida2,$flag_fim_noturno);
						
						$horario_almoco = new Datetime($row['data']." 12:00");
						$horario_janta = new Datetime($row['data']." 20:00");
						
						$almocou1 = desconta_hora_alimentacao($horario_almoco,$batida1,$batida2,$almocou1);
						date_modify($horario_almoco, '-1 days');
						$almocou1 = desconta_hora_alimentacao($horario_almoco,$batida1,$batida2,$almocou1);
						$jantou1 = desconta_hora_alimentacao($horario_janta,$batida1,$batida2,$jantou1);
						date_modify($horario_janta, '-1 days');
						$jantou1 = desconta_hora_alimentacao($horario_janta,$batida1,$batida2,$jantou1);
						
						$hh = date_diff($batida1, $batida2);
						$horas = $hh->format("%H");
						$minutos = $hh->format("%I")/60;
						$hh = $horas + $minutos;
						$hh1 += $hh;
						
						$hh_noturno_atual_1 = hh_noturno($flag_inicio_noturno,$flag_fim_noturno,$batida1,$batida2,$row['data']);
						$hh_noturno_1 += $hh_noturno_atual_1;
						
						//Erro - Entrada - Saída - ... - Entrada - Saída
						$batida1 = tolerancia_horario($batidas[$i+1],$hora_prev_1,"+5 minutes","-5 minutes",$row['data']);
						$batida2 = tolerancia_horario($batidas[$i+2],$hora_prev_4,"+5 minutes","-0 minutes",$row['data']);
						
						$flag_entrada_prevista = plano_horario_trabalho($batidas[$i+1],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-5 minutes",$flag_entrada_prevista);
						$flag_saida_prevista = plano_horario_trabalho($batidas[$i+2],$hora_prev_1,$hora_prev_2,$hora_prev_3,$hora_prev_4,"+5 minutes","-0 minutes",$flag_saida_prevista);
						
						$flag_inicio_noturno = verifica_hora_pertence_periodo($hora_inicio_noturno,$batida1,$batida2,$flag_inicio_noturno);
						$flag_fim_noturno = verifica_hora_pertence_periodo($hora_fim_noturno,$batida1,$batida2,$flag_fim_noturno);
						
						$horario_almoco = new Datetime($row['data']." 12:00");
						$horario_janta = new Datetime($row['data']." 20:00");
						
						$almocou2 = desconta_hora_alimentacao($horario_almoco,$batida1,$batida2,$almocou2);
						date_modify($horario_almoco, '-1 days');
						$almocou2 = desconta_hora_alimentacao($horario_almoco,$batida1,$batida2,$almocou2);
						$jantou2 = desconta_hora_alimentacao($horario_janta,$batida1,$batida2,$jantou2);
						date_modify($horario_janta, '-1 days');
						$jantou2 = desconta_hora_alimentacao($horario_janta,$batida1,$batida2,$jantou2);					
						
						$hh = date_diff($batida1, $batida2);
						$horas = $hh->format("%H");
						$minutos = $hh->format("%I")/60;
						$hh = $horas + $minutos;
						$hh2 += $hh;
						
						$hh_noturno_atual_2 = hh_noturno($flag_inicio_noturno,$flag_fim_noturno,$batida1,$batida2,$row['data']);
						$hh_noturno_2 += $hh_noturno_atual_2;
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
						$hh_noturno_atual = $hh_noturno_atual_2;
						$hh_noturno += $hh_noturno_2;
						
					}
					else {
						$hh_correto = $hh1;
						$hh_noturno_atual = $hh_noturno_atual_1;
						$hh_noturno += $hh_noturno_1;
					}
				}
				//FIM PROCESSO PRINCIPAL
			
				//Caso hora extra atual igual ao deficit atual, possível troca de horário
				if ($hora_extra_atual==$hora_incompleta_atual AND($hora_extra_atual>0 AND $hora_incompleta_atual>0)){
					$flag_possivel_troca_horario = true;
				}
				//Caso tenha mais hora extra do que deficit de horas no dia
				if (($hora_extra_atual>=$hora_incompleta_atual)AND($hora_extra_atual>0 AND $hora_incompleta_atual>0)){
					$flag_possivel_compensacao = true;
				}
				//Ativa flag de hora noturna
				if ($hh_noturno_atual){
					$flag_hora_noturna = true;
				}
				//Reduçao e aumento de hora correta de acordo com o horário de verão
				if ($flag_fim_horario_verao AND $flag_virou_dia){
					$hh_correto -=1;
					$hh_noturno_atual -= 1;
					$hh_noturno -= 1;
				}
				elseif ($flag_inicio_horario_verao AND $flag_virou_dia){
					$hh_correto +=1;
					$hh_noturno_atual += 1;
					$hh_noturno += 1;
				}

				//Ativa flag possivel inversão (não necessariamente um erro, mas um indicativo de erro)
				//onde HH correto é maior que horas de descanso e horas de descanso menor que 11
				if (isset($hora_descanso) AND $hh_correto>$hora_descanso AND $hora_descanso<11){
					$flag_possivel_inversao = true;
				}
				//Ativa flag hora extra proibida caso mais horas extras do que o previsto por lei "+2 horas"
				if (($hora_prev_1<>"")OR($hora_prev_4<>"")){
					if ($hora_extra_atual>2 AND $hora_extra_atual-2>=0.1){
						$flag_hora_extra_proibida = true;
					}
				}
				//Salva última batida de ponto para verificar trabalho na virada de um dia
				$hh_total += $hh_correto;
				if ($qt_batidas>=1) {
					$ultima_batida = new Datetime($row['data']." ".$batidas[$qt_batidas-1]);
					if ($flag_virou_dia){
						date_modify($ultima_batida, '+1 days');
					}
				}
				else {
					$ultima_batida = "";
				}
				//Imprime data atual
				echo $data_atual->format('d-m-Y');
				//Caso exista hora de trabalho previsto
				if ($flag_hora_nao_programada){
					echo "<b style='color:red'> Horário não previsto </b>";
				}
				elseif ($hora_prev_1<>"" AND $hora_prev_4<>""){
					echo "<b> Hora prevista: ".$hora_prev_1->format('H:i')." - ".$hora_prev_4->format('H:i')." PHT: ".round($pht,2)."</b>";
					if ($flag_x_y){
						echo "<b style='color: orange;'> Horário X-Y</b>";
					}
				}
				else {
					echo "<b style='color:red'> Horário não previsto </b>";
				}
				//Imprime Batidas de ponto
				if ($qt_batidas>1){
					echo " Batidas: ".$qt_batidas." ";
					foreach ($batidas as $ponto) {
						$vetor_batidas[] = new Datetime ($row['data']." ".$ponto);
						echo $ponto. " * ";
					}
				}
				elseif ($qt_batidas=1 AND $batidas[0]<>""){
					$vetor_batidas[] = new Datetime ($row['data']." ".$batidas[0]);
					echo " Batidas: ".$qt_batidas." ".$batidas[0];
				}
				elseif ($qt_batidas=0 OR $batidas[0]==""){
					echo "<b style='color: blue;'> Nenhuma batida de ponto</b>";
				}
				//HH mais correto
				echo " HH 1: ".round($hh1, 2)." HH 2: ".round($hh2, 2)." <b>HH mais correto: ".round($hh_correto, 2)."</b>";
				//Férias
				if ($flag_ferias){
					echo "<b style='color: green;'> Período de Férias</b>";
				}
				//Apenas 1 batida no ponto
				if ($flag_falta_batida){
					echo "<b style='color: purple;'> Apenas uma batida de ponto!</b>";
				}
				//Mais de duas batidas
				if ($flag_batidas_extras){
					echo "<b style='color: purple;'> Mais que duas batidas de ponto!</b>";
				}
				//Hora extra ou hora incompleta
				if ($hora_extra_atual>0){
					echo "<b style='color: cornflowerblue;'> Hora extra: ".round($hora_extra_atual,2)."</b>";
					if ($flag_hora_extra_proibida){
						echo "<b style='color: coral;'> Hora Extra Proibida: ".round($hora_extra_atual-2,2)."</b>";
					}
					if ($hora_extra_dia_seguinte>0){
						echo "<b style='color: aqua;'> Hora Extra Dia Seguinte: ".round($hora_extra_dia_seguinte,2)."</b>";
					}
				}
				if ($hora_incompleta_atual>0) {
					//Nenhuma batida num dia programado
					if ($flag_falta AND !$flag_feriado AND !$flag_ferias){
						echo "<b style='color: deeppink;'> Falta não justificada!</b>";
						if ($hora_extra>=$pht){
							echo " <b style='background-color:cornflowerblue; color: pink;'>Compensação Completa!</b>";
						}
					}
					else {
						echo "<b style='color: red;'> Déficit de horas: ".round($hora_incompleta_atual,2)."</b>";
						if ($flag_possivel_compensacao){
							echo " <b style='background-color:cornflowerblue; color: pink;'>Compensação no dia!</b>";
						}
						elseif ($hora_extra>=$pht){
							echo " <b style='background-color:cornflowerblue; color: pink;'>Compensação Banco de horas!</b>";
						}
					}
				}
			
				//Menos de 11 horas de descanso
				if ($flag_descanso){
					echo "<b style='color: red;'> (".round($hora_descanso,2).") Horas de descanso</b>";
				}
				//Se descanso é menor que HH correto
				if ($flag_possivel_inversao){
					echo "<b style='color: salmon;'> Possível Inversão de Horas</b>";
				}
				//Hora Noturna
				if ($flag_hora_noturna){
					echo " <b style='background-color: gray; color: white;'>(".round($hh_noturno_atual,2).") Horas Noturnas</b>";
				}
				//Virou o dia
				if ($flag_virou_dia){
					echo "<b style='color: brown;'> Virou o dia!</b>";
				}
				elseif ($flag_pht_virada AND $flag_entrada_prevista AND $flag_saida_prevista){
					echo "<b style='color: brown;'> Virou o dia!</b>";
				}
				//PHT Virou o dia
				if ($flag_pht_virada){
					echo "<b style='color: darkgreen;'> PHT Virou o dia!</b>";
				}
				//Possível troca de horario
				if ($flag_possivel_troca_horario){
					echo "<b style='color: red;'> Possível troca de horario!</b>";
				}
				//HH do dia anterior
	/* 			if ($flag_hh_dia_anterior){
					echo "<b style='color: darkgoldenrod;'> HH do dia anterior!</b>";
				} */
				//Trabalho Emergencial
				if ($flag_trabalho_emergencial){
					echo "<b style='color: brown;'> Batida em dia não programado!</b>";
				}
				//Entrada ou saídas Previstas ou não
				if ($flag_entrada_prevista AND $flag_saida_prevista AND $flag_entrada_prevista=='Entrada' AND $flag_saida_prevista=='Saída'){
					echo "<b style='color: green;'> Entrada e Saída Previstas!</b>";
				}
				elseif ($flag_entrada_prevista AND $flag_entrada_prevista=='Entrada'){
					echo "<b style='color: green;'> Entrada Prevista!</b>";
				}
				elseif ($flag_saida_prevista AND $flag_saida_prevista=='Saída'){
					echo "<b style='color: green;'> Saída Prevista!</b>";
				}
				elseif (!$flag_feriado AND !(date('w', strtotime($row['data'])) == 6)AND !(date('w', strtotime($row['data'])) == 0)){
					if (!$flag_ferias AND !$flag_falta AND !$flag_falta_batida){
						echo "<b style='color: darkviolet;'> Entrada e Saída Incorretas!</b>".$flag_falta;
					}
				}
				//Caso seja fim de semana
				if(date('w', strtotime($data_atual->format('Y-m-d'))) == 6) {
					echo "<b style='color: orange;'> Sábado </b><b style='background-color: yellow;color: blue;'>PHT Semanal: ".$pht_semanal."</b> ";
					$pht_semanal = 0;
				}
				elseif(date('w', strtotime($data_atual->format('Y-m-d'))) == 0) {
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
			//Média hh por dia trabalhado
			if ($total_dias>0){
				$media_hh = $hh_total / $total_dias;
			}
			else{
				$media_hh = 0;
			}
			//Imprime todas as informações totais da matrícula
			echo "<br><b>Dias trabalhados: ".$total_dias.
				" HH Total: ".round($hh_total,2).
				" Média HH/dia trabalhado: ".round($media_hh,2).
				" </b><br><b style='background-color: gray; color: white;'> HH Noturnos: ".round($hh_noturno,2).
				"<br></b><b style='color: cornflowerblue;'> Hora extra: ".round($hora_extra,2).
				" HH50: ".round($hh50,2)." HH75: ".round($hh75,2)." HH100: ".round($hh100,2).
				"<br></b><b style='color: red;'> Déficit de horas: ".round($hora_incompleta,2).
				"</b><br><b style='color: blue;'>PHT Mensal: ".round($pht_mensal,2);
			echo "</b><br><br><br>";
		}
	}
?>