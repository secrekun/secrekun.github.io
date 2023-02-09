<?php
if(Config::init()->isLogged()) header('Location: ' . Config::$_PAGE_URL . '');
if(isset($_POST['submit'])) 
{
	$problem = 0;
	if(empty($_POST['nickname']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['roleplay']) || empty($_POST['powergaming']) || empty($_POST['metagaming']) || empty($_POST['revengekill'])) 
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Completeaza cu atentie toate field-urile!</div>'; 
		$problem = 1;
	}
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['email'])) 
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Emailul introdus nu este conform! (ex@example.ro)</div>';
		$problem = 1;
	}
	if(isset($_POST['nickname']) && !empty($_POST['nickname']))
	{
		$q = Config::$g_con->prepare("SELECT * FROM `accounts` WHERE `username` = ? LIMIT 0,1");
		$q->execute(array($_POST['nickname']));
		if($q->rowCount() != 0) 
		{
			echo '<div class="alert alert-info"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Numele pe care l-ai introdus este deja folosit!</div>';
			$problem = 1;
		}
		$q = Config::$g_con->prepare("SELECT * FROM `ucp_accounts` WHERE `username` = ? LIMIT 0,1");
		$q->execute(array($_POST['nickname']));
		if($q->rowCount() != 0) 
		{
			echo '<div class="alert alert-info"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Numele pe care l-ai introdus este deja pe lista de asteptare!</div>';
			$problem = 1;
		}
	}
	if(isset($_POST['email']) && !empty($_POST['email']))
	{
		$q = Config::$g_con->prepare("SELECT * FROM `accounts` WHERE `email` = ? LIMIT 0,1");
		$q->execute(array($_POST['email']));
		if($q->rowCount() != 0) 
		{
			echo '<div class="alert alert-info"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Email-ul pe care l-ai introdus este deja folosit!</div>';
			$problem = 1;
		}
		$q = Config::$g_con->prepare("SELECT * FROM `ucp_accounts` WHERE `email` = ? LIMIT 0,1");
		$q->execute(array($_POST['email']));
		if($q->rowCount() != 0) 
		{
			echo '<div class="alert alert-info"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Email-ul pe care l-ai introdus este pe lista de asteptare!</div>';
			$problem = 1;
		}
	}
	if($problem == 0)
	{
		$q = Config::$g_con->prepare('INSERT INTO `ucp_accounts` (`username`,`password`,`email`,`roleplay`,`powergaming`,`metagaming`,`revengekill`) VALUES (?, ?, ?, ?, ?, ?, ?)');
		$q->execute(array($_POST['nickname'],$_POST['password'],$_POST['email'],$_POST['roleplay'],$_POST['powergaming'],$_POST['metagaming'],$_POST['revengekill']));
		echo '<div class="alert alert-success"><i class="fa fa-check-square-o">&nbsp;&nbsp;</i>Contul necesita activarea unui admin! Aceasta poate dura cateva ore sau chiar instant!</div>';
		$_SESSION['register'] = '';
		header('Refresh: 3; URL=' . Config::$_PAGE_URL . '');
		return;
	}
}
if(isset(Config::$_url[1])) 
{
	if(Config::$_url[1] == $_SESSION['register'])
	{
		echo '<div style = "text-align:left; margin-left: 12px;">';
		echo "<b><font size=4px; color='orange'>Inregistrare cont</font></b><br>";
		echo "<b><font size=2px; color='grey'>Completeaza cu atentie datele de mai jos pentru a te putea connecta la server!</font></b>";
		echo '<hr><form action="" method="post">';
		
		echo '<i class="fa fa-info-circle green">&nbsp;&nbsp;</i>Alegeti un nickname usor de memorat! Cu acesta te vei connecta la server.<br>
			<input style="margin:5px; width: 30%" type="text" name="nickname" placeholder="Nickname"/><br><br>';
			
		echo '<i class="fa fa-info-circle green">&nbsp;&nbsp;</i>Foloseste o parola complexa, cat sa o tii minte. Cu aceasta te vei loga cu nickname-ul de mai sus.<br>
			<input style="margin:5px; width: 30%" type="text" name="password" placeholder="Password"/><br><br>';
			
		echo '<i class="fa fa-info-circle green">&nbsp;&nbsp;</i>Introdu email-ul tau. Daca acesta va fi introdus gresit, si se intampla ceva cu contul dvs, nu raspundem de el.<br>
			<input style="margin:5px; width: 30%" type="text" name="email" placeholder="Email"/><br><br>';
		
		echo '<i class="fa fa-edit green">&nbsp;&nbsp;</i>Descrie ce intelegi prin <b>RolePlay</b>.<br>
			<textarea style="margin:5px; width: 97%" type="text" name="roleplay" placeholder="Definitie RolePlay"/></textarea><br><br>';
			
		echo '<i class="fa fa-edit green">&nbsp;&nbsp;</i>Descrie ce intelegi prin <b>PowerGaming</b>.<br>
			<textarea style="margin:5px; width: 97%" type="text" name="powergaming" placeholder="Definitie PowerGaming"/></textarea><br><br>';
			
		echo '<i class="fa fa-edit green">&nbsp;&nbsp;</i>Descrie ce intelegi prin <b>MetaGaming</b>.<br>
			<textarea style="margin:5px; width: 97%" type="text" name="metagaming" placeholder="Definitie MetaGaming"></textarea><br><br>';
		
		echo '<i class="fa fa-edit green">&nbsp;&nbsp;</i>Descrie ce intelegi prin <b>RevengeKill</b>.<br>
			<textarea style="margin:5px; width: 97%" type="text" name="revengekill" placeholder="Definitie RevengeKill"/></textarea><br><br>';
		echo'<button type="submit" name="submit">INREGISTRARE</a></button></form></div>';
		
		return;
	}
}
if(isset($_POST['f_other'])) 
{
	header('URL=' . Config::$_PAGE_URL . 'register');
}
if(isset($_POST['f_check'])) 
{
	$count = 0;
	$checked = (isset($_POST['radio0']))?true:false; if($checked == true) { if($_POST['radio0'] !== '#1') $count++; }
	$checked = (isset($_POST['radio1']))?true:false; if($checked == true) { if($_POST['radio1'] !== '#3') $count++; }
	$checked = (isset($_POST['radio2']))?true:false; if($checked == true) { if($_POST['radio2'] !== '#1') $count++; }
	$checked = (isset($_POST['radio3']))?true:false; if($checked == true) { if($_POST['radio3'] !== '#2') $count++; }
	$checked = (isset($_POST['radio4']))?true:false; if($checked == true) { if($_POST['radio4'] !== '#2') $count++; }
	$checked = (isset($_POST['radio5']))?true:false; if($checked == true) { if($_POST['radio5'] !== '#3') $count++; }
	$checked = (isset($_POST['radio6']))?true:false; if($checked == true) { if($_POST['radio6'] !== '#2') $count++; }
	$checked = (isset($_POST['radio7']))?true:false; if($checked == true) { if($_POST['radio7'] !== '#3') $count++; }
	$checked = (isset($_POST['radio8']))?true:false; if($checked == true) { if($_POST['radio8'] !== '#2') $count++; }
	$checked = (isset($_POST['radio9']))?true:false; if($checked == true) { if($_POST['radio9'] !== '#1') $count++; }
	$checked = (isset($_POST['radio10']))?true:false; if($checked == true) { if($_POST['radio10'] !== '#1') $count++; }
	$checked = (isset($_POST['radio11']))?true:false; if($checked == true) { if($_POST['radio11'] !== '#2') $count++; }
	$checked = (isset($_POST['radio12']))?true:false; if($checked == true) { if($_POST['radio12'] !== '#3') $count++; }
	
	if($count > 0) echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle">&nbsp;&nbsp;</i>Ai raspuns gresit la o intrebare sau mai multe! Completeaza cu atentie!</div>';
	if($count == 0)
	{
		$characters = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
		echo $characters;
		$_SESSION['register'] = $characters;
		echo '<br>';
		echo 'URL=' . Config::$_PAGE_URL . 'register/'. $_SESSION['register'] .'';
		echo '<br>';
		echo $characters;
		header('Location: ' . Config::$_PAGE_URL . 'register/'. $_SESSION['register'] .'');
		return;
	}
}
?>

<Div ID=X style="text-align:left; margin-left: 12px;">
<?php
echo "<b><font size=4px; color='orange'>Inregistrare cont</font></b><br>";
echo "<b><font size=2px; color='grey'>Pentru a te putea inregistra, trebuie sa fim siguri ca ai minimul de cunostinte pentru a juca pe un server RolePlay!</font></b><br>";
unset($_SESSION['register']);
?>
<br><br>

<form action="" method="post">
<?php
$x = 0;
$mem = Config::RandQuest(0,8);
do 
{
	$question = explode(",", $mem);
	echo '<br><b>'.Config::$reg_questions[$question[$x]].'</b>';
	if($question[$x] == 0)
	{
		echo '<br>
			<input type="radio" name="radio0" value="#1" id="1"checked="check">Chat-ul OOC se foloseste cand oferi niste explicatii care nu tin de o actiune IC.<br>
			<input type="radio" name="radio0" value="#2" id="2">Chat-ul OOC pot sa il folosesc cand vreau sa vorbesc cu cineva IC.<br>
			<input type="radio" name="radio0" value="#3" id="3">Chat-ul OOC pot sa il folosesc ca sa imi exprim gandurile si sentimentele IC.<br><br>
		';
	}
	if($question[$x] == 1)
	{
		echo '<br>
			<input type="radio" name="radio1" value="#1" id="1"checked="check">/me sare 10 metri si il prinde pe Lorenzo .<br>
			<input type="radio" name="radio1" value="#2" id="2">/me vrea sa fie prieten cu Andrew, acesta accepta?<br>
			<input type="radio" name="radio1" value="#3" id="3">/me duce mana dreapta catre torpedoul masini urmand sa apuce actele dupa care sa i le ofere lui Brian.<br><br>
		';
	}
	if($question[$x] == 2)
	{
		echo '<br>
			<input type="radio" name="radio2" value="#1" id="1"checked="check">/do pe tricoul lui Jack se pot observa mai multe pete de sange.<br>
			<input type="radio" name="radio2" value="#2" id="2">/do baga mana dreapta in buzunarul pantalonilor urmand sa scoata telefonul.<br>
			<input type="radio" name="radio2" value="#3" id="3">/do vrei sa mergem cu masina mea la job?<br><br>
		';
	}
	if($question[$x] == 3)
	{
		echo '<br>
			<input type="radio" name="radio3" value="#1" id="1"checked="check">/me scote un pistol din sacou urmand sa il impuste in cap de Jack.<br>
			<input type="radio" name="radio3" value="#2" id="2">/me duce mana dreapta la buzunarul din spate al pantalonilor urmand sa apuce portofelul.<br>
			<input type="radio" name="radio3" value="#3" id="3">/me a fost electrocutat de tazer urmand sa se ridice si sa o i-a la fuga.<br><br>
		';
	}
	if($question[$x] == 4)
	{
		echo '<br>
			<input type="radio" name="radio4" value="#1" id="1" checked="check">Jack se afla intr-un cartier de hispanici,acesta incepe sa ii injure si sa faca gesturi obscene.<br>
			<input type="radio" name="radio4" value="#2" id="2">Mike a fost impuscat de trei ori in cap acesta fiind inconstient.<br>
			<input type="radio" name="radio4" value="#3" id="3">Hector a fost calcat de un tir,acesta se ridica si pleaca.<br><br>
		';
	}
	if($question[$x] == 5)
	{
		echo '<br>
			<input type="radio" name="radio5" value="#3" id="1" checked="check">Chat-ul IC se foloseste pentru a exprima gandurile si sentimentele iar chat-ul OOC se foloseste pentru a coopera cu un admin.<br>
			<input type="radio" name="radio5" value="#2" id="2">Chat-ul IC se foloseste ca sa rogi pe cineva sa deblocheze PM iar chat-ul OOC se foloseste pentru a evita o actiune IC.<br>
			<input type="radio" name="radio5" value="#1" id="3">Chat-ul IC se foloseste pentru a vorbi cu prieteni OOC in caz ca nu ai microfon si ei sunt pe Skype iar chat-ul OOC se foloseste pentru a da detalii legate de o actiune.<br><br>
		';
	}
	if($question[$x] == 6)
	{
		echo '<br>
			<input type="radio" name="radio6" value="#2" id="1"checked="check">Ii vad numele unui Player si ii strig desi nu l-am cunoscut niciodata.<br>
			<input type="radio" name="radio6" value="#1" id="2">Vorbesc cu un membru al departamentului cum putem spiona o gruparea.<br>
			<input type="radio" name="radio6" value="#3" id="3">Vorbesc strict IC cu un officer legat de o rapire la care am fost de fata.<br><br>
		';
	}
	if($question[$x] == 7)
	{
		echo '<br>
			<input type="radio" name="radio7" value="#3" id="1"checked="check">Vorbesc pe chatul IC cu un admin el fiind Aduty.<br>
			<input type="radio" name="radio7" value="#2" id="2">Port un dialog cu un prieten el pe chatul IC si eu la fel.<br>
			<input type="radio" name="radio7" value="#1" id="3">Vreau sa cheam un taxi si sun la numarul respectiv.<br><br>
		';
	}
	if($question[$x] == 8)
	{
		echo '<br>
			<input type="radio" name="radio8" value="#2" id="1" checked="check">Dau /re sau /report.<br>
			<input type="radio" name="radio8" value="#1" id="2">Fac spam pe chatul IC ca vreau un admin la mine.<br>
			<input type="radio" name="radio8" value="#3" id="3">Incep sa spamez pe /b ca vreau sa ma ajute un admin.<br><br>
		';
	}
	if($question[$x] == 9)
	{
		echo '<br>
			<input type="radio" name="radio9" value="#1" id="1"checked="check">Ma intalnesc cu persoana respectiva IC si il cred.<br>
			<input type="radio" name="radio9" value="#2" id="2">Ma uit pm forum daca a postat o poza cu /stat in care ii se vede numarul.<br>
			<input type="radio" name="radio9" value="#3" id="3">Ii dau /pm daca imi poate da numarul lui de telefon.<br><br>
		';
	}
	if($question[$x] == 10)
	{
		echo '<br>
			<input type="radio" name="radio10" value="#1" id="1" checked="check">Ma uit pe gov-ul factiunilor respective si in functie de programul acestora aplic sau nu.<br>
			<input type="radio" name="radio10" value="#2" id="2">Ii dau /pm liderului factiuni la care vreau sa aplic sa vad daca sunt deschise.<br>
			<input type="radio" name="radio10" value="#3" id="3">Dau un /re sau /report in spernata ca staff-ul imi va spune daca sunt deschise sau nu.<br><br>
		';
	}
	if($question[$x] == 11)
	{
		echo '<br>
			<input type="radio" name="radio11" value="#1" id="1" checked="check">Dau rapid /q ca sa nu apuce sa ma jefuiasca.<br>
			<input type="radio" name="radio11" value="#2" id="2">Rolez cu acestia incercand sa scap sau sa chem ajutor.<br>
			<input type="radio" name="radio11" value="#3" id="3">Dau /pm unui prieten sa vina sa ii calce cu masina pentru a scapa.<br><br>
		';
	}
	if($question[$x] == 12)
	{
		echo '<br>
			<input type="radio" name="radio12" value="#1" id="1" checked="check">Cand cineva scoate arma la mine eu scot bata la el.<br>
			<input type="radio" name="radio12" value="#2" id="2">Daca cineva are arma indreptata spre mine iar eu ma pun sa comentez si il injur stind ca o sa primesc PK.<br>
			<input type="radio" name="radio12" value="#3" id="3">Daca cineva are arma indreptata spre mine iar eu execut toate comenzile spuse de acesta in speranta ca nu o sa ma omoare.<br><br>
		';
	}
	$x++;
} while ($x<8);

//echo '<b></b><br><input type="radio" name="radio0" value="1" id="1"><br><input type="radio" name="radio0" value="2" id="2"><br><input type="radio" name="radio0" value="3" id="3"><br><br>';
?>
<button type="submit" name="f_other">ALTE INTREBARI</a></button> <button type="submit" name="f_check" style="width: 20%">PASUL URMATOR</a></button>
</form>
</div>