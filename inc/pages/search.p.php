<?php
if(!isset($_POST['f_name']) && !Config::init()->_getPage()) { echo '<font color="red">Nu ai cautat nimic.</font>'; return; }
if(isset($_POST['f_name'])) $_SESSION['search'] = $_POST['f_name'];
$q = Config::$g_con->prepare("SELECT * FROM `accounts` WHERE `Name` LIKE ? " . Config::init()->_pagLimit());
$q->execute(array('%'.$_SESSION['search'].'%'));
if(!$q->rowCount()) { echo '<font color="red">Nici un jucator cu acest nume.</font>'; return; }
echo 'Rezultate gasite<BR><BR>';
echo '<table class="table table-bordered table-striped table-hover">';
while($row = $q->fetch()) {
	echo '<tr><td><center><a href="'.Config::$_PAGE_URL.'profile/'.str_replace('.','(2698)',$row['Name']).'">' .$row['Name'] . '</a></center></td></tr>';
}
echo '</table>';
$q = Config::$g_con->prepare("SELECT * FROM `accounts` WHERE `Name` LIKE ?");
$q->execute(array('%'.$_SESSION['search'].'%'));
echo Config::init()->_pagLinks($q->rowCount());
?>