<?php
session_start();
$_SESSION['current_page'] = "items.php";
include 'Dao.php';
include 'random.php';
$Dao = new Dao();
$myPDO=$Dao->getConnection();
$index = 1; //number of components

$numWeapons= $myPDO->query("select COUNT(*) from default_items")->fetch()[0];
$numEffects= $myPDO->query("select COUNT(*) from item_magic_effects")->fetch()[0];
$numBackstories= $myPDO->query("select COUNT(*) from item_backstories")->fetch()[0];

$weapon = randomRow($numWeapons);
$effect = randomRow($numEffects);
$backstory = randomRow($numBackstories);

$weapons = array("placeholder weapon");
$damages = array("placeholder damage");
$adjectives = array("placeholder adjective");
$effects = array("placeholder effect");
$backstories = array("placeholder backstory");

array_push($weapons, $myPDO->query("select item_type from default_items where id = $weapon")->fetch()[0]);
array_push($damages, $myPDO->query("select damage from default_items where id = $weapon")->fetch()[0]);
array_push($adjectives, $myPDO->query("select adjective from item_magic_effects where id = $effect")->fetch()[0]);
array_push($effects, $myPDO->query("select magical_effect from item_magic_effects where id = $effect")->fetch()[0]);
array_push($backstories, $myPDO->query("select backstory from item_backstories where id = $backstory")->fetch()[0]);
?>
<html>
  <head>
    <link rel="stylesheet" href="css/layoutItems.css">
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
				<div class= comptext id = comptext0> Magical weapon: </div>
				<div id= weapondiv>
				<input class="textboxes" id="weapons" type="text" name="weapons" value="<?php echo $weapons[$index];?>">
				<input class="textboxes" id="adjectives" type="text" name="adjectives" value="<?php echo $adjectives[$index];?>">
				</div>
				<textarea class="textboxes" id="backstories" rows="3"  name="backstories"><?php echo $backstories[$index];?></textarea>
				<div class= comptext id = comptext1> On hit, the item does the damage shown below: </div>
				<input class="textboxes" id="damages" type="text" name="damages" value="<?php echo $damages[$index];?>">
				<div class= comptext id = comptext1> The item also has the following additional effect: </div>
				<textarea class="textboxes" id="effects" rows="6"  name="effects" ><?php echo $effects[$index];?></textarea>
				
			</li>
			<input type=button id ="add" value="+" class = "components">
		</ul>
			</div>
		<div id = "footer">
			<div id = "fcontent1">Footer Stuff</div>
		</div>

  </body>
</html>