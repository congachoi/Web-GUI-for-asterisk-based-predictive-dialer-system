
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
<div align=center><h2>Абоненты системы оповещения</h2></div>
<hr>
 <?php
 //Mysql
 $mysql = mysql_connect("localhost", "root", "vicidialnow") or die(mysql_error());
 mysql_select_db("asterisk") or die(mysql_error());
 
 //Обработка формы
 if ($_SERVER["REQUEST_METHOD"] == "POST" ) {

$uploadOk = 1;


//Удаление списка
	if(isset($_POST['delete_list'])) {
		foreach ($_POST['delete_list'] as $list_del){
		mysql_query("DELETE FROM vicidial_lists WHERE list_id = '".$list_del."'") or die(mysql_error());
		mysql_query("DELETE FROM vicidial_list WHERE list_id = '".$list_del."'") or die(mysql_error());
		}
	} 

//Добавление списка
	if($_POST['list_name'] != "" ) {
		$list_id = rand(1050, 10000);
		mysql_query('insert into vicidial_lists values("'.$list_id.'","'.$_POST['list_name'].'","92355983","Y","'.$_POST['list_name'].'","'.date('Y-m-d h:i:s').'","","","","","","","","","","","","","","COUNTRY_AND_AREA_CODE","Y","2099-12-31")') 
	or die(mysql_error()); 
	}
	
// Проверка размера файла
if ($_FILES["leadfile"]["size"] > 2000000 ) {
    echo '<div id="warning">Файл слишком большой!</div>';
    $uploadOk = 0;
    
}

// Проверка типа файла
if (isset($_FILES["leadfile"]) && $_FILES["leadfile"]["type"] != "application/vnd.ms-excel" ) {
    echo '<div id="warning">Загрузи файл в формате XLS !</div>';
    $uploadOk = 0;
    
}

	
	if(isset($_FILES["leadfile"]) && $_POST['listtoadd'] != "" && $uploadOk == 1) {
		
		//Конвертация файла
		$new_filename = preg_replace("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", '.txt', $_FILES["leadfile"]["name"]);
		exec ('cp '.$_FILES["leadfile"]["tmp_name"].' /tmp/'.$_FILES["leadfile"]["name"]);
		exec('/var/www/html/vicidial/sheet2tab.pl /tmp/'.$_FILES["leadfile"]["name"].' /tmp/'.$new_filename);
		
		//Счетчики
		$dups_number=0;
		$total=0;
		$error=0;
		
		$file = fopen('/tmp/'.$new_filename,'r');
			while ($line = fgets($file)) {
				
				$total++;
				$leads = preg_split("/\\t/", $line);
					$phone_number = preg_replace('/\,/', '', $leads[0]);
					$alt_number = preg_replace('/\,/', '', $leads[2]);
					//Проверка дубката номера
					$sql = mysql_query("SELECT phone_number FROM vicidial_list WHERE phone_number='".$phone_number."' and list_id='".$_POST['listtoadd']."'");
					$dups = mysql_num_rows($sql);
					if ($dups) {
						$dups_number++;
						$notdup=false;
					} else {
						$notdup=true;
					}
					//Проверка номера
					if (preg_match('/^[0-9]{12}+$/', $phone_number) || preg_match('/^[0-9]{5}+$/', $phone_number) || preg_match('/^[0-9]{7}+$/', $phone_number)) {
						$isnumber=true;
					} else {
						$error++;
						$isnumber=false;
					}
					
					//Коммит строки в базу
					if ($notdup && $isnumber){
						//print $_POST['listtoadd']."  ". $phone_number ."  ". $alt_number ."  ". $leads[1] . $leads[3]. "<br>";
						mysql_query('insert into vicidial_list values("","","","","","","'.$_POST['listtoadd'].'","-5.00","N","1","'.$phone_number.'","","'.$leads[1].'","","","","","","","","","","","","","'.$alt_number.'","'.$leads[3].'","","","","0","","","","")') or die(mysql_error());
					}
			}
			unlink("/tmp/".$new_filename);
			unlink("/tmp/".$_FILES["leadfile"]["name"]);
			print ("<h2>Список ".$_FILES["leadfile"]["name"]." загружен</h2>");
			print ("Дубликатов: ".$dups_number." Ошибок: ".$error." Всего: ".$total);
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
 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">	 
<hr>
<input type="file" name="leadfile" id="leadfile">

Список:
<select name="listtoadd"> 
	<option value="">Выбор...</option>
	<?php
	
	$list = mysql_query("select list_id,list_name from vicidial_lists") or die(mysql_error());
	while($list_data = mysql_fetch_array( $list )){
	Print "<option value=".$list_data['list_id'].">".$list_data['list_name']."</option>";
}
	?>
	</select>

  <input type="submit" value="Добавить" name="Upload">
 </form> 


<div class="boxads">Прототип системы оповещения.
 Версия 1.0 beta<br> <b>Источники информации: </b><br>&#9679; Шаблоны CSS -<a href="http://www.free-css-templates.com">David Herreman </a> 
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
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/settings.php" target="_blank">Настройки системы</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/journal.php" target="_blank">Журнал доступа</a> <br />
			</div>
		</div>
	</div>
	<br />&nbsp;<br />
	<div id="footer">Copyright &copy; 2016 US | Design: СЦС 
		 
</div>
	
	

</body>
</html>
