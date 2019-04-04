<?php
session_start();
$_SESSION['current_page'] = "dungeons.php";
include 'Dao.php';
include 'random.php';
if(!isset($_SESSION['view_saved']))
	$_SESSION['view_saved'] = false;
if(!isset($_SESSION['logged_in']))
	$_SESSION['logged_in'] = false;
$Dao = new Dao();
$myPDO=$Dao->getConnection();
$index = 1; //number of components

$numEnvs= $myPDO->query("select COUNT(*) from dungeon_environment_name")->fetch()[0];
$numLights= $myPDO->query("select COUNT(*) from dungeon_lighting")->fetch()[0];
$numLocations= $myPDO->query("select COUNT(*) from dungeon_loot_location")->fetch()[0];
//echo "numEnvs: ".$numEnvs; echo " Env: ".randomRow($numEnvs);
//echo " numLights: ".$numLights; echo " Light: ".randomRow($numLights);
//echo " numLocations: ".$numLocations; echo " Location: ".randomRow($numLocations);

$env = randomRow($numEnvs);
$light = randomRow($numLights);
$location = randomRow($numLocations);

$name = array("placeholder name");
$dungeon_environment = array("placeholder environment");
$dungeon_lighting = array("placeholder lighting");
$loot_locations = array("placeholder location");

array_push($name, $myPDO->query("select name from dungeon_environment_name where id = $env")->fetch()[0]);
array_push($dungeon_environment, $myPDO->query("select environment from dungeon_environment_name where id = $env")->fetch()[0]);
array_push($dungeon_lighting, $myPDO->query("select lighting from dungeon_lighting where id = $light")->fetch()[0]);
array_push($loot_locations, $myPDO->query("select location from dungeon_loot_location where id = $location")->fetch()[0]);
?>
<html>
  <head>
    <link rel="stylesheet" href="css/layoutDungeons.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
  </head>
  <body>
    	<div id="container">
		<ul id="top-container">
			<li id="logo"><img src="CodyDND.png"></li>
			<?php 	if($_SESSION['logged_in']){ echo "<button onclick=\"window.location.href='logout.php';\"id='login'>logout</button>"; }
					else {echo"<button onclick=\"window.location.href='login.php';\"id='login'>login/register</button>";}
			?>
		</ul>
		<?php if ($_SESSION['logged_in'] && (!isset($_SESSION['view_saved'])|| !$_SESSION['view_saved']))
			{
				echo "<button id='welcome' onclick=\"window.location.href='view_saved.php';\">".$_SESSION['user']."'s saved</button>";
			}
			else if($_SESSION['logged_in'] && $_SESSION['view_saved'])
			{
				echo "<button id='welcome' onclick=\"window.location.href='view_saved.php';\">Generate more</button>";
			}
		?>
		<div id="title">Randomly Generate DnD</div>
		<ul id="tabs">
			<li id="character-sheets" class="tabs" ><a href=" character-sheets.php">Character Sheets</a></li>
			<li id="items" class="tabs" ><a href="items.php">Items</a></li>
			<li id="encounters" class="tabs" ><a href="encounters.php">Encounters</a></li>
			<li id="NPCs" class="tabs" ><a href="NPCs.php">NPCs</a></li>
			<li id="dungeons" class="tabs" ><a href="dungeons.php">Dungeons</a></li>
		</ul>
		<ul id = components>
			<?php
			if(!$_SESSION['view_saved']){
				echo
				'<form id ="component1" class = "components" action="save_dungeon.php">
					<div class= comptext id = comptext0> The dungeon is: </div>
					<input class="textboxes" id="name" type="text" name="name" value="'.$name[$index].'">
					<div class= comptext id = comptext0> and is located: </div>
					<input class="textboxes" id="dungeon_environment" type="text" name="dungeon_environment" value="'.$dungeon_environment[$index].'">
					<div class= comptext id = comptext2> The dungeon is currently: </div>
					<input class="textboxes" id="dungeon_lighting" type="text" name="dungeon_lighting" value="'.$dungeon_lighting[$index].'">
					<div class= comptext id = comptext3> There is loot in this dungeon:</div>
					<input class="textboxes" id="loot_locations" type="text" name="loot_locations" value="'.$loot_locations[$index].'">
					<input id = "submit" type="submit" value="Save">
				</form>
				<input type=button id ="add" value="+" class = "components">';
			}
			else
			{
				$numsavedstmt = $myPDO->prepare("select saved_dungeons from user where username = '$_SESSION[user]'");
				$numsavedstmt->execute();
				$numsaved = (($numsavedstmt->fetch()[0]));
				if($numsaved == 0){
				echo '	<form id ="component1" class = "components" action="save_dungeon.php">
						<div class= comptext id = comptext0> You currently have no saved dungeons. Generate more and then save a dungeon you like. </div>
						</form>';
				}
				else{
					//display saved
				}
			}
			?>
		</ul>
			</div>
		<div id = "footer">
			<div id = "fcontent1" >This web site is not affiliated with, endorsed, sponsored, or specifically approved by Wizards of the Coast LLC. This web site may use the trademarks and other intellectual property of Wizards of the Coast LLC, which is permitted under Wizards' Fan Site Policy. For example, Dungeons & Dragons® is a trademark of Wizards of the Coast. For more information about Wizards of the Coast or any of Wizards' trademarks or other intellectual property, please visit their website at (www.wizards.com).</div>
		</div>

  </body>
</html>