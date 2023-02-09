<?php
if(!Config::init()->isLogged()) header('Location: ' . Config::$_PAGE_URL . '');
if(isset(Config::$_url[1])) 
{
	if($_SESSION['new_email_url'] == Config::$_url[1])
	{
		$q = Config::$g_con->prepare("UPDATE `accounts` SET `email` = ? WHERE `username` = ?");
		$q->execute(array($_SESSION['new_email'],$_SESSION['user']));
		echo '<div class="alert alert-success"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Adresa de email a fost schimbata cu succes! Email: <b>'.$_SESSION['new_email'].'</b></div>';
		unset($_SESSION['new_email']);
		unset($_SESSION['new_email_url']);
	} else header('Location: ' . Config::$_PAGE_URL . 'changemail');
	return;
}
if(isset($_POST['e_submit'])) {
	if(empty($_POST['e_curent']) || empty($_POST['e_nou']) || empty($_POST['e_repeat_nou'])) 
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Completeaza cu atentie toate randurile!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changemail');
		return;
	}
	if(!filter_var($_POST['e_curent'], FILTER_VALIDATE_EMAIL) && !empty($_POST['e_curent'])) 
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>#1 Emailul pe care l-ai introdus nu este valid!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changemail');
		return;
	}
	if(!filter_var($_POST['e_nou'], FILTER_VALIDATE_EMAIL) && !empty($_POST['e_nou'])) 
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>#2 Emailul pe care l-ai introdus nu este valid!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changemail');
		return;
	}
	if(!filter_var($_POST['e_repeat_nou'], FILTER_VALIDATE_EMAIL) && !empty($_POST['e_repeat_nou'])) 
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>#3 Emailul pe care l-ai introdus nu este valid!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changemail');
		return;
	}
	if(!empty($_POST['e_curent']) && filter_var($_POST['e_curent'], FILTER_VALIDATE_EMAIL))
	{
		$q = Config::$g_con->prepare("SELECT * FROM `accounts` WHERE `email` = ? AND `username` = ? LIMIT 0,1");
		$q->execute(array($_POST['e_curent'],$_SESSION['user']));
		if($q->rowCount() == 0) {
			echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Emailul pe care l-ai introdus nu corespunde cu contul dvs!</div>';
			header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changemail');
			return;
		}
	}
	if($_POST['e_nou'] !== $_POST['e_repeat_nou'])
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Rescrie cu atentie noua adresa!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changemail');
		return;
	}
	if($_POST['e_curent'] == $_POST['e_nou'])
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Nu poti introduce aceeasi adresa!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changemail');
		return;
	}
	$q = Config::$g_con->prepare("SELECT * FROM `accounts` WHERE `email` = ? LIMIT 0,1");
	$q->execute(array($_POST['e_repeat_nou']));
	if($q->rowCount() != 0) {
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Emailul pe care l-ai introdus, este folosit deja de un alt cont!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changemail');
		return;
	}
	$characters = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
	$_SESSION['new_email'] = $_POST['e_repeat_nou'];
	$_SESSION['new_email_url'] = $characters;
	$to = $_POST['e_curent'];
	$subject = '[HARD-RP]Registration Announcement';
	$message = 'Pentru a valida noua adresa de email: '.$_POST['e_repeat_nou'].' , utilizeaza link-ul generat mai jos:
'.Config::$_PAGE_URL.'changemail/'.$characters.'

Nu divulgati nimanui datele de conectare la server! Nu raspundem de eventualele furturi datorita neglijentei dvs.
O zi buna!';
	$headers = 'From: server@hard-roleplay.com' . "\r\n" .
		'Reply-To: server@hard-roleplay.com' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message, $headers);
	echo '<div class="alert alert-success"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Pentru a confirma schimbarea, acceseaza linkul trimis catre adresa curenta!</div>';
}
?>
<form action="" method="post">
	<div style="text-align: left; margin-left:15px;">
		<b><font color="#D6D6D6">SCHIMBA EMAIL-UL CONTULUI</font></b>
	</div><BR>
	<?php 
	echo '<i class="fa fa-edit green">&nbsp;&nbsp;</i>Completeaza cu atentie aceste date! Email curent: '; 
	echo Config::init()->getPlayerData($_SESSION['user'],'email');
	?>
	<BR>
	<input style="margin:5px; width: 70%" type="text" name="e_curent" placeholder="Email curent"/><BR>
	<input style="margin:5px; width: 70%" type="text" name="e_nou" placeholder="Email nou"/><BR>
	<input style="margin:5px; width: 70%" type="text" name="e_repeat_nou" placeholder="Repeta email"/><BR><BR>
	<button type="submit" name="e_submit">FINALIZEAZA SCHIMBAREA</a></button>
</form>