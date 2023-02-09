<?php
echo '<table class="table table-bordered table-striped table-hover">';
echo '<thead><tr style="color: #fff">
		<td><center>Nick Name</center></td>
		<td><center>Admin Level</center></td>
		<td><center>Last Login</center></td>
	  </tr></thead>';

$q = Config::$g_con->prepare("SELECT * FROM `characters` WHERE `Admin` > 0 ORDER BY `Admin` DESC");
$q->execute();
while($row = $q->fetch()) {
	echo '<tr>';
	echo '<td><center>'.$row['Username'].'</center></td>';
	echo '<td><center>'.$row['Admin'].'</center></td>';
	$timestamp=$row['LastLogin'];
	echo '<td><center>'.gmdate("Y-m-d H:i:s", $timestamp).'</center></td>';
	echo '</tr>';
}
echo '</table>';

?>