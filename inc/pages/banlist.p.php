<?php
if(isset($_POST['unban'])) {
	if(!Config::init()->isLogged()) header('Location: ' . Config::$_PAGE_URL . '');
	if(Config::init()->getPlayerData($_SESSION['user'],'Admin') > 2)
	{
		echo '<div class="alert alert-success"><i class="fa fa-check-square">&nbsp;&nbsp;</i>Banul a fost scos cu succes!</div>';
		$q = Config::$g_con->prepare("DELETE FROM `blacklist` WHERE id = ?");
		$q->execute(array($_POST['unban']));
	} 
	else
	{
		header('Location: ' . Config::$_PAGE_URL . '');
	}
}
echo '<table class="table table-bordered table-striped table-hover">';
echo '<thead><tr style="color: #DEDEDE;">
		<td><center>Nume</center></td>
		<td><center>Admin</center></td>
		<td><center>Motiv</center></td>
		<td><center>Data banarii</center></td>';
		if(Config::init()->getPlayerData($_SESSION['user'],'Admin') > 2)
		{
			echo '<td><center>Unban</center></td>';
		}
	  echo '</tr></thead>';
$q = Config::$g_con->prepare("SELECT * FROM `blacklist`" . Config::init()->_pagLimit());
$q->execute();
while($row = $q->fetch()) {
	echo '<tr>';
	echo '<td><center>'.$row['Username'].'</center></td>';
	echo '<td><center>'.$row['BannedBy'].'</center></td>';
	echo '<td><center>'.$row['Reason'].'</center></td>';
	echo '<td><center>'.$row['Date'].'</center></td>';
	if(Config::init()->getPlayerData($_SESSION['user'],'Admin') > 2)
	{
		echo '<td><center>
		<form action="" method="post">
			<button style="width: 50%;" type="submit" name="unban" value="'.$row['id'].'">
				<font color="#fff">
					<i class="fa fa-times"></i>
				</font>
			</button>
		</form>
		</center></td>';
	}
	echo '</tr>';
}
echo '</table>';
echo Config::init()->_pagLinks(Config::init()->rows('blacklist'));
?>