<!DOCTYPE html>
<?php
require('../db_config.php');
$conn = new PDO("mysql:host=".$host.";dbname=".$database, $username, $password);

if(!$conn){
    echo "Could not connect!";
}

$fecha="";
if(empty($_GET['fecha'])){
    $fecha='0';
}
else
    $fecha = $_GET['fecha'];

$cla="";
if(empty($_GET['cla'])){
	$cla='Totales';
}
else{
    $cla = $_GET['cla'];
}

$offset="";
if(empty($_GET['offset'])){
    $offset='0';
}
else
    $offset = $_GET['offset'];

$ali="";
if(empty($_GET['ali'])){
    $ali='';
}
else
    $ali = $_GET['ali'];

$fx="";
if(empty($_GET['fx'])){
    $fx='';
}
else
    $fx = $_GET['fx'];

$tx="";
if(empty($_GET['tx'])){
    $tx='';
}
else
    $tx = $_GET['tx'];

$fy="";
if(empty($_GET['fy'])){
    $fy='';
}
else
    $fy = $_GET['fy'];

$ty="";
if(empty($_GET['ty'])){
    $ty='';
}
else
    $ty = $_GET['ty'];

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

  <h1>Inactive Players</h1>
  <br>
  <form method="get">
  <input type="hidden" id="server" name="server" value="<?php echo $server;?>">
<table class="filters">
    <tr>
        <th>Inactive since</th>
        <td><select name="fecha" id="fecha">
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

        </select></td>

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
        <th>from X</th>
        <td><input type="text" name="fx" id="fx" value="<?php echo $fx;?>"></td>

        <th>till X</th>
        <td><input type="text" name="tx" id="tx" value="<?php echo $tx;?>"></td>
    </tr>
    <tr>
        <th>from Y</th>
        <td><input type="text" name="fy" id="fy" value="<?php echo $fy;?>"></td>

        <th>till Y</th>
        <td><input type="text" name="ty" id="ty" value="<?php echo $ty;?>"></td>
    </tr>
    <tr>
        <th>Order by</th>
        <td><select name="cla" id="cla">
        <option value="Totales" <?php if($cla=='Totales') echo 'selected' ?>>Puntación Total</option>
  <option value="Constructor" <?php if($cla=='Constructor') echo 'selected' ?>>Maestro Constructor</option>
  <option value="Investigadores" <?php if($cla=='Investigadores') echo 'selected' ?>>Investigadores</option>
  <option value="Generales" <?php if($cla=='Generales') echo 'selected' ?>>Generales</option>
  <option value="Oro" <?php if($cla=='Oro') echo 'selected' ?>>Reserva de Oro</option>
        </select></td>

        <th>Start</th>
        <td><select name="offset" id="offset">
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
        </select></td>
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
        $order="";
        
        if($cla=="Totales")
            $order="(b.Constructor+b.Investigadores+b.Generales)";
        else
            $order="b.".$cla;
        $and="";
        if($ali!=""){
            $and=$and." and alianza.nombre ='".$ali."' ";
        }
        if($tx!=""){
            $and=$and." and x<=".$tx;
        }
        if($fx!=""){
            $and=$and." and x>=".$fx;
        }
        if($ty!=""){
            $and=$and." and y<=".$ty;
        }
        if($fy!=""){
            $and=$and." and y>=".$fy;
        }
        if($tpoints!=""){
            $and=$and." and (b.Constructor+b.Investigadores+b.Generales)<=".$tpoints;
        }
        if($fpoints!=""){
            $and=$and." and (b.Constructor+b.Investigadores+b.Generales)>=".$fpoints;
        }

        $sql = "select player.nombre as 'Player', alianza.nombre as 'Alliance', city.nombre as 'Town-Name', city.nivel as 'TownLv', x, y, wonderName as 'Wonder', woodlv as 'Wood', good, goodlv, FORMAT(b.Constructor,0) as 'Constructor', FORMAT(b.Investigadores,0) as 'Investigadores', FORMAT(b.Generales,0) as 'Generales', FORMAT(b.Oro,0) as 'Oro', FORMAT((b.Constructor+b.Investigadores+b.Generales),0) as 'Totales' 
        from player 
        left join alianza on player.idAlianza = alianza.idalianza 
        join city on city.playerid=player.idplayer 
        join isla on city.islaid = isla.idisla left 
        join puntos b on player.idplayer=b.idPlayer 
        join updates ub on ub.numero=b.update 
        where ub.numero = (select max(numero) from updates where server='".$server."') 
        and city.update=ub.numero 
        and player.server='".$server."' and (alianza.server ='".$server."' or alianza.server is null) and ub.server='".$server."' and city.server='".$server."' and isla.server='".$server."' 
        and estado='inactive' ".$and.
        " order by ".$order.
        " desc limit ".$offset.",50";
        $result = $conn->prepare($sql);
        $result->execute();
        if($result->rowCount() > 0):
            $rows = $result->fetchAll();
            $i=1+$offset;
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