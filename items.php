<?php
session_start();
$_SESSION['current_page'] = "items.php";
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
		<?php if ($_SESSION['logged_in'] && (!$_SESSION['view_saved']))
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
				'<form id ="component1" method="post" class = "components" action="save_items.php">
					<div class= comptext id = comptext0> Magical weapon: </div>
					<div id= weapondiv>
					<input class="textboxes" id="weapons" type="text" name="weapons" value="'.$weapons[$index].'">
					<input class="textboxes" id="adjectives" type="text" name="adjectives" value="'.$adjectives[$index].'">
					</div>
					<textarea class="textboxes" id="backstories" rows="3"  name="backstories">'.$backstories[$index].'</textarea>
					<div class= comptext id = comptext1> On hit, the item does the damage shown below: </div>
					<input class="textboxes" id="damages" type="text" name="damages" value="'.$damages[$index].'">
					<div class= comptext id = comptext1> The item also has the following additional effect: </div>
					<textarea class="textboxes" id="effects" rows="6"  name="effects" >'.$effects[$index].'</textarea>
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
				$numsavedstmt = $myPDO->prepare("select saved_items from user where username = '$_SESSION[user]'");
				$numsavedstmt->execute();
				$numsaved = (($numsavedstmt->fetch()[0]));
				if($numsaved == 0){
				echo '	<form id ="component1" class = "components" action="save_item.php">
						<div class= comptext id = comptext0> You currently have no saved items. Generate more and then save an item you like. </div>
						</form>';
				}
				else{
					//display saved
					
					//fetch saved data
					$idstmt = $myPDO->prepare("SELECT id FROM saved_items WHERE user_id = '$_SESSION[userid]'");
					$idstmt->execute();
					$saved_id = $idstmt->fetchAll();
					
					$weaponstmt = $myPDO->prepare("SELECT item_type FROM saved_items WHERE user_id = '$_SESSION[userid]'");
					$weaponstmt->execute();
					$saved_weapon = $weaponstmt->fetchAll();
					
					$adjstmt = $myPDO->prepare("SELECT item_adjective FROM saved_items WHERE user_id = '$_SESSION[userid]'");
					$adjstmt->execute();
					$saved_adjective = $adjstmt->fetchAll();
					
					$backstorystmt = $myPDO->prepare("SELECT item_backstory FROM saved_items WHERE user_id = '$_SESSION[userid]'");
					$backstorystmt->execute();
					$saved_backstory = $backstorystmt->fetchAll();
					
					$damagestmt = $myPDO->prepare("SELECT item_damage FROM saved_items WHERE user_id = '$_SESSION[userid]'");
					$damagestmt->execute();
					$saved_damage = $damagestmt->fetchAll();
					
					$effectstmt = $myPDO->prepare("SELECT item_effects FROM saved_items WHERE user_id = '$_SESSION[userid]'");
					$effectstmt->execute();
					$saved_effect = $effectstmt->fetchAll();
					//echo saved components
					for($i = 0; $i<=$numsaved-1; $i++){
					$echo =
						'<form id ="component1" method = "post" class = "components" action="save_items.php">
						<div class= comptext id = comptext0> Magical weapon: </div>
						<div id= weapondiv>
						<input class="textboxes" id="weapons" type="text" name="weapons" value="'.$saved_weapon[$i][0].'">
						<input class="textboxes" id="adjectives" type="text" name="adjectives" value="'.$saved_adjective[$i][0].'">
						</div>
						<textarea class="textboxes" id="backstories" rows="3"  name="backstories">'.$saved_backstory[$i][0].'</textarea>
						<div class= comptext id = comptext1> On hit, the item does the damage shown below: </div>
						<input class="textboxes" id="damages" type="text" name="damages" value="'.$saved_damage[$i][0].'">
						<div class= comptext id = comptext1> The item also has the following additional effect: </div>
						<textarea class="textboxes" id="effects" rows="6"  name="effects" >'.$saved_effect[$i][0].'</textarea>
						<input id="hiddentextboxes" type="text" name="saved_id" value="'.$saved_id[$i][0].'">
						<input id = "submit" type="submit" value="Save">';					
						
							if (isset($_SESSION['message'])) {
								$echo .="<div id='message'>" . $_SESSION['message'] . "</div>";
							}
							unset($_SESSION['message']);
					$echo .='</form>';
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