<?php
if(Config::init()->isLogged()) header('Location: ' . Config::$_PAGE_URL . '');
if(isset($_POST['f_submit'])) {
	$q = Config::$g_con->prepare("SELECT * FROM `accounts` WHERE `Username` = ? AND `Password` = ? LIMIT 0,1");
	$q->execute(array($_POST['f_name'],hash('whirlpool',$_POST['f_password'])));
	if($q->rowCount() != 0) {
		$row = $q->fetch();
		$_SESSION['user'] = $row['Username'];
		header('Location: ' . Config::$_PAGE_URL . '');
	} else echo '<center><font color="red">Nume sau parola gresita.</font>';
}
if(isset($_POST['f_register'])) 
{
	header('Location: ' . Config::$_PAGE_URL . 'register');
}
?>
<form action="" method="post">
	<div style="text-align: left; margin-left:15px;">
		<b><font color="#D6D6D6">LOGIN PANEL</font></b>
	</div>
	<input style="margin:5px; width: 86%" type="text" name="f_name" placeholder="Nume"/><BR>
	<input style="margin:5px; width: 86%" type="password" name="f_password" placeholder="Parola"/><BR><BR>
	<button type="submit" name="f_submit">AUTENTIFICARE</a></button>
	<button type="submit" name="f_register">ÎNREGISTRARE</a></button>
</form>