<?
	require_once UTILS_PATH . "class_CDateUtils.php";	
		
	class CSudokuWeekWinners
	{
		var $Connection;
		
		var $id;
		var $week_start;
		var $week_end;
		var $place;
		var $points;
		var $user_id;
		
		function CSudokuWeekWinners($Connection, $id = 0)
		{
			$this->Connection = $Connection;
			
			if ($id)
			{
				
				$sql = "SET NAMES CP1251;";
				$this->Connection->query($sql);
								
				$sql = 'SELECT * FROM ' . PREFIX . 'sudoku_week_winners WHERE ID=' . $id;
				
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
			$this->week_start = $rs['week_start'];
			$this->week_end = $rs['week_end'];
			$this->place = $rs['place'];
			$this->user_id = $rs['user_id'];
			$this->points = $rs['points'];
			
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
			$sql = 'UPDATE ' . PREFIX . 'sudoku_week_winners ' .
					'SET ' .
					'	`week_start`="' . nullIfEmpty($this->week_start) . '", ' .
					'	`week_end`="' . nullIfEmpty($this->week_end) . '", ' .					
					'	`user_id`="' . nullIfEmpty($this->user_id) . '", ' .
					'	`points`="' . nullIfEmpty($this->points) . '", ' .
					'	`place`=' . zeroIfNull($this->place) . ' ' .					
					'WHERE ID=' . $this->id;
					echo $sql . '<br/>';
			$this->Connection->query($sql);
		}
		
		function insert()
		{
			
				$sql = "SET NAMES CP1251;";
				$this->Connection->query($sql);
							
			$sql = 'INSERT INTO ' . PREFIX . 'sudoku_week_winners ' .
					'	(' .
					'		`week_start`, ' .
					'		`week_end`, ' .				
					'		`user_id`, ' .
					'		`points`, ' .
					'		`place` ' .
					'	) ' .
					'VALUES ' .
					'	( "' .
							nullIfEmpty($this->week_start) . '", "' .
							nullIfEmpty($this->week_end) . '", "' .							
							nullIfEmpty($this->user_id) . '", ' .
							nullIfEmpty($this->points) . ', ' .
							zeroIfNull($this->place) . ' ' .
					'	) ';

			$this->Connection->query($sql);
		}
		
		function delete()
		{
			$sql = 'DELETE FROM ' . PREFIX . 'sudoku_week_winners WHERE ID=' . $this->id;
			$this->Connection->query($sql);
		}

		function validateToSave(&$Error)
		{
			if (!$this->week_start)
				$Error->add('Необходимо указать начало недели');
			if (!$this->week_start)
				$Error->add('Необходимо указать конец недели');
				
		}
		
		function validateToDelete(&$Error)
		{

		}
				
		function getSudokuWeekWinnerList($Connection, $week_start = false, $week_end = false, $user_id = false)
		{
			$dataList = array();
			
				$sql = "SET NAMES CP1251;";
				$Connection->query($sql);			
			
			$sql =	'SELECT * ' .
					'FROM ' . PREFIX . 'sudoku_week_winners ' .
					'WHERE 1 ';
			if ($week_start) $sql .= ' AND week_start = ' . $week_start;
			if ($week_end) $sql .= ' AND week_end = ' . $week_end;
			if ($user_id) $sql .= ' AND user_id = ' . $user_id;
			$sql .= ' ORDER BY points DESC ';
			if ($limit) $sql .= ' LIMIT ' . $limit;

			$Recordset = $Connection->query($sql);
			while ($rs = $Recordset->fetch_assoc())
			{
				$Color = new CSudokuWeekWinners($Connection);
				$Color->fillData($rs);
				$dataList[] = $Color;

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