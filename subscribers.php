
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

//Удаление списка
	if(isset($_POST['delete_list'])) {
		foreach ($_POST['delete_list'] as $list_del){
		mysql_query("DELETE FROM vicidial_lists WHERE list_id = '".$list_del."'") or die(mysql_error());
		mysql_query("DELETE FROM vicidial_list WHERE list_id = '".$list_del."'") or die(mysql_error());
		}
	} 

//Добавление списка
	if(isset($_POST['list_name']) ) {
		$list_id = rand(1050, 10000);
		mysql_query('insert into vicidial_lists values("'.$list_id.'","'.$_POST['list_name'].'","92355983","Y")') 
	or die(mysql_error()); 
	}
	
	
	if(isset($_POST['listtoadd']) ) {
include 'vicidial/admin_listloader_fourth_gen.php';
}
}
		 ?>
<h2>Списки абонентов</h2>		 
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">		 
<hr>
<table border cellpadding=3 style=width:100% algin=center>
	<th>№</th><th>Название</th><th>ID</th><th>Количество абонентов</th><th>Удалить</th>
<?php
//Вывод перечня списков
$number = 1 ;
$list = mysql_query("select list_id,list_name from vicidial_lists") or die(mysql_error());
  while($list_data = mysql_fetch_array( $list )){
	$data = mysql_query("select COUNT(*) from vicidial_list where list_id = '".$list_data['list_id']."'") or die(mysql_error());
	$count = mysql_fetch_array( $data );
	Print "<tr>";
	print("<td>".$number."</td> ");
	print("<td>".$list_data['list_name']."</td> ");
	print("<td>".$list_data['list_id']."</td> ");
	print("<td>".$count['COUNT(*)']."</td> ");
	print('<td><input type="checkbox" name="delete_list['.$number.']" value="'.$list_data['list_id'].'" /></td> ');
	Print "</tr>";
	$number++;
}
					
?>

</table>
<input type="submit" name="deletelist" value="Удалить">
</form>

<h2>Добавление нового списка</h2>
 <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">		 
<hr>
	Имя:<input type="text" name="list_name" value="" maxlength="30" >
  <input type="submit" name="add_list" value="Добавить">
 </form> 
 <hr>
 
 <h2>Добавление абонентов в список</h2>
 <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">		 
<hr>
<select name="listtoadd"> 
	<option value="">Выбор...</option>
	<?php
	
	$list = mysql_query("select list_id,list_name from vicidial_lists") or die(mysql_error());
	while($list_data = mysql_fetch_array( $list )){
	Print "<option value=".$list_data['list_id'].">".$list_data['list_name']."</option>";
}
	?>
	</select>
  <input type="submit" name="add_file" value="Добавить">
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
