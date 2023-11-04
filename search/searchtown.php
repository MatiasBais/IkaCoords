<!DOCTYPE html>
<?php
require('../db_config.php');
$conn = new PDO("mysql:host=" . $host . ";dbname=" . $database, $username, $password);

if (!$conn) {
    echo "Could not connect!";
}

$ali = "";
if (empty($_GET['ali'])) {
    $ali = '';
} else
    $ali = $_GET['ali'];

$fx = "";
if (empty($_GET['fx'])) {
    $fx = '';
} else
    $fx = $_GET['fx'];

$tx = "";
if (empty($_GET['tx'])) {
    $tx = '';
} else
    $tx = $_GET['tx'];

$fy = "";
if (empty($_GET['fy'])) {
    $fy = '';
} else
    $fy = $_GET['fy'];

$ty = "";
if (empty($_GET['ty'])) {
    $ty = '';
} else
    $ty = $_GET['ty'];

$player = "";
if (empty($_GET['player']))
    $player = "";
else
    $player = $_GET['player'];

$town = "";
if (empty($_GET['town']))
    $town = "";
else
    $town = $_GET['town'];
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
            <br>
            <form method="get">
                <input type="hidden" id="server" name="server" value="<?php echo $server; ?>">
                <table class="filterss filters">
                    <tr>
                        <th>Player Name</th>

                        <td colspan="2">
                            <input type="text" name="player" id="player" value="<?php echo $player; ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>Alliance</th>

                        <td colspan="2">
                            <input type="text" name="ali" id="ali" value="<?php echo $ali; ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>Town Name</th>

                        <td colspan="2">
                            <input type="text" name="town" id="town" value="<?php echo $town; ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>X</th>
                        <td>
                            <input type="text" name="fx" id="fx" value="<?php echo $fx; ?>"> TO
                        </td>
                        <td>
                            <input type="text" name="tx" id="tx" value="<?php echo $tx; ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>Y</th>
                        <td>
                            <input type="text" name="fy" id="fy" value="<?php echo $fy; ?>"> TO
                        </td>
                        <td>
                            <input type="text" name="ty" id="ty" value="<?php echo $ty; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
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

                $and = "";
                if ($ali != "") {
                    $and = $and . " and alianza.nombre ='" . $ali . "' ";
                }
                if ($tx != "") {
                    $and = $and . " and x<=" . $tx;
                }
                if ($fx != "") {
                    $and = $and . " and x>=" . $fx;
                }
                if ($ty != "") {
                    $and = $and . " and y<=" . $ty;
                }
                if ($fy != "") {
                    $and = $and . " and y>=" . $fy;
                }
                if ($player != "") {
                    $and = $and . " and player.nombre='" . $player . "'";
                }
                if ($town != "") {
                    $and = $and . " and city.nombre='" . $town . "'";
                }

                $sql = "select player.nombre as 'Player', alianza.nombre as 'Alliance', city.nombre as 'Town-Name', city.nivel as 'TownLv', x, y, wonderName as 'Wonder', woodlv as 'Wood', good, goodlv, FORMAT(b.Constructor,0) as 'Constructor', FORMAT(b.Investigadores,0) as 'Investigadores', FORMAT(b.Generales,0) as 'Generales', FORMAT(b.Oro,0) as 'Oro', FORMAT((b.Constructor+b.Investigadores+b.Generales),0) as 'Totales' 
        from player 
        left join alianza on player.idAlianza = alianza.idalianza 
        join city on city.playerid=player.idplayer 
        join isla on city.islaid = isla.idisla left 
        join puntos b on player.idplayer=b.idPlayer 
        join updates ub on ub.numero=b.update 
        where ub.numero = (select max(numero) from updates where server='" . $server . "') 
        and city.update=ub.numero 
        and player.server='" . $server . "' and (alianza.server ='" . $server . "' or alianza.server is null) and ub.server='" . $server . "' and city.server='" . $server . "' and isla.server='" . $server . "' 
        " . $and .
                    " limit 50";
                $result = $conn->prepare($sql);
                $result->execute();
                if ($result->rowCount() > 0):
                    $rows = $result->fetchAll();
                    foreach ($rows as $row):

                        ?>
                        <tr>
                            <td>
                                <a
                                    href="../search/searchplayer.php?player=<?php echo $row['Player']; ?>&server=<?php echo $server; ?>">
                                    <?php echo $row['Player']; ?>
                                </a>
                            </td>
                            <td>
                                <?php echo $row['Alliance']; ?>
                            </td>
                            <td>
                                <?php echo $row['Town-Name']; ?>
                            </td>
                            <td>
                                <?php echo $row['TownLv']; ?>
                            </td>
                            <td>
                                <?php echo $row['x']; ?>
                            </td>
                            <td>
                                <?php echo $row['y']; ?>
                            </td>
                            <td>
                                <?php echo $row['Wonder']; ?>
                            </td>
                            <td>
                                <?php echo $row['Wood']; ?>
                            </td>
                            <td>
                                <?php echo $row['goodlv'] ?>
                            </td>
                            <td>
                                <?php echo $row['Totales']; ?>
                            </td>
                            <td>
                                <?php echo $row['Constructor']; ?>
                            </td>
                            <td>
                                <?php echo $row['Investigadores']; ?>
                            </td>
                            <td>
                                <?php echo $row['Generales']; ?>
                            </td>
                            <td>
                                <?php echo $row['Oro']; ?>
                            </td>
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