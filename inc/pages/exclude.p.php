<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #454646;border-collapse: collapse;}
.tftable th {font-size:12px;background-color: rgba(36, 36, 36, 1);;border-width: 1px;padding: 8px;border-style: solid;border-color: #454646;text-align:left;width: 20%;}
.tftable tr {background-color:#333333;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #454646;}
</style>
<?php
if(!Config::init()->isLogged()) header('Location: ' . Config::$_PAGE_URL . '');
if(Config::init()->getPlayerData($_SESSION['user'],'Admin') < 3) header('Location: ' . Config::$_PAGE_URL . '');

if(isset($_POST['exclude_a'])) {
	echo '<div class="alert alert-success"><i class="fa fa-check-square">&nbsp;&nbsp;</i>Adminul a fost demis cu succes.</div>';
	$q = Config::$g_con->prepare("UPDATE `characters` SET `Admin` = 0 WHERE `ID` = ?");
	$q->execute(array($_POST['exclude_a']));
	return;
}
if(isset($_POST['exclude_l'])) {
	echo '<div class="alert alert-success"><i class="fa fa-check-square">&nbsp;&nbsp;</i>Liderul a fost demis cu succes.</div>';
	$q = Config::$g_con->prepare("UPDATE `characters` SET `Faction` = -1, `FactionRank` = 0 WHERE `ID` = ?");
	$q->execute(array($_POST['exclude_l']));
	return;
}

echo '<table class="tftable" border="1" style="width: 90%; color: #D1D1D1;">';
echo '<thead><tr style="color: #DEDEDE;">
		<td><center><font color="#fff" size="2px">Nume</font></center></td>
		<td><center><font color="#fff" size="2px">Admin Level</font></center></td>
		<td><center><font color="#fff" size="2px">Last Login</font></center></td>
		<td><center><font color="#fff" size="2px">Exclude</font></center></td>
	  </tr></thead>';
$q = Config::$g_con->prepare("SELECT * FROM `characters` WHERE `Admin` > 0");
$q->execute();
while($row = $q->fetch()) {
	echo '<tr>';
	echo '<td><center>'.$row['Character'].'</center></td>';
	echo '<td><center>'.$row['Admin'].'</center></td>';
	$timestamp=$row['LastLogin'];
	echo '<td><center>'.gmdate("Y-m-d H:i:s", $timestamp).'</center></td>';
	if(Config::init()->getPlayerData($_SESSION['user'],'Admin') > 2)
	{
		echo '<td><center>
		<form action="" method="post">
			<button style="width: 50%; margin-top:6px;" type="submit" name="exclude_a" value="'.$row['ID'].'">
				<font color="#fff">
					<i class="fa fa-times"></i>
				</font>
			</button>
		</form>
		</center></td>';
	}
	echo '</tr>';
}
echo '</table><hr>';

echo '<table class="tftable" border="1" style="width: 90%; color: #D1D1D1;">';
echo '<thead><tr style="color: #DEDEDE;">
		<td><center><font color="#fff" size="2px">Nume</font></center></td>
		<td><center><font color="#fff" size="2px">Faction Leader</font></center></td>
		<td><center><font color="#fff" size="2px">Last Login</font></center></td>
		<td><center><font color="#fff" size="2px">Exclude</font></center></td>
	  </tr></thead>';
$q = Config::$g_con->prepare("SELECT * FROM `characters`");
$q->execute();
while($row = $q->fetch()) {
	if($row['Faction'] == 1 && $row['FactionRank'] >= 11 || $row['Faction'] == 2 && $row['FactionRank'] >= 8 || $row['Faction'] == 3 && $row['FactionRank'] >= 15 || $row['Faction'] == 4 && $row['FactionRank'] >= 9)
	{
		$faction = $row['Faction']-1;
		
		echo '<tr>';
		echo '<td><center>'.$row['Character'].'</center></td>';
		echo '<td><center>'.$faction.'</center></td>';
		$timestamp=$row['LastLogin'];
		echo '<td><center>'.gmdate("Y-m-d H:i:s", $timestamp).'</center></td>';
		if(Config::init()->getPlayerData($_SESSION['user'],'Admin') > 2)
		{
			echo '<td><center>
			<form action="" method="post">
				<button style="width: 50%; margin-top:6px;" type="submit" name="exclude_l" value="'.$row['ID'].'">
					<font color="#fff">
						<i class="fa fa-times"></i>
					</font>
				</button>
			</form>
			</center></td>';
		}
		echo '</tr>';
	}
}
echo '</table><hr>';
?>