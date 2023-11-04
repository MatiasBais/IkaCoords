<!DOCTYPE html>
<?php
set_time_limit(200);
require('../db_config.php');
$conn = new PDO("mysql:host=" . $host . ";dbname=" . $database, $username, $password);

if (!$conn) {
    echo "Could not connect!";
}
$cla = "";
if (empty($_GET['cla'])) {
    $cla = 'Totales';
} else {
    $cla = $_GET['cla'];
}
$server = "";
if (empty($_GET['server']))
    $server = "Alpha";
else
    $server = $_GET['server'];

$country = "";
if (empty($_GET['country']))
    $country = "España";
else
    $country = $_GET['country'];
$fecha = "31";
if (empty($_GET['fecha'])) {
    $sql = "SELECT numero, DATE_FORMAT(fecha,'%d/%m/%y') as 'Fecha' FROM updates where server='" . $server . "' order by numero desc";
    $result = $conn->prepare($sql);
    $result->execute();
    if ($result->rowCount() > 0):
        $rows = $result->fetchAll();
        foreach ($rows as $row):
            $fecha = $row['numero'];
        endforeach;
    endif;
} else
    $fecha = $_GET['fecha'];

$fecha2 = "";

if (empty($_GET['fecha2'])) {
    $sql = "SELECT numero, DATE_FORMAT(fecha,'%d/%m/%y') as 'Fecha' FROM updates where server='" . $server . "' order by numero asc";
    $result = $conn->prepare($sql);
    $result->execute();
    if ($result->rowCount() > 0):
        $rows = $result->fetchAll();
        foreach ($rows as $row):
            $fecha2 = $row['numero'];
        endforeach;
    endif;
} else
    $fecha2 = $_GET['fecha2'];

$offset = "";
if (empty($_GET['offset'])) {
    $offset = '0';
} else
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

    $subpage = "home";
    $template = file_get_contents('../templates/Bars/topBar.html');
    $template = str_replace("{server}", $server, $template);

    // Define data
    


    // Output the final HTML
    echo $template;
    ?>
    <div class="row">
        <?php

        if ($subpage == "search")
            $template = file_get_contents('../templates/Bars/leftBarSearch.html');
        if ($subpage == "home")
            $template = file_get_contents('../templates/Bars/leftBarHome.html');
        if ($subpage == "statistics")
            $template = file_get_contents('../templates/Bars/leftBarStatistics.html');

        $template = str_replace("{server}", $server, $template);
        $template = str_replace("{{$server}}", 'selected', $template);
        echo $template;

        ?>
        <script>
            var selectEl = document.getElementById('server');
            selectEl.onchange = function () {
                var goto = this.value;
                window.location = window.location.href.split('?')[0] + "?server=" + this.value;

            };
        </script>
        <div class="middle">

            <h1>Players</h1>
            <br>
            <form method="get">
                <input type="hidden" id="server" name="server" value="<?php echo $server; ?>">
                <table class="filters">
                    <tr>
                        <th>Posición:</th>
                        <td>
                            <select name="offset" id="offset">
                                <?php
                                $selected = $offset / 50;
                                for ($j = 0; $j < 80; $j++) {
                                    echo '<option value="';
                                    echo $j * 50;
                                    echo '"';
                                    if ($j == $selected)
                                        echo ' selected';
                                    echo '>';
                                    echo $j * 50 + 1;
                                    echo '-';
                                    echo 50 * ($j + 1);
                                    echo '</option>';
                                }
                                ?>
                            </select>
                        </td>

                    </tr>
                    <tr>
                        <th>Desde:</th>
                        <td>
                            <select name="fecha" id="fecha">
                                <?php
                                $sql = "SELECT numero, DATE_FORMAT(fecha,'%d/%m/%y') as 'Fecha' FROM updates where server='" . $server . "' order by numero desc";
                                $result = $conn->prepare($sql);
                                $result->execute();
                                if ($result->rowCount() > 0):
                                    $rows = $result->fetchAll();
                                    foreach ($rows as $row):

                                        ?>
                                        <option value="<?php echo $row['numero']; ?>" <?php if ($fecha == $row['numero'])
                                               echo 'selected'; ?>>
                                            <?php echo $row['Fecha'] ?>
                                        </option>

                                        <?php
                                    endforeach;
                                endif;
                                ?>


                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Hasta:</th>
                        <td>
                            <select name="fecha2" id="fecha2">
                                <?php
                                $sql = "SELECT numero, DATE_FORMAT(fecha,'%d/%m/%y') as 'Fecha' FROM updates where server='" . $server . "' order by numero asc";
                                $result = $conn->prepare($sql);
                                $result->execute();
                                if ($result->rowCount() > 0):
                                    $rows = $result->fetchAll();
                                    foreach ($rows as $row):

                                        ?>
                                        <option value="<?php echo $row['numero']; ?>" <?php if ($fecha2 == $row['numero'])
                                               echo 'selected'; ?>>
                                            <?php echo $row['Fecha'] ?>
                                        </option>

                                        <?php
                                    endforeach;
                                endif;
                                ?>


                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Clasificación:</th>
                        <td>
                            <select name="cla" id="cla">
                                <option value="Totales" <?php if ($cla == 'Totales')
                                    echo 'selected' ?>>Puntación Total
                                    </option>
                                    <option value="Constructor" <?php if ($cla == 'Constructor')
                                    echo 'selected' ?>>Maestro
                                        Constructor</option>
                                    <option value="Investigadores" <?php if ($cla == 'Investigadores')
                                    echo 'selected' ?>>
                                        Investigadores</option>
                                    <option value="Generales" <?php if ($cla == 'Generales')
                                    echo 'selected' ?>>Generales
                                    </option>
                                    <option value="Oro" <?php if ($cla == 'Oro')
                                    echo 'selected' ?>>Reserva de Oro</option>
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
                            <th>Rank</th>
                            <th>Player</th>
                            <th>Aliance</th>
                            <th>Points</th>
                            <th>Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $order = "";
                                if ($cla == "Totales")
                                    $order = "((b.Constructor+b.Investigadores+b.Generales)-(a.Constructor+a.Investigadores+a.Generales))";
                                if ($cla == "Constructor")
                                    $order = "(b.Constructor-a.Constructor)";
                                if ($cla == "Investigadores")
                                    $order = "(b.Investigadores-a.Investigadores)";
                                if ($cla == "Generales")
                                    $order = "(b.Generales-a.Generales)";
                                if ($cla == "Oro")
                                    $order = "(b.Oro-a.Oro)";



                                $sql = "select alianza.nombre as 'ali', player.nombre as 'nombre', DATE_FORMAT(ub.fecha,'%d/%m/%y') as 'Fecha',FORMAT((b.Constructor+b.Investigadores+b.Generales),0)as 'Totales',FORMAT(((b.Constructor+b.Investigadores+b.Generales)-(a.Constructor+a.Investigadores+a.Generales)),0) as 'Δ-Totales' ,FORMAT(b.Constructor,0) as 'Constructor', FORMAT((b.Constructor-a.Constructor),0) as 'Δ-Constructor',FORMAT(b.investigadores,0) as 'Investigadores', FORMAT((b.Investigadores-a.Investigadores),0) as 'Δ-Investigadores', FORMAT(b.Generales,0) as 'Generales', FORMAT((b.Generales-a.Generales),0) as 'Δ-Generales', FORMAT(b.oro,0) as 'Oro', FORMAT((b.Oro-a.Oro),0) as 'Δ-Oro' ,ua.numero,ub.numero from player
            left join puntos a on player.idplayer=a.idPlayer
            left join puntos b on player.idplayer=b.idPlayer
            join updates ua on ua.numero =a.update
            join updates ub on ub.numero=b.update
            left join alianza on player.idAlianza = alianza.idalianza 
            where ub.numero = (select max(p3.update) from puntos p3 where p3.update<=" . $fecha2 . " and p3.idplayer=player.idplayer) 
            and ua.numero= (select min(p2.update) from puntos p2 where p2.idplayer=player.idplayer and p2.update>=" . $fecha . ") 
            and  player.server = '" . $server . "' and (alianza.server = '" . $server . "' or alianza.server is null) and ua.server = '" . $server . "' and ub.server = '" . $server . "' 
            order by " . $order . " desc 
            limit " . $offset . ",50";

                                $result = $conn->prepare($sql);
                                $result->execute();
                                if ($result->rowCount() > 0):
                                    $rows = $result->fetchAll();
                                    $i = 1 + $offset;
                                    foreach ($rows as $row):

                                        ?>
                            <tr class="active-row">
                                <td>
                                    <?php echo $i; ?>
                                </td>
                                <td><a
                                        href="../search/searchplayer.php?player=<?php echo $row['nombre']; ?>&server=<?php echo $server; ?>">
                                        <?php echo $row['nombre']; ?>
                                    </a>
                                </td>
                                <td>
                                    <?php echo $row['ali']; ?>
                                </td>
                                <td>
                                    <?php echo $row[$cla]; ?>
                                </td>
                                <td>
                                    <?php echo $row['Δ-' . $cla]; ?>
                                </td>
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