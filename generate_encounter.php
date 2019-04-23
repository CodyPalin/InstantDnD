<?php
session_start();
include 'Dao.php';
include 'random.php';
$Dao = new Dao();
$myPDO=$Dao->getConnection();

$sql = "select * from encounter_size where difficulty='".$_POST['difficulty']."' and level = '".$_POST['level']."'";
$stmt = $myPDO->prepare($sql);
$stmt->execute();
$array = $stmt->fetchAll();
$count = count($array);

$encounter = $array[rand(0,$count-1)];
$num_monsters = rand($encounter['minimum'],$encounter['maximum']);
$num_monster = []; //array for the number of each type of monster
$total = 0;
for($i=1;$i<=8;$i++)
{
	if($total < $num_monsters){
	$num_monster[$i] = rand(1,$num_monsters-$total);
	$total += $num_monster[$i];
	}
}
$types = count($num_monster);
$type = [];
$monsterstmt = $myPDO->prepare("select * from monster_list where CR_rating=".$encounter['CR']);
$monsterstmt->execute();
$monsterArray = $monsterstmt->fetchAll();
//print_r($monsterArray);
for($i=1;$i<=$types;$i++)
{
	$type[$i] = $monsterArray[rand(0,(count($monsterArray)-1))];
	
}
$result =
	'<form id ="component1" method = "post" class = "components" action="save_encounter.php">
		<div class= comptext id = comptext0> You encounter '.$num_monsters.' monsters: </div>';
		for($i=1;$i<=$types;$i++)
		{
			$plural = 's';
			if($num_monster[$i] ==1)
				$plural = '';
			$result.='<input class="textboxes" id="monster'.$i.'" type="text" name="name" value="'.$num_monster[$i].' '.$type[$i]['monster'].$plural.'">';
		}
$result.='<input id = "submit" type="submit" value="Save"></form>';
echo $result;
?>