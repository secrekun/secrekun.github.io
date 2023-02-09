
<?php
if(!isset(Config::$_url[1]) && !Config::init()->isLogged()) header('Location:' . Config::$_PAGE_URL);
if(isset(Config::$_url[1])) {
	$q = Config::$g_con->prepare("SELECT * FROM `accounts` WHERE `username` = ?");
	$q->execute(array(str_replace('(2698)','.',Config::$_url[1])));
	if(!$q->rowCount()) {
		echo 'Acest jucator nu exista.';
		return;
	}
	$name = str_replace('(2698)','.',Config::$_url[1]);
}
else $name = $_SESSION['user'];

if(Config::init()->getPlayerData($name,'Leader') != 0)
	$factiune = Config::$faction[Config::init()->getPlayerData($name,'Leader')] . ' <i>[Leader]</i>';
else if(!Config::init()->getPlayerData($name,'Leader') && Config::init()->getPlayerData($name,'Member') != 0)
	$factiune = Config::$faction[Config::init()->getPlayerData($name,'Member')];
else $factiune = Config::$faction[Config::init()->getPlayerData($name,'Member')];
include_once 'inc/samp.inc.php';
$server = new Server('93.119.26.153','7777');
if(!$server->isOnline()) $status = '<font color="red">Offline</font>';
else {
	$sts = 0;
	$players = $server->getBasicPlayers();
	foreach($players as $player) if($name === $player['nickname']) { $status = '<font color="green">Online</font>'; $sts = 1; break; }
	if(!$sts) $status = '<font color="red">Offline</font>';
}
?>
</pre>
<hr>
<span class="label label-info">Informatii cont</span><br><br>
<span class="ucpm">Skin</span><br>
<br><center><img src="<?php echo Config::$_PAGE_URL; ?>images/skins/Skin_<?php echo Config::init()->getPlayerData($name,'pChar'); ?>.png"></center><br>
<span class="ucpm">Nume: <?php echo $name; ?></span><br>
<span class="ucpm">Level: <?php echo Config::init()->getPlayerData($name,'pLevel'); ?></span><br>
<span class="ucpm">Bani: <?php echo number_format(Config::init()->getPlayerData($name,'pCash')+Config::init()->getPlayerData($name,'pAccount'),0,'.','.'); ?></span><br>
<span class="ucpm">Casa: <?php echo Config::init()->getPlayerData($name,'pPhousekey'); ?></span><br>
<span class="ucpm">Afacere: <?php echo Config::init()->getPlayerData($name,'pPbiskey'); ?></span><br>
<span class="ucpm">Nr. telefon: <?php echo Config::init()->getPlayerData($name,'pPnumber'); ?></span><br>
<span class="ucpm">Factiune: <?php echo $factiune; ?></span><br>
<span class="ucpm">Ore jucate: <?php echo Config::init()->getPlayerData($name,'pConnectTime'); ?></span><br>
<span class="ucpm">Puncte respect: <?php echo Config::init()->getPlayerData($name,'pExp') . '/' . (Config::init()->getPlayerData($name,'pLevel')+1)*4; ?></span><br>
<span class="ucpm">Job: <?php echo Config::$job[Config::init()->getPlayerData($name,'pJob')]; ?></span><br>
<span class="ucpm">Status: <?php echo $status; ?></span><br>
<hr>
<span class="label label-info">Masini personale</span><br><br>
<?php
$q = Config::$g_con->prepare("SELECT * FROM `ds_vehicles` WHERE `Owner` = ?");
$q->execute(array($name));
if(!$q->rowCount()) echo '<span class="ucpm">Nici o masina.</span><br>';
else {
	while($row = $q->fetch()) {
		if($row['DonatorCar']) {
			echo '<img style="border:1px solid #000;" src="'.Config::$_PAGE_URL.'car/'.$row['Model'].'" width="200">';
		} else echo '<img style="border:1px solid #000;" src="'.Config::$_PAGE_URL.'images/cars/'.$row['Model'].'.jpg" width="200">';
		
	}
}	
?>
<hr>
<span class="label label-info">Licente</span><br><br>
<span class="ucpm">Licenta de condus: <?php echo (Config::init()->getPlayerData($name,'pCarLic') ? 'Da' : 'Nu'); ?></span><br>
<span class="ucpm">Licenta de zburat: <?php echo (Config::init()->getPlayerData($name,'pFlyLic') ? 'Da' : 'Nu'); ?></span><br>
<span class="ucpm">Licenta de navigare: <?php echo (Config::init()->getPlayerData($name,'pBoatLic') ? 'Da' : 'Nu'); ?></span><br>
<span class="ucpm">Licenta de pescuit: <?php echo (Config::init()->getPlayerData($name,'pFishLic') ? 'Da' : 'Nu'); ?></span><br>
<span class="ucpm">Licenta de arme : <?php echo (Config::init()->getPlayerData($name,'pGunLic') ? 'Da' : 'Nu'); ?></span><br>
<hr>
<span class="label label-info">Arme donator</span><br><br>
<?php
$dGun1 = Config::init()->getPlayerData($name,'dGun1');
$dGun2 = Config::init()->getPlayerData($name,'dGun2');
$dGun3 = Config::init()->getPlayerData($name,'dGun3');
$dGun4 = Config::init()->getPlayerData($name,'dGun4');
$dGun5 = Config::init()->getPlayerData($name,'dGun5');
if($dGun1 > 0)
	echo '<img src="'.Config::$_PAGE_URL.'images/weapons/'.$dGun1.'.jpg"/>';
if($dGun2 > 0)
	echo '<img src="'.Config::$_PAGE_URL.'images/weapons/'.$dGun2.'.jpg"/>';
if($dGun3 > 0)
	echo '<img src="'.Config::$_PAGE_URL.'images/weapons/'.$dGun3.'.jpg"/>';
if($dGun4 > 0)
	echo '<img src="'.Config::$_PAGE_URL.'images/weapons/'.$dGun4.'.jpg"/>';
if($dGun5 > 0)
	echo '<img src="'.Config::$_PAGE_URL.'images/weapons/'.$dGun5.'.jpg"/>';
if(!$dGun1 && !$dGun2 && !$dGun3 && !$dGun4 && !$dGun5)	echo '<span class="ucpm">Nici o arma.</span><br>';
?>

<hr>