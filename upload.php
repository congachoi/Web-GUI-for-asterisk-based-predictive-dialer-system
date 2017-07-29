
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

<div align=center>	<h2>Список файлов оповещения</h2></div>

<hr>
<?php 
$mysql = mysql_connect("localhost", "root", "vicidialnow") or die(mysql_error());
mysql_select_db("asterisk") or die(mysql_error());

 if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
 // Инициализация переменных
 $target_dir = "/var/www/html/sounds/";
 $target_name = preg_split("/\./", $_FILES["fileToUpload"]["name"], -1, PREG_SPLIT_NO_EMPTY);
 $target_file = $target_dir ."go_".$target_name[0].".wav";
 $uploadOk = 1;


// Проверка дубликата 
if (file_exists($target_file) && empty($_POST['delete_code'])) {
    echo '<div id="warning">Такой файл уже существует!</div>';
    $uploadOk = 0;
} 
// Проверка размера файла
if ($_FILES["fileToUpload"]["size"] > 2000000 && empty($_POST['delete_code'])) {
    echo '<div id="warning">Файл слишком большой!</div>';
    $uploadOk = 0;
}
// Проверка имени файла
$cor_name = preg_replace("/[^A-Z0-9._-]/i", "", $_FILES["fileToUpload"]["name"]);
if ($cor_name[0] == "." && empty($_POST['delete_code'])) {
    echo '<div id="warning">Ошибка наименования файла!</div>';
    $uploadOk = 0;
}
// Загрузка файла
if ($uploadOk == 0 && empty($_POST['delete_code'])) {
    echo '<div id="warning">Ошибка. Файл не добавлен!</div>';
} elseif (empty($_POST['delete_code'])) {
	//Конвертация файла в совместимый формат

	exec("ffmpeg -i ".$_FILES["fileToUpload"]["tmp_name"]." -ac 1 -ar 8000 ".$target_file);
    if (file_exists($target_file)) {
        echo "<h2>Файл ".$target_name[0]. " был добавлен.</h2>";
        // Коммит в базу
        mysql_query('insert into alarm_codes values("'.$target_name[0].'","'.$_POST['header'].'","'.$_POST['message'].'","'.$_POST['broadcast'].'")') or die(mysql_error());  
    } else {
        echo '<div id="warning">Ошибка при конвертации файла!</div>';
    }

 }
 //Удаление из базы
 if(isset($_POST['delete_code'])) {
	 
	 foreach ($_POST['delete_code'] as $db_code){
	  $file_path = $target_dir."go_".$db_code.".wav";

	  if (file_exists($file_path)) {
         unlink($file_path);
         mysql_query("DELETE FROM alarm_codes WHERE alarm_code = '".$db_code."'");
      }
   }
 }
}
//Таблица с файлами на сервере
 $sql_data = mysql_query("select * from alarm_codes") or die(mysql_error());
 
  $number = 1;
  print '<form method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]) .'" onsubmit="';
        print "return confirm('Вы уверены?');";
                print '">';
Print "<table border cellpadding=3 style=width:100% algin=center>";
Print "<th>№</th><th>Файл</th><th>Тема сообщения</th><th>Текст сообщения</th><th>Шир. вещ.</th><th>Удалить</th>";
 while($alarm = mysql_fetch_array( $sql_data )) 
 { 
	 
	  Print "<tr>";
	   Print "<td>".$number."</td> ";
	   Print "<td><a href=/sounds/go_".$alarm['alarm_code'].".wav>".$alarm['alarm_code']."</a> </td> ";
	    Print "<td>".$alarm['header']."</td> ";
	    Print "<td>".$alarm['message']."</td> ";
	    
if ($alarm['broadcast'] == "TRUE"){
	$broadcast="ДА";
}else{
	$broadcast="НЕТ";
}
	    Print "<td>".$broadcast."</td> ";
	     Print '<td><input type="checkbox" name="delete_code['.$number.']" value="'.$alarm['alarm_code'].'" /></td></tr>'; 
         $number++;
 }
 Print '</th></table><input type="submit" name="submit" value="Удалить"></form>'; 
mysql_close($mysql);
 ?><hr>
 <div align=center>	<h2>Загрузка файлов в систему автоматического оповещения</h2></div><br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <h2>Выбери файл для загрузки:</h2>
    <input type="file" name="fileToUpload" id="fileToUpload">    
<h3>Буквы кирилического алфавита использовать ЗАПРЕЩЕНО.
<br>Примеры названия файла: 
<br>GO_test.mp3
<br>CH-GO-01-01-16.wav
<br>Точка "." в названии файла - зарезервированный символ
<br>Максимальный размер файла: 2Мб
</h3>
<hr><br>
  <h2>Настройки кода оповещения</h2>
<hr>
	<h2>Тема сообщения:</h2>
<input type="text" name="header" value="" maxlength="60" size="64">
<h2>Текст сообщения:</h2>
<textarea name="message" rows="5" maxlength="400" cols="64"></textarea>
<h3>Максимальное количество символов: 400</h3>
<h2>Широковещательное сообщение:</h2>
  <input type="radio" name="broadcast" value="TRUE" > ДА<br>
  <input type="radio" name="broadcast" value="FALSE" checked> НЕТ<br>
 <hr> 
 <table border=0 cellpadding=4 algin=center > 
  <TR> 
	  <TD BGCOLOR="#FF0000"><input type="submit" value="ОТПРАВИТЬ" name="Upload"></TD>
	  <TD BGCOLOR="#FF0000"><input type="reset" value="СБРОС"></TD>
  </TR>
  </table>
</form>
  <hr>

<div class="boxads">Cистема оповещения.
 Версия 1.1 <br> <b>Источники информации: </b><br>&#9679; Шаблоны CSS -<a href="http://www.free-css-templates.com">David Herreman </a> 
<br><b>Среда разработки: </b><br>&#9679; Geany.<br> 
2016г. ,СЦС. <a href="mailto:samohin-iv@utg.gazprom.ru">Самохин И.В.</a></div>
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

