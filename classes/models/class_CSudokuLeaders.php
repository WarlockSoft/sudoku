<?
	require_once UTILS_PATH . "class_CDateUtils.php";	
		
	class CSudokuLeaders
	{
		var $Connection;
		
		var $id;
		var $sudoku_id;
		var $name;
		var $results;
		var $points;
		var $cnt;
		var $user_id;
		
		function CSudokuLeaders($Connection, $id = 0)
		{
			$this->Connection = $Connection;
			
			if ($id)
			{
				
				$sql = "SET NAMES CP1251;";
				$this->Connection->query($sql);
								
				$sql = 'SELECT * FROM ' . PREFIX . 'sudoku_leaders WHERE ID=' . $id;
				
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
			$this->sudoku_id = $rs['sudoku_id'];
			$this->name = $rs['name'];
			$this->results = $rs['results'];
			$this->user_id = $rs['user_id'];
			$this->us_id = $rs['us_id'];
			$this->points = $rs['points'];
			$this->cnt = $rs['cnt'];
			$this->showname = $rs["showname"];
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
			$sql = 'UPDATE ' . PREFIX . 'sudoku_leaders ' .
					'SET ' .
					'	`name`="' . nullIfEmpty($this->name) . '", ' .
					'	`sudoku_id`="' . nullIfEmpty($this->sudoku_id) . '", ' .					
					'	`user_id`="' . nullIfEmpty($this->user_id) . '", ' .
					'	`points`="' . nullIfEmpty($this->points) . '", ' .
					'	`results`=' . zeroIfNull($this->results) . ' ' .					
					'WHERE ID=' . $this->id;
					echo $sql . '<br/>';
			$this->Connection->query($sql);
		}
		
		function insert()
		{
			
				$sql = "SET NAMES CP1251;";
				$this->Connection->query($sql);
							
			$sql = 'INSERT INTO ' . PREFIX . 'sudoku_leaders ' .
					'	(' .
					'		`name`, ' .
					'		`sudoku_id`, ' .				
					'		`user_id`, ' .
					'		`points`, ' .
					'		`results` ' .
					'	) ' .
					'VALUES ' .
					'	( "' .
							nullIfEmpty($this->name) . '", "' .
							nullIfEmpty($this->sudoku_id) . '", "' .							
							nullIfEmpty($this->user_id) . '", ' .
							nullIfEmpty($this->points) . ', ' .
							zeroIfNull($this->results) . ' ' .
					'	) ';

			$this->Connection->query($sql);
		}
		
		function delete()
		{
			$sql = 'DELETE FROM ' . PREFIX . 'sudoku_leaders WHERE ID=' . $this->id;
			$this->Connection->query($sql);
		}

		function validateToSave(&$Error)
		{
			if (!$this->sudoku_id)
				$Error->add('Необходимо указать код Судоку');
				
		}
		
		function validateToDelete(&$Error)
		{

		}
				
		function getSudokuLeadersList($Connection, $sudoku_id = false, $limit = 10)
		{
			$dataList = array();
			
				$sql = "SET NAMES CP1251;";
				$Connection->query($sql);			
			
			$sql =	'SELECT * ' .
					'FROM ' . PREFIX . 'sudoku_leaders ' .
					'WHERE 1 ';
			if ($sudoku_id) $sql .= 'AND sudoku_id = ' . $sudoku_id;
			$sql .= ' ORDER BY results ASC ';
			if ($limit) $sql .= ' LIMIT ' . $limit;

			$Recordset = $Connection->query($sql);
			while ($rs = $Recordset->fetch_assoc())
			{
				$Color = new CSudokuLeaders($Connection);
				$Color->fillData($rs);
				$dataList[] = $Color;

			}
			$Recordset->free_result();

			return $dataList;			
		}
		
		function getSudokuWeekLeaders($Connection, $week_start, $week_end, $user_id = false)
		{
			$dataList = array();
				
			$sql = "SELECT *, count(id) as cnt, sum(points) as points 
					FROM `sudoku_leaders` 
					WHERE (user_id <> '' and user_id <> 'NULL') and  dt >= '" . $week_start . "' and dt<= '" . $week_end . "' ";
			if ($user_id) $sql .= ' AND user_id = ' . $user_id  . ' ';
					$sql .= 'group by user_id order by points desc';
		
			$Recordset = $Connection->query($sql);
			while ($rs = $Recordset->fetch_assoc())
			{
				$Color = new CSudokuLeaders($Connection);
				$Color->fillData($rs);
				$dataList[] = $Color;
		
			}
			$Recordset->free_result();
		
			return $dataList;
		}
		
		function getSudokuLeadersByUserAndSudoku($Connection, $sudoku_id, $user_id)
		{
			$dataList = array();
				
			$sql = "SET NAMES CP1251;";
			$Connection->query($sql);
				
			$sql =	'SELECT * ' .
					'FROM ' . PREFIX . 'sudoku_leaders ' .
					'WHERE 1 ';
			if ($sudoku_id) $sql .= ' AND sudoku_id = ' . $sudoku_id;
			if ($user_id) $sql .= ' AND user_id = ' . $user_id;
			$sql .= ' ORDER BY results ASC lIMIT 1';
		//echo $sql;
			$Recordset = $Connection->query($sql);
			while ($rs = $Recordset->fetch_assoc())
			{
				$Color = new CSudokuLeaders($Connection);
				$Color->fillData($rs);
				$Recordset->free_result();
				return $Color;
		
			}
			
		
			
		}		
		
		function getSudokuLeadersCnt($Connection, $sudoku_id = false, $user_id = false)
		{
			$dataList = array();
				
			$sql = "SET NAMES CP1251;";
			$Connection->query($sql);
				
			$sql =	'SELECT count(*) as cnt ' .
					'FROM ' . PREFIX . 'sudoku_leaders ' .
					'WHERE 1 ';
			if ($sudoku_id) $sql .= ' AND sudoku_id = ' . $sudoku_id;
			if ($user_id) $sql .= ' AND user_id = ' . $user_id;
			$sql .= ' ORDER BY results ASC lIMIT 5';
		
			$Recordset = $Connection->query($sql);
			while ($rs = $Recordset->fetch_assoc())
			{
				$Recordset->free_result();
				return $rs["cnt"];
		
			}
			
		
			
		}	
		
		function getSudokuLeadersTable($Connection)
		{
			$dataList = array();
		
			$sql = "SET NAMES CP1251;";
			$Connection->query($sql);
		
			$sql =	'SELECT us.id, us.name,  us.rating, count(sl.id) as cnt  FROM `user` us 
						left join sudoku_leaders sl on sl.user_id = us.id
						WHERE 1
						group by us.id
						order by us.rating desc, cnt asc 
						';
		
			$Recordset = $Connection->query($sql);
			while ($rs = $Recordset->fetch_assoc())
			{
				
				$dataList[] = $rs;
				
		
			}
			$Recordset->free_result();
		
			return $dataList;
		}
		
		
		function getSudokuWinners($Connection, $user_id)
		{
			$dataList = array();
		
			$sql = "SET NAMES CP1251;";
			$Connection->query($sql);
		
			$sql =	'SELECT * FROM `sudoku_leaders` sl
					INNER JOIN sudoku on sudoku.id = sl.sudoku_id and sl.results = sudoku.besttime
					WHERE 1 AND sl.user_id=' . $user_id;
		
			$Recordset = $Connection->query($sql);
			while ($rs = $Recordset->fetch_assoc())
			{
		
				$dataList[] = $rs;
		
		
			}
			$Recordset->free_result();
		
			return $dataList;
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