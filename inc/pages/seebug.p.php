<?php 
if(Config::init()->getBugData('ucp_tickets',Config::$_url[1],'Name') !== $_SESSION['user'] && Config::init()->getPlayerData($_SESSION['user'],'Admin') < 1) header('Location: ' . Config::$_PAGE_URL . '');
if(isset($_POST['s_reply'])) {
	if(!$_POST['f_name']) echo '<div class="alert alert-danger">Nu ai scris nici un comentariu!</div>';
	else { 
		echo '<div class="alert alert-success">Ai raspuns cu succes ticketului.</div>';
		
		$q = Config::$g_con->prepare('INSERT INTO `ucp_reply_tickets` (`Text`, `Admin`, `TicketID`) VALUES (?, ?, ?)');
		$q->execute(array($_POST['f_name'],$_SESSION['user'],Config::$_url[1]));
	}
}
echo '<table class="table table-bordered table-striped table-hover">
	<thead><tr style="color: #DEDEDE;">
	<td><center>Player</center></td>
	<td><center>Status</center></td>
	<td><center>Data raportarii</center></td>
  </tr></thead>';
$q = Config::$g_con->prepare("SELECT * FROM `ucp_tickets` WHERE `id` = ?");
$q->execute(array(Config::$_url[1]));
while($row = $q->fetch()) {
	echo '<tr>';
	echo '<td><center>'.$row['Name'].'</center></td>';
	echo '<td><center>'.($row['Status'] != 0 ? "<font color='red'>Inchis</font>" : "<font color='green'>Deschis</font>").'</center></td>';
	echo '<td><center>'.$row['Date'].'</center></td>';
	echo '</tr>
	<table class="table table-bordered table-striped table-hover">
		<thead>
			<tr style="color: #DEDEDE;">
			<tr><td>'.$row['Ticket'].'</td></tr>
	</table><hr>
	';
	$n = Config::$g_con->prepare("SELECT * FROM `ucp_reply_tickets` WHERE `TicketID` = ?");
	$n->execute(array(Config::$_url[1]));
	while($new = $n->fetch()) {
		echo '</tr>
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr style="color: #DEDEDE;">
				<td>Autor: '.$new['Admin'].' ('.$new['Date'].')</td></tr></thead>
				<tr><td>'.$new['Text'].'</td></tr>
		</table>
		';
	}
	echo '<hr>';
	if($row['Status'] == 0)
	{
		echo '
		<form action="" method="post">
			<div style="text-align: left; margin-left:15px;">
				<b><font color="#D6D6D6">LEAVE A COMMENT</font></b>
			</div>
			<input style="margin:5px; width: 90%" type="text" name="f_name" placeholder="Your message"/><BR>
			<button type="submit" name="s_reply">REPLY</a></button>
		</form>
		';
	}
}
echo '</table>';
return;
echo '<div class="alert alert-info"><i class="fa fa-info-circle">&nbsp;&nbsp;</i>Descrierea ta trebuie sa fie cat mai clar redactata! Dupa trimiterea acesteia, veti primi un raspuns in maximum 6 ore!</div>'; 
?>