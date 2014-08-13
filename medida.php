<?php
 
// Este script recebe os dados do Arduino na forma de um GET com
// os valores da corrente passados como parâmetros na URL e os
// armazena no banco de dados.
//
// A URL terá a seguinte forma:
// http://localhost/medida.php?irms=10&potencia=100
 
// conectar ao banco de dados
// altere os parâmetros de acordo com seu ambiente
$link = mysql_connect('localhost', 'root1', '');
 
// selecione sua base de dados
// altere os parâmetros de acordo com seu ambiente
@mysql_select_db('energy') or die( "Unable to select database");
 
// prepara os dados que vamos enviar
$horario = date('Y-m-d H:i:s');
$corrente = $_GET['irms'];
$potencia = $_GET['potencia'];
 
// grava os dados no banco
$query = "INSERT INTO medidas VALUES('$horario','$corrente','$potencia')";
mysql_query($query);
mysql_close();
 
?>
