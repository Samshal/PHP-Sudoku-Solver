
<?php
/*
	Description: 	This test file stores the current game play (an array actually)
					In a session variable, you could decide to use cookies,
					html5 state saving or any method of keeping the current data in memory
					that works best for you.

	Author: 		Samuel Adeshina

	Dependency:		The sudoku.php class file bundled together with this file is required
					run the test.php file in a browser to get a 'visual instance' of how this works
*/
	require_once("sudoku.php");
	SESSION_START();
	if (!isset($_GET["loc"]) || !isset($_GET["sudokutype"]) || !isset($_GET["startover"])
		|| $_GET["sudokutype"] == '' || $_GET["startover"] == '' || $_GET["loc"] == ''
	   )
		{
			$_GET["sudokutype"] = 9;
			$_GET["startover"] = "false";
			$_GET["loc"] = "a1";
		}
	$loc = $_GET["loc"];
	$startover = $_GET["startover"];
	$sudokType = $_GET["sudokutype"];	
	$mySudoku = new sudoku($sudokType); //Instantiating the sudoku class with a grid size of whatever is in the variable: $sudokType
	if ($startover == "true")
	{
		if (isset($_SESSION["currentmoves"]))
		{
			unset($_SESSION["currentmoves"]);
		}	
	}
	if (!isset($_SESSION["currentmoves"]) || !is_array($_SESSION["currentmoves"]))
	{
		$_SESSION["currentmoves"] = $mySudoku->_populateSubs(); //Populating the board
	}	
	//echo "<center>Number Of Iterations For Current Play: ";
	$_SESSION["currentmoves"] = $mySudoku->_solveSudoku($loc, $_SESSION["currentmoves"]); //Generates the value of the grid with a name of : $loc ($loc could be a1, c4, i8 and so on)
	$mySudoku->_viewSudoku($_SESSION["currentmoves"]);
	if (in_array(-1, $_SESSION["currentmoves"]))
	{
		$index = array_search(-1, $_SESSION["currentmoves"]);
		$_SESSION["currentmoves"][$index] = '--'; //displays a custom character(s) in the grid incase the value cant be determined through brute force
	}
?>