<?php

	session_start();
	require_once "../settings.php";	
	require_once UTILS_PATH . "utils.php";	
	require_once MODELS_PATH . "class_CSudoku.php";
	require_once MODELS_PATH . "class_CUsers.php";
	require_once MODELS_PATH . "class_CSudokuLeaders.php";
	require_once MODELS_PATH . "class_CSudokuWeekWinners.php";

	$Connection = SetConnection();
	
	
	require_once SITE_HOME_DIR . "inc/inc_header_konkurs.php";

	$user_id = loadIntParam("userid", 0);
	

		$ThisUser = new CUsers($Connection, $user_id);

	
?>

<style>

fieldset {
  width:100%;
  border: 1px solid #339dc7;
  /* ����� ������������� ��� ������� */
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
$week_start = date("Y-m-d 00:00:00", strtotime("last Monday"));
$week_end = date("Y-m-d 23:59:59", strtotime("next Sunday"));
$SudokuLeader = CSudokuLeaders::getSudokuWeekLeaders($Connection, $week_start, $week_end, $ThisUser->id);
$SudokuTable  = CSudokuLeaders::getSudokuLeadersTable($Connection);
$SudokuWinners = CSudokuLeaders::getSudokuWinners($Connection, $ThisUser->id);


foreach ($SudokuTable as $place => $CurSudokuTable){

	if ($CurSudokuTable["id"] == $ThisUser->id){

		$place = $place+1;
		$sudoku_all = $CurSudokuTable["cnt"];
		$points_all = $CurSudokuTable["rating"];
		break;
	}
}
?>
<div align="center" class="user">
������������ <span><?= $ThisUser->name ?></span>
<br /><Br />
<table>
<tr>
<td>���������� �����</td><td width="100">&nbsp;</td><td><span><?= $place?></span></td>
</tr>
<tr>
<td>����������� ������ �����</td><td>&nbsp;</td><td><span><?= $sudoku_all ?></span></td>
</tr>
<tr>
<td>������������ ������ �����</td><td>&nbsp;</td><td><span><?= $points_all ?></span></td>
</tr>
<tr>
<td>����������� ������ �� ���� ������</td><td>&nbsp;</td><td><span><?= $SudokuLeader[0]->cnt ?></span></td>
</tr>
<tr>
<td>������������ ������ �� ���� ������</td><td>&nbsp;</td><td><span><?= $SudokuLeader[0]->points ?></span></td>
</tr>
<tr>
<td>���������� ������ ���� � ����������� ������</td><td>&nbsp;</td><td><span><?= count($SudokuWinners)?></span></td>
</tr>

</table>
</div>
<?php 
if ($place < 4){
switch($place){
	case "1": $kubok = "<img src='/images/all_gold.png' width=200>"; $descr = "����� � ����� ������"; break;
	case "2": $kubok = "<img src='/images/all_silver.png' width=150>";  $descr = "������ ����� � ����� ������"; break;
	case "3": $kubok = "<img src='/images/all_bronze.png' width=100>";  $descr = "������ ����� � ����� ������";  break;
}


?>
<fieldset>
  <legend>
    ������ � �������������
  </legend>
<div class="info" style="float:left;">   
<abbr title="<?= $descr ?><br /><Br />�������: <?= $points_all ?> ������<br />���������: <?= $sudoku_all ?> ������ "  rel="tooltip">
	<?= $kubok ?>           
</abbr>
</div> 
</fieldset>
<?php 		
}

$WeekWinners = CSudokuWeekWinners::getSudokuWeekWinnerList($Connection, false, false, $ThisUser->id);

 if ($WeekWinners){
?>
<fieldset>
  <legend>
    ���������� ������
  </legend>
<?php 
	foreach ($WeekWinners as $CurWinner){
		switch($CurWinner->place){
			case "1": $kubokWeekWinner = "<img src='/images/week_gold.png' width=200>"; $descrWeekWinner = "���������� ������ � " . $CurWinner->week_start . " �� " . $CurWinner->week_end . " "; break;
			case "2": $kubokWeekWinner = "<img src='/images/week_silver.png' width=150>";  $descrWeekWinner = "������ ����� �� ������ � " . $CurWinner->week_start . " �� " . $CurWinner->week_end . " "; break;
			case "3": $kubokWeekWinner = "<img src='/images/week_bronze.png' width=100>";  $descrWeekWinner = "������ ����� �� ������ � " . $CurWinner->week_start . " �� " . $CurWinner->week_end . " ";  break;
		}
		?>
		<div class="info" style="float:left;">  
			<abbr title="<?= $descrWeekWinner ?><br /><Br />�������: <?= $CurWinner->points ?> ������"  rel="tooltip">
				<?= $kubokWeekWinner ?>           
			</abbr>
		</div>
		<?php 	
	}	

	?>
	</div> 
	</fieldset>
	<?php 
}


if (count($SudokuWinners) > 0){
?>
<fieldset>
  <legend>
    ��������� � ������
  </legend>

<?php 


foreach ($SudokuWinners as $CurSudokuWinner){

?>     
<div class="info" style="float:left;">   
<abbr title="������ �<?= $CurSudokuWinner["sudoku_id"]; ?> <br /><Br />�����: <?= showBestTime($CurSudokuWinner["results"])?>"  rel="tooltip">
	<a href="/sudoku/index.php?nid=<?= $CurSudokuWinner["sudoku_id"] ?>">
            <img src="/images/sudoku_leader.png" width=100>
	</a>            
</abbr>
</div>            
<?php 
}
?>            
</fieldset>

<?php 
}	
	
	require_once SITE_HOME_DIR . "inc/inc_footer_konkurs.php";