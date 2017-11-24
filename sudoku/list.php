<?php
	session_start();
	require_once "../settings.php";	
	require_once UTILS_PATH . "utils.php";	
	require_once MODELS_PATH . "class_CSudoku.php";
	require_once MODELS_PATH . "class_CUsers.php";
	require_once MODELS_PATH . "class_CSudokuLeaders.php";

		
	$Connection = SetConnection();
	$Photo["photo"] = "/sudoku/sudoku.png";
	$newTitle = "Судоку! Японские кроссворды!";
	$News->name = "Разгадывайте японские кроссворды Судоку разной сложности. Постоянно пополняемая база. Проведите время с пользой для мозга!";
	require_once SITE_HOME_DIR . "inc/inc_header_konkurs.php";
	$page = LoadIntParam("page", 0);


?>

<style>
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
	width:20px;
	height:20px;
	border: 1px solid grey; /* Граница вокруг ячеек */
}
.internal input{
	width:20px;
	height:20px;
	font-size:15px;
	text-align:center
}

.listSudoku td {
	border: 1px solid black;
}

.listSudoku td table td{
	border: 0px;
}
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
</style>

<script type="text/javascript" src="/js/jquery.mask.min.js"></script>

<script type="text/javascript">
jQuery(function($){
   $("input").mask("9");
});
</script>	

<div align="center">
<table width=1200>
<tr><td colspan=5 align="center"><?= showBanner($AllBanners, 9); ?></td></tr>
<tr><td colspan=5 align="center">&nbsp;</td></tr> 
<tr><td align="center">&nbsp;</td><td colspan=3">
<br /><Br />
<div align="center">

			<div onclick="yaCounter44873380.reachGoal('shareGame');return true;" class="razdel2">Нравится судоку? Расскажи о ней друзьям!<br /><br />
<script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
<script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
<div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,viber,whatsapp" data-counter=""></div>
</div> 
<br /><Br />
<div style="background: rgba(187,79,42,.2); padding: 1px 5px;">
<? include SITE_HOME_DIR . "/sudoku/inc_menu.php"; ?>
</div>
<br /><Br />
</div>
</td></tr>
</td></tr></table>
<table width=1200 class="listSudoku">
<?php 

$SudokuTable  = CSudokuLeaders::getSudokuLeadersTable($Connection);
foreach ($SudokuTable as $place => $CurSudokuTable)
{

	$array[$CurSudokuTable["id"]] = $place+1;

}

$SudokuList = CSudoku::getSudokuList($Connection);

$countNews = count($SudokuList);
foreach ($SudokuList as $curSudoku)
{
	
	$sud++;
	$count++;


	
	if (($count > $page*10) and ($count <= ($page+1)*10))
	{
		
		switch($curSudoku->level){
			case "1": $SudokuLevel = "Легкий";break;
			case "2": $SudokuLevel = "Средний";break;
			case "3": $SudokuLevel = "Сложный";break;
			default: $SudokuLevel = "Неопределен";break;
		}
		
		$data = explode(";", $curSudoku->sudoku);
		$cntData = sizeof($data);
		if ($_SESSION["user"]){
			$SudokuWin = CSudokuLeaders::getSudokuLeadersByUserAndSudoku($Connection, $curSudoku->id, $_SESSION["user"]);
			if (count($SudokuWin) > 0){
				$backgroundcolor = "background-color:#fda27e;";
			}
			else {
				$backgroundcolor = "";
			}
		}
		
?>
<tr style="<?= $backgroundcolor ?>border: 1px solid grey;cursor: pointer;" title="Играть Судоку №<?= $curSudoku->id ?>" onclick="window.location.href='index.php?nid=<?=$curSudoku->id ?>'">
<td width=40%>
<div align="center"><strong>Судоку № - <?= $curSudoku->id ?></strong></div>
<div align="center"><strong>Сыгран:</strong> <?= $curSudoku->visited ?> раз.</div>
<div align="center"><strong>Разгадан:</strong> <?= $curSudoku->wins ?> раз.</div>
<div align="center"><strong>Лучшее время:</strong> <?= showBestTime($curSudoku->besttime)?> </div>
<div align="center"><strong>Уровень:</strong> <?= $SudokuLevel ?>. </div>
<div align="center"><strong>Необходимо отгадать:</strong> <?= 81-$cntData ?> цифр.</div>
<div align="center"><strong>Рейтинг судоку:</strong> <?= round($curSudoku->mark / $curSudoku->voted,1) ?> (<?= $curSudoku->voted?>)</div>

</td><td width=40% align="center">
<table width=300px;>
<?php 
$cntLeaders = 0;
$SudokuLeaders = CSudokuLeaders::getSudokuLeadersList($Connection, $curSudoku->id);

if (sizeof($SudokuLeaders)>0){

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
						<abbr  title="<?= $Leader ?> <br /><br />Зарегистрированный игрок<br /><br />Разгадал <?= $cnt ?> судоку. <br /><Br />Находится на <?= $array[$CurSudokuLeader->user_id] ?> месте в общем зачете!"  rel="tooltip">
							<a href="/sudoku/personal/<?= $SudokuLeader->id?>/"><span style="color:#ee5931;"><?= $Leader ?></span></a>
						</abbr> 
					</td><Td> <?= showBestTime($CurSudokuLeader->results) ?></td></tr>
					<?php 
				}
				else 
				{		
		?>
		<tr><td><?= $cntLeaders ?>.</td><td><?= $CurSudokuLeader->name ?> </td><Td> <?= showBestTime($CurSudokuLeader->results) ?></td></tr>
		<?php
				} 
	}
}
else{
	?>
	<tr><Td>Станьте первым, кто попадет в лидерский зачет данного Судоку.</td></tr>
	<?php 
}
	
?>
</table>

</td><td width=15%>
<form name="sudoku" id="sudoku">
<table class="external" >
<tr>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="<?= $sud ?>111"></td><td><input type="text" id="<?= $sud ?>112"></td><td><input type="text" id="<?= $sud ?>113"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>121"></td><td><input type="text" id="<?= $sud ?>122"></td><td><input type="text" id="<?= $sud ?>123"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>131"></td><td><input type="text" id="<?= $sud ?>132"></td><td><input type="text" id="<?= $sud ?>133"></td></tr>
  </table>
</td>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="<?= $sud ?>214"></td><td><input type="text" id="<?= $sud ?>215"></td><td><input type="text" id="<?= $sud ?>216"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>224"></td><td><input type="text" id="<?= $sud ?>225"></td><td><input type="text" id="<?= $sud ?>226"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>234"></td><td><input type="text" id="<?= $sud ?>235"></td><td><input type="text" id="<?= $sud ?>236"></td></tr>
  </table>
</td>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="<?= $sud ?>317"></td><td><input type="text" id="<?= $sud ?>318"></td><td><input type="text" id="<?= $sud ?>319"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>327"></td><td><input type="text" id="<?= $sud ?>328"></td><td><input type="text" id="<?= $sud ?>329"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>337"></td><td><input type="text" id="<?= $sud ?>338"></td><td><input type="text" id="<?= $sud ?>339"></td></tr>
  </table>
</td>
</tr>
<tr>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="<?= $sud ?>441"></td><td><input type="text" id="<?= $sud ?>442"></td><td><input type="text" id="<?= $sud ?>443"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>451"></td><td><input type="text" id="<?= $sud ?>452"></td><td><input type="text" id="<?= $sud ?>453"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>461"></td><td><input type="text" id="<?= $sud ?>462"></td><td><input type="text" id="<?= $sud ?>463"></td></tr>
  </table>
</td>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="<?= $sud ?>544"></td><td><input type="text" id="<?= $sud ?>545"></td><td><input type="text" id="<?= $sud ?>546"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>554"></td><td><input type="text" id="<?= $sud ?>555"></td><td><input type="text" id="<?= $sud ?>556"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>564"></td><td><input type="text" id="<?= $sud ?>565"></td><td><input type="text" id="<?= $sud ?>566"></td></tr>
  </table>
</td>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="<?= $sud ?>647"></td><td><input type="text" id="<?= $sud ?>648"></td><td><input type="text" id="<?= $sud ?>649"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>657"></td><td><input type="text" id="<?= $sud ?>658"></td><td><input type="text" id="<?= $sud ?>659"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>667"></td><td><input type="text" id="<?= $sud ?>668"></td><td><input type="text" id="<?= $sud ?>669"></td></tr>
  </table>
</td>
</tr>
<tr>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="<?= $sud ?>771"></td><td><input type="text" id="<?= $sud ?>772"></td><td><input type="text" id="<?= $sud ?>773"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>781"></td><td><input type="text" id="<?= $sud ?>782"></td><td><input type="text" id="<?= $sud ?>783"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>791"></td><td><input type="text" id="<?= $sud ?>792"></td><td><input type="text" id="<?= $sud ?>793"></td></tr>
  </table>
</td>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="<?= $sud ?>874"></td><td><input type="text" id="<?= $sud ?>875"></td><td><input type="text" id="<?= $sud ?>876"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>884"></td><td><input type="text" id="<?= $sud ?>885"></td><td><input type="text" id="<?= $sud ?>886"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>894"></td><td><input type="text" id="<?= $sud ?>895"></td><td><input type="text" id="<?= $sud ?>896"></td></tr>
  </table>
</td>
<td>
  <table class="internal">
  	<tr><td><input type="text" id="<?= $sud ?>977"></td><td><input type="text" id="<?= $sud ?>978"></td><td><input type="text" id="<?= $sud ?>979"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>987"></td><td><input type="text" id="<?= $sud ?>988"></td><td><input type="text" id="<?= $sud ?>989"></td></tr>
  	<tr><td><input type="text" id="<?= $sud ?>997"></td><td><input type="text" id="<?= $sud ?>998"></td><td><input type="text" id="<?= $sud ?>999"></td></tr>
  </table>
</td>
</tr>
</table>
</form>

<?php 
		foreach ($data as $curData)
		{
			$arr = explode(":",$curData);
		
			?><script>$("#<?= $sud ?><?= $arr[1] ?>").val("<?= $arr[0] ?>").css("backgroundColor", "#8bf79c").attr("disabled", true);</script><?php 
			
		}
	}
}

?>



</table>
<table width=1200>
<tr><td colspan=5 align="center">
	<?php 
	CPageNums::writePageNums(ceil($countNews/10), $page);
	?>
</td></tr>
	
	
<tr><td colspan=5 align="center"><?= showBanner($AllBanners, 8); ?></td></tr>
<tr><td align="center">&nbsp;</td><td colspan=3"><div align="justify">

<div id="mc-container" align="center"></div>
<script type="text/javascript">
cackle_widget = window.cackle_widget || [];
cackle_widget.push({widget: 'Comment', id: 53339, channel: "sudoku-list"});
(function() {
    var mc = document.createElement('script');
    mc.type = 'text/javascript';
    mc.async = true;
    mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mc, s.nextSibling);
})();
</script>

</td></tr>

<tr><td colspan=5 align="center">

</td></tr>       
       
       </table>
</div>

	<script type="text/javascript" src="/js/sudoku.js"></script>
	

<?php 
	require_once SITE_HOME_DIR . "inc/inc_footer_konkurs.php";