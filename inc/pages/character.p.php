<?php
if(!isset(Config::$_url[1])) header('Location:' . Config::$_PAGE_URL);
if(!Config::init()->isLogged()) header('Location:' . Config::$_PAGE_URL);
$q = Config::$g_con->prepare('SELECT * FROM `characters` WHERE `Character` = ?');
$q->execute(array(Config::$_url[1]));
if($q->rowCount() == 0) header('Location:' . Config::$_PAGE_URL);

if(isset($_POST['skin_submit'])) {
	if($_SESSION['user'] !== Config::init()->getPlayerData($_SESSION['user'],'Username')) header('Location:' . Config::$_PAGE_URL);
	if(empty($_POST['skin_select']))
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Completeaza cu atentie id-ul skinului!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'character/'.Config::$_url[1].'');
		return;
	}
	if(!is_numeric($_POST['skin_select']))
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>ID-ul skinului are o valoare incorecta! (doar cifre)</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'character/'.Config::$_url[1].'');
		return;
	}
	if($_POST['skin_select'] > 299)
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>ID-ul skinului poate fi maxim 299!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'character/'.Config::$_url[1].'');
		return;
	}
	if($_POST['skin_select'] < 0)
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>ID-ul skinului poate fi format doar din valori pozitive!</div>';
		header('Refresh: 2; URL=' . Config::$_PAGE_URL . 'character/'.Config::$_url[1].'');
		return;
	}
	$q = Config::$g_con->prepare("UPDATE `characters` SET `Skin` = ? WHERE `Character` = ? AND `Username` = ?");
	$q->execute(array($_POST['skin_select'],Config::$_url[1],$_SESSION['user']));
	echo '<div class="alert alert-success"><i class="fa fa-check-circle-o">&nbsp;&nbsp;</i>Skinul caracterului a fost schimbat cu succes!</div>';
}
?>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #454646;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#333333;border-width: 1px;padding: 8px;border-style: solid;border-color: #454646;text-align:left;width: 50%;}
.tftable tr {background-color:#333333;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #454646;}
.tftable tr:hover {background-color:#444444;}
</style>

<table class="tftable" border="1" style="width: 60%; color: #D1D1D1;">
<tr><th>Numele caracterului</th><th><?php echo Config::$_url[1]; ?></th></tr>
<tr><td>Bani in mana</td><td><?php echo number_format(Config::init()->getPlayerCharData(Config::$_url[1],'Money'),0,'.','.'); ?>$</td></tr>
<tr><td>Cont bancar</td><td><?php echo number_format(Config::init()->getPlayerCharData(Config::$_url[1],'BankMoney'),0,'.','.'); ?>$</td></tr>
<tr><td>Timp conectat</td><td><?php echo Config::init()->getPlayerCharData(Config::$_url[1],'PlayingHours'); ?></td></tr>
<tr><td>Sex</td><td><?php echo (Config::init()->getPlayerCharData(Config::$_url[1],'Gender') != 0 ? "Masculin" : "Feminin"); ?></td></tr>
<tr><td>Factiune</td><td><?php echo Config::$faction[Config::init()->getPlayerCharData(Config::$_url[1],'Faction')]; ?></td></tr>
<tr><td>Job</td><td><?php echo Config::$job[Config::init()->getPlayerCharData(Config::$_url[1],'Job')]; ?></td></tr>
</table>
<br>
<table class="tftable" border="1" style="width: 60%; color: #D1D1D1;">
<tr><th>Skin Avatar</th><th><center><img src="<?php echo Config::$_PAGE_URL; ?>images/avatars/<?php echo Config::init()->getPlayerCharData(Config::$_url[1],'Skin'); ?>.png"></center></th></tr>
<form action="" method="post">
<tr>
	<?php 
	if(Config::init()->getPlayerCharData(Config::$_url[1],'Username') == $_SESSION['user'])
	{
	?>
	<th><?php echo '<input style="margin:5px;" type="text" name="skin_select" placeholder="Introdu noul ID."/><br><center><button type="submit" name="skin_submit">Modifica</a></button></center>'; ?></th>
	<th><?php echo '<font color="red"><i class="fa fa-info-circle">&nbsp;&nbsp;</i>Pentru a vedea lista skin-urilor utilizeaza link-ul <a href="http://wiki.sa-mp.com/wiki/Skins:All">acesta</a>!</font>'; ?></th>
	<?php
	}
	?>
</tr>
</form>
</table>
<hr>
<table class="tftable" border="1" style="width: 60%; color: #D1D1D1;">
<?php
$q = Config::$g_con->prepare("SELECT * FROM `cars` WHERE `carOwner` = ?");
$q->execute(array(Config::init()->getPlayerCharData(Config::$_url[1],'ID')));
$count = 0;
while($row = $q->fetch()) 
{
	$count++;
	echo '<table class="tftable" border="1" style="width: 60%; color: #D1D1D1;"><br><tr><th><center>Vehicle Number #'.$count.'</center></th></tr></table>';
	echo '<table class="tftable" border="1" style="width: 60%; color: #D1D1D1;"><tr><th><center><img style="border:1px solid #000; width:150px;" src="'.Config::$_PAGE_URL.'images/cars/'.$row['carModel'].'.jpg"></center></th></tr></table>';
	echo '<table class="tftable" border="1" style="width: 60%; color: #D1D1D1;">';
	echo '<tr><td><center>Culori masina</center></td><td><center>
	<span style="display: inline-block;width:15px;height:15px;background-color:'.Config::$vehColors[$row['carColor1']].'"></span>
	<span style="display: inline-block;width:15px;height:15px;background-color:'.Config::$vehColors[$row['carColor2']].'"></span>
	</center></td></tr>';
}	
if($count == 0) echo '<th><center><font color="red">Nu detii masini personale!</font></center></th>';
?>
</table>
<hr>
<?php
$q = Config::$g_con->prepare("SELECT * FROM `houses` WHERE `houseOwner` = ?");
$q->execute(array(Config::init()->getPlayerCharData(Config::$_url[1],'ID')));
$count = 0;
while($row = $q->fetch()) 
{
	$count++;
	echo '<table class="tftable" border="1" style="width: 60%; color: #D1D1D1;"><br><tr><th><center>House Number #'.$count.'</center></th></tr></table>';
	echo '<table class="tftable" border="1" style="width: 60%; color: #D1D1D1;">';
	echo '<tr><td><center>Adresa casei</center></td><td><center>'.$row['houseAddress'].'</center></td></tr>';
	echo '<tr><td><center>Valoarea casei</center></td><td><center>'. number_format($row['housePrice'],0,'.','.') .'$</center></td></tr>';
}	
if($count == 0) echo '<table class="tftable" border="1" style="width: 60%; color: #D1D1D1;"><tr><th><center><font color="red">Nu detii o locuinta!</font></center></th></tr></table>';
?>
</table>