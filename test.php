<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Play Sudoku!</title>
		<style>
			body{
				height: 100%;
				background-color: #ADD8E6;
			}
			#header
			{
				width: 100%;
				height: 10px;
				background-color: transparent;
				border-radius: 0px 0px 5px 5px;
				word-spacing: 60%;
				letter-spacing: 50%;
				text-align: center;
				font-size: 2em;
				font-variant: small-caps;
				font-weight: bold;
				font-family: verdana, sans-serif;
				color: #800000;
				text-shadow: 1px 1px 10px #F5FFFA;
			}
			#header > h2
			{
				position: relative;
				top: -43px;
			}
			#control
			{
				width: 25%;
				background-color: #000;
				float: left;
				height: 600px;
				border: 2px solid #fff;
				border-radius: 20px;
			}
			#container
			{
				width: 70%;
				background-color: #fff;
				float: right;
				border: 2px solid #000;
				border-radius: 20px;
			}
			table
			{
				border-radius: 20px;
			}
			td
			{
				border: 1px solid #000;
				width: 10%;
			}
			#location
			{
				width: 99%;
				background-color: transparent;
				border: 3px solid #fff;
				border-radius: 20px 20px 0px 0px;
				box-shadow: 5px 4px 7px #ADD8E6 3px;
				height: 120px;
				text-transform: lowercase;
				font-size: 2em;
				text-align: center;
				color: #800000;
			}
			#location:hover, #location:active
			{
				background-color: #3d3d3d;
				font-size: 3em;
				color: #fff;
			}
			#submit
			{
				width: 100%;
				background-color: #800000;
				border: 3px solid #fff;
				border-radius: 0px 0px 20px 20px;
				box-shadow: 5px 4px 7px #ADD8E6 3px;
				height: 120px;
				font-variant: small-caps;
				font-size: 2em;
				text-align: center;
				color: #F5FFFA;
			}
			#submit:hover
			{
				background-color: #fff;
				font-size: 4em;
				color: #800000;
			}
			#submit:active
			{
				background-color: #ADD8E6;
				font-size: 3em;
				color: #800000;
			}
			.abitbig
			{
				width: 45%;
				height: 50px;
				padding-left: 20px;
				border-radius: 10px;
				margin-left: 2px;
			}
		</style>
		<script src ="jquery.js"></script>
	</head>
	<body>
		<div id = "header">
			<h2>Play Sudoku!</h2>
		</div>
		<div id = "control">
			<input type = "text" id = "location" placeholder = "Enter The Location To Fill"/>
			<br/><br/>
			<select id = "so" class = "abitbig">
				<option value = ''>Start Over?</option>
				<option value = "true">Yes</option>
				<option value = "false">No</option>
			</select>
			<input type = "number" class = "abitbig" min = "1" max = "9" placeholder = "9 X 9 or 4 X 4?" id = "st"/>
			<br/><br/>
			<input type = "submit" id = "submit" value = "Calculate!" />
		</div>
		<div id = "container">
		</div>
	</body>
	<script>
		$(document).ready(function(){
			$("#submit").on("click", function(){
				var val = $("#location").val();
				var so = $("#so").val();
				var st = $("#st").val();
				//alert("so = "+so+", st = "+st+", val = "+val);
				if (so == '')
				{
					so = "false";
				}
				else
				{
					so = "true";
				}
				if (val == '')
				{
					alert("You have not entered a valid location");
				}
				else
				{
					$("#container").load("testProcessor.php?loc="+val+"&startover="+so+"&sudokutype="+st);
					$("#so").val('');
				}
			});
		});
	</script>
</html>