
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html>
<head>
	<title>Система автоматического оповещения</title>
	<meta http-equiv="content-type" content="text/html; charset=windows-1251" />
	<meta http-equiv="Content-Language" content="RU" />
	<meta http-equiv="imagetoolbar" content="no" />
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

<br>

	<div align=center>	<h2>Запуск системы автоматического оповещения</h2></div><br>
		

<?php 
 
 mysql_connect("your.hostaddress.com", "username", "password") or die(mysql_error()); 
 mysql_select_db("Database_Name") or die(mysql_error()); 
 $data = mysql_query("SELECT * FROM friends WHERE pet='Cat'") 
 or die(mysql_error()); 
 Print "<table border cellpadding=3>"; 
 while($info = mysql_fetch_array( $data )) 
 { 
 Print "<tr>"; 
 Print "<th>Name:</th> <td>".$info['name'] . "</td> "; 
 Print "<th>Color:</th> <td>".$info['fav_color'] . "</td> "; 
 Print "<th>Food:</th> <td>".$info['fav_food'] . "</td> "; 
 Print "<th>Pet:</th> <td>".$info['pet'] . " </td></tr>"; 
 } 
 Print "</table>"; 
 ?> 
<div class="boxads">Прототип системы оповещения.
 Версия 0.3<br> <b>Источники информации: </b><br>&#9679; Шаблоны CSS -<a href="http://www.free-css-templates.com">David Herreman </a> 
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



			</div>
			</div>
		</div>
	</div>
	<br />&nbsp;<br />
	<div id="footer">Copyright &copy; 2015 US | Design: СЦС 
		 
</div>
	
	

</body>
</html>
