<?php
/*
 *--------------ABOUT-----------------------------
 *    sudoku.php
 *    Copyright (C) 2015 Samuel Adeshina
 *--------------LICENSE----------------------------
 *    This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 *------------------------------------------------
 */
# ----------------------------------------------------------------------------
#  @DESCRIPTION: Base Class For Sudoku: A PHP Package That can generate sudoku of any
#										size (provided its a perfect square such as 
#										4 X 4, 9 X 9 or 16 X 16). It can also solve the generated
#										puzzle with up to 92% accuracy in a worse-case scenerio
#
#  @INSTANTIATION EXAMPLE: $class_instantiation = new sudoku("Integer Representing The Sudoku Size To Work With");
#
#  @AUTHOR: Samuel Adeshina <samueladeshina73@gmail.com>
#  
#  @CREATED: Apr-2015: Initial version <samueladeshina73@gmail.com>
#
#  @REQUIRED FILES:
#  										+ [It is advisable to use javascript for asynchronous transfer of data]
#											so as to create a "real game experience"]
#										+ [See the test.php and testProcessor.php bundled together with this class file
#											to view an example implementation/instantiation of this class]
# ----------------------------------------------------------------------------
class sudoku
{
	public static $alphs = array(1=>"a", 2=>"b", 3=>"c", 4=>"d", 5=>"e", 6=>"f", 7=>"g", 8=>"h", 9=>"i", 10=>"j",
								 11=>"k", 12=>"l", 13=>"m", 14=>"n", 15=>"o", 16=>"p", 17=>"q", 18=>"r", 19=>"s",
								 20=>"t", 21=>"u", 22=>"v", 23=>"w", 24=>"x", 25=>"y", 26=>"z");  //for naming the sudoku square grids
	public $gridXgrid; //integer and a perfect square representing the size of sudoku to manipulate such as 9, 16, 25 ad 4
	public $subs = array();
	private $names = array();
	private $values = array();
	private $subGroups = array();
	public function __construct($grids)
	{
		$this->gridXgrid = $grids;
		$this->names[] = self::_nameSquares();
		/*foreach ($this->names as $key=>$names)
		{
			$this->values["$names[$key]"] = '';
		}*/
		for ($c = 0, $d = count($this->names[0]); $c < $d; $c++)
		{
			$name = $this->names[0][$c];
			$this->values["$name"] = '';
		}
		$this->subs = self::_getSubs();
	}
	/*Instantiation of the _nameSquares() function.
		It is used to assign unique names to the sudoku grids. 
		for example, in a 4 X 4 Sudoku, it returns an array containing the
		following: a1, b1, c1, d1, a2, b2, c2, d2 which represents the grids
		in the 'invisible sudoku board'
	*/
	protected function _nameSquares()
	{
		for ($i = 1, $j = $this->gridXgrid; $i <= $j; $i++)
		{
			for ($a = 1, $b = $this->gridXgrid; $a <= $b; $a++)
			{
				$name[] = self::$alphs[$a].$i;
			}
		}
		return $name;
	}
	protected function _getSubs()
	{
		$part = sqrt($this->gridXgrid);
		$stopped = 0;
		for ($c = 1; $c <= $part; $c++)
		{
			$parts[$c] = "sub".$c;
		}
		$part = $part * 2;
		for ($c = ($part/2) + 1; $c <= $part; $c++)
		{
			$parts[$c] = "sub".$c;
		}
		return $parts;
	}
	protected function _getSubGroups()
	{
		if ($this->gridXgrid == 4)
		{
			$this->subGroups["sub1"] = array(1=>"a1", 2=>"b1", 3=>"a2", 4=>"b2");
			$this->subGroups["sub2"] = array(1=>"c1", 2=>"d1", 3=>"c2", 4=>"d2");
			$this->subGroups["sub3"] = array(1=>"a3", 2=>"b3", 3=>"a4", 4=>"b4");
			$this->subGroups["sub4"] = array(1=>"c3", 2=>"d3", 3=>"c4", 4=>"d4");
		}
		else if($this->gridXgrid == 9)
		{
			$this->subGroups["sub1"] = array(1=>"a1", 2=>"b1", 3=>"c1", 4=>"a2", 5=>"b2", 6=>"c2", 7=>"a3", 8=>"b3", 9=>"c3");
			$this->subGroups["sub2"] = array(1=>"d1", 2=>"e1", 3=>"f1", 4=>"d2", 5=>"e2", 6=>"f2", 7=>"d3", 8=>"e3", 9=>"f3");
			$this->subGroups["sub3"] = array(1=>"g1", 2=>"h1", 3=>"i1", 4=>"g2", 5=>"h2", 6=>"i2", 7=>"g3", 8=>"h3", 9=>"i3");
			$this->subGroups["sub4"] = array(1=>"a4", 2=>"b4", 3=>"c4", 4=>"a5", 5=>"b5", 6=>"c5", 7=>"a6", 8=>"b6", 9=>"c6");
			$this->subGroups["sub5"] = array(1=>"d4", 2=>"e4", 3=>"f4", 4=>"d5", 5=>"e5", 6=>"f5", 7=>"d6", 8=>"e6", 9=>"f6");
			$this->subGroups["sub6"] = array(1=>"g4", 2=>"h4", 3=>"i4", 4=>"g5", 5=>"h5", 6=>"i5", 7=>"g6", 8=>"h6", 9=>"i6");
		}
		return $this->subGroups;
	}
	private function _isValidMove($index, $num, $values) //This function determines if a play is valid or not
	{
		$alph_index = substr($index, 0, 1);
		$num_index = substr($index, -1, 1);
		for ($i = 1; $i <= $this->gridXgrid; $i++)
		{
			$keyAI = self::$alphs[$i].$num_index;
			$keyNI = $alph_index.$i;
			if ($values[$keyAI] == $num || $values[$keyNI] == $num)
			{
				//move is invalid
				return 0;
			}
		}
		foreach ($this->subGroups as $key=>$subgroup)
		{
			if ($index == $key)
			{
				if (in_array($values, $subgroup))
				{
					return 0;
				}
				break;
			}
		}
		return 1;
	}
	public function _populateSubs() // This assigns randomly generated numbers to randomly generated grids (Instantiates The Game)
	{
		$num_of_subs = (sqrt($this->gridXgrid)) * 2;
		for ($i = 1; $i <= $num_of_subs; $i++)
		{
			$currentSub = $this->subs[$i];
			A:
			$subsRandPos = rand(1, $this->gridXgrid);
			$subsRandNum = rand(1, $this->gridXgrid);
			$pos = $this->_getSubGroups()["$currentSub"][$subsRandPos];
			if (self::_isValidMove($pos, $subsRandNum, $this->values))
			{
				$this->values[$pos] = $subsRandNum;
			}
			else
			{
				//brute-force
				goto A;
			}
		}
		return $this->values;
	}
	public function _solveSudoku($pos, $values) //Just as the name implies, it iterates through the 'board' and solves the sudoku puzzle
	{
		$num_of_subs = (sqrt($this->gridXgrid)) * 2;
		for ($i = 1; $i <= $num_of_subs; $i++)
		{
			$currentSub = $this->subs[$i];
			$counter = 0;
			if ($values[$pos] == '')
			{
				B:
				$subsRandNum = rand(1, $this->gridXgrid);
				if (self::_isValidMove($pos, $subsRandNum, $values))
				{
					echo "$pos was solved for $counter iterations<br/>";
					$values[$pos] = $subsRandNum;
				}
				else
				{
					//brute-force: ends iteration after iterating for 4 times the number of grids been solved for.
					//It means no possible answer can be determined although it rarely happens
					if ($counter == (4*$this->gridXgrid))
					{
						$values[$pos] = "-1";
					}
					else
					{
						$counter += 1;
						goto B;							
					}
				}
			}
		}
		return $values;
	}
	/*
		You can customize the method below to suite you
		It returns a table-like view of the sudoku populated sudoku board
		You can decide to a canvas instead or pictures or any design that suites
		your representation of the game board
	*/
	public function _viewSudoku($values) 
	{
		echo "<table border='1' width='100%'>";
		for ($j = 1; $j <= $this->gridXgrid; $j++)
		{
			echo "<tr width = '50%' height = '2%'>";
			for ($i = 1; $i <= $this->gridXgrid; $i++)
			{
				$currentField = self::$alphs[$i].$j;
				$currentValue = $values[$currentField];
				echo "<td><small>$currentField<small><br/><center><h1>$currentValue</h1></td>";
			}
			echo "</tr>";
		}
		echo "<table></center>";
	}
}
?>