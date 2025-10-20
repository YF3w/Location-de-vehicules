<?php
header("Content-Type: application/json");

// Données fictives
$vehicules = [
    ["id" => 1, "type" => "SUV", "marque" => "Peugeot", "modele" => "3008", "prix" => 80, "disponible" => true],
    ["id" => 2, "type" => "Berline", "marque" => "Renault", "modele" => "Clio", "prix" => 60, "disponible" => false],
    ["id" => 3, "type" => "Citadine", "marque" => "Fiat", "modele" => "500", "prix" => 50, "disponible" => true]
];

// Méthode pour récupérer un véhicule par ID
function getVehiculeById($id, $vehicules) {
    foreach ($vehicules as $v) {
        if ($v['id'] === $id) {
            return $v;
        }
    }
    return null;
}

// Méthode pour récupérer tous les véhicules
function getAllVehicules($vehicules) {
    return $vehicules;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    if (isset($_GET['id'])) {
        // Si un ID est fourni, retourne un seul véhicule
        $id = (int) $_GET['id'];
        $vehicule = getVehiculeById($id, $vehicules);

        if ($vehicule) {
            echo json_encode($vehicule);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Véhicule non trouvé"]);
        }
    } else {
        // Sinon, retourne tous les véhicules
        echo json_encode(getAllVehicules($vehicules));
    }
}

// Ajouter un véhicule (POST)
elseif ($method == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $data["id"] = count($vehicules) + 1;
    $vehicules[] = $data;

    echo json_encode(["message" => "Véhicule ajouté (fictif)", "data" => $data]);
}
?>
