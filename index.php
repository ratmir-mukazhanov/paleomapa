<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.4.0/ol.css">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Paleomapa</title>
</head>
<body>

    <div id="mySidebar" class="sidebar">
        <button class="closebtn" onclick="closeNav()">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
            <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
        </svg>

        </button>
        <a href="#" class="loadData" data-url="carregar.php">Carregar dados</a>
        <a href="#" class="editData" data-url="editar.php">Editar dados</a>
        <a href="#" class="map" data-url="mapa.php">Ver mapa</a>
    </div>

    <div id="main">
        <button id="openNav" onclick="openNav()">
        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
            <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/>
        </svg>
        </button>
        <h1>Paleomapa</h1>

        <div id="mapCanvas"></div>
    </div>

    <script src="./js/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/ol@v7.4.0/dist/ol.js"></script>
</body>
</html>
