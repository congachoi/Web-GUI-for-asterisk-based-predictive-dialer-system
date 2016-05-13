
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html>
<head>
	<title>Система автоматического внутреннего оповещения "Сирена"</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
<div align=center><h2>Настройки системы оповещения</h2></div>
<hr>
 <?php
 //Mysql
 $mysql = mysql_connect("localhost", "root", "vicidialnow") or die(mysql_error());
 mysql_select_db("asterisk") or die(mysql_error());
 
 //Обработка формы
 if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
	 
	 //Настройка транка
	 if(isset($_POST['dialout'])) {
	 mysql_query("update vicidial_server_carriers set registration_string = '".$_POST['dialout']."' where carrier_id = 'freepbx'") or die(mysql_error());
	}
//Количество исходящих линий
if(isset($_POST['campaign_outlines'])) {
mysql_query("update vicidial_remote_agents set number_of_lines = '".$_POST['campaign_outlines']."' where campaign_id = '92355983'") or die(mysql_error());
}
//Удаление пользователя
	if(isset($_POST['delete_user'])) {
		foreach ($_POST['delete_user'] as $user_del){

		exec("htpasswd -D /etc/htpasswd/sirena ".$user_del);
		}
	} 
//Добавление пользователя
	if(isset($_POST['username']) && isset($_POST['password'])) {
		exec ("htpasswd -b /etc/htpasswd/sirena ".$_POST['username']." ".$_POST['password']);
	}

//Изменение номера внутреннего оповещения
	if(isset($_POST['broadcast_number']) ) {
		mysql_query("update vicidial_list set phone_number = '".$_POST['broadcast_number']."' where list_id = '1001'") or die(mysql_error());
	}

} 
		 ?>
<h2>Список зарегистрированных пользователей</h2>		 
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">		 
<hr>
<table border cellpadding=3 style=width:100% algin=center>
	<th>№</th><th>Пользователь</th><th>Удалить</th>
<?php
//Вывод списка пользователей
$number = 1 ;
$file = fopen('/etc/htpasswd/sirena','r');
while ($line = fgets($file)) {
	$user=preg_split("/\:/", $line);
	Print "<tr>";
	print("<td>".$number."</td> ");
	print("<td>".$user[0]."</td> ");
	print('<td><input type="checkbox" name="delete_user['.$number.']" value="'.$user[0].'" /></td> ');
	Print "</tr>";
	$number++;
}
fclose($file);					
?>
</table>
<input type="submit" name="deleteuser" value="Удалить">
</form>
<h2>Добавление нового пользователя</h2>
 <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">		 
<hr>
	Имя:<input type="text" name="username" value="" maxlength="30" >
	Пароль:<input type="text" name="password" value="" maxlength="30" >
  <input type="submit" name="add_user" value="Добавить">
 </form> 
  
  <h2>Количество исходящих линий для обзвона</h2>
 <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">		 
<hr>
	<select name="campaign_outlines"> 
  <option value="1">1</option>
  <option value="3">3</option>
  <option value="5">5</option> 
  <option value="10">10</option>
  <option value="15">15</option>
  <option value="20">20</option>
  <option value="25">25</option>
  <option value="30">30</option>
   <?php 
 //Количество исходящих линий
 $data = mysql_query("select number_of_lines from vicidial_remote_agents where campaign_id = '92355983'") or die(mysql_error());
  $campaign_outlines = mysql_fetch_array( $data );

  Print "<option value=".$campaign_outlines['number_of_lines']." selected>".$campaign_outlines['number_of_lines']."</option>";
  
  ?>
</select>
   <input type="submit" name="lines_count" value="ОК">
 </form> 
  
  <h2>Настройка исходящего транка</h2>
  <hr>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">		
    <?php 
 //Настройка исходящего транка
   $data = mysql_query("select * from vicidial_server_carriers where carrier_id = 'freepbx'") or die(mysql_error());
   $dialout = mysql_fetch_array( $data );
   print "<input type=text name=dialout value='".$dialout['registration_string']."'size=64>";
   ?>
    <input type="submit" name="submit" value="ОК">
 </form>
 
  <h2>Номер телефона внутреннего оповещения</h2>
 <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">		 
<hr><?php
	$data = mysql_query("select phone_number from vicidial_list where list_id = '1001'") or die(mysql_error());
	$br_number = mysql_fetch_array( $data );
	print ('Номер:<input type="text" name="broadcast_number" value="'.$br_number['phone_number'].'" maxlength="20" >');
	mysql_close($mysql);
	?>
  <input type="submit" name="broadcast" value="Изменить">
 </form> 
  
<div class="boxads">Прототип системы оповещения.
 Версия 0.9<br> <b>Источники информации: </b><br>&#9679; Шаблоны CSS -<a href="http://www.free-css-templates.com">David Herreman </a> 
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
			<img src="images/arrow.gif" alt="" /> <a href="/" target="_blank">Autodialme</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="http://10.16.167.14" target="_blank">Freepbx</a> <br />
<img src="images/arrow.gif" alt="" /> <a href="/sirena/list.php" target="_blank">Статус вызовов</a> <br />
<img src="images/arrow.gif" alt="" /> <a href="/vicidial/admin_listloader_fourth_gen.php" target="_blank">Добавление списков</a> <br />
<img src="images/arrow.gif" alt="" /> <a href="/audiostore" target="_blank">Добавление файлов</a> <br />
			</div>
			</div>
		</div>
	</div>
	<br />&nbsp;<br />
	<div id="footer">Copyright &copy; 2016 US | Design: СЦС 
		 
</div>
	
	

</body>
</html>
