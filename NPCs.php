<?php
session_start();
$_SESSION['current_page'] = "NPCs.php";
include 'Dao.php';
include 'random.php';
$Dao = new Dao();
$myPDO=$Dao->getConnection();
$index = 1; //number of components

$numWeapons= $myPDO->query("select COUNT(*) from default_items")->fetch()[0];
$numBackgrounds= $myPDO->query("select COUNT(*) from npc_background")->fetch()[0];
$numLoots= $myPDO->query("select COUNT(*) from npc_loot")->fetch()[0];
$numAlignments= $myPDO->query("select COUNT(*) from alignment")->fetch()[0];
$numClasses= $myPDO->query("select COUNT(*) from class")->fetch()[0];
$numNames= $myPDO->query("select COUNT(*) from random_names")->fetch()[0];

$weapon = randomRow($numWeapons);
$background = randomRow($numBackgrounds);
$loot = randomRow($numLoots);
$alignment = randomRow($numAlignments);
$class = randomRow($numClasses);
$name = randomRow($numNames);

$weapons = array("placeholder weapon");
$damages = array("placeholder damage");
$loots = array("placeholder loot");
$alignments = array("placeholder alignment");
$names = array("placeholder name");
$races = array("placeholder race");
$backgrounds = array("placeholder background");

array_push($weapons, $myPDO->query("select item_type from default_items where id = $weapon")->fetch()[0]);
array_push($damages, $myPDO->query("select damage from default_items where id = $weapon")->fetch()[0]);
array_push($loots, $myPDO->query("select loot from npc_loot where id = $loot")->fetch()[0]);
array_push($alignments, $myPDO->query("select alignments from alignment where id = $alignment")->fetch()[0]);
array_push($names, $myPDO->query("select name from random_names where id = $name")->fetch()[0]);
array_push($races, $myPDO->query("select race from random_names where id = $name")->fetch()[0]);
array_push($backgrounds, $myPDO->query("select NPC_background from npc_background where id = $background")->fetch()[0]);

?>
<html>
  <head>
	<meta name="google" content="notranslate">
    <link rel="stylesheet" href="css/layoutNPC.css">
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
			<li id ="component1" class = "components">
				<div class= comptext id = comptext0> NPC: </div>
				<div id= namediv>
				<input class="textboxes" id="name" type="text" name="name" value="<?php echo $names[$index];?>">
				<input class="textboxes" id="race" type="text" name="race" value="<?php echo $races[$index];?>">
				</div>
				<input class="textboxes" id="background" type="text" name="background" value="<?php echo $backgrounds[$index];?>">
				<div class= comptext id = comptext1> Alignment: </div>
				<input class="textboxes" id="alignment" type="text" name="alignment" value="<?php echo $alignments[$index];?>">
				<div class= comptext id = comptext1> <?php echo $names[$index];?> has the following items: </div>
				<input class="textboxes" id="weapons" type="text" name="weapons" value="<?php echo $weapons[$index];?>">
				<input class="textboxes" id="loots" type="text" name="loots" value="<?php echo $loots[$index];?>">
				<div class= comptext id = comptext1> <?php echo $names[$index];?> can perform these attacks: </div>
				<input class="textboxes" id="damages" type="text" name="damages" value="<?php echo $weapons[$index].': '.$damages[$index];?>">
				
			</li>
			<input type=button id ="add" value="+" class = "components">
		</ul>
			</div>
		<div id = "footer">
			<div id = "fcontent1">Footer Stuff</div>
		</div>

  </body>
</html>