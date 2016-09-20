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
<div align=center><h2>Запуск системы автоматического оповещения</h2></div>
<hr>
 <?php
 //Выход из системы	
if(isset($_POST['logout'])) {
    header('WWW-Authenticate: Basic realm="Authentication Required"');
    header('HTTP/1.0 401 Unauthorized');
    echo "<h2>Необходимо указать пользовательские данные</h2>";
 
    exit;
}
 //Mysql
 $mysql = mysql_connect("localhost", "root", "vicidialnow") or die(mysql_error());
 mysql_select_db("asterisk") or die(mysql_error());
  //Проверка занятости системы
	$sql_data2 = mysql_query("select status from vicidial_list where status = 'NEW'") 	or die(mysql_error());
	$status = mysql_fetch_array( $sql_data2 );	
	$gammu = exec("ps -A | grep  gammu");
	if(empty($status) && empty($gammu)) {
	Print '<h2>Статус оповещения: Отключено</h2>';
	} else {
		Print '<h2>Статус оповещения: Работа</h2>';
		$inwork = "true";
	}
//Обработка формы
 if ($_SERVER["REQUEST_METHOD"] == "POST" ) {

//Вывод ошибки при повторном запуске
if(isset($_POST['alarm_code']) && $_POST['list_code'] && isset($inwork)) {
	Print '<div id="warning">СИСТЕМА В РАБОТЕ!!! <br> ДЛЯ ПОВТОРНОГО ЗАПУСКА ПРОИЗВЕДИ ОСТАНОВКУ!!!</div>';
}
//Остановка системы
if (isset ($_POST['stop'])) {
	mysql_query("update vicidial_list set status = 'SP' where status !='PM'") or die(mysql_error());
	exec('killall -9 bash');
	exec('killall -9 gammu');
	print '<div id="warning">АВАРИЙНАЯ ОСТАНОВКА</div>';
}

//Обзвон абонентов	 
	if($_POST['dial'] == 'ON'  && empty($_POST['stop']) && $_POST['alarm_code'] != '' && empty($status)) {
	   
	mysql_query("update vicidial_list set status = 'NEW',called_since_last_reset = 'N',gmt_offset_now = '-5.00' where list_id ='". $_POST['list_code']."'") or die(mysql_error());
	//Перезвонить недоступных
	exec("bash /usr/bin/redial.sh ".$_POST['list_code']." >/dev/null 2>/dev/null &");
	}else { 
	$unchoose_count++;
		}
//Выбор кода оповещения		
	if(isset($_POST['alarm_code']) && $unchoose_count < '3' && empty($_POST['stop']) && empty($status)) {	     
	 
        mysql_query("update vicidial_campaigns set survey_first_audio_file = 'go_".$_POST['alarm_code']."' where campaign_id = '92355983'") or die(mysql_error());
      $sql_data = mysql_query("select * from alarm_codes where alarm_code = '".$_POST['alarm_code']."'") or die(mysql_error());
      $data = mysql_fetch_array( $sql_data );
      $header = $data['header'];
      $message = $data['message'];
   }
   

//Рассылка почты
 	if ($_POST['mail'] == 'ON' && $_POST['list_code'] != '' && $_POST['alarm_code'] != '' && empty($_POST['stop']) && empty($status)){
	$subject = '=?utf-8?b?'.base64_encode($header).'?=';
        $headers = 'From: callcenter@utg.gazprom.ru' . "\r\n" .
        'Content-Type: text/html; charset=UTF-8' .
        'X-Mailer: PHP/' . phpversion();
    $mail = mysql_query("select email from vicidial_list where list_id ='". $_POST['list_code']."'") or die(mysql_error());    
		while($email = mysql_fetch_array( $mail )) 
		{ 
			if($email['email'] != '' && empty($_POST['stop'])) {
			mail($email['email'], $subject, $message, $headers);
			} elseif (isset($_POST['stop'])){
			break;
			}
		} 
		 
	}else { 
	$unchoose_count++;
		}
//Рассылка sms
 	if($_POST['sms'] == 'ON' && $_POST['list_code'] != '' && $_POST['alarm_code'] != '' && empty($_POST['stop']) && empty($status)){
	$sql_data = mysql_query("select phone_number from vicidial_list where list_id ='". $_POST['list_code']."'") or die(mysql_error()) ;
	    $locale='ru_RU.UTF-8';
        setlocale(LC_ALL,$locale);
        putenv('LC_ALL='.$locale);
        $curr_date=exec('date -Im');
	    exec('echo "'.$message.'" > /tmp/message-'.$curr_date); 
	   
		while (  $phone_number = mysql_fetch_array( $sql_data ) ) 
		{ 
                if($phone_number['phone_number'] != '' && empty($_POST['stop']) && preg_match('/^[0-9]{12}+$/', $phone_number['phone_number'])) {
                $phone  = preg_split("/[\9,]+/", $phone_number['phone_number'] , 2);
              
                exec('echo "'.$phone[1].'" >> /tmp/phones-'.$curr_date); 
                 
                }
				
		}
		exec('bash /usr/bin/sendsms.sh '.$curr_date.' >/dev/null 2>/dev/null &'); 
	}else { 
	$unchoose_count++;
		}		
//Вывод ошибок
 if($unchoose_count == '3' && empty($_POST['stop']) && empty($status)){
  Print '<div id="warning">Выбери способ оповещения</div>';
  }
	if(empty($_POST['alarm_code']) && isset($_POST['dial']) && empty($_POST['stop'])) { 
	Print '<div id="warning">Выбери код оповещения</div>';
		}	
			if(empty($_POST['list_code']) && empty($_POST['stop'])) { 
		Print '<div id="warning">Выбери список абонентов</div>';
			}	
		
//Запись в журнал
if($unchoose_count < '3' && isset($_POST['alarm_code']) && isset($_POST['list_code']) && empty($_POST['stop'])){
	
	mysql_query('insert into alarm_journal values("'.date("Y-m-d 
H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_POST['dial'].'","'.$_POST['mail'].'","'.$_POST['sms'].'","'.$_POST['alarm_code'].'","'.$_POST['list_code'].'","'.$_SERVER['REMOTE_USER'].'")') or 
die(mysql_error());
}
}		


		 ?>

		 <hr>
		 <h2>Состояние узлов системы</h2>
	 <table border=0 cellpadding=4 algin=center > 

<TR>
<TD BGCOLOR="#7FFFF4"><h2>Телефонная линия:</h2></TD>

<?php
if(exec('sudo /usr/sbin/asterisk -x "sip show registry" | grep Registered')){
print "<TD BGCOLOR=#00FF07><h2>OK</h2></TD>";
} else {
print "<TD BGCOLOR=#FA0008><h2>Нет связи с АТС</h2></TD>";
}
?>

</TR>
<TR>
<TD BGCOLOR="#7FFFF4"><h2>Модем СМС:</h2></TD>

<?php
if(exec('ls /dev/ttyUSB0')){
print "<TD BGCOLOR=#00FF07><h2>OK</h2></TD>";	
} else {
print "<TD BGCOLOR=#FA0008><h2>Модем не подключен</h2></TD>";
}
?>

</TR>
<TR>
<TD BGCOLOR="#7FFFF4"><h2>Почтовый сервер:</h2></TD>

<?php
if(exec('ping -c 1 10.16.160.4')){
print "<TD BGCOLOR=#00FF07><h2>OK</h2></TD>";	
} else {
print "<TD BGCOLOR=#FA0008><h2>Нет связи с сервером<h2></TD>";
}
?>

</TR>	 
</table>		 
<hr>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return confirm('Вы уверены?');">		 
<p>
<h2>Укажи список абонентов:</h2>
<select name="list_code"> 
  <option value="">Выбор...</option>
 <?php 
 //Список абонентов
 $data = mysql_query("select list_id,list_name from vicidial_lists where list_id != '1001'") or die(mysql_error());
  while($list_data = mysql_fetch_array( $data )) 
 { 
  Print "<option value=".$list_data['list_id'].">".$list_data['list_name']."</option>";
}

  ?>
</select>
</p>
<hr>
<p>
<h2>Укажи код оповещения:</h2>
<select name="alarm_code">
	<option value="">Выбор...</option>
<?php 
 //Список файлов
 $sql_data = mysql_query("select * from alarm_codes") or die(mysql_error());
   while($alarm = mysql_fetch_array( $sql_data ))
 { 
	 
         Print "<option value=".$alarm['alarm_code'].">".$alarm['header']."</option>";
 }
 mysql_close($mysql);
  ?>
</select>
</p>
<hr>
	<h2>Укажи способы оповещения:</h2> 
  <p> 
  <table border=0 cellpadding=4 algin=center > 

<TR>
<TD BGCOLOR="#7FFFF4"><h2>Обзвон абонентов:</h2></TD>
<TD BGCOLOR="#FFFF00"> <input type="checkbox" name="dial" value="ON" /></TD>
</TR>
<TR>
<TD BGCOLOR="#7FFFF4"><h2>Рассылка писем:</h2></TD>
<TD BGCOLOR="#FFFF00"> <input type="checkbox" name="mail" value="ON" /></TD>
</TR>
<TR>
<TD BGCOLOR="#7FFFF4"><h2>Рассылка СМС:</h2></TD>
<TD BGCOLOR="#FFFF00"> <input type="checkbox" name="sms" value="ON" /></TD>
</TR>
 </table>
 <hr>

 <table border=0 cellpadding=4 algin=center > 
  <TR> 
	  <TD BGCOLOR="#FF0000"><input type="submit" name="submit" value="СТАРТ"></TD>
	  <TD BGCOLOR="#FF0000"><input type="submit" name="stop" value="СТОП"></TD>
	  <TD BGCOLOR="#FF0000"><input type="reset" value="СБРОС"></TD>

	  

  </TR>
  </table>
</form>
 
  
  
  
  
<div class="boxads">Прототип системы оповещения.
 Версия 1.1  <br> <b>Источники информации: </b><br>&#9679; Шаблоны CSS -<a href="http://www.free-css-templates.com">David Herreman </a> 
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
			
			
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/list.php" target="_blank">Протокол оповещения</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/alarm.php" target="_blank">Запуск оповещения</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/broadcast.php" target="_blank">Этажное оповещение</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/subscribers.php" target="_blank">Добавление абонентов</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/upload.php" target="_blank">Добавление файлов</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/settings.php" target="_blank">Настройки системы</a> <br />
			<img src="images/arrow.gif" alt="" /> <a href="/sirena/journal.php" target="_blank">Журнал доступа</a> <br />
			<input type="submit" name="logout" value="Выход">
			</div>
			</div>
		</div>
	</div>
	<br />&nbsp;<br />
	<div id="footer">Copyright &copy; 2016 US | Design: СЦС 
		 
</div>
	
	

</body>
</html>
