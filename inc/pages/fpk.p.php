<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #454646;border-collapse: collapse;}
.tftable th {font-size:12px;background-color: rgba(36, 36, 36, 1);;border-width: 1px;padding: 8px;border-style: solid;border-color: #454646;text-align:left;width: 20%;}
.tftable tr {background-color:#333333;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #454646;}
</style>
<?php
if(!Config::init()->isLogged()) header('Location: ' . Config::$_PAGE_URL . '');
if(isset($_POST['dismiss'])) {
	echo '<div class="alert alert-success"><i class="fa fa-check-square">&nbsp;&nbsp;</i>Acest membru a fost exclus cu succes din echipa ta!</div>';
	$q = Config::$g_con->prepare("UPDATE `characters` SET `Member` = -1, `Rank` = 0 WHERE `id` = ?");
	$q->execute(array($_POST['dismiss']));
}
$count = 0;
$q = Config::$g_con->prepare("SELECT * FROM `characters` WHERE `accountid` = ?");
$q->execute(array(Config::init()->getPlayerData($_SESSION['user'],'id')));
while($row = $q->fetch()) 
{
	if($row['Member'] == 1 && $row['Rank'] >= 15 || $row['Member'] == 4 && $row['Rank'] >= 6 || $row['Member'] == 6 && $row['Rank'] >= 6)
	{
		echo '<table class="tftable" border="1" style="width: 90%; color: #D1D1D1;">';
		echo '<tr><th><center><font color="#fff" size="2px">Leader <b>Panel</b> for '. $row['username']. '</font></center></th></tr>';
		echo '</table>';
		echo '<table class="tftable" border="1" style="width: 90%; color: #D1D1D1;">';		
		echo '<tr>
			<th><center><font color="#fff">Member name</font></center></th>
			<th><center><font color="#fff">Rank</font></center></th>
			<th><center><font color="#fff">Last login</font></center></th>
			<th><center><font color="#fff">Dismiss</font></center></th>
		</tr>';
		$n = Config::$g_con->prepare("SELECT * FROM `characters` WHERE `Member` = ? AND `Rank` > 0 ORDER BY Rank DESC");
		$n->execute(array($row['Member']));
		while($name = $n->fetch()) {
			//if($name['accountid'] !== Config::init()->getPlayerData($_SESSION['user'],'id')) In cazul in care vrei ca liderul si conturile sale sa nu apara.
			//{
				echo '<tr><td><center>'. $name['username'] .'</center></td>
				<td><center>'. $name['Rank'] .'<center></td>
				<td><center>'. Config::init()->getPlayerDataID($name['accountid'],'lastlogin') .'<center></td>
				<td><center><form action="" method="post"><button style="margin-top: 6px; width: 50%" type="submit" name="dismiss" value="'. $name['id'] .'"><font color="#fff"><i class="fa fa-times"></i></font></button></form></a>
				</tr>';
			//}
		}
		echo '</tr></thead>';
		echo '</table><br>';
		$count++;
	}
}
if($count == 0) header('Location: ' . Config::$_PAGE_URL . '');
?>