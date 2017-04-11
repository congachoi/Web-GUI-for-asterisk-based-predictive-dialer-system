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
 <script type="text/javascript">     
    function PrintDiv() {    
       var divToPrint = document.getElementById('divToPrint');
       var popupWin = window.open('', '_blank', 'width=300,height=300');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
            }
 </script>
<body>


<div class="content">
	<div id="toph"></div>
	<div id="header">
	
	</div>
	<div id="main">
		<div class="center">

<div align=center>	<h2>Протокол вызовов системы автоматического оповещения</h2></div>

<hr>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >

	<table border cellpadding=3 style=width:100% algin=center>
<th><h2>Укажи список абонентов:</h2></th>
<th><select name="list_code">
  <option value="">Выбор...</option>	
	
<?php 
$mysql = mysql_connect("localhost", "root", "vicidialnow") or die(mysql_error());
mysql_select_db("asterisk") or die(mysql_error());
  //Список абонентов
 $list = mysql_query("select list_id,list_name from vicidial_lists") or die(mysql_error());
  while($list_data = mysql_fetch_array( $list )) 
 { 
  if (isset($_POST['list_code']) &&
   $_POST['list_code'] == $list_data['list_id']) {
  Print "<option value=".$list_data['list_id']." selected>".$list_data['list_name']."</option>";
 } else {
	 Print "<option value=".$list_data['list_id'].">".$list_data['list_name']."</option>";
 }
}
print '</select></th><th><input type="submit" name="submit" value="Показать"></th></table>';

//Определение потока с данными
$size_fp = 1 * 1024 * 1024;
$fp = fopen("php://temp/maxmemory:$size_fp", 'r+');

 //Обработка и запись данных в память
 function print_table($fp){
 $sql_data = mysql_query("select phone_number,first_name,last_local_call_time,status from vicidial_list where list_id = '". $_POST['list_code']."' ") or die(mysql_error());
 $count=0; 
 $total=0;
 while($raw = mysql_fetch_array( $sql_data )){
	 $total++;
 if($raw['status'] == PM || $raw['status'] == PU){
	 $count++;
 }
}

	$sql_data = mysql_query("select phone_number,first_name,last_local_call_time,status from vicidial_list where list_id = '". $_POST['list_code']."' ") or die(mysql_error());
 fwrite($fp,'<div id="warning">Абонентов обработано: '.$count.' |  Абонентов всего: '.$total.'</div>');
 fwrite($fp,"<table border cellpadding=3 style=width:100% algin=center>");
 fwrite($fp,"<th>№</th><th>Номер телефона:</th><th>ФИО:</th><th>Время:</th> <th>Статус:</th> "); 
 $number = 1;
 while($info = mysql_fetch_array( $sql_data )) 
 { 
	 switch ($info['status'] ) {
		 case "PM":
			$status = "Сообщение прослушано";
			break;
		 case "PU":
			$status = "Сообщение недослушано";
			break;	
		 case "NA":
			$status = "Нет ответа";
			break;	
		 case "B":
			$status = "Абонент занят";
			break;
		 case "SP":
			$status = "Остановлено";
			break;	 
         case "ADD":
            $status = "Не обработан";
            break;
         case "AA":
            $status = "Автоответчик";
            break;
         case "NEW":
            $status = "В очереди";
            break;
         default:
			$status = "Неизвестно";
			break;
	 }
 fwrite($fp,"<tr>"); 
 fwrite($fp,"<td>".$number . "</td> "); 
 fwrite($fp,"<td>".$info['phone_number'] . "</td> "); 
 fwrite($fp,"<td>".$info['first_name'] . "</td> "); 
 fwrite($fp,"<td>".$info['last_local_call_time'] . "</td> "); 
 fwrite($fp,"<td>".$status. " </td></tr>"); 
 $number++; 
 } 
 fwrite($fp,'</table>'); 
 rewind($fp);
}
if($_POST['list_code'] != '' ){
	print '<div id="divToPrint">';
	print_table($fp);
	//Вывод содержимого потока
	echo stream_get_contents($fp);
	fclose($fp);
	//Кнопка печати
	print '</div><div>
  <input type="button" value="Печать таблицы" onclick="PrintDiv();" />
	</div>';
	print '<hr><input type="submit" name="send_mail" value="Отправить отчет ПДС">';
}
//Отправка отчета ПДС 
if($_POST['list_code'] != '' && isset($_POST['send_mail'])){
	//Определение потока с данными
	$size_fp = 1 * 1024 * 1024;
	$fp = fopen("php://temp/maxmemory:$size_fp", 'r+');
	//Заголовки письма
	$subject = '=?utf-8?b?'.base64_encode("Протокол вызовов системы автоматического оповещения от ".date("d-m-Y")).'?=';
        $headers = 'From: sirena@utg.gazprom.ru' . "\r\n" .
        'Content-Type: text/html; charset=UTF-8' .
        'X-Mailer: PHP/' . phpversion();
	$to = 'pds@utg.gazprom.ru';
	print_table($fp);
	$body = '
	<html>
    <head><meta http-equiv="content-type" content="text/html; charset=utf-8" /></head><body>
	'.stream_get_contents($fp).'</body>
	</html>        
	';
//Отправка письма
if (mail($to, $subject, $body, $headers)) {

	echo("<h1>Сообщение отправлено</h1>");
	} else {
	echo("<h1>Ошибка отправления</h1>");
	}
	fclose($fp);
}
 print "</form>";
 mysql_close($mysql);
  ?>

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
