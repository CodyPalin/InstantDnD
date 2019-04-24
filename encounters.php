<?php
session_start();
$_SESSION['current_page'] = "encounters.php";
include 'Dao.php';
if(!isset($_SESSION['view_saved']))
	$_SESSION['view_saved'] = false;
if(!isset($_SESSION['logged_in']))
	$_SESSION['logged_in'] = false;
$Dao = new Dao();
$myPDO=$Dao->getConnection();
?>
<html>
  <head>
    <link rel="stylesheet" href="css/layoutEncounters.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
  </head>
	<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script>
		function generate(id) {
			var x = document.forms[id]['level'].value;
			var y = document.forms[id]['difficulty'].value;
			//alert("generate component at difficulty: " +y+ " and level: " +x+" id: "+id);
			//$("#"+id).slideUp(0);
			event.preventDefault();
			var dataString ="difficulty="+y+"&level="+x;
			$.ajax({
				type: "POST",
				url: 'generate_encounter.php',
				data: dataString,
				success: function(result){
					$("#"+id).html(result);
					$("#"+id).attr("onsubmit","");
					$("#"+id).attr("action","save_encounter.php");
					$("#"+id).attr("id", "new_".id);
					
				}
			});
		}
		
	</script>
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
				$echo = "	<form id ='component1' method = 'post' action= 'generate_encounter.php' onsubmit=\"return generate(id)\" class = 'components' >
							<div class= comptext id = comptext0> Generate encounter: </div>
							<div class= comptext id = comptext1> With a difficulty of </div>
							<select name='difficulty'>
								<option value='easy'>Easy</option>
								<option value='medium'>Medium</option>
								<option value='hard'>Hard</option>
								<option value='deadly'>Deadly</option>
							</select>
							<div class= comptext id = comptext2> for 4 characters of level: </div>
							<select name='level'>
								<option value='1'>1</option>
								<option value='2'>2</option>
								<option value='3'>3</option>
								<option value='4'>4</option>
								<option value='5'>5</option>
								<option value='6'>6</option>
								<option value='7'>7</option>
								<option value='8'>8</option>
								<option value='9'>9</option>
								<option value='10'>10</option>
								<option value='11'>11</option>
								<option value='12'>12</option>
								<option value='13'>13</option>
								<option value='14'>14</option>
								<option value='15'>15</option>
								<option value='16'>16</option>
								<option value='17'>17</option>
								<option value='18'>18</option>
								<option value='19'>19</option>
								<option value='20'>20</option>
							</select>
							<input id = 'generate1' class=\"generatebutton\" type='submit' value='Generate'>
						";
						if (isset($_SESSION['message'])) {
							$echo .="<div id='message'>" . $_SESSION['message'] . "</div>";
						}
						unset($_SESSION['message']);
				$echo .=	
				'</form>
				<input type=button id ="add" value="+" class = "components">';
				echo $echo;
			}
			else //if saved is set
			{	//show saved components
				$numsavedstmt = $myPDO->prepare("select saved_encounters from user where username = '$_SESSION[user]'");
				$numsavedstmt->execute();
				$numsaved = (($numsavedstmt->fetch()[0]));
				if($numsaved == 0){
				echo '	<form id ="component1" class = "components" action="save_encounter.php">
						<div class= comptext id = comptext0> You currently have no saved encounters. Generate more and then save an encounter you like. </div>
						</form>';
				}
				else{
					$stmt = $myPDO->prepare("SELECT * FROM saved_encounters WHERE user_id = '$_SESSION[userid]'");
					$stmt->execute();
					$saved = $stmt->fetchAll();
					//print_r($saved);
					$idstmt = $myPDO->prepare("SELECT id FROM saved_encounters WHERE user_id = '$_SESSION[userid]'");
					$idstmt->execute();
					$saved_id = $idstmt->fetchAll();
					
					//$saved['monster1'] through monster 8 are arrays, empty elements will be null
					
					//echo saved components
					for($i = 0; $i<=$numsaved-1; $i++){
						
						$numMonsters = 8;
						for($j = 1; $j<=8; $j++)
						{
							if(is_null($saved[$i]['monster'.$j])){
								$numMonsters = $j-1;
								break;
							}
						}
						
						$echo =
							'<form id new_component'.$i.'" method = "post" class = "components" action="save_encounter.php">
								<div class= comptext id = comptext0> You encounter these monsters: </div>';
								for($j=1;$j<=$numMonsters;$j++)
								{
									
									$echo.='<input class="textboxes" id="monster'.$j.'" type="text" name="monster'.$j.'" value="'.$saved[$i]['monster'.$j].'">';
								}
								if (isset($_SESSION['message'])) {
								$echo .="<div id='message'>" . $_SESSION['message'] . "</div>";
								}
								unset($_SESSION['message']);
						$echo.='<input id="hiddentextboxes" type="text" name="saved_id" value="'.$saved_id[$i][0].'">
								<input id = "submit" type="submit" value="Save"></form>';
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
