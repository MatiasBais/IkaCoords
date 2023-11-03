<!DOCTYPE html>
<?php
require('../db_config.php');
$conn = new PDO("mysql:host=".$host.";dbname=".$database, $username, $password);

if(!$conn){
    echo "Could not connect!";
}

$player="";
if(empty($_GET['player'])){
    $player='';
}
else
    $player = $_GET['player'];

$ali="";
if(empty($_GET['ali'])){
    $ali='';
}
else
    $ali = $_GET['ali'];

$fpoints="";
if(empty($_GET['fpoints'])){
    $fpoints='';
}
else
    $fpoints = $_GET['fpoints'];

$tpoints="";
if(empty($_GET['tpoints'])){
    $tpoints='';
}
else
    $tpoints = $_GET['tpoints'];
$server="";
if(empty($_GET['server']))
    $server="Alpha";
else
    $server=$_GET['server'];
    
$country="";
if(empty($_GET['country']))
    $country="España";
else
    $country=$_GET['country'];    
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>IkaCoords</title>
</head>
<body class="body">
<?php

$subpage = "search";
$template = file_get_contents('../templates/Bars/topBar.html');
    $template = str_replace("{server}", $server, $template);

    // Define data



    // Output the final HTML
    echo $template;
    ?>
    <div class="row">
    <?php

    if($subpage == "search")
        $template = file_get_contents('../templates/Bars/leftBarSearch.html');
    if($subpage == "home")
        $template = file_get_contents('../templates/Bars/leftBarHome.html');
    if($subpage == "statistics")
        $template = file_get_contents('../templates/Bars/leftBarStatistics.html');

    $template = str_replace("{server}", $server, $template);
    $template = str_replace("{{$server}}", 'selected', $template);
echo $template;

?>
<script>
    var selectEl = document.getElementById('server');
    selectEl.onchange = function(){
        var goto = this.value;
        window.location = window.location.href.split('?')[0] +"?server="+ this.value;

    };
    </script>
  <div class="middle">

  <h1>Players</h1>
  <br>
  <form method="get">
  <input type="hidden" id="server" name="server" value="<?php echo $server;?>">
<table class="filters">
    <tr>
        <th>Player</th>
        <td>
			<input type="text" name="player" id="player" value="<?php echo $player;?>">
		</td>

        <th>Alliance</th>
        <td><input type="text" name="ali" id="ali" value="<?php echo $ali;?>"></td>
        
    </tr>
    <tr>
        <th>From Points</th>
        <td><input type="text" name="fpoints" id="fpoints" value="<?php echo $fpoints;?>"></td>

        <th>Till Points</th>
        <td><input type="text" name="tpoints" id="tpoints" value="<?php echo $tpoints;?>"></td>
        
    </tr>
    <tr>
        <td colspan="4">       
    <button type="submit">Search</button>
        </td>
    </tr>
</table>
</form>
<br>
<h2>Players</h2>
    <br>
    
<table class="results">
<tr>
        <th>Player</th>
        <th>Alliance</th>
        <th>Totales</th>
	<th>Δ-Totales</th>
        <th>Constructor</th>
	<th>Δ-Constructor</th>
        <th>Investigadores</th>
	<th>Δ-Investigadores</th>
        <th>Generales</th>
	<th>Δ-Generales</th>
        <th>Oro</th>
	<th>Δ-Oro</th>
	<th>Fecha</th>
    </tr>
    <?php
        
        $and="";
        if($ali!=""){
            $and=$and." and alianza.nombre ='".$ali."' ";
        }
        
        if($tpoints!=""){
            $and=$and." and (b.Constructor+b.Investigadores+b.Generales)<=".$tpoints;
        }
        if($fpoints!=""){
            $and=$and." and (b.Constructor+b.Investigadores+b.Generales)>=".$fpoints;
        }
		if($player!=""){
			$and=$and." and player.nombre like '%".$player."' ";
		}

        $sql = "select player.nombre as 'Player', alianza.nombre as 'Alianza', DATE_FORMAT(ub.fecha,'%d/%m/%y') as 'Fecha',FORMAT((b.Constructor+b.Investigadores+b.Generales),0)as 'Totales',FORMAT(((b.Constructor+b.Investigadores+b.Generales)-(a.Constructor+a.Investigadores+a.Generales)),0) as 'Δ-Totales' ,FORMAT(b.Constructor,0) as 'Constructor', FORMAT((b.Constructor-a.Constructor),0) as 'Δ-Constructor',FORMAT(b.investigadores, 0) as 'Investigadores', FORMAT((b.Investigadores-a.Investigadores),0) as 'Δ-Investigadores', FORMAT(b.Generales,0) as 'Generales', FORMAT((b.Generales-a.Generales),0) as 'Δ-Generales', FORMAT(b.oro,0) as 'Oro', FORMAT((b.Oro-a.Oro),0) as 'Δ-Oro' ,ua.numero,ub.numero from player
		left join puntos a on player.idplayer=a.idPlayer
        left join puntos b on player.idplayer=b.idPlayer
		left join alianza on player.idalianza=alianza.idalianza
        join updates ua on ua.numero =a.update
        join updates ub on ub.numero=b.update
        where ub.fecha = (select fecha from updates up where fecha > ua.fecha and server='".$server."' order by fecha asc limit 1 ) 
         and player.server='".$server."' and (alianza.server ='".$server."' or alianza.server is null) and ua.server='".$server."' and ub.server='".$server."' 
         ".$and.
        " order by ub.fecha 
         desc limit 50";
        $result = $conn->prepare($sql);
        $result->execute();
        if($result->rowCount() > 0):
            $rows = $result->fetchAll();
            foreach($rows as $row):
            
        ?>
        <tr>
            <td>
				<a href="../search/searchtown.php?player=<?php echo $row['Player'];?>&server=<?php echo $server;?>">
					<?php echo $row['Player'];  ?>
				</a>
			</td>
            <td><?php echo $row['Alianza'];  ?></td>
            <td><?php echo $row['Totales'];  ?></td>
            <td><?php echo $row['Δ-Totales'];  ?></td>
            <td><?php echo $row['Constructor'];  ?></td>
            <td><?php echo $row['Δ-Constructor'];  ?></td>
            <td><?php echo $row['Investigadores'];  ?></td>
            <td><?php echo $row['Δ-Investigadores'];  ?></td>
            <td><?php echo $row['Generales'];  ?></td>
            <td><?php echo $row['Δ-Generales'];  ?></td>
            <td><?php echo $row['Oro'];  ?></td>
            <td><?php echo $row['Δ-Oro'];  ?></td>
            <td><?php echo $row['Fecha'];  ?></td>
        </tr>
        <?php
        endforeach;
    endif;
        ?>

</table>


  </div>

</div>

<div class="footer">
    IkaCoords
</div>

</body>
</html>