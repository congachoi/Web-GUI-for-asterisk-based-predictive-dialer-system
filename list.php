
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html>
<head>
	<title>Система автоматического оповещения "Сирена"</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="RU" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="MSSmartTagsPreventParsing" content="true" />
	<meta name="description" content="LGBlue Free Css Template" />
	<meta name="keywords" content="free,css,template,business" />
	<META HTTP-EQUIV="refresh" CONTENT="15">
	<style type="text/css" media="all">@import "images/style.css";</style>
	
</head>

<body>


<div class="content">
	<div id="toph"></div>
	<div id="header">
	
	</div>
	<div id="main">
		<div class="center">

<br>

	<div align=center>	<h2>Статус вызовов в системе автоматического оповещения</h2></div><br>
		
<hr>
<?php 
$mysql = mysql_connect("localhost", "root", "vicidialnow") or die(mysql_error());
mysql_select_db("asterisk") or die(mysql_error());
 if(isset($_POST['list_code'])) {
 
 $data = mysql_query("select phone_number,first_name,last_local_call_time,status from vicidial_list where list_id = '". $_POST['list_code']."' AND status != 'NEW'") or die(mysql_error()); 
 Print "<table border cellpadding=3 style=width:100% algin=center>";
 Print "<th>Номер:</th><th>Имя:</th><th>Время:</th> <th>Статус:</th> "; 
 while($info = mysql_fetch_array( $data )) 
 { 
 Print "<tr>"; 
 Print "<td>".$info['phone_number'] . "</td> "; 
 Print "<td>".$info['first_name'] . "</td> "; 
 Print "<td>".$info['last_local_call_time'] . "</td> "; 
 Print "<td>".$info['status'] . " </td></tr>"; 
 } 
 Print "</table>"; 
}
 ?> <hr>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">	
	<p>
<h2>Укажи список абонентов:</h2>
<select name="list_code"> 
  <option value="">Выбор...</option>
 <?php 
 //Список абонентов
 $list = mysql_query("select list_id,list_name from vicidial_lists") or die(mysql_error());
  while($list_data = mysql_fetch_array( $list )) 
 { 
  Print "<option value=".$list_data['list_id'].">".$list_data['list_name']."</option>";
}
 mysql_close($mysql);
  ?>
</select>
</p>
<hr>
<div align=center><input type="submit" name="submit" value="Показать"></div>
</form>
<div class="boxads">Прототип системы оповещения.
 Версия 0.5.2<br> <b>Источники информации: </b><br>&#9679; Шаблоны CSS -<a href="http://www.free-css-templates.com">David Herreman </a> 
<br><b>Среда разработки: </b><br>&#9679; Geany.<br> 
2015г. ,СЦС. <a href="mailto:@utg.gazprom.ru"></a></div>
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
			<img src="images/arrow.gif" alt="" /> <a href="http://10.16.101.132" target="_blank">Autodialme</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="http://10.16.167.14" target="_blank">Freepbx</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/alarm.php" target="_blank">Запуск системы</a> <br />



			</div>
			</div>
		</div>
	</div>
	<br />&nbsp;<br />
	<div id="footer">Copyright &copy; 2015 US | Design: СЦС 
		 
</div>
	
	

</body>
</html>

