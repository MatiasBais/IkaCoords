<!DOCTYPE html>
<?php
require('../db_config.php');
$conn = new PDO("mysql:host=".$host.";dbname=".$database, $username, $password);

if(!$conn){
    echo "Could not connect!";
}



$offset="";
if(empty($_GET['offset'])){
    $offset='0';
}
else
    $offset = $_GET['offset'];
$server="Alpha";
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
        $sql = "SELECT numero, DATE_FORMAT(fecha,'%d/%m/%y') as 'Fecha' FROM updates  where updates.server='".$server."' order by numero asc";
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

  <h1>Players</h1>
  <br>
  <form method="get">
  <input type="hidden" id="server" name="server" value="<?php echo $server;?>">
<table class="filters">
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
        $sql = "SELECT numero, DATE_FORMAT(fecha,'%d/%m/%y') as 'Fecha' FROM updates where updates.server='".$server."' order by numero desc ";
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
<thead>
        <tr>
        <th>Posición</th>
            <th>Jugador</th>
			<th>Alianza</th>
            <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
        <?php
        
 
    

        $sql = "select player.nombre as 'nombre', 
alianza.nombre as 'Alianza',
count(city.idcity) as'Cantidad' 
from player 
join city on playerid=idplayer
join updates ub on ub.numero=city.update
left join alianza on player.idalianza = alianza.idalianza  
        where ub.numero =".$fecha.
        " and player.server='".$server."' and city.server='".$server."' and (alianza.server = '".$server."' or alianza.server is null)    
        group by idplayer 
		order by count(city.idcity) desc 
        limit ".$offset.",50";;
        $result = $conn->prepare($sql);
        $result->execute();
        if($result->rowCount() > 0):
            $rows = $result->fetchAll();
            $i=1+$offset;
            foreach($rows as $row):
            
        ?>
        <tr class="active-row">
        <td><?php echo $i; ?></td>
             <td>
				<a href="../search/searchtown.php?player=<?php echo $row['nombre'];?>&server=<?php echo $server;?>">
					<?php echo $row['nombre'];  ?>
				</a>
			</td>
            <td><?php echo $row['Alianza'];  ?></td>
            <td><?php echo $row['Cantidad']; ?></td>
        </tr>
        </tbody>

        <?php
        $i++;
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