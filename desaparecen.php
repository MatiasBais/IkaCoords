<!DOCTYPE html>
<?php
$conn = new PDO("mysql:host=ikacoords.heliohost.us:3306;dbname=ikacoords_ikariam", "ikacoords_ad", "admin");

if(!$conn){
    echo "Could not connect!";
}
$cla="";
if(empty($_GET['cla'])){
	$cla='Totales';
}
else{
    $cla = $_GET['cla'];
}
$fecha="";
if(empty($_GET['fecha'])){
     $sql = "SELECT numero, DATE_FORMAT(fecha,'%d/%m/%y') as 'Fecha' FROM updates order by numero asc";
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
    <div class="topnav">
        <a href="/../index.php">Home</a>
        <a href="/../search.php">Search</a>
        <a href="/../statistics.php">Statistics</a>
        <a href="/../world.php">World</a>
        <a href="/../calculators.php">Calculators</a>
    </div>
    <div class="row">
  <div class="left">
      <table id="left-table">

        <th>Home</th>
        <tr><td><a href="home/topplayer.php">Top Players</a></td></tr>
        <tr><td><a href="home/flopplayer.php">Flop Players</a></td></tr>
        <tr><td><a href="home/topalliances.php">Top Alliances</a></td></tr>
        <tr><td><a href="home/flopalliances.php">Flop Alliances</a></td></tr>
        <tr><td><a href="">Show World</a></td></tr>
    </table>       
    <select name="pais" id="pais">
        <option value="es">España</option>
    </select>
    <br>
    <select name="server" id="server">
        <option value="1">Alpha</option>
        <option value="2">Kerberos</option>
    </select>
      
     
    </div>
  <div class="middle">

  <h1>Players</h1>
  <br>
  <form method="get">
<table class="filters">
    
<tr>
<th>Se puso rojo:</th>
<td>
    <select name="fecha" id="fecha">
        <?php
        $sql = "SELECT numero, DATE_FORMAT(fecha,'%d/%m/%y') as 'Fecha' FROM updates order by numero asc";
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
		$fecha2 = 0;
		$sql = "SELECT numero, DATE_FORMAT(fecha,'%d/%m/%y') as 'Fecha' FROM updates where numero < ".$fecha." order by numero asc";
        $result = $conn->prepare($sql);
        $result->execute();
        if($result->rowCount() > 0):
            $rows = $result->fetchAll();
            foreach($rows as $row):
         		$fecha2 = $row['numero'];
				echo $row['numero'];
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
    </tr>
    <?php
        
        

        $sql = "select player.nombre as 'Player', alianza.nombre as 'Alliance', city.nombre as 'Town-Name', city.nivel as 'TownLv', x, y, wonderName as 'Wonder', woodlv as 'Wood', good, goodlv 
        from player 
        left join alianza on player.idAlianza = alianza.idalianza 
        join city on city.playerid=player.idplayer 
        join isla on city.islaid = isla.idisla left
        join updates ub on ub.numero=city.update 
        where ub.numero = ".$fecha." 
        and city.server='Alpha' 
        and isla.server='Alpha'  
		and idplayer in (select idplayer from puntos where puntos.update = ".$fecha2.") 
		and idplayer not in (select idplayer from puntos where puntos.update = ".$fecha.")";
        $result = $conn->prepare($sql);
        $result->execute();
        if($result->rowCount() > 0):
            $rows = $result->fetchAll();
            foreach($rows as $row):
            
        ?>
        <tr>
            <td>
				<a href="searchplayer.php?player=<?php echo $row['Player'];?>">
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