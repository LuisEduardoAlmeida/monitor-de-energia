
<html>    // Página PHP 
<head>
	Engenheiro Lucas Macedo - UniSant´Anna
	 <?php
//echo "<meta HTTP-EQUIV='refresh' CONTENT='5;URL=http://localhost/energy'>"; // Essa rotina atualiza a página a cada 2 segundos
//?> 
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Medidor de Consumo de Energia</title>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
	  google.load("visualization", "1.0", {packages:["corechart"]});
	  google.setOnLoadCallback(drawChart);
	  function drawChart() {
	    var data = new google.visualization.DataTable();
	    data.addColumn('string', 'Horario');
	    data.addColumn('number', 'Potencia');
	    data.addRows([
				<?php
 
					//error_reporting(E_ALL);
					//ini_set('display_errors', 'On');
 
					$link = mysql_connect('localhost', 'root1', ''); // configure host, usuario e senha de acordo com seu ambiente MySQL
					@mysql_select_db('energy') or die( "Unable to select database");
					$yday = date('Y-m-d h:i:s', strtotime("-1 day"));
					$query = "SELECT * FROM medidas WHERE horario > '$yday'";
					$result = mysql_query($query);
					$row = mysql_fetch_array($result);
					if ($row) {
						$continue = true;
					} else {
						$continue = false;
					}
					while ($continue) {	
				    $horario=$row['horario'];
				    $potencia=$row['potencia'];
				    echo("['$horario',$potencia]");
						$row = mysql_fetch_array($result);
						if ($row) {
							$continue = true;
							echo(",\n");
						} else {
							$continue = false;
							echo("\n");
						}
					}
				?>	    	
	    ]);
 
	    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
	    chart.draw(data, {width: 1000, height: 400, title: 'Consumo de Energia',
	                      hAxis: {title: 'Horario', titleTextStyle: {color: '#FF0000'}}
	                     });
	  }
	</script>
</head>
<body>
	<h1>Medidor de Consumo de Energia</h1>
 
	<h2>Consumo das Últimas 24 horas</h2>
 
	<?php
		//error_reporting(E_ALL);
		ini_set('display_errors', 'On');
 
		$link = mysql_connect('localhost', 'root1', '');
		@mysql_select_db('energy') or die( "Unable to select database");
		$yday = date('Y-m-d h:i:s', strtotime("-1 day"));
		$query = "SELECT * FROM medidas WHERE horario > '$yday'";
		$result = mysql_query($query);
		
		$consumo = 0.0;
		$horario_anterior = '';
		$potencia_anteior = 0;
		$horario_atual = '';
		$potencia_atual =0;
		$var_horario = '';
 
		while ($row = mysql_fetch_array($result)) {	
	    $horario_atual=$row['horario'];
	    $potencia_atual=$row['potencia'];
	    echo("$horario_atual ==> ");
	    echo(strtotime($horario_atual));
 
			if ($horario_anterior <> '') {
	    	$var_horario = strtotime($horario_atual) - strtotime($horario_anterior);
	    	$consumo_atual = $var_horario * ($potencia_atual + $potencia_anterior) / 2;
	    	$consumo = $consumo + $consumo_atual;
	    	echo(".");
	    }
	    	$horario_anterior=$horario_atual;
	    	$potencia_anterior=$potencia_atual;
				echo("$var_horario <br>\n");
		}
        
	?>
 
	O consumo total foi de <strong><?php echo($consumo); ?> Joules</strong> ou <strong><?php echo($consumo / 3600000); ?> KWh</strong>. <br>
 
	<div id="chart_div"></div>
	
	<h2>Últimas Medidas</h2>
 
	<?php
	// Neste bloco vamos conectar ao banco de dados e
	// ler os dados da medições enviadas pelo Arudino.
 
	// conectar ao banco de dados
	// altere os parâmetros de acordo com seu ambiente
	$link = mysql_connect('localhost', 'root1', '');
 
	// selecione sua base de dados
	// altere os parâmetros de acordo com seu ambiente
	@mysql_select_db('energy') or die( "Unable to select database");
 
	// realiza a query qye conta o total de medidas na tabela de medidas
	$query="SELECT count(*) as total FROM medidas";
	$result=mysql_query($query);
	$data=mysql_fetch_assoc($result);
	$total_de_medidas = $data['total'];
 
	// realiza a query que lê as 20 últimas medidas databela de medidas
	$query="SELECT * FROM medidas ORDER BY horario DESC LIMIT 20";
	$result=mysql_query($query);
	$num=mysql_numrows($result);
	mysql_close();
?>
 
 
	Mostrando as 20 últimas medidas de um total de <strong><?php echo($total_de_medidas) ?> medidas</strong>. <br><br>
 
	<table border="1" cellspacing="2" cellpadding="2">
	<tr>
		<td><strong>Horário</strong></td>
		<td><strong>Corrente (A)</strong></td>
		<td><strong>Potência (W)</strong></td>
	</tr>
 
	<?php
		$i=0;
		while (($i < $num) AND ($i < 20)) {
			$horario=mysql_result($result,$i,"horario");
			$corrente=mysql_result($result,$i,"corrente");
			$potencia=mysql_result($result,$i,"potencia");
	?>
 
	<tr>
		<td align="right"><?php echo $horario; ?></td>
		<td align="right"><?php echo $corrente; ?></td>
		<td align="right"><?php echo $potencia; ?></td>
	</tr>
 
	<?php
			$i++;
		}
 
		// mysql_close();
	?>
 
	</table>
 
 
 
</body>
</html>
