<?php
echo '<table class="table table-bordered table-striped table-hover">';
foreach(Config::$faction as $key=>$fac) {
	if(Config::$faction[0] === $fac) continue;
	echo '<tr><td>' . $key . '</td>';
	echo '<td>' . $fac . '</td>';
	$q = Config::$g_con->prepare("SELECT * FROM `accounts` WHERE `pLeader` = ?");
	$q->execute(array($key));
	if($q->rowCount() >= 2) {
		echo '<td>';
		while($row = $q->fetch())
			echo $row['Name'] . ' | ';
		echo '</td><tr>';	
	} else {
		$row = $q->fetch();
		echo '<td>'.$row['Name'].'</td></tr>';	
	}	
}
echo '</table>';
?>