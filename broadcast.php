
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html>
<head>
	<title>Система автоматического оповещения "Сирена"</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="RU" />
	<meta http-equiv="imagetoolbar" content="no" />
	<link type="image/x-icon" href="/sirena/images/favicon.ico" rel="icon"/>
	<meta name="MSSmartTagsPreventParsing" content="true" />
	<meta name="description" content="LGBlue Free Css Template" />
	<meta name="keywords" content="free,css,template,business" />
	<style type="text/css" media="all">@import "images/style.css";</style>
	
</head>

<body>


<div class="content">
	<div id="toph"></div>
	<div id="header">
	
	</div>
	<div id="main">
		<div class="center">
<div align=center><h2>Запуск системы широковещательного оповещения</h2></div>
<hr>
 <?php
 //Mysql
 $mysql = mysql_connect("localhost", "root", "vicidialnow") or die(mysql_error());
 mysql_select_db("asterisk") or die(mysql_error());
 
 //Обработка формы
 if ($_SERVER["REQUEST_METHOD"] == "POST" ) {

//Остановка системы
if (isset ($_POST['stop'])) {
	mysql_query("update vicidial_list set status = 'SP' where status !='PU' and list_id ='1001'") or die(mysql_error());
	print '<div id="warning">АВАРИЙНАЯ ОСТАНОВКА</div>';
}

//Обзвон абонентов	 
	if(isset($_POST['start']) && empty($_POST['stop']) ) {
	mysql_query("update vicidial_list set status = 'NEW',called_since_last_reset = 'N',gmt_offset_now = '-5.00' where list_id ='1001'") or die(mysql_error());
	}
//Выбор кода оповещения		
	if(isset($_POST['alarm_code']) && empty($_POST['stop'])) {	     
		
        mysql_query("update vicidial_campaigns set survey_first_audio_file = 'go_".$_POST['alarm_code']."' where campaign_id = '76873962'") or die(mysql_error());
     
   }

}		
 //Статус системы
	$sql_data2 = mysql_query("select status from vicidial_list where status = 'NEW' and list_id ='1001'") 	or die(mysql_error());
	$status = mysql_fetch_array( $sql_data2 );
	if(empty($status)) {
	Print '<h2>Статус внутреннего оповещения: Отключено</h2>';
	} else {
		Print '<h2>Статус внутреннего оповещения: Работа</h2>';
	}
//Запись в журнал	
if(isset($_POST['alarm_code']) && empty($_POST['stop'])){
	
	mysql_query('insert into alarm_journal values("'.date("Y-m-d 
	H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","","","","'.$_POST['alarm_code'].'","HALL","'.$_SERVER['REMOTE_USER'].'")') or 
	die(mysql_error());
}
	 
		 ?>
		 
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return confirm('Вы уверены?');">		 
<hr>
<p>
<h2>Укажи код оповещения:</h2>
<select name="alarm_code">
	<option value="">Выбор...</option>
<?php 
 //Список файлов
 
$sql_data = mysql_query("select * from alarm_codes where broadcast = 'TRUE'") or die(mysql_error());
   while($alarm = mysql_fetch_array( $sql_data ))
 { 
	 
         Print "<option value=".$alarm['alarm_code'].">".$alarm['header']."</option>";
 }
 mysql_close($mysql);
  ?>
</select>
</p>
					
 <hr>
 <table border=0 cellpadding=4 algin=center > 
  <TR> 
	  <TD BGCOLOR="#FF0000"><input type="submit" name="start" value="СТАРТ"></TD>
	  <TD BGCOLOR="#FF0000"><input type="submit" name="stop" value="СТОП"></TD>
	  <TD BGCOLOR="#FF0000"><input type="reset" value="СБРОС"></TD>
  </TR>
  </table>
</form>
 
  
  
  
  
<div class="boxads">Система широковещательного оповещения.
 Версия 1.1 <br> <b>Источники информации: </b><br>&#9679; Шаблоны CSS -<a href="http://www.free-css-templates.com">David Herreman </a> 
<br><b>Среда разработки: </b><br>&#9679; Geany.<br> 2016г. ,СЦС. <a href="mailto:samohin-iv@utg.gazprom.ru">Самохин И.В.</a></div>
			</div>
		<div class="leftmenu">
		
			<div class="padding">
	
<img src="images/top_logo.jpg" alt="Газпром трансгаз Саратов"/>
			<br />
			<hr />

			<h2>Ссылки</h2>
			<div class="links">
			<img src="images/arrow.gif" alt="" /> <a href="http://ts.utg.gazprom.ru/telsprav.aspx" target="_blank">Телефонный справочник ООО "Газпром трансгаз Саратов"</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="http://www.utg.gazprom.ru/newUTG/default.aspx" target="_blank">Официальный сайт ООО "Газпром трансгаз Саратов"</a> <br />
			<br>
			
			<img src="images/arrow.gif" alt="" /> <a href="http://10.16.167.14" target="_blank">Freepbx</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/list.php" target="_blank">Протокол оповещения</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/alarm.php" target="_blank">Запуск оповещения</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/broadcast.php" target="_blank">Этажное оповещение</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/subscribers.php" target="_blank">Добавление абонентов</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/upload.php" target="_blank">Добавление файлов</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/admin/settings.php" target="_blank">Настройки системы</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/journal.php" target="_blank">Журнал доступа</a> <br />
			</div>
			</div>
		</div>
	</div>
	<br />&nbsp;<br />
	<div id="footer">Copyright &copy; 2016 US | Design: СЦС 
		 
</div>
	
	

</body>
</html>
