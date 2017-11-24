<?php

	session_start();
	require_once "../settings.php";	
	require_once UTILS_PATH . "utils.php";	
	require_once MODELS_PATH . "class_CSudoku.php";
	require_once MODELS_PATH . "class_CUsers.php";
	require_once MODELS_PATH . "class_CSudokuLeaders.php";

	$Connection = SetConnection();
	
	$s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
	$user = json_decode($s, true);

	if ($user["email"]){
	
		
		$User = CUsers::getUserByEmail($Connection, $user["email"]);
		
		if (!$User->id){
			$User = new CUsers($Connection);
			$User->name = iconv('utf-8', 'windows-1251', $user["first_name"]);
			$User->surname = iconv('utf-8', 'windows-1251', $user["last_name"]);
			$User->email = $user["email"];
			$User->photo = $user["photo_big"];
			$User->user_id = $user["profile"];
			$User->save();
			$_SESSION["user"] = $User->id;
		}
		else {
			$_SESSION["user"] = $User->id;
		}
	
	}	
	
	if ($_SESSION["user"])
	{
		$User = new CUsers($Connection, $_SESSION["user"]);
	}
	
	$Photo["photo"] = "/sudoku/sudoku.png";
	$newTitle = "������! �������� ����������!";
	$News->name = "������������ �������� ���������� ������ ������ ���������. ��������� ����������� ����. ��������� ����� � ������� ��� �����!";
	require_once SITE_HOME_DIR . "inc/inc_header_konkurs.php";
	$level = loadIntParam("level", 0);
	$nid = loadIntParam("nid", 0);
	
	if ($_SERVER["QUERY_STRING"])
		$querystring = "&" . $_SERVER["QUERY_STRING"];
	else
		$querystring = ""; 
	


	
	$Sudoku = CSudoku::getRandomSudoku($Connection, true, $level, $nid);
	CSudoku::incRating($Connection, $Sudoku["id"]);
	$AllSudoku = CSudoku::getSudokuCnt($Connection);
	
	switch($Sudoku["level"]){
		case "1": $SudokuLevel = "������";break;
		case "2": $SudokuLevel = "�������";break;
		case "3": $SudokuLevel = "�������";break;
		default: $SudokuLevel = "�����������";break;
	}

$data = explode(";", $Sudoku["sudoku"]);
$cntData = sizeof($data);

?>

<style>
a.button16 {
  display: inline-block;
  text-decoration: none;
  padding: 1em;
  outline: none;
  border-radius: 1px;
}
a.button16:hover {
  background-image:
   radial-gradient(1px 45% at 0% 50%, rgba(0,0,0,.6), transparent),
   radial-gradient(1px 45% at 100% 50%, rgba(0,0,0,.6), transparent); 
}
a.button16:active {
  background-image:
   radial-gradient(45% 45% at 50% 100%, rgba(255,255,255,.9), rgba(255,255,255,0)),
   linear-gradient(rgba(255,255,255,.4), rgba(255,255,255,.3));
  box-shadow:
   inset rgba(162,95,42,.4) 0 0 0 1px,
   inset rgba(255,255,255,.9) 0 0 1px 3px;
}
.sudokuName{
	font-size:28px;
	text-align:center;
	padding-top:40px;
	padding-bottom:20px;
}
.external {
	text-align:center;	
}
.external td{
	border: 2px solid black; 
    border-collapse: collapse;
}


.internal td {
	width:50px;
	height:50px;
	border: 1px solid grey; /* ������� ������ ����� */
}
.internal input{
	width:50px;
	height:50px;
	font-size:35px;
	text-align:center
}

.sudokumenu{
	height:50px;
	font-size:35px;
	text-align:center
}

</style>

<script type="text/javascript" src="/js/jquery.mask.min.js"></script>

<script type="text/javascript">
jQuery(function($){
   $("input").mask("9");
});
</script>	

<div align="center">
<table width=1200>
<tr><td colspan=3 align="center"><?= showBanner($AllBanners, 5); ?></td></tr>
<tr><td colspan=3 align="center">&nbsp;</td></tr> 
<tr><td align="center" width="50%" valign="top" colspan=2>
<div style="float:left;margin-right:10px;">
<script type="text/javascript" src="//vk.com/js/api/openapi.js?146"></script>

<!-- VK Widget -->
<div id="vk_groups"></div>
<script type="text/javascript">
VK.Widgets.Group("vk_groups", {mode: 3}, 151541012);
</script><br /><br />

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.10&appId=1544122475830555";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-follow" data-href="https://www.facebook.com/%D0%A1%D1%83%D0%B4%D0%BE%D0%BA%D1%83-1754453188186803/" data-width="202" data-height="71" data-layout="standard" data-size="small" data-show-faces="true"></div>

</div>

<div align="justify" style="height:300px;overflow:scroll;">
<strong>01-11-2017</strong> ����� ��� ���� ����� ���������������, ������ � <a href="https://www.freegamesplay.ru/sudoku/list.php">����� ������</a> ����� �������� �� ������, ������� �� ����� ��� ���������.    <br /><hr><br />
<strong>31-10-2017</strong> ��, �������, ������� <a href="/sudoku/personal.php">����������</a>. ������ ��� ������������������ ������������ ����� ����� � ������ ���������� � ���������� ���� ����������: ������� ������ ����������, ������� ������ ������� �� ���� ������ � �� ������� ������. ����� �� ������� ������� � ����� ������ �� ��������� ������� � ������ ��������. ����� ����, ����� ���������� ����� ������� �� ������� ������������. ����� � ���������� �������� ����� �� ������ � ��������� �������������. �� ��� ������, ����� ��� ���� ���������� ������! ����������� � ����! <br /><hr><br />
<strong>30-10-2017</strong> � ��� ����� ����������. �� ������� ��� ���� ������� - "������ ������". ������ ����������� �� ����� ���������� ���������� ��������� ������. ���������� � ������� ����� ��������� � ������ �������� ������������ � ����� �������� ���� ���������. <br /><hr><br />
<strong>22-10-2017</strong> ������! ����� ��������� ���, ��� �� ������ ���� ������� � ����� ������ ������. �� ���� ���������� �� ���������� ������. ������ <a href="/sudoku/list.php">�������� �� ������</a> � �������� �������������! <br /><hr><br />
<strong>22-09-2017</strong> �� ������� ����������� ������� ������ ������� ������. ������ ��������� ����� ������� ������ ������������ ������, ����� �� ����������� ������ ������ ��� ���! <br /><hr><br />
<strong>09-09-2017</strong> - ������! �� �������� ������� �����������. ������ �������������� � ���� ����� �� ����� ���������� ����. �������� ����� ����������� �������� ����������� �����. � ���������, �� ������������, ������� ������������������ �����, ���������������� ��� ����������� �����. � ����� � ���� � ��� ���������� ����������� ������ ��������. �� �� ����� ����������. ����� ����, ��� �� ������������� ��� ����� ��������, �� ���������� ���� ������ � ��������� ��� ���� � ����� �������. ���� ������ �� ����� ��������!<br /><hr><br />
<strong>25-08-2017</strong> ������! �� ������� ����������� ����� �������! ������ �� ������� ����� �������������� � ����� ������� ����� �����! � ���������� �� ������� ����������� ��������� ��������� ����������� ����, ����� ��������� ������ � ������ ���. ����� ���� � ��������� ������� �� ������� ������� ����� ������� �������! ���, ��� �������� ������ ���� ������! �� ��� ����� ��� ���������� ���� ��������������. ����������� ������ ����� ��������� ������. ����������� ����� ������� ���������� ���� ����� ����������� ���� �����! ������� ����������� ��������� �������: �� ������������ ������� ������ �� ��������� ���� ����, �������� - 3, �������� - 5. ��� ������ �����, ��� ���� ��� �������!
</div>
</td><td width="50%" align="center">
<div id="mc-container" align="center" style="width:70%;overflow: scroll;width:80%;height:300px;"></div>
<script type="text/javascript">
cackle_widget = window.cackle_widget || [];
cackle_widget.push({widget: 'Comment', id: 53339, channel: "sudoku-<?= $nid ?>"});
(function() {
    var mc = document.createElement('script');
    mc.type = 'text/javascript';
    mc.async = true;
    mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mc, s.nextSibling);
})();
</script>
</td>
</tr>
<tr><td colspan=3">
<div align="center">

			<div onclick="yaCounter44873380.reachGoal('shareGame');return true;" class="razdel2">����������� ����? �������� � ��� �������!<br /><br />
<script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
<script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,viber,whatsapp" data-counter=""></div>
</div> 
</div>
</td></tr>
<tr><td colspan=3>
<div style="text-align:center;background: rgba(187,79,42,.2); padding: 1px 5px;">

<? include SITE_HOME_DIR . "/sudoku/inc_menu.php"; ?>


</div>
</td></tr>
<tr><td valign="top">

<div align="center">
<br /><br />
<div align="center"><strong>������ ��� ������������ ������ �<?= $Sudoku["id"] ?><br /><br /></strong></div>
<br /><Br />

<table width=300px; class="leadersudoku">
<tr><th>�</th><th>���</th><th>�����</th></tr>
<?php 
$SudokuLeaders = CSudokuLeaders::getSudokuLeadersList($Connection, $Sudoku["id"]);
$SudokuTable  = CSudokuLeaders::getSudokuLeadersTable($Connection);
if (sizeof($SudokuLeaders)>0){



foreach ($SudokuTable as $place => $CurSudokuTable)
{
	
	$array[$CurSudokuTable["id"]] = $place+1;
	
}

	foreach ($SudokuLeaders as $CurSudokuLeader){

		$cntLeaders++;
		if ($CurSudokuLeader->user_id && $CurSudokuLeader->user_id <> "NULL")
		{
			$SudokuLeader = new CUsers($Connection, $CurSudokuLeader->user_id);
			$Leader = $SudokuLeader->name; 
			$cnt  = CSudokuLeaders::getSudokuLeadersCnt($Connection, false, $CurSudokuLeader->user_id);
//echo ' --- ' . $CurSudokuLeader->user_id;
			//$place = array_keys($SudokuTable, $CurSudokuLeader->user_id);
			?>
			<tr><td><?= $cntLeaders ?>.</td><td>
				<abbr  title="<?= $Leader ?> <br /><br />������������������ �����<br /><br />�������� <?= $cnt ?> ������. <br /><Br />��������� �� <?= $array[$CurSudokuLeader->user_id] ?> ����� � ����� ������!"  rel="tooltip">
					<a href="/sudoku/personal/<?= $SudokuLeader->id ?>/">
						<span style="color:#ee5931;"><?= $Leader ?></span>
					</a>
				</abbr> 
			</td><Td> <?= showBestTime($CurSudokuLeader->results) ?></td></tr>
			<?php 
		}
		else 
		{
			$Leader = $CurSudokuLeader->name;
			?>
			<tr><td><?= $cntLeaders ?>.</td><td><?= $Leader ?> </td><Td> <?= showBestTime($CurSudokuLeader->results) ?></td></tr>
			<?php 			
		}
		

	}
}
else{
	?>
	<tr><Td colspan=3>������� ������, ��� ������� � ��������� ����� ������� ������.</td></tr>
	<?php 
}
	
?>
</table>
</div>
</td>
<?php 
$now = strftime("%a", time());
if ($now == "Mon") 
	$week_start = date("Y-m-d 00:00:00");
else
	$week_start = date("Y-m-d 00:00:00", strtotime("last Monday"));

$week_end = date("Y-m-d 23:59:59", strtotime("next Sunday"));
?>

<td valign="top">
<div align="center">
<br /><br />
<div align="center"><strong>������ ������ (������������������) <br /> � <?= substr($week_start, 0, strlen($week_start)-9) ?> �� <?= substr($week_end, 0, strlen($week_end)-9) ?></strong></div>
<br /><Br />
<table width=200px; class="leadersudoku">
<tr><th>�����</th><th>���</th><th>����������� ������</th><th>�������</th></tr>
<?php 

$SudokuWeekTable = CSudokuLeaders::getSudokuWeekLeaders($Connection, $week_start, $week_end);

foreach ($SudokuWeekTable as $CurSudokuTable)
{

	$cntPlace++;
	$CurUser = new CUsers($Connection, $CurSudokuTable->user_id);
	
	if ($cntPlace > 10){
		break;
	}

		?>
				<tr>
					<td><?= $cntPlace ?>.</td>
					<td width=40%><span>
				<abbr  title="<?= $CurUser->name ?> <br /><br />������������������ ����� <br /><br />������� �� ��� ��� �������� �� �������� ����������!"  rel="tooltip">
					<a href="/sudoku/personal/<?= $CurUser->id ?>/">
						<?= $CurUser->name ?>
					</a>
				</abbr>				
					</span></td>
					<td width=25%> <?= $CurSudokuTable->cnt ?></td>
					<td width=25%> <?= $CurSudokuTable->points ?></td>					
				</tr>
				<?php 


}
?>
</table>
</td>


<td valign="top">
<div align="center">
<br /><br />
<div align="center"><strong>������ � ����� ������ (������������������)</strong><br /><br /></div>
<br /><Br />
<table width=200px; class="leadersudoku">
<tr><th>�����</th><th>���</th><th>����������� ������</th><th>�������</th></tr>
<?php 

foreach ($SudokuTable as $place => $CurSudokuTable)
{

	$cntLeaders++;
	$cntLeaderAll++;
	

		?>
				<tr>
					<td><?= $place+1 ?>.</td>
					<td width=40%>
				<abbr  title="<?= $CurSudokuTable["name"] ?> <br /><br />������������������ ����� <br /><br />������� �� ��� ��� �������� �� �������� ����������!"  rel="tooltip">
					<a href="/sudoku/personal/<?= $CurSudokuTable["id"] ?>/">
						<span><?= $CurSudokuTable["name"] ?></span>
					</a>
				</abbr>					
					</td>
					<td width=25%> <?= $CurSudokuTable["cnt"] ?></td>
					<td width=25%> <?= $CurSudokuTable["rating"] ?></td>					
				</tr>
				<?php 

if ($cntLeaderAll > 9)
	break;
}
?>
</table>
</td></tr>
<tr><td colspan=3>

</td></tr></table>
<table width=1200> 
<tr><td colspan=5 align="center">&nbsp;</td></tr>
<tr><td width="350" valign="top">
<div class="sudokuName">������ �<?= $Sudoku["id"] ?> �� <?= $AllSudoku?></div>
<div align="center"><strong>������:</strong> <?= $Sudoku["visited"] ?> ���.</div>
<div align="center"><strong>��������:</strong> <?= $Sudoku["wins"]?> ���.</div>
<div align="center"><strong>������ �����:</strong> <?= showBestTime($Sudoku["besttime"])?> </div>
<div align="center"><strong>�������:</strong> <?= $SudokuLevel ?>. </div>
<div align="center"><strong>���������� ��������:</strong> <?= 81-$cntData ?> ����.</div>
<br /><br />
<div class="timer" align="center"></div>
<div class="sudokuRules">
<div align="justify">&nbsp;&nbsp;������! ����� ����������� � ����� ��������, ��� ����� �������������� � ������� ��������. ��� �������� ��������� � ����������� ���� ����������. ����� ������ ����������� ������ ��������. � ���� ������ ���� ���������� ����� ����� ��������� � �� ������ ���� �������� ������ ���������, �� ���������� ������������� �� ����� � �� �� ������� ����������� � ����� ��������.</div>
<div align="justify">&nbsp;&nbsp;�������� ���, ��� �� ������ ����������� ������� ������ �� ��������� 5 �����. �� ������� ������ - 3 ����. �� ������ ������ - 1 ����. </div>

</div>
</td>

<td width="30">&nbsp;</td><td width=500 valign="top">
<form name="sudoku" id="sudoku">
<table class="external" >
<tr>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="111"></td><td><input type="text" id="112"></td><td><input type="text" id="113"></td></tr>
  	<tr><td><input type="text" id="121"></td><td><input type="text" id="122"></td><td><input type="text" id="123"></td></tr>
  	<tr><td><input type="text" id="131"></td><td><input type="text" id="132"></td><td><input type="text" id="133"></td></tr>
  </table>
</td>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="214"></td><td><input type="text" id="215"></td><td><input type="text" id="216"></td></tr>
  	<tr><td><input type="text" id="224"></td><td><input type="text" id="225"></td><td><input type="text" id="226"></td></tr>
  	<tr><td><input type="text" id="234"></td><td><input type="text" id="235"></td><td><input type="text" id="236"></td></tr>
  </table>
</td>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="317"></td><td><input type="text" id="318"></td><td><input type="text" id="319"></td></tr>
  	<tr><td><input type="text" id="327"></td><td><input type="text" id="328"></td><td><input type="text" id="329"></td></tr>
  	<tr><td><input type="text" id="337"></td><td><input type="text" id="338"></td><td><input type="text" id="339"></td></tr>
  </table>
</td>
</tr>
<tr>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="441"></td><td><input type="text" id="442"></td><td><input type="text" id="443"></td></tr>
  	<tr><td><input type="text" id="451"></td><td><input type="text" id="452"></td><td><input type="text" id="453"></td></tr>
  	<tr><td><input type="text" id="461"></td><td><input type="text" id="462"></td><td><input type="text" id="463"></td></tr>
  </table>
</td>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="544"></td><td><input type="text" id="545"></td><td><input type="text" id="546"></td></tr>
  	<tr><td><input type="text" id="554"></td><td><input type="text" id="555"></td><td><input type="text" id="556"></td></tr>
  	<tr><td><input type="text" id="564"></td><td><input type="text" id="565"></td><td><input type="text" id="566"></td></tr>
  </table>
</td>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="647"></td><td><input type="text" id="648"></td><td><input type="text" id="649"></td></tr>
  	<tr><td><input type="text" id="657"></td><td><input type="text" id="658"></td><td><input type="text" id="659"></td></tr>
  	<tr><td><input type="text" id="667"></td><td><input type="text" id="668"></td><td><input type="text" id="669"></td></tr>
  </table>
</td>
</tr>
<tr>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="771"></td><td><input type="text" id="772"></td><td><input type="text" id="773"></td></tr>
  	<tr><td><input type="text" id="781"></td><td><input type="text" id="782"></td><td><input type="text" id="783"></td></tr>
  	<tr><td><input type="text" id="791"></td><td><input type="text" id="792"></td><td><input type="text" id="793"></td></tr>
  </table>
</td>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="874"></td><td><input type="text" id="875"></td><td><input type="text" id="876"></td></tr>
  	<tr><td><input type="text" id="884"></td><td><input type="text" id="885"></td><td><input type="text" id="886"></td></tr>
  	<tr><td><input type="text" id="894"></td><td><input type="text" id="895"></td><td><input type="text" id="896"></td></tr>
  </table>
</td>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="977"></td><td><input type="text" id="978"></td><td><input type="text" id="979"></td></tr>
  	<tr><td><input type="text" id="987"></td><td><input type="text" id="988"></td><td><input type="text" id="989"></td></tr>
  	<tr><td><input type="text" id="997"></td><td><input type="text" id="998"></td><td><input type="text" id="999"></td></tr>
  </table>
</td>
</tr>
</table>
</form>
</td><td width="30">&nbsp;</td><td width=350 valign="top">




<br /><Br />
<?php 
	if (!$User->id)
	{
?>
	<div class="social" align="center"><strong>������������� ��� ������� � ����� �������� ����� ���� �� ���������� ����� � ������� ������ ����. </strong><br /><br />
		<script src="//ulogin.ru/js/ulogin.js"></script>
		<div id="uLogin" data-ulogin="display=panel;theme=classic;fields=first_name,last_name,email,photo_big;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=https%3A%2F%2Fwww.freegamesplay.ru%2Fsudoku%2Findex.php;mobilebuttons=0;"></div>
	</div>
<?php 
		
	}
	else{
		?>
			<div align="center">������������, <?= $User->name ?></div>
		<?php 
	}
	?>	
	<br /><br />
	<div align="center">
<span id="timer" style="font-size:20px;"></span>
</div>
	<br /><Br />
<div align="center">��������� ���� ������ ������!</div>
<br />
<div align="center"><?php drawRatingSudoku($Sudoku["id"], $Sudoku); ?></div>
<br />
<br /><br />

<div align="center" style="font-size:20px;">
<a href="<?= SITE_HOME_URL?>sudoku/list.php"><strong>������� ������</strong></a><br />
<a href="/sudoku/personal.php"><strong>����������</strong></a><br />
<a href="/sudoku/index.php"><strong>��������� ������</strong></a><br />
<a href="/sudoku/index.php?level=1"><strong>������</strong></a><br />
<a href="/sudoku/index.php?level=2"><strong>�������</strong></a><br />
<a href="/sudoku/index.php?level=3"><strong>�������</strong></a>
</div>


















       </td></tr>
<tr><td colspan=5 align="center"><?= showBanner($AllBanners, 8); ?></td></tr>
<tr><td align="center">&nbsp;</td><td colspan=3"><div align="justify">



</td></tr>

<tr><td colspan=5 align="center">

</td></tr>       
       
       </table>
</div>

<script>
var gameId = <?= $Sudoku["id"] ?>;
var uid = "<?= $User->id ?>";
var q1 = new Array(), q2 = new Array(), q3 = new Array(), q4 = new Array(), q5 = new Array(), q6 = new Array(), q7 = new Array(), q8 = new Array(), q9 = new Array();
var h1 = new Array(), h2 = new Array(), h3 = new Array(), h4 = new Array(), h5 = new Array(), h6 = new Array(), h7 = new Array(), h8 = new Array(), h9 = new Array();
var v1 = new Array(), v2 = new Array(), v3 = new Array(), v4 = new Array(), v5 = new Array(), v6 = new Array(), v7 = new Array(), v8 = new Array(), v9 = new Array();

<?php 

foreach ($data as $curData)
{
	$arr = explode(":",$curData);
	
	?>$("#<?= $arr[1] ?>").val("<?= $arr[0] ?>").css("backgroundColor", "#8bf79c").attr("disabled", true);	
	<?php 
	
}
?>


	</script>
	<script type="text/javascript" src="/js/sudoku.js"></script>
	

<?php 
	require_once SITE_HOME_DIR . "inc/inc_footer_konkurs.php";