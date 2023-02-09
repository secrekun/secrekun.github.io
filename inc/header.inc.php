<html>
 <head>
	<title>Hard-Roleplay , Back in business</title>
    <meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo Config::$_PAGE_URL ?>css/style.css" type="text/css" media="all">
	<link rel="stylesheet" href="<?php echo Config::$_PAGE_URL ?>css/font-awesome.min.css" type="text/css" media="all">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="icon" href="favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
</head>
<body>
<div class="shell">	
	<div id="header" style="position: relative; left: 0; top: 0;">
		<center>
		<img src="<?php echo Config::$_PAGE_URL ?>images/logo.png" style="position: relative; width: 100%; height: 260px; top: 0; left: 0;">
		<img src="<?php echo Config::$_PAGE_URL ?>images/logo_2.png" style="position: absolute; width: 550px; height: 150px; border: 0px solid #fff; top: 50px; left: 260px;">
		</center>
	</div>
	<div id="navigation">
		<?php
			include_once 'menu.inc.php';
		?>
	</div>
	<div style="margin-top: 6px;"/>
	<div class="sidebar" style="">
		<BR>
		<center>
			<?php
				if(!Config::init()->isLogged()) include 'inc/pages/login.p.php';
				else 
				{
					if(isset($_POST['b_logout']))
						header('Location: ' . Config::$_PAGE_URL . 'logout');
					if(isset($_POST['b_join']))
						header('Location: samp://188.212.107.240:7777');
					echo '<table class="table table-bordered table-striped" style="">';
					echo '<td><center><font color="#fff">Bine ai venit, <b>' . $_SESSION['user'] . '</font></center></td>';
					$q = Config::$g_con->prepare("SELECT * FROM `characters` WHERE `Username` = ?");
					$q->execute(array($_SESSION['user']));
					while($row = $q->fetch()) {
						echo '<tr style="font-size: 13px;"><td><center><a href="' . Config::$_PAGE_URL . 'character/'. $row['Character'] .'">'. $row['Character'] .'</a></center></td></tr>';
					}
					echo '</tr></thead>';
					echo '</table>';
					
					echo '<table class="table table-bordered table-striped" style="">';
					/*echo '<td style="font-size: 13px;"><center><font color="#fff"><a href="' . Config::$_PAGE_URL . 'changemail">Schimba email</a></font></center></td>';*/
					echo '<tr style="font-size: 13px;"><td><center><font color="#fff"><a href="' . Config::$_PAGE_URL . 'changepass">Schimba parola</a></font></center></td></tr>';
					
					$q = Config::$g_con->prepare("SELECT * FROM `characters` WHERE `Username` = ?");
					$q->execute(array($_SESSION['user'],'id'));
					while($row = $q->fetch()) {
						if($row['Faction'] == 1 && $row['FactionRank'] >= 15 || $row['Faction'] == 4 && $row['FactionRank'] >= 6 || $row['Faction'] == 6 && $row['FactionRank'] >= 6)
						{
							echo '<table class="table table-bordered table-striped" style="">';
							echo '<td><center><font color="#fff">Leader <b>Panel</b> for '. $row['Character']. '</font></center></td>';
							echo '<tr style="font-size: 13px;"><td><center><font color="#fff"><a href="' . Config::$_PAGE_URL . 'fpk">Demite un membru</a></font></center></td></tr>';
							echo '</tr></thead>';
							echo '</table>';
						}
					}
					
					echo '</thead>';
					echo '</table>';
					
					if(Config::init()->getPlayerData($_SESSION['user'],'Admin') > 2)
					{
						echo '<table class="table table-bordered table-striped" style="">';
						echo '<td><center><font color="#fff">Admin <b>Panel</font></center></td>';
						echo '<tr style="font-size: 13px;"><td><center><a href="' . Config::$_PAGE_URL . 'exclude">Exclude un membru al staffului</a></center></td></tr>';
						echo '</tr></thead>';
						echo '</table>';
					}
					?>
					<form action="" method="post">
						<button type="submit" name="b_logout"><font color="#fff"><i class="fa fa-lock">&nbsp;&nbsp;</i>LOG OUT</font></button>
					</form>
					<?php
					
				}
				echo '<hr>';
				
				include 'inc/samp.inc.php';
				$server = new Server('188.212.107.240','7777');
				if(!$server->isOnline()) echo '<font color="red">Serverul este offline.</font>';
				else {
					$info = $server->getInfo();
					echo '<table id="monitoring">';
						echo '<tbody><tr><td>Hostname</td><td>Slots</td><td>Status</td></tr>';
						echo '<tr><td>' . $info['hostname'] . '</td><td>' . $info['players'] . '/' . $info['maxplayers'] . '</td><td><span style="color:green">ON</span></td></tr>';
					echo '</tbody></table> ';					
					echo '<form action="" method="post">
						<button style="width: 50%" type="submit" name="b_join"><font color="#fff"><i class="fa fa-gamepad">&nbsp;&nbsp;</i>JOIN SERVER</font></button>
					</form>';
				}
				echo '<hr><iframe width="260" height="215" src="//www.youtube.com/embed/H2pnhHlPKfE" frameborder="0" allowfullscreen></iframe>';
				
			?>
		</center>	
		<BR>
	</div>
	<?php 
	function getBrowser() 
	{ 
		$u_agent = $_SERVER['HTTP_USER_AGENT']; 
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";

		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
		
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Internet Explorer'; 
			$ub = "MSIE"; 
		} 
		elseif(preg_match('/Firefox/i',$u_agent)) 
		{ 
			$bname = 'Mozilla Firefox'; 
			$ub = "Firefox"; 
		} 
		elseif(preg_match('/Chrome/i',$u_agent)) 
		{ 
			$bname = 'Google Chrome'; 
			$ub = "Chrome"; 
		} 
		elseif(preg_match('/Safari/i',$u_agent)) 
		{ 
			$bname = 'Apple Safari'; 
			$ub = "Safari"; 
		} 
		elseif(preg_match('/Opera/i',$u_agent)) 
		{ 
			$bname = 'Opera'; 
			$ub = "Opera"; 
		} 
		elseif(preg_match('/Netscape/i',$u_agent)) 
		{ 
			$bname = 'Netscape'; 
			$ub = "Netscape"; 
		} 
		
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
		
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
		
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
		
		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	} 
	
	$ua=getBrowser();
	if($ua['name'] == 'Mozilla Firefox')
	{
		if(Config::init()->isLogged())
			echo '<div class="well" style="min-height: 736px;"><center><BR>';
		else
			echo '<div class="well" style="min-height: 590px;"><center><BR>';
	} else {
		if(Config::init()->isLogged())
			echo '<div class="well" style="min-height: 728px;"><center><BR>';
		else
			echo '<div class="well" style="min-height: 578px;"><center><BR>';
	}
	?>
	<BR>