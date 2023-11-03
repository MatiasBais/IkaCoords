<!DOCTYPE html>
<?php
require('../db_config.php');
$conn = new PDO("mysql:host=".$host.";dbname=".$database, $username, $password);

if(!$conn){
    echo "Could not connect!";
}
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
$fecha="";
if(empty($_GET['fecha'])){
     $sql = "SELECT numero, DATE_FORMAT(fecha,'%d/%m/%y') as 'Fecha' FROM updates where server='".$server."' order by numero asc";
        $result = $conn->prepare($sql);
        $result->execute();
        if($result->rowCount() > 0):
            $rows = $result->fetchAll();
            foreach($rows as $row):
       			$fecha = $row['numero'];
			endforeach;
        endif;
}
else
    $fecha = $_GET['fecha'];

$offset="";
if(empty($_GET['offset'])){
    $offset='0';
}
else
    $offset = $_GET['offset'];
      
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

$subpage = "statistics";
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
  <br>
  <form method="get">
  <input type="hidden" id="server" name="server" value="<?php echo $server;?>">
<table class="filterss filters">
    <tr>
    <th >Posición:</th>
    <th>Día:</th>
</tr>
<tr>
<td>
    <select name="offset" id="offset">
		<?php
		$selected = $offset / 50;
		for($j=0;$j<80;$j++){
			echo '<option value="';
			echo $j*50;
			echo '"';
			if($j == $selected)
				echo ' selected';
			echo '>';
			echo $j*50+1;
			echo '-';
			echo 50*($j+1);
			echo '</option>';
		}
	?>
</select>
</td>
<td>
    <select name="fecha" id="fecha">
        <?php
        $sql = "SELECT numero, DATE_FORMAT(fecha,'%d/%m/%y') as 'Fecha' FROM updates where server='".$server."' order by numero desc";
        $result = $conn->prepare($sql);
        $result->execute();
        if($result->rowCount() > 0):
            $rows = $result->fetchAll();
            foreach($rows as $row):
            
                ?>
                <option value="<?php echo $row['numero'];?>" <?php if($fecha==$row['numero']) echo 'selected'; ?>><?php echo $row['Fecha'] ?></option>
        
                <?php
                endforeach;
            endif;
                ?>
        
  
</select>
</td>
	</tr>
	<tr>
<td colspan="2">
    <input type="submit" name="" id="">
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
        <th>Town-Name</th>
        <th>TownLv</th>
        <th>X</th>
        <th>Y</th>
        <th>Wonder</th>
        <th>Wood</th>
        <th>Good</th>
        <th>Totales</th>
        <th>Constructor</th>
        <th>Investigadores</th>
        <th>Generales</th>
        <th>Oro</th>
    </tr>
    <?php
  

        $sql = "select player.nombre as 'Player', alianza.nombre as 'Alliance', city.nombre as 'Town-Name', city.nivel as 'TownLv', x, y, wonderName as 'Wonder', woodlv as 'Wood', good, goodlv, FORMAT(b.Constructor,0) as 'Constructor', FORMAT(b.Investigadores,0) as 'Investigadores', FORMAT(b.Generales,0) as 'Generales', FORMAT(b.Oro,0) as 'Oro', FORMAT((b.Constructor+b.Investigadores+b.Generales),0) as 'Totales' 
        from player 
        left join alianza on player.idAlianza = alianza.idalianza 
        join city on city.playerid=player.idplayer 
        join isla on city.islaid = isla.idisla left 
        join puntos b on player.idplayer=b.idPlayer 
        join updates ub on ub.numero=b.update 
        where ub.numero =".$fecha." 
        and city.update=ub.numero 
        and player.server='".$server."' and city.server='".$server."' and (alianza.server = '".$server."' or alianza.server is null) and isla.server='".$server."' 
		order by city.nivel desc ".
        "limit ".$offset.",50";
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
            <td><?php echo $row['Alliance'];  ?></td>
            <td><?php echo $row['Town-Name'];  ?></td>
            <td><?php echo $row['TownLv'];  ?></td>
            <td><?php echo $row['x'];  ?></td>
            <td><?php echo $row['y'];  ?></td>
            <td><?php echo $row['Wonder'];  ?></td>
            <td><?php echo $row['Wood'];  ?></td>
            <td><?php echo $row['goodlv']  ?></td>
            <td><?php echo $row['Totales'];  ?></td>
            <td><?php echo $row['Constructor'];  ?></td>
            <td><?php echo $row['Investigadores'];  ?></td>
            <td><?php echo $row['Generales'];  ?></td>
            <td><?php echo $row['Oro'];  ?></td>
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