<?php

	session_start();
	require_once "../settings.php";	
	require_once UTILS_PATH . "utils.php";	
	require_once MODELS_PATH . "class_CSudoku.php";
	require_once MODELS_PATH . "class_CUsers.php";
	require_once MODELS_PATH . "class_CSudokuLeaders.php";

	$Connection = SetConnection();
	
	
	require_once SITE_HOME_DIR . "inc/inc_header_konkurs.php";



	if ($_SESSION["user"])
		$User = new CUsers($Connection, $_SESSION["user"]);
	else{
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
	}
	
?>

<style>

fieldset {
  width:100%;
  border: 1px solid #339dc7;
  /* Чтобы подстраивался под контент */
  display: inline-block;
}

legend {
  font-size:20px;
  color: #000;
}

p.name{
	font-size:20px;
	position:absolute; 
	display:inline-block; 
	margin:-12px -10px -1000px -550px; 
	background:#e6e6e6
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
	border: 1px solid grey; /* Граница вокруг ячеек */
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
.user{
	padding:20px;
	font-size:35px;
}
.user span{
	color:#686767;
	font-style:italic;
}

.user table{
	font-size:25px;
}

.user table span{
	color:#f21111;
	font-style:italic;
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

<tr><td>

</td></tr>
<tr><td colspan=3>
<div style="text-align:center;background: rgba(187,79,42,.2); padding: 1px 5px;">
<? include SITE_HOME_DIR . "/sudoku/inc_menu.php"; ?>
</div>
</td></tr>
</table>
<?php

if (!$_SESSION["user"]){


	?>
	<br /><br />
			<div class="social" align="center"><strong>Для доступа к данной странице Вам нужно авторизоваться<br /><br />
				<script src="//ulogin.ru/js/ulogin.js"></script>
				<div id="uLogin" data-ulogin="display=panel;theme=classic;fields=first_name,last_name,email,photo_big;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=https%3A%2F%2Fwww.freegamesplay.ru%2Fsudoku%2Fpersonal.php;mobilebuttons=0;"></div>
			</div>
			</div>
		<?php

		
		require_once SITE_HOME_DIR . "inc/inc_footer_konkurs.php";exit;		
	}



$week_start = date("Y-m-d 00:00:00", strtotime("last Monday"));
$week_end = date("Y-m-d 23:59:59", strtotime("next Sunday"));
$SudokuLeader = CSudokuLeaders::getSudokuWeekLeaders($Connection, $week_start, $week_end, $User->id);
$SudokuTable  = CSudokuLeaders::getSudokuLeadersTable($Connection);
$SudokuWinners = CSudokuLeaders::getSudokuWinners($Connection, $User->id);

foreach ($SudokuTable as $place => $CurSudokuTable){

	if ($CurSudokuTable["id"] == $User->id){

		$place = $place+1;
		$sudoku_all = $CurSudokuTable["cnt"];
		$points_all = $CurSudokuTable["rating"];
		break;
	}
}
?>
<div align="center" class="user">
ПОЛЬЗОВАТЕЛЬ <span><?= $User->name ?></span>
<br /><Br />
<table>
<tr>
<td>Занимаемое место</td><td>&nbsp;</td><td><span><?= $place?></span></td>
</tr>
<tr>
<td>Разгаданных судоку всего</td><td>&nbsp;</td><td><span><?= $sudoku_all ?></span></td>
</tr>
<tr>
<td>Заработанных баллов всего</td><td>&nbsp;</td><td><span><?= $points_all ?></span></td>
</tr>
<tr>
<td>Разгаданных судоку на этой неделе</td><td>&nbsp;</td><td><span><?= $SudokuLeader[0]->cnt ?></span></td>
</tr>
<tr>
<td>Заработанных быллов на этой неделе</td><td>&nbsp;</td><td><span><?= $SudokuLeader[0]->points ?></span></td>
</tr>
<tr>
<td>Количество первых мест в разгаданных судоку</td><td>&nbsp;</td><td><span><?= count($SudokuWinners)?></span></td>
</tr>

</table>
</div>
<?php 
if ($place < 4){

?>

        
 
        <div class="nagrada">
            <p class="name">Победы в соревнованиях</p>
            <span style="height:100px;">&nbsp;<?= $nagrady ?></span>
        </div>

<?php 
}
if (count($SudokuWinners) > 0){
?>
<fieldset>
  <legend>
    Лидерство в судоку
  </legend>

<?php 


foreach ($SudokuWinners as $CurSudokuWinner){

?>            
<abbr title="Судоке №<?= $CurSudokuWinner["sudoku_id"]; ?> <br /><Br />Время: <?= showBestTime($CurSudokuWinner["results"])?>"  rel="tooltip">
	<a href="/sudoku/index.php?nid=<?= $CurSudokuWinner["sudoku_id"] ?>">
            <img src="/images/sudoku_leader.png" width=100 align="left">
	</a>            
</abbr>            
<?php 
}
?>            
</fieldset>

<?php 
}	
	
	require_once SITE_HOME_DIR . "inc/inc_footer_konkurs.php";