<?php
if(!Config::init()->isLogged())
{
	echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Pentru a trimite o raportare a unui bug, trebuie sa fiti logati!</div>';
	return;
}
if(isset($_POST['submit'])) {
	if(!$_POST['description']) echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Pentru a trimite o raportare a unui bug, trebuie sa completati spatiul!</div>';
	else { 
		echo '<div class="alert alert-success"><i class="fa fa-check-square-o">&nbsp;&nbsp;</i>Cererea dvs. a fost stocata cu succes in baza noastra de date!</div>';

		$q = Config::$g_con->prepare('INSERT INTO `ucp_tickets` (`Name`, `Ticket`, `Date`) VALUES (?, ?, ?)');
		$q->execute(array($_SESSION['user'],$_POST['description'],date('Y/m/d H:i:s')));
	}
}
if(isset($_POST['b_lock'])) {
	$q = Config::$g_con->prepare('UPDATE `ucp_tickets` SET `Status` = 1 WHERE `id` = ?');
	$q->execute(array($_POST['b_lock']));
	echo '<div class="alert alert-success"><i class="fa fa-check-square-o">&nbsp;&nbsp;</i>Topicul a fost <strong>inchis</strong>!</div>';
}
if(isset($_POST['b_unlock'])) {
	$q = Config::$g_con->prepare('UPDATE `ucp_tickets` SET `Status` = 0 WHERE `id` = ?');
	$q->execute(array($_POST['b_unlock']));
	echo '<div class="alert alert-success"><i class="fa fa-check-square-o">&nbsp;&nbsp;</i>Topicul a fost <strong>re-deschis</strong>!</div>';
}

?>
<?php 
if(Config::init()->getPlayerData($_SESSION['user'],'Admin') > 0)
{
	echo '<table class="table table-bordered table-striped table-hover">';
	echo '<thead><tr style="color: #DEDEDE;">
			<td><center>Player</center></td>
			<td><center>Ticket</center></td>
			<td><center>Status</center></td>
			<td><center>Data raportarii</center></td>
			<td><center>Actiune</center></td>
		  </tr></thead>';
	$q = Config::$g_con->prepare("SELECT * FROM `ucp_tickets` ORDER BY `id` DESC");
	$q->execute();
	while($row = $q->fetch()) {
		$ticket_text = (strlen(strip_tags($row['Ticket'])) > 10) ? substr($row['Ticket'], 0, 10) . '...' : $row['Ticket'];
		echo '<tr>';
		echo '<td><center>'.$row['Name'].'</center></td>';
		echo '<td><center>'. $ticket_text .'</center></td>';
		echo '<td><center>'.($row['Status'] != 0 ? "<font color='red'>Inchis</font>" : "<font color='green'>Deschis</font>").'</center></td>';
		echo '<td><center>'.$row['Date'].'</center></td>';
		echo '<td><center><form action="" method="post"><a href="' . Config::$_PAGE_URL . 'seebug/'.$row['id'].'"><i class="fa fa-eye"></i></a>';
			if($row['Status'] == 0)
				echo ' <button type="submit" name="b_lock" style="width: 38%" value="'. $row['id'] .'"><i class="fa fa-lock" style="color: red"></i></button>';
			else
				echo ' <button type="submit" name="b_unlock" style="width: 40%" value="'. $row['id'] .'"><i class="fa fa-unlock" style="color: green"></i></button>';
		echo '</center></td></form>';
		echo '</tr>';
	}
	echo '</table>';
	echo Config::init()->_pagLinks(Config::init()->rows('bans'));
	echo '<table class="table table-bordered table-striped" style="width:250px;">';
	echo '<thead><tr style="color: #DEDEDE;">
			<td><center>Total raportari inregistrate</center></td>
			<td><center>' . Config::init()->rows('ucp_tickets') . '</center></td>
		  </tr></thead>';
	echo '</table>';
	return;
}
echo '<div class="alert alert-info"><i class="fa fa-info-circle">&nbsp;&nbsp;</i>Descrierea ta trebuie sa fie cat mai clar redactata! Dupa trimiterea acesteia, veti primi un raspuns in maximum 6 ore!</div>'; 
?>
<form name="ticket_form"  id="ticket_form" action="" method="post">
	<label class="block clearfix">
		<span class="block input-icon input-icon-right">
			<input type="text" class="form-control" placeholder="Descrie problema" name="description" style="font-family: Segoe UI; width: 90%;"/>
			<i class="icon-user"></i>
		</span>
	</label><br><br>
	
	<hr>
	<div class="clearfix">
		<input type="submit" name="submit" class="width-35 btn btn-sm btn-primary" value="TRIMITE" style="font-family: Segoe UI;"></input>
	</div>
	<div class="space-4"></div>
</form>
<hr>
<?php 
echo '<table class="table table-bordered table-striped table-hover">';
echo '<thead><tr style="color: #DEDEDE;">
		<td><center>Your name</center></td>
		<td><center>Ticket</center></td>
		<td><center>Status</center></td>
		<td><center>Data raportarii</center></td>
		<td><center>Actiune</center></td>
	  </tr></thead>';
$q = Config::$g_con->prepare("SELECT * FROM `ucp_tickets` WHERE `Name` = ?");
$q->execute(array($_SESSION['user']));
while($row = $q->fetch()) {
	$ticket_text = (strlen(strip_tags($row['Ticket'])) > 10) ? substr($row['Ticket'], 0, 10) . '...' : $row['Ticket'];
	echo '<tr>';
	echo '<td><center>'.$row['Name'].'</center></td>';
	echo '<td><center>'. $ticket_text .'</center></td>';
	echo '<td><center>'.($row['Status'] != 0 ? "<font color='red'>Inchis</font>" : "<font color='green'>Deschis</font>").'</center></td>';
	echo '<td><center>'.$row['Date'].'</center></td>';
	echo '<td><center><a href="' . Config::$_PAGE_URL . 'seebug/'.$row['id'].'"><i class="fa fa-eye"></i></a>';
	echo '</tr>';
}
echo '</table>';
?>