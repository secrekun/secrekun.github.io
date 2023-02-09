<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #454646;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#333333;border-width: 1px;padding: 8px;border-style: solid;border-color: #454646;text-align:left;}
.tftable tr {background-color:#333333;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #454646;}
.tftable tr:hover {background-color:#444444;}
</style>
<?php
if(!Config::init()->isLogged()) 
{
	header('Location: ' . Config::$_PAGE_URL . '');
}
if(Config::getPlayerData($_SESSION['user'],'Admin') < 1) 
{
	header('Location: ' . Config::$_PAGE_URL . '');
	return;
}
if(isset($_POST['accept'])) 
{
	$q = Config::$g_con->prepare('SELECT * FROM `ucp_accounts` WHERE `id` = ?');
	$q->execute(array(Config::$_url[1]));
	while($row = $q->fetch(PDO::FETCH_OBJ)) 
	{
		$q = Config::$g_con->prepare('INSERT INTO `accounts` (`Username`,`Password`,`RegisterDate`, `Email`) VALUES (?, ?, ?, ?)');
		$q->execute(array($row->username,hash('whirlpool', $row->password),date('Y-m-d / H:m:s'),$row->email));
		echo '<div class="alert alert-success"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Contul a fost validat cu succes! Acesta se poate loga acum.</div>';

		$to      = $row->email;
		$subject = '[HARD-RP]Registration Announcement';
$message = 'In urma unei analizari a cererii dvs. de a va inregistra pe serverul SERVER.HARD-ROLEPLAY.COM, acesta a fost ACCEPTAT!

Datele de conectare a serverului:
Nickname: '.$row->username.'
Password: '.$row->password.'
Email: '.$row->email.'
Data efectuarii inregistrarii: '.$row->date.'

Nu divulgati nimanui datele de conectare la server! Nu raspundem de eventualele furturi datorita neglijentei dvs.
O zi buna!
';
		$headers = 'From: server@hard-roleplay.com' . "\r\n" .
			'Reply-To: server@hard-roleplay.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
		
		$q = Config::$g_con->prepare('DELETE FROM `ucp_accounts` WHERE `id` = ? ');
		$q->execute(array(Config::$_url[1]));
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'acp');
	}
}
if(isset($_POST['decline'])) 
{
	if(empty($_POST['reason']))
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Completeaza cu atentie motivul refuzului!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'acp/'.Config::$_url[1].'');
		return;
	}
	$q = Config::$g_con->prepare('SELECT * FROM `ucp_accounts` WHERE `id` = ?');
	$q->execute(array(Config::$_url[1]));
	while($row = $q->fetch(PDO::FETCH_OBJ)) 
	{
		echo '<div class="alert alert-info"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Contul a fost sters din lista conturilor aflate in curs de validare!</div>';

		$to      = $row->email;
		$subject = '[HARD-RP]Registration Announcement';
$message = 'In urma unei analizari a cererii dvs. de a va inregistra pe serverul SERVER.HARD-ROLEPLAY.COM, acesta a fost RESPINS!
Motivul fiind: '.$_POST['reason'].'

Informatii suplimentare:
Nickname: '.$row->username.'
Data efectuarii inregistrarii: '.$row->date.'

O zi buna!
';
		$headers = 'From: server@hard-roleplay.com' . "\r\n" .
			'Reply-To: server@hard-roleplay.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
		
		$q = Config::$g_con->prepare('DELETE FROM `ucp_accounts` WHERE `id` = ? ');
		$q->execute(array(Config::$_url[1]));
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'acp');
	}
}

if(isset(Config::$_url[1])) 
{
	$q = Config::$g_con->prepare('SELECT * FROM `ucp_accounts` WHERE `id` = ?');
	$q->execute(array(Config::$_url[1]));
	if($q->rowCount() == 0) header('Location: ' . Config::$_PAGE_URL . 'acp');
	while($row = $q->fetch(PDO::FETCH_OBJ)) {
		echo '<div style="text-align: left; margin-left: 12px;"><font size=4px; color="orange" >Decizie cont <b>'.$row->username.'</font></b></div><br><br>';
		echo '<table class="tftable" border="1" style="width: 90%; color: #D1D1D1; word-wrap: break-word;">
			<tr><th>Numele contului</th><th>'.$row->username.'</th></tr>
			<tr><th>Email-ul contului</th><th>'.$row->email.'</th></tr>
			<tr><th>Definitie RolePlay</th><th><p class="lighter line-height-125" style="word-break:break-all;">'.$row->roleplay.'</p></th></tr>
			<tr><th>Definitie PowerGaming</th><th><p class="lighter line-height-125" style="word-break:break-all;">'.$row->powergaming.'</p></th></tr>
			<tr><th>Definitie MetaGaming</th><th><p class="lighter line-height-125" style="word-break:break-all;">'.$row->metagaming.'</p></th></tr>
			<tr><th>Definitie RevengeKill</th><th><p class="lighter line-height-125" style="word-break:break-all;">'.$row->revengekill.'</p></th></tr>
		';
	}
	echo '</table><br><br>';
	echo '<form action="" method="post">
		<font color="red"><i class="fa fa-info-circle">&nbsp;&nbsp;</i>Acest field se va completa in cazul in care contul urmeaza sa fie refuzat!<br>
		<input style="margin:5px; width: 30%" type="text" name="reason" placeholder="Motivul refuzului"/><BR><BR>
		<button type="submit" name="decline"><font color="red">REFUZA</font></a></button> 
		<button type="submit" name="accept"><font color="green">ACCEPTA</font></a></button>
	</form>';
	return;
}
?>
<table id="login-table" class="table table-striped table-hover table-bordered">
<tbody>
<tr class="success">
	<td><font color="#fff">Username</font></td>
	<td><font color="#fff">Email</font></td>
	<td><font color="#fff">Registration Date</font></td>
	<td><font color="#fff">Actions</font></td>
</tr>
<?php
$q = Config::$g_con->prepare('SELECT * FROM `ucp_accounts` ORDER BY `id` DESC ');
$q->execute();
while($row = $q->fetch(PDO::FETCH_OBJ)) {
	echo
	'<tr>
		<td>'.$row->username.'</td>
		<td>'.$row->email.'</td>
		<td>'.$row->date.'</td>
		<td><a href="'.Config::$_PAGE_URL.'acp/'.$row->id.'"><i class="fa fa-share-square-o fa-lg" style="margin-top: 5px;"></i></i></a></td>
	</tr>';
}
?>
</tbody>
</table>