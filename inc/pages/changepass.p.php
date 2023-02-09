<?php
if(!Config::init()->isLogged()) header('Location: ' . Config::$_PAGE_URL . '');
if(isset($_POST['pass_submit'])) {
	if(empty($_POST['current_pass']) || empty($_POST['new_pass']) || empty($_POST['repeat_new_pass'])) 
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Completeaza cu atentie toate randurile!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changepass');
		return;
	}
	if($_POST['new_pass'] !== $_POST['repeat_new_pass'])
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Rescrie cu atentie noua parola!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changepass');
		return;
	}
	if($_POST['current_pass'] == $_POST['repeat_new_pass'])
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Nu poti introduce aceeasi parola!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changepass');
		return;
	}
	$q = Config::$g_con->prepare("SELECT * FROM `accounts` WHERE `Username` = ? AND `Password` = ? LIMIT 0,1");
	$q->execute(array($_SESSION['user'],hash('whirlpool',$_POST['current_pass'])));
	if($q->rowCount() == 0) {
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Parola introdusa nu corespunde cu acest cont!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changepass');
		return;
	}
	$q = Config::$g_con->prepare("UPDATE `accounts` SET `Password` = ? WHERE `Username` = ? AND `Password` = ?");
	$q->execute(array(hash('whirlpool',$_POST['repeat_new_pass']),$_SESSION['user'],hash('whirlpool',$_POST['current_pass'])));
	
	/*$to = Config::init()->getPlayerData($_SESSION['user'],'email');
	$subject = '[HARD-RP]New password';
	$message = 'De curand s-a efectuat o schimbare a parolei contului, acesta este un mesaj de informare.
Noua parola este: '.$_POST['repeat_new_pass'].'

Nu divulgati nimanui datele de conectare la server! Nu raspundem de eventualele furturi datorita neglijentei dvs.
O zi buna!';
	$headers = 'From: server@hard-roleplay.com' . "\r\n" .
		'Reply-To: server@hard-roleplay.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message, $headers);*/
	echo '<div class="alert alert-success"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Parola a fost schimbata cu succes! Un email a fost trimis catre adresa dvs!</div>';
}
?>
<form action="" method="post">
	<div style="text-align: left; margin-left:15px;">
		<b><font color="#D6D6D6">SCHIMBA PAROLA CONTULUI</font></b>
	</div><BR>
	<?php 
	echo '<i class="fa fa-edit green">&nbsp;&nbsp;</i>Completeaza cu atentie aceste date!'; 
	?>
	<BR>
	<input style="margin:5px; width: 70%" type="password" name="current_pass" placeholder="Parola curenta"/><BR>
	<input style="margin:5px; width: 70%" type="password" name="new_pass" placeholder="Noua parola"/><BR>
	<input style="margin:5px; width: 70%" type="password" name="repeat_new_pass" placeholder="Repeta nou parola"/><BR><BR>
	<button type="submit" name="pass_submit">FINALIZEAZA SCHIMBAREA</a></button>
</form>