<?php
if(!Config::init()->isLogged()) header('Location: ' . Config::$_PAGE_URL);
if(!isset(Config::$_url[1])) header('Location:' . Config::$_PAGE_URL);
if(Config::init()->getPlayerCharData(Config::$_url[1],'accountid') !== Config::init()->getPlayerData($_SESSION['user'],'id')) header('Location:' . Config::$_PAGE_URL);
if(isset($_POST['skin_submit'])) {
	if(empty($_POST['skin_select']))
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Completeaza cu atentie id-ul skinului!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changeskin/'.Config::$_url[1].'');
		return;
	}
	if(!is_numeric($_POST['skin_select']))
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>ID-ul skinului are o valoare incorecta! (doar cifre)</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changeskin/'.Config::$_url[1].'');
		return;
	}
	if($_POST['skin_select'] > 299)
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>ID-ul skinului poate fi maxim 299!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changeskin/'.Config::$_url[1].'');
		return;
	}
	if($_POST['skin_select'] < 0)
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>ID-ul skinului poate fi format doar din valori pozitive!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'changeskin/'.Config::$_url[1].'');
		return;
	}
	$q = Config::$g_con->prepare("UPDATE `characters` SET `skin` = ? WHERE `username` = ? AND `accountid` = ?");
	$q->execute(array($_POST['skin_select'],Config::$_url[1],Config::init()->getPlayerData($_SESSION['user'],'id')));
	echo '<div class="alert alert-success"><i class="fa fa-check-circle-o">&nbsp;&nbsp;</i>Skinul caracterului a fost schimbat cu succes!</div>';
}
?>
<form action="" method="post">
	<div style="text-align: left; margin-left:15px;">
		<b><font color="#D6D6D6">SCHIMBA SKIN-UL CARACTERULUI <?php echo Config::$_url[1]; ?></font></b>
	</div><BR>
	<?php 
	echo '<font color="red"><i class="fa fa-info-circle">&nbsp;&nbsp;</i>Pentru a vedea lista skin-urilor utilizeaza link-ul <a href="http://wiki.sa-mp.com/wiki/Skins:All">acesta</a>!</font>'; 
	?>
	<BR>
	<input style="margin:5px; width: 70%" type="text" name="skin_select" placeholder="Introdu noul ID."/><BR><BR>
	<button type="submit" name="skin_submit">FINALIZEAZA SCHIMBAREA</a></button>
</form>