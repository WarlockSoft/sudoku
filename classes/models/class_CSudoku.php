<?
	require_once UTILS_PATH . "class_CDateUtils.php";	
		
	class CSudoku
	{
		var $Connection;
		
		var $id;
		var $level;
		var $sudoku;
		var $visited;
		var $voted;
		var $mark;
		var $wins;
		var $besttime;
		var $deleted;
		
		function CSudoku($Connection, $id = 0)
		{
			$this->Connection = $Connection;
			
			if ($id)
			{
				
				$sql = "SET NAMES CP1251;";
				$this->Connection->query($sql);
								
				$sql = 'SELECT * FROM ' . PREFIX . 'sudoku WHERE ID=' . $id;
				
				$Recordset = $this->Connection->query($sql);
				if ($rs = $Recordset->fetch_assoc())
				{
					$this->fillData($rs);
				}
				$Recordset->free_result();
			}
		}
		
		function fillData($rs)
		{
			$this->id = $rs['id'];
			$this->level = $rs['level'];
			$this->sudoku = $rs['sudoku'];
			$this->visited = $rs['visited'];
			$this->voted = $rs['voted'];
			$this->mark = $rs['mark'];
			$this->wins = $rs['wins'];
			$this->besttime = $rs['besttime'];
			$this->deleted = $rs['deleted'];
		}
		
		function save()
		{
			if ($this->id)
				$this->update();
			else
			{
				$this->insert();
				$this->id = $this->Connection->insert_id();
			}
			
			return $this->id;
		}
		
		function update()
		{
			$sql = 'UPDATE ' . PREFIX . 'sudoku ' .
					'SET ' .
					'	`level`="' . nullIfEmpty($this->level) . '", ' .
					'	`sudoku`="' . nullIfEmpty($this->sudoku) . '", ' .					
					'	`deleted`=' . zeroIfNull($this->deleted) . ' ' .					
					'WHERE ID=' . $this->id;
					
			$this->Connection->query($sql);
		}
		
		function insert()
		{
			
				$sql = "SET NAMES CP1251;";
				$this->Connection->query($sql);
							
			$sql = 'INSERT INTO ' . PREFIX . 'sudoku ' .
					'	(' .
					'		`level`, ' .
					'		`sudoku`, ' .				
					'		`deleted` ' .
					'	) ' .
					'VALUES ' .
					'	( "' .
							nullIfEmpty($this->level) . '", "' .
							nullIfEmpty($this->sudoku) . '", ' .							
							zeroIfNull($this->deleted) . ' ' .
					'	) ';

			$this->Connection->query($sql);
		}
		
		function delete()
		{
			$sql = 'DELETE FROM ' . PREFIX . 'sudoku WHERE ID=' . $this->id;
			$this->Connection->query($sql);
		}

		function validateToSave(&$Error)
		{
			if (!$this->sudoku)
				$Error->add('Необходимо указать код Судоку');
				
		}
		
		function validateToDelete(&$Error)
		{

		}
				
		function getSudokuList($Connection, $only_enabled = 1)
		{
			$dataList = array();
			
				$sql = "SET NAMES CP1251;";
				$Connection->query($sql);			
			
			$sql =	'SELECT * ' .
					'FROM ' . PREFIX . 'sudoku ' .
					'WHERE 1 ';
			if ($only_enabled) $sql .= 'AND deleted=0 ';
			$sql .= ' ORDER BY id ASC ';

			$Recordset = $Connection->query($sql);
			while ($rs = $Recordset->fetch_assoc())
			{
				$Color = new CSudoku($Connection);
				$Color->fillData($rs);
				$dataList[] = $Color;

			}
			$Recordset->free_result();

			return $dataList;			
		}
		
		function getSudokuCnt($Connection, $only_enabled = 1)
		{
			$dataList = array();
				
			$sql = "SET NAMES CP1251;";
			$Connection->query($sql);
				
			$sql =	'SELECT count(*) as cnt ' .
					'FROM ' . PREFIX . 'sudoku ' .
					'WHERE 1 ';

			if ($only_enabled) $sql .= 'AND deleted=0 ';
			$sql .= ' ORDER BY id DESC ';
		
			$Recordset = $Connection->query($sql);
			while ($rs = $Recordset->fetch_assoc())
			{
				return $rs["cnt"];
		
			}
			$Recordset->free_result();
		
			return $Color;
		}
		
		function getRandomSudoku($Connection, $only_enabled = 1, $level = false, $nid = false)
		{
			$dataList = array();
				
			$sql = "SET NAMES CP1251;";
			$Connection->query($sql);
				
			$sql =	'SELECT * ' .
					'FROM ' . PREFIX . 'sudoku ' .
					'WHERE 1 ';
			if ($level) $sql .= " AND level = " . $level;
			if ($nid) $sql .= " AND id = " . $nid; 
			if ($only_enabled) $sql .= ' AND deleted=0 ';
			$sql .= ' ORDER BY RAND() LIMIT 1 ';
		
			$Recordset = $Connection->query($sql);
			while ($rs = $Recordset->fetch_assoc())
			{
				return $rs;
		
			}
			$Recordset->free_result();
		
			return $Color;
		}
		
		function incRating($Connection, $gameId)
		{
				
			$sql = 'UPDATE sudoku ' .
					'SET ' .
					'	`visited`= `visited` + 1 ' .
					'WHERE id = ' . $gameId;
		
			$Connection->query($sql);
		
		}
		
		function incWins($Connection, $gameId)
		{
		
			$sql = 'UPDATE sudoku ' .
					'SET ' .
					'	`wins`= `wins` + 1 ' .
					'WHERE id = ' . $gameId;
		
			$Connection->query($sql);
		
		}	

		function updBestTime($Connection, $gameId, $besttime)
		{
		
			$sql = 'UPDATE sudoku ' .
					'SET ' .
					'	`besttime`=  ' . $besttime . ' ' .
					' WHERE id = ' . $gameId;
		
			$Connection->query($sql);
		
		}		
		
		function itemVote($Connection, $gameId, $mark)
		{
		
		
			$sql = 'UPDATE sudoku ' .
					'SET ' .
					'	`voted`= `voted` + 1, `mark` = `mark` + ' . $mark .
					' WHERE id = ' . $gameId;
		
			$Connection->query($sql);
		
		}		
		
		function ConvertTime($time = "", $format = 1)
		{
			//$time = "2009-05-05 12:57:01";
			$time = CDateUtils::getTimestamp($time);
			$year = CDateUtils::getYear($time);
			$month = CDateUtils::getMonth($time, GETNAMEOFMONTH);
			$day = CDateUtils::getDay($time);
			
			
			if ($format == 1)
			{
				$data = "$day $month $year";
			}

			
			return $data;			
		}		
		
		
	}