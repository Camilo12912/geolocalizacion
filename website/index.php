<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Web with API</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form {
            background: #fff;
            padding: 20px;
            margin: 0 auto;
            max-width: 400px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        select, button {
            padding: 10px;
            margin-top: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: #fff;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <h1>Lugares cercanos</h1>

    <form method="GET" action="">
        <label for="place_type">Seleccione el tipo de lugar:</label>
        <select name="place_type" id="place_type">
            <option value="restaurantes" <?php echo (isset($_GET['place_type']) && $_GET['place_type'] === 'restaurantes') ? 'selected' : ''; ?>>Restaurantes</option>
            <option value="hoteles" <?php echo (isset($_GET['place_type']) && $_GET['place_type'] === 'hoteles') ? 'selected' : ''; ?>>Hoteles</option>
            <option value="lugares turísticos" <?php echo (isset($_GET['place_type']) && $_GET['place_type'] === 'lugares turísticos') ? 'selected' : ''; ?>>Lugares Turísticos</option>
        </select>
        <input type="hidden" name="latitude" value="7.9004">
        <input type="hidden" name="longitude" value="-72.5029">
        <button type="submit">Buscar</button>
    </form>

    <?php
    // Obtener parámetros de la solicitud
    $place_type = isset($_GET['place_type']) ? $_GET['place_type'] : 'hoteles';
    $latitude = isset($_GET['latitude']) ? $_GET['latitude'] : '7.9004';
    $longitude = isset($_GET['longitude']) ? $_GET['longitude'] : '-72.5029';

    // URL de la API que corre en el servicio de Flask (Python)
    $api_url = "http://fruit-service:5000/search_places?place_type=" . urlencode($place_type) . "&latitude=$latitude&longitude=$longitude";

    // Inicializamos cURL
    $curl = curl_init($api_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    // Decodificamos la respuesta JSON
    $data = json_decode($response, true);

    if ($data && isset($data['places'])) {
        echo "<ul>";
        foreach ($data['places'] as $place) {
            echo "<li>" . htmlspecialchars($place['name']) . " (Lat: " . $place['lat'] . ", Lon: " . $place['lon'] . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No se encontraron lugares.</p>";
    }
    ?>
</body>
</html>
