<?php
include_once 'inc/samp.inc.php';
$server = new Server('93.119.26.153','7777');
if(!$server->isOnline()) { echo '<font color="#FF0000">Serverul nu este online.</font>'; return; }
echo '<font color="blue" size="4">Total jucatori online <font color="green">' . $server->get('players') . '</font></font><BR><BR>';
echo '<table class="table table-bordered table-striped table-hover">';
echo '<thead><tr><td><center><b>Nume</b></center></td><td><center><b>Level</center></b></td></tr></thead>';
$players = $server->getBasicPlayers();
foreach($players as $player) {
	echo '<tr><td><center><a href="'.Config::$_PAGE_URL.'profile/'.str_replace('.','(2698)',$player['nickname']).'">' .$player['nickname']. '</a></center></td>
	<td><center>'.$player['score'].'</center></td></tr>';
}
echo '</table>';
?>