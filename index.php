<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <title>Document</title>
</head>
<body>
    <h1>Paleomapa</h1>

    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a class="loadData" href="./carregar.php">Carregar dados</a>
        <a class="editData" href="./editar.php">Editar dados</a>
        <a class="map" href="./mapa.php">Ver mapa</a>    
    </div>


    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
    
    <div id="main">

        <canvas class="mapCanvas">
        </canvas>





    </div>


    <script src="./js/index.js"></script>
</body>
</html>