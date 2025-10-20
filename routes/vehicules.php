<?php
header("Content-Type: application/json");

// Données fictives
$vehicules = [
    ["id" => 1, "type" => "SUV", "marque" => "Peugeot", "modele" => "3008", "prix" => 80, "disponible" => true],
    ["id" => 2, "type" => "Berline", "marque" => "Renault", "modele" => "Clio", "prix" => 60, "disponible" => false],
    ["id" => 3, "type" => "Citadine", "marque" => "Fiat", "modele" => "500", "prix" => 50, "disponible" => true]
];

// --- Méthodes séparées ---

// ---------------- Focntion GET ALL -------------------
function getAllVehicules($vehicules) {
    echo json_encode($vehicules);
}

// ---------------- Focntion GET par ID -------------------
function getVehiculeById($vehicules) {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["message" => "ID requis"]);
        return;
    }
    $id = (int) $_GET['id'];
    foreach ($vehicules as $v) {
        if ($v['id'] === $id) {
            echo json_encode($v);
            return;
        }
    }
    http_response_code(404);
    echo json_encode(["message" => "Véhicule non trouvé"]);
}

// ---------------- Focntion POST -------------------
function addVehicule(&$vehicules) {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || !isset($data["type"]) || !isset($data["marque"]) || !isset($data["modele"]) || !isset($data["prix"])) {
        http_response_code(400);
        echo json_encode(["message" => "Données invalides"]);
        return;
    }
    $data["id"] = count($vehicules) + 1;
    $vehicules[] = $data;
    echo json_encode(["message" => "Véhicule ajouté (fictif)", "data" => $data]);
}


// ---------------- Focntion DELETE -------------------

function deleteVehicule(&$vehicules) {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["message" => "ID requis"]);
        return;
    }

    $id = (int) $_GET['id'];
    foreach ($vehicules as $index => $v) {
        if ($v['id'] === $id) {
            array_splice($vehicules, $index, 1);
            echo json_encode(["message" => "Véhicule supprimé"]);
            return;
        }
    }

    http_response_code(404);
    echo json_encode(["message" => "Véhicule non trouvé"]);
}


// --- Gestionnaire de requêtes HTTP ---
// ---------------- Les Callbacks -------------------

$routes = [
    'GET' => function() use ($vehicules) {
        if (isset($_GET['id'])) {
            getVehiculeById($vehicules);
        } else {
            getAllVehicules($vehicules);
        }
    },
    'POST' => function() use (&$vehicules) {
        addVehicule($vehicules);
    },
    'DELETE' => function() use (&$vehicules) {
    deleteVehicule($vehicules);
},
];

$method = $_SERVER['REQUEST_METHOD'];

if (isset($routes[$method])) {
    $routes[$method](); // appelle la fonction correspondante
} else {
    http_response_code(405);
    echo json_encode(["message" => "Méthode non autorisée"]);
}
?>
