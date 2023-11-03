<!DOCTYPE html>
<?php
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

    $subpage = "home";
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
  
  <div id='cafecito'>
    <h2 class='title'>
        ¿Te gustaría apoyar el proyecto? Ayudame a seguir manteniéndolo
    </h2>
    <a href='https://cafecito.app/ikacoords' rel='noopener' target='_blank'><img alt='Invitame un café en cafecito.app' src='https://cdn.cafecito.app/imgs/buttons/button_1.png' srcset='https://cdn.cafecito.app/imgs/buttons/button_1.png 1x, https://cdn.cafecito.app/imgs/buttons/button_1_2x.png 2x, https://cdn.cafecito.app/imgs/buttons/button_1_3.75x.png 3.75x'/></a>
    <br/>
    <span style='font-style:italic;font-size:0.8em'>
        Por el momento, sólo podés invitar cafecitos si sos de Argentina.
    </span>
    <br>
    <br>
    <a href='http://paypal.me/ikacoords' rel='noopener' target='_blank'><img alt='Paypal' src='https://www.paypalobjects.com/es_ES/ES/i/btn/btn_donateCC_LG.gif'/></a>
    <br/>
<br>
<h2>
¿Por qué donar?
</h2>
Actualmente la web está en un servicio gratis, por lo que la cantidad de informacion que podes almacenar es muy limita, por eso el limitado número de servidores,
<br>Nos gustaría poder movernos a un servicio que nos permita crecer sin preocuparnos, pero este tiene un costo de 5 dolares mensuales.
<br>Como objetivo adicional, nos gustaria contar con un servidor para automatizar la actualización de los datos que actualmente es muy lenta.
    </div>
  </div>
  <div class="right">
  
  </div>
</div>

<div class="footer">
    IkaCoords
</div>

</body>
</html>