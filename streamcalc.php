<! DOCTYPE html>
<html>
<head>
	<title>0FFcast Stream Calculator</title>
	<style type="text/css">
		body
		{
			margin: 0 auto;
		}		
		html, h1, form, fieldset, legend, ol, li 
		{
			margin: 0;
			padding: 0;
		}
		body 
		{
			background: #ffffff;
			color: #111111;
			font-family: Georgia, "Times New Roman", Times, serif;
			padding: 20px;
		}
		
		h1
		{
			font-size: 20pt;
			font-family: Georgia, "Times New Roman", Times, serif;
			text-align: center;
		}
		
		form#streamcalc
		{
			background: #9cbc2c;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			border-radius: 5px;
			padding: 20px;
			width: 400px;
		}
		form#streamcalc fieldset 
		{
			border: none;
			margin-bottom: 10px;
		}
		
		form#streamcalc legend 
		{
			color: #384313;
			font-size: 16px;
			font-weight: bold;
			padding-bottom: 10px;
			text-shadow: 0 1px 1px #c0d576;
		}
		
		form#streamcalc fieldset:last-of-type 
		{
			margin-bottom: 0;
		}

		form#streamcalc fieldset fieldset legend 
		{
			color: #111111;
			font-size: 13px;
			font-weight: normal;
			padding-bottom: 0;
		}

		form#streamcalc ol li 
		{
			background: #b9cf6a;
			background: rgba(255,255,255,.3);
			border-color: #e3ebc3;
			border-color: rgba(255,255,255,.6);
			border-style: solid;
			border-width: 2px;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			border-radius: 5px;
			line-height: 30px;
			list-style: none;
			padding: 5px 10px;
			margin-bottom: 5px;
		}
		
		form#streamcalc ol ol li 
		{
			background: none;
			border: none;
			float: left;
		}
		
		form#streamcalc label 
		{
			float: left;
			font-size: 13px;
			width: 110px;
		}
		
		form#streamcalc label[for=high], label[for=standard]
		{
			line-height: 20px;
			padding-right: 50px;
		}

		form#streamcalc fieldset fieldset label 
		{
			background:none no-repeat left 50%;
			line-height: 20px;
			padding: 0 0 0 60px;
			width: auto;
		}
		
		form#streamcalc fieldset fieldset label:hover 
		{
			cursor: pointer;
		}

		form#streamcalc input:not([type=radio]),
		
		form#streamcalc textarea 
		{
			background: #ffffff;
			border: none;
			-moz-border-radius: 3px;
			-webkit-border-radius: 3px;
			-khtml-border-radius: 3px;
			border-radius: 3px;
			font: italic 13px Georgia, "Times New Roman", Times, serif;
			outline: none;
			padding: 5px;
			width: 200px;
		}
		
		form#streamcalc input:not([type=submit]):focus,
		
		form#streamcalc textarea:focus 
		{
			background: #eaeaea;
		}

		form#streamcalc input[type=radio] 
		{
			float: left;
			margin-right: 10px;
		}

		form#streamcalc button 
		{
			background: #384313;
			border: none;
			-moz-border-radius: 20px;
			-webkit-border-radius: 20px;
			-khtml-border-radius: 20px;
			border-radius: 20px;
			color: #ffffff;
			display: block;
			font: 18px Georgia, "Times New Roman", Times, serif;
			letter-spacing: 1px;
			margin: auto;
			padding: 7px 25px;
			text-shadow: 0 1px 1px #000000;
			text-transform: uppercase;
		}
		
		form#streamcalc button:hover 
		{
			background: #1e2506;
			cursor: pointer;
		}
		
		#values, #recommendedres
		{
			display:none;
		}
		
		#loading 
		{
			display: none;
		}
	</style>
	<?php
	try
	{
		$bitrate = (int)$_POST['bitrate'];
		$width = (int)$_POST['width'];
		$height = (int)$_POST['height'];	
		$framerate = (float)$_POST['framerate'];
		$ratio = $_POST['definition'];
		$ratiowidth = 0;
		$ratioheight = 0;
		if ($ratio == "SD")
		{
			$ratiowidth = 4;
			$ratioheight = 3;
		}
		else
		{
			$ratiowidth = 16;
			$ratioheight = 9;
		}		
		if ($bitrate == 0 || $width == 0 || $height == 0|| $framerate == 0)
		{
			throw new Exception("Value missing");
		}
		$recommended = array();
		$value = ($bitrate * 1000)/($width * $height * $framerate);
		$n = 0;
		if ($value > 0.19 && $value < 0.21)
		{
			$recommended[$n] = "".$width." x ".$height." Value: ".$value;
			$n++;
		}
		$scaledwidth = $width;
		$scaledheight = $height;
		
		while ($scaledwidth > 100)
		{			
			$scaledwidth = $scaledwidth - $ratiowidth;
			$scaledheight = $scaledheight - $ratioheight;
			$newval = ($bitrate * 1000)/($scaledwidth * $scaledheight * $framerate);
			if ($newval > 0.1999 && $newval < 0.20999)
			{
				$recommended[$n] = "".$scaledwidth." x ".$scaledheight." Value: ".$newval;
				$n++;
			}
		}
	}
	catch(Exception $e)
	{
		$value = null;
	}
	?>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script>
	$(document).ready(function()
	{
		$("#loading").hide();
		if($("#framerate").val() != 0)
		{
			$("#values").fadeIn('slow');
		}
		
		if($("#framerate").val() != 0)
		{
			$("#recommendedres").fadeIn('slow');
		}
	});
	
	$("#streamcalc").submit(function()
	{
		$("#loading").show();
		$("#values").fadeOut('slow');
		$("#recommendedres").fadeOut('slow');
	});
	</script>
</head>

<body>
	<form id="streamcalc" action="" method="POST">
	<h1>0ffcast Stream Calculator</h1>
	<fieldset id="values">
		<legend>Stream Value</legend>
		<ol>
			<li id="streamvalue">
			<?php 
				if ($value != null)
				{
					echo "Value: ".$value;
				}
			?></li>
		<ol>
	</fieldset>
	
	<fieldset id="recommendedres">
		<legend>Recommended Resolutions</legend>
		<ol id="reslist">
		<?php
		$count = count($recommended);
		for ($i = 0; $i < $count; $i++) 
		{
			echo "<li>".$recommended[$i]."</li>";
		}
		?>
		</ol>
	</fieldset>
	<br />
	<fieldset>
		<legend>Definition</legend>
		<ol>
			<li>
				<input id="standard" name="definition" type="radio" value="SD" />
				<label for="standard">Standard (4:3)</label>
				<input id="high" name="definition" type="radio" value="HD" checked/>
				<label for="high">High (16:9)</label><br/>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Bit & Frame Rates</legend>
		<ol>
			<li>
				<label for=bitrate>Bit Rate</label>
				<input id=bitrate name=bitrate type=number min=1 value=<?php echo $bitrate?> autofocus required>
			</li>
			<li>
				<label for=framerate>Frame Rate</label>
				<input id=framerate name=framerate type=number min=1 value=<?php echo $framerate?> required>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Dimensions</legend>
		<ol>
			<li>
				<label for=width>Width</label>
				<input id=width name=width type=number min=1 value=<?php echo $width ?> required>
			</li>
			<li>
				<label for=height>Height</label>
				<input id=height name=height type=number min=1 value=<?php echo $height ?> required>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<button type=submit id="submit">Calculate! <img src="./ajax-loader.gif" name="loading" id="loading" width="15px" height="15px" /></button>
	</fieldset>
	</form>
</body>
</html>