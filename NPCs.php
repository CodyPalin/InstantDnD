<?php
session_start();
$_SESSION['current_page'] = "NPCs.php";
include 'Dao.php';
include 'random.php';
if(!isset($_SESSION['view_saved']))
	$_SESSION['view_saved'] = false;
if(!isset($_SESSION['logged_in']))
	$_SESSION['logged_in'] = false;
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
			<?php
			if(!$_SESSION['view_saved']){
				$echo =
				'<form id ="component1" method = "post" class = "components" action="save_NPC.php">
					<div class= comptext id = comptext0> NPC: </div>
					<div id= namediv>
					<input class="textboxes" id="name" type="text" name="name" value="'.$names[$index].'">
					<input class="textboxes" id="race" type="text" name="race" value="'.$races[$index].'">
					</div>
					<input class="textboxes" id="background" type="text" name="background" value="'.$backgrounds[$index].'">
					<div class= comptext id = comptext1> Alignment: </div>
					<input class="textboxes" id="alignment" type="text" name="alignment" value="'.$alignments[$index].'">
					<div class= comptext id = comptext1> <?php echo $names[$index];?> has the following items: </div>
					<input class="textboxes" id="weapons" type="text" name="weapons" value="'.$weapons[$index].'">
					<input class="textboxes" id="loots" type="text" name="loots" value="'.$loots[$index].'">
					<div class= comptext id = comptext1>'. $names[$index] .' can perform this attack with a '.$weapons[$index].'</div>
					<input class="textboxes" id="damages" type="text" name="damages" value="'.$damages[$index].'">
					<input id = "submit" type="submit" value="Save">';
					
						if (isset($_SESSION['message'])) {
								$echo .="<div id='message'>" . $_SESSION['message'] . "</div>";
							}
							unset($_SESSION['message']);
				$echo .=
				'</form>
				<input type=button id ="add" value="+" class = "components">';
				echo $echo;
			}
			else
			{
				$numsavedstmt = $myPDO->prepare("select saved_NPCs from user where username = '$_SESSION[user]'");
				$numsavedstmt->execute();
				$numsaved = (($numsavedstmt->fetch()[0]));
				if($numsaved == 0){
				echo '	<form id ="component1" class = "components" action="save_NPC.php">
						<div class= comptext id = comptext0> You currently have no saved NPCs. Generate more and then save an NPC you like. </div>
						</form>';
				}
				else{
					//display saved
					
					//fetch saved data
					$idstmt = $myPDO->prepare("SELECT id FROM saved_npcs WHERE user_id = '$_SESSION[userid]'");
					$idstmt->execute();
					$saved_id = $idstmt->fetchAll();
					
					$namestmt = $myPDO->prepare("SELECT NPC_name FROM saved_npcs WHERE user_id = '$_SESSION[userid]'");
					$namestmt->execute();
					$saved_names = $namestmt->fetchAll();
					
					$racestmt = $myPDO->prepare("SELECT NPC_race FROM saved_npcs WHERE user_id = '$_SESSION[userid]'");
					$racestmt->execute();
					$saved_races = $racestmt->fetchAll();
					
					$backgroundstmt = $myPDO->prepare("SELECT NPC_background FROM saved_npcs WHERE user_id = '$_SESSION[userid]'");
					$backgroundstmt->execute();
					$saved_backgrounds = $backgroundstmt->fetchAll();
					
					$alignmentstmt = $myPDO->prepare("SELECT char_alignment FROM saved_npcs WHERE user_id = '$_SESSION[userid]'");
					$alignmentstmt->execute();
					$saved_alignments = $alignmentstmt->fetchAll();
					
					$weaponstmt = $myPDO->prepare("SELECT NPC_weapon FROM saved_npcs WHERE user_id = '$_SESSION[userid]'");
					$weaponstmt->execute();
					$saved_weapons = $weaponstmt->fetchAll();
					
					$lootstmt = $myPDO->prepare("SELECT NPC_loot FROM saved_npcs WHERE user_id = '$_SESSION[userid]'");
					$lootstmt->execute();
					$saved_loots = $lootstmt->fetchAll();
					
					$damagestmt = $myPDO->prepare("SELECT NPC_weapon_damage FROM saved_npcs WHERE user_id = '$_SESSION[userid]'");
					$damagestmt->execute();
					$saved_damages = $damagestmt->fetchAll();
					//echo saved components
					for($i = 0; $i<=$numsaved-1; $i++){
					$echo =
						'<form id ="component'.$i.'" method = "post" class = "components" action="save_NPC.php">
						<div class= comptext id = comptext0> NPC: </div>
						<div id= namediv>
						<input class="textboxes" id="name" type="text" name="name" value="'.$saved_names[$i][0].'">
						<input class="textboxes" id="race" type="text" name="race" value="'.$saved_races[$i][0].'">
						</div>
						<input class="textboxes" id="background" type="text" name="background" value="'.$saved_backgrounds[$i][0].'">
						<div class= comptext id = comptext1> Alignment: </div>
						<input class="textboxes" id="alignment" type="text" name="alignment" value="'.$saved_alignments[$i][0].'">
						<div class= comptext id = comptext1> <?php echo $names[$index];?> has the following items: </div>
						<input class="textboxes" id="weapons" type="text" name="weapons" value="'.$saved_weapons[$i][0].'">
						<input class="textboxes" id="loots" type="text" name="loots" value="'.$saved_loots[$i][0].'">
						<div class= comptext id = comptext1>'. $saved_names[$i][0] .' can perform this attack with a '.$saved_weapons[$i][0].'</div>
						<input class="textboxes" id="damages" type="text" name="damages" value="'.$saved_damages[$i][0].'">
						<input id="hiddentextboxes" type="text" name="saved_id" value="'.$saved_id[$i][0].'">
						<input id = "submit" type="submit" value="Save">';
						
							if (isset($_SESSION['message'])) {
									$echo .="<div id='message'>" . $_SESSION['message'] . "</div>";
								}
								unset($_SESSION['message']);
						$echo .=
						'</form>';
						echo $echo;
					}
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