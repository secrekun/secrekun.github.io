<?php
class Config {
	private static $instance;
	public static $g_con;
	public static $_url = array();
	private static $_pages = array(
		'login','banlist','stafflist','online','donate','profile','logout','admin','bugreport','search','character','register','acp','changepass','changemail','changeskin',
		'fpk','exclude','seebug'
	);
	public static $faction = array(),$job = array(),$vehColors = array(),$reg_questions = array(),$save_questions = array();
	private static $_perPage = 20;
	public static $_PAGE_URL = 'http://hard-roleplay.com/';

	private function __construct() {
		$db['mysql'] = array(
			'host' 		=> 	'93.119.26.250',
			'username' 	=> 	'zp_hid6062',
			'password' 	=> 	'SWKhPhbdDQPCjGD5',
			'dbname' 	=> 	'zp_hid6062'
		);

		try {
			self::$g_con = new PDO('mysql:host='.$db['mysql']['host'].';dbname='.$db['mysql']['dbname'].';charset=utf8',$db['mysql']['username'],$db['mysql']['password']);
			self::$g_con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			self::$g_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		self::_getUrl();
		self::arrays();
	}
	
	public static function init()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public static function isLogged() {
		return isset($_SESSION['user']) ? true : false;
	}
	public static function isRegStep() {
		return isset($_SESSION['register']) ? true : false;
	}
	

	private static function _getUrl() {
		$url = isset($_GET['page']) ? $_GET['page'] : null;
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        self::$_url = explode('/', $url);
	}
	
	public static function getContent() {
		if(self::$_url[0] === 'car') { include 'inc/pages/car.p.php'; return; }
		include_once 'inc/header.inc.php';
		if(in_array(self::$_url[0],self::$_pages) && self::$_url[0] !== 'admin') 
			include 'inc/pages/' . self::$_url[0] . '.p.php';
		else if(self::$_url[0] === 'admin' && isset(self::$_url[1])) 		
			include 'inc/pages/admin/' . self::$_url[1] . '.a.php';
		else if(self::$_url[0] === 'admin' && !isset(self::$_url[1])) 
			include 'inc/pages/admin/index.a.php';
		else 
			include_once 'inc/pages/index.p.php'; 
		include_once 'inc/footer.inc.php';	
	}
	
	public static function rows($table) {
		if(is_array($table)) {
			$rows = 0;
			foreach($table as $val) {
				$q = self::$g_con->prepare("SELECT * FROM `".$val."`");
				$q->execute();
				$rows += $q->rowCount();
			}
			return $rows;
		}
		$q = self::$g_con->prepare("SELECT * FROM `".$table."`");
		$q->execute();
		return $q->rowCount();
	}

	public static function _pagLimit() {
		if(!isset(self::$_url[2]))
			self::$_url[2] = 1;
		return "LIMIT ".((self::$_url[2] * self::$_perPage) - self::$_perPage).",".self::$_perPage;
	}

	public static function createTable($table,$table_content = array()) {
		$q = self::$g_con->prepare("SELECT * FROM `".$table."`".Config::init()->_pagLimit());
		$q->execute();
		if($q->rowCount()) {
			echo '<div class="table-responsive"><table id="'.$table.'-table" class="table table-striped table-bordered table-hover"><thead><tr>';
			foreach($table_content as $key=>$content) {
					echo '<th><center>' . $key . '</center></th>';
			}
			echo '</thead><tbody>';				
			while($row = $q->fetch()) {
				echo '<tr>';
				foreach($table_content as $key=>$content) {
					foreach($content as $action) {
						if(is_callable($action)) {
							echo '<td class="'.$content['func_row'].$row['id'].'"><center>';
							call_user_func($action,$row[$content['func_row']]); 
							continue;
						}
						if(isset($content['func_row'])) continue;
						preg_match_all('/{\w*}/',$action, $actions);
						foreach($actions[0] as $acc) {
							$action = str_replace($acc,$row[str_replace('}','',str_replace('{','',$acc))],$action);
						}
						echo '<td class="'.str_replace('}','',str_replace('{','',$acc)).$row['id'].'"><center>' .$action;
					}	
					echo '</center></td>';
				}
				echo '</tr>';
			}
			echo '</tbody></table></div>';
			echo self::init()->_pagLinks(self::init()->rows($table));
		} else echo self::Message('danger','There are no rows in this table.','center');
	}

	public static function _pagLinks($rows) {
		if(!isset(self::$_url[2]))
			self::$_url[2] = 1;
		$adjacents = "2";
		$prev = self::$_url[2] - 1;
		$next = self::$_url[2] + 1;
		$lastpage = ceil($rows/self::$_perPage);
		$lpm1 = $lastpage - 1;

		$pagination = "";
		if($lastpage > 1)
		{   
			if ($lastpage < 7 + ($adjacents * 2))
			{   
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == self::$_url[2])
						$pagination.= "<a class='btn btn-default' type='button' href='#'>$counter</a>";
					else
						$pagination.= "<a class='btn btn-default' type='button' href='".self::$_PAGE_URL.self::$_url[0]."/page/$counter'>$counter</a>";                   
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))
			{
				if(self::$_url[2] < 1 + ($adjacents * 2))       
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == self::$_url[2])
							$pagination.= "<a class='btn btn-default' type='button' href='#'>$counter</a>";
						else
							$pagination.= "<a class='btn btn-default' type='button' href='".self::$_PAGE_URL.self::$_url[0]."/page/$counter'>$counter</a>";                   
					}
					$pagination.= "<a class='btn btn-default' type='button' href='#'>...</a>";
					$pagination.= "<a class='btn btn-default' type='button' href='".self::$_PAGE_URL.self::$_url[0]."/page/$lpm1'>$lpm1</a>";
					$pagination.= "<a class='btn btn-default' type='button' href='".self::$_PAGE_URL.self::$_url[0]."/page/$lastpage'>$lastpage</a>";       
				}
				elseif($lastpage - ($adjacents * 2) > self::$_url[2] && self::$_url[2] > ($adjacents * 2))
				{
					$pagination.= "<a class='btn btn-default' type='button' href='".self::$_PAGE_URL.self::$_url[0]."/page/1'>1</a>";
					$pagination.= "<a class='btn btn-default' type='button' href='".self::$_PAGE_URL.self::$_url[0]."/page/2'>2</a>";
					$pagination.= "<a class='btn btn-default' type='button' href='#'>...</a>";
					for ($counter = self::$_url[2] - $adjacents; $counter <= self::$_url[2] + $adjacents; $counter++)
					{
						if ($counter == self::$_url[2])
							$pagination.= "<a class='btn btn-default' type='button' href='#'>$counter</a>";
						else
							$pagination.= "<a class='btn btn-default' type='button' href='".self::$_PAGE_URL.self::$_url[0]."/page/$counter'>$counter</a>";                   
					}
					$pagination.= "<a class='btn btn-default' type='button' href='#'>...</a>";
					$pagination.= "<a class='btn btn-default' type='button' href='".self::$_PAGE_URL.self::$_url[0]."/page/$lpm1'>$lpm1</a>";
					$pagination.= "<a class='btn btn-default' type='button' href='".self::$_PAGE_URL.self::$_url[0]."/page/$lastpage'>$lastpage</a>";      
				}
				else
				{
					$pagination.= "<a class='btn btn-default' type='button' href='".self::$_PAGE_URL.self::$_url[0]."/page/1'>1</a>";
					$pagination.= "<a class='btn btn-default' type='button' href='".self::$_PAGE_URL.self::$_url[0]."/page/2'>2</a>";
					$pagination.= "<a class='btn btn-default' type='button' href='#'>...</a>";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == self::$_url[2])
							$pagination.= "<a class='btn btn-default' type='button' href='#'>$counter</a>";
						else
							$pagination.= "<a class='btn btn-default' type='button' href='".self::$_PAGE_URL.self::$_url[0]."/page/$counter'>$counter</a>";                   
					}
				}
			}
		}
		return $pagination;
	}
	
	public static function getPlayerData($name,$data) {
		$q = self::$g_con->prepare("SELECT `".$data."` FROM `accounts` WHERE `username` = '$name'");
		$q->execute();
		if($q) {
			$udata = $q->fetch();
			return $udata[$data];
		}	
		else return 0;
	}
	public static function getPlayerDataID($name,$data) {
		$q = self::$g_con->prepare("SELECT `".$data."` FROM `accounts` WHERE `id` = '$name'");
		$q->execute();
		if($q) {
			$udata = $q->fetch();
			return $udata[$data];
		}	
		else return 0;
	}
	
	public static function getPlayerCharData($name,$data) {
		$q = self::$g_con->prepare("SELECT `".$data."` FROM `characters` WHERE `username` = '$name'");
		$q->execute();
		if($q) {
			$udata = $q->fetch();
			return $udata[$data];
		}	
		else return 0;
	}
	public static function getPlayerCharDataID($name,$data) {
		$q = self::$g_con->prepare("SELECT `".$data."` FROM `characters` WHERE `id` = $name");
		$q->execute();
		if($q) {
			$udata = $q->fetch();
			return $udata[$data];
		}	
		else return 0;
	}
	
	public static function RandQuest($min, $max) {
		ob_start();
		$value = ob_get_clean();
		$numbers = range($min, $max);
		shuffle($numbers);
		$value = join(',', $numbers);
		return $value;
	}
	public static function getBugData($table,$name,$data) {
		$q = self::$g_con->prepare("SELECT `".$data."` FROM `".$table."` WHERE `id` = '$name'");
		$q->execute();
		if($q) {
			$udata = $q->fetch();
			return $udata[$data];
		}	
		else return 0;
	}
	public static function getData($table,$name,$data) {
		$q = self::$g_con->prepare("SELECT `".$data."` FROM `".$table."` WHERE `username` = '$name'");
		$q->execute();
		if($q) {
			$udata = $q->fetch();
			return $udata[$data];
		}	
		else return 0;
	}

	public static function _getPage() {
		return self::$_url[0];
	}

	public static function getPage() {
		return isset(self::$_url[2]) ? self::$_url[2] : 1;
	}
	
	private static function arrays() {
		self::$reg_questions = array(
			0 => 'Cum se foloseste chat-ul OOC?',
			1 => 'Care dintre /me-urile de mai jos este corect?',
			2 => 'Care dintre /do-urile de mai jos este corect?',
			3 => 'Care dintre urmatoarle actiuni NU este PowerGaming?',
			4 => 'Care dintre urmatoarele situati NU este Non-rp?',
			5 => 'Care este diferenta dintre chat-ul IC si cel OOC?',
			6 => 'Care dintre stituatiile urmatoare este Metagaming?',
			7 => 'Care dintre situatiile urmatoare este Mixing?',
			8 => 'Cum pot obtine ajutorul unui admin in joc?',
			9 => 'Cum pot afla numarul unei persoane de telefon?',
			10 => 'Cum pot afla daca se recruteaza officeri sau medici?',
			11 => 'Cum ma comport intr-o actiune la care sunt jefuit?',
			12 => 'Care dintre situatiile de mai jos este Rp-Fear?'
		);
		self::$faction = array(
			-1 => 'Civil',
			0 => 'LSPD',
			4 => 'San News',
			6 => 'EMS'
		);
		
		self::$job = array(
			-1 => 'Somer',
			0 => 'Trash Cleaner',
			1 => 'Drug Dealer',
			2 => 'Arm Dealer',
			3 => 'Taxi Driver',
			5 => 'Mecanic',
			8 => 'PizzaMan',
			9 => 'Crafter'
		);
		self::$vehColors = array(
			'#000000', '#F5F5F5', '#2A77A1', '#840410', '#263739', '#86446E', '#D78E10', '#4C75B7', '#BDBEC6', '#5E7072',
			'#46597A', '#656A79', '#5D7E8D', '#58595A', '#D6DAD6', '#9CA1A3', '#335F3F', '#730E1A', '#7B0A2A', '#9F9D94',
			'#3B4E78', '#732E3E', '#691E3B', '#96918C', '#515459', '#3F3E45', '#A5A9A7', '#635C5A', '#3D4A68', '#979592',
			'#421F21', '#5F272B', '#8494AB', '#767B7C', '#646464', '#5A5752', '#252527', '#2D3A35', '#93A396', '#6D7A88',
			'#221918', '#6F675F', '#7C1C2A', '#5F0A15', '#193826', '#5D1B20', '#9D9872', '#7A7560', '#989586', '#ADB0B0',
			'#848988', '#304F45', '#4D6268', '#162248', '#272F4B', '#7D6256', '#9EA4AB', '#9C8D71', '#6D1822', '#4E6881',
			'#9C9C98', '#917347', '#661C26', '#949D9F', '#A4A7A5', '#8E8C46', '#341A1E', '#6A7A8C', '#AAAD8E', '#AB988F',
			'#851F2E', '#6F8297', '#585853', '#9AA790', '#601A23', '#20202C', '#A4A096', '#AA9D84', '#78222B', '#0E316D',
			'#722A3F', '#7B715E', '#741D28', '#1E2E32', '#4D322F', '#7C1B44', '#2E5B20', '#395A83', '#6D2837', '#A7A28F',
			'#AFB1B1', '#364155', '#6D6C6E', '#0F6A89', '#204B6B', '#2B3E57', '#9B9F9D', '#6C8495', '#4D8495', '#AE9B7F',
			'#406C8F', '#1F253B', '#AB9276', '#134573', '#96816C', '#64686A', '#105082', '#A19983', '#385694', '#525661',
			'#7F6956', '#8C929A', '#596E87', '#473532', '#44624F', '#730A27', '#223457', '#640D1B', '#A3ADC6', '#695853',
			'#9B8B80', '#620B1C', '#5B5D5E', '#624428', '#731827', '#1B376D', '#EC6AAE', '#000000',
			'#177517', '#210606', '#125478', '#452A0D', '#571E1E', '#010701', '#25225A', '#2C89AA', '#8A4DBD', '#35963A',
			'#B7B7B7', '#464C8D', '#84888C', '#817867', '#817A26', '#6A506F', '#583E6F', '#8CB972', '#824F78', '#6D276A',
			'#1E1D13', '#1E1306', '#1F2518', '#2C4531', '#1E4C99', '#2E5F43', '#1E9948', '#1E9999', '#999976', '#7C8499',
			'#992E1E', '#2C1E08', '#142407', '#993E4D', '#1E4C99', '#198181', '#1A292A', '#16616F', '#1B6687', '#6C3F99',
			'#481A0E', '#7A7399', '#746D99', '#53387E', '#222407', '#3E190C', '#46210E', '#991E1E', '#8D4C8D', '#805B80',
			'#7B3E7E', '#3C1737', '#733517', '#781818', '#83341A', '#8E2F1C', '#7E3E53', '#7C6D7C', '#020C02', '#072407',
			'#163012', '#16301B', '#642B4F', '#368452', '#999590', '#818D96', '#99991E', '#7F994C', '#839292', '#788222',
			'#2B3C99', '#3A3A0B', '#8A794E', '#0E1F49', '#15371C', '#15273A', '#375775', '#060820', '#071326', '#20394B',
			'#2C5089', '#15426C', '#103250', '#241663', '#692015', '#8C8D94', '#516013', '#090F02', '#8C573A', '#52888E',
			'#995C52', '#99581E', '#993A63', '#998F4E', '#99311E', '#0D1842', '#521E1E', '#42420D', '#4C991E', '#082A1D',
			'#96821D', '#197F19', '#3B141F', '#745217', '#893F8D', '#7E1A6C', '#0B370B', '#27450D', '#071F24', '#784573',
			'#8A653A', '#732617', '#319490', '#56941D', '#59163D', '#1B8A2F', '#38160B', '#041804', '#355D8E', '#2E3F5B',
			'#561A28', '#4E0E27', '#706C67', '#3B3E42', '#2E2D33', '#7B7E7D', '#4A4442', '#28344E'
		);
	}
	
}
?>
<link rel="stylesheet" href="<?php echo Config::$_PAGE_URL ?>css/font-awesome.min.css">