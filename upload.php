
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
	<style type="text/css" media="all">@import "images/style.css";</style>
	
</head>

<body>


<div class="content">
	<div id="toph"></div>
	<div id="header">
	
	</div>
	<div id="main">
		<div class="center">

<div align=center>	<h2>Список оповещения</h2></div>

<hr>
<?php 
$mysql = mysql_connect("localhost", "root", "vicidialnow") or die(mysql_error());
mysql_select_db("asterisk") or die(mysql_error());

 if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
 // Инициализация переменных
 $target_dir = "/var/lib/asterisk/sounds";
 $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
 $uploadOk = 1;
 $FileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Проверка расширения
	if($FileType != "wav"  ) {
    echo '<div id="warning">Загрузи файл в формате WAV!</div>';
    $uploadOk = 0;
}
// Проверка дубликата 
if (file_exists($target_file)) {
    echo '<div id="warning">Такой файл уже существует!</div>';
    $uploadOk = 0;
} 
// Проверка размера файла
if ($_FILES["fileToUpload"]["size"] > 2000000) {
    echo '<div id="warning">Файл слишком большой!</div>';
    $uploadOk = 0;
}
// Загрузка файла
if ($uploadOk == 0) {
    echo '<div id="warning">Ошибка. Файл не добавлен!</div>';
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "Файл ". basename( $_FILES["fileToUpload"]["name"]). " был добавлен.";
        // Коммит в базу
        mysql_query('insert into alarm_codes values("'.$_FILES["fileToUpload"]["name"].'","'.$_POST['header'].'","'.$_POST['message'].'")') or die(mysql_error());  
    } else {
        echo '<div id="warning">Ошибка. Файл не добавлен!</div>';
    }

 }
 //Удаление из базы
 if(isset($_POST['delete_code'])) {
	 mysql_query("DELETE FROM alarm_codes WHERE alarm_code = '".$_POST['delete_code']."'");
 }
}

 $sql_data = mysql_query("select * from alarm_codes") or die(mysql_error());
 
  $number = 1;
Print "<table border cellpadding=3 style=width:100% algin=center>";
Print "<th>№</th><th>Файл</th><th>Заголовок сообщения</th><th>Текст сообщения</th><th>Удалить</th>";
 while($alarm = mysql_fetch_array( $sql_data )) 
 { 
	 $name=preg_split("/[\.wav,]+/", $alarm['alarm_code']);
	  Print "<tr>";
	   Print "<td>".$number."</td> ";
	   Print "<td>".$name[0] . " </td> ";
	    Print "<td>".$alarm['header']."</td> ";
	    Print "<td>".$alarm['message']."</td> ";
	     Print '<td><input type="checkbox" name="delete_code" value="'.$alarm['alarm_code'].'" /></td></tr>'; 
         $number++;
 }
 Print "</table>"; 
mysql_close($mysql);
 ?><hr>
 <div align=center>	<h2>Загрузка файлов в систему автоматического оповещения</h2></div>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <h2>Выбери файл для загрузки:</h2>
    <input type="file" name="fileToUpload" id="fileToUpload">    
<h2>Параметры файла: 8кгц моно wav</h2>
  <h2>Настройки текстового оповещения</h2>
<hr>
	<h2>Введи тему сообщения:</h2>
<input type="text" name="header" value=""  size="64">
<h2>Введи текст сообщения:</h2>
<textarea name="message" rows="5" cols="64"></textarea>

<input type="submit" value="Upload" name="Отправить">
</form>
  <hr>

<div class="boxads">Прототип системы оповещения.
 Версия 0.5.3<br> <b>Источники информации: </b><br>&#9679; Шаблоны CSS -<a href="http://www.free-css-templates.com">David Herreman </a> 
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
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/alarm.php" target="_blank">Запуск системы</a> <br />



			</div>
			</div>
		</div>
	</div>
	<br />&nbsp;<br />
	<div id="footer">Copyright &copy; 2015 US | Design: СЦС 
		 
</div>
	
	

</body>
</html>

