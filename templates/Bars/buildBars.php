<?php
// Read the HTML template file into a string
$subpage = $_GET['subpage'];
//$server = $_GET['server'];
//$country = $_GET['country'];
$template = file_get_contents(__DIR__.'\topBar.html');

// // Define data
// $data = array(
//     'server' => 'server',
// );

// // Replace placeholders with data
// foreach ($data as $placeholder => $value) {
//     $template = str_replace("{{$placeholder}}", $value, $template);
// }

// Output the final HTML
echo $template;

if($subpage == "search")
    $template = file_get_contents(__DIR__.'\leftBarSearch.html');
if($subpage == "home")
    $template = file_get_contents(__DIR__.'\leftBarHome.html');
if($subpage == "statistics")
    $template = file_get_contents(__DIR__.'\leftBarStatistics.html');


echo $template;

?>