<?php
if(Config::init()->isLogged()) {
	if(Config::init()->getPlayerData($_SESSION['user'],'Admin') >= 1 && Config::init()->_getPage() === 'admin') {
		echo '<li><a href="'.Config::$_PAGE_URL.'">Acasa</a></li>';
		return;
	}
}	
?>
<ul>
<a href="<?php echo Config::$_PAGE_URL ?>"><li><i class="fa fa-home fa-2x fa-lg">&nbsp;</i><font size = '3px'>ACASA</font></li></a>
<a target="_blank" href="http://hard-roleplay.com/server"><li><i class="fa fa-comment fa-2x fa-lg">&nbsp;</i><font size = '3px'>FORUM</font></li></a>
<a href="<?php echo Config::$_PAGE_URL ?>banlist"><li><i class="fa fa-folder-open fa-2x fa-lg">&nbsp;</i><font size = '3px'>BAN LIST</font></li>
<a href="<?php echo Config::$_PAGE_URL ?>stafflist"><li><i class="fa fa-users fa-2x fa-lg">&nbsp;</i><font size = '3px'>&nbsp;STAFF LIST</font></li></a>
<a href="<?php echo Config::$_PAGE_URL ?>bugreport"><li><i class="fa fa-cogs fa-2x fa-lg">&nbsp;</i><font size = '3px'>RAPORTEAZA UN BUG</font></li></a>
<a href="<?php echo Config::$_PAGE_URL ?>donate"><li><i class="fa fa-shopping-cart fa-2x fa-lg">&nbsp;</i><font size = '3px'>SHOP ONLINE</font></li></a>
<?php
	if(!Config::init()->isLogged()) 
		echo '<a href="'.Config::$_PAGE_URL.'login"><li style = "width: 11.6%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-unlock fa-2x fa-lg">&nbsp;</i><font size = "3px">LOGIN</font></li></a>';
	else if(Config::init()->getPlayerData($_SESSION['user'],'Admin') >= 1)
		echo '<a href="'.Config::$_PAGE_URL.'acp"><li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-bolt fa-2x fa-lg">&nbsp;&nbsp;</i><font size = "3px">ACP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></li></a>';
	else if(Config::init()->getPlayerData($_SESSION['user'],'Admin') < 1 && Config::init()->isLogged())
		echo '<a href="'.Config::$_PAGE_URL.'logout"><li><i class="fa fa-unlock fa-2x fa-lg">&nbsp;&nbsp;</i><font size = "3px">LOG OUT&nbsp;&nbsp;&nbsp;</font></li></a>';
?>
</ul>