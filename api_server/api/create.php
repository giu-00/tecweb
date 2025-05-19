<?php
require 'cors.php';

// specifico il formato della risposta (JSON)
header("Content-Type: application/json; charset=UTF-8");
 
// includo le classi per la gestione dei dati
include_once '../dataMgr/Database.php';
include_once '../dataMgr/Product.php';

// creo una connessione al DBMS
$database = new Database();
$db = $database->getConnection();
 
// creo un'istanza di Prodotto
$product = new Product($db);

// leggo i dati nel body della request (metodo POST)
$data = json_decode(file_get_contents("php://input"));
 
// controllo che i dati ci siano...
if(
    !empty($data->name) &&
    !empty($data->price) &&
    !empty($data->description) &&
    !empty($data->cat_id)
) {
 
    // inserisco i valori nelle variabili d'istanza dell'oggetto $product
    $product->setName($data->name);
    $product->setPrice($data->price);
    $product->setDescription($data->description);
    $product->setCategory_id($data->cat_id);
 
	// invoco il metodo create() che crea un nuovo prodotto
    if($product->create()){ // se va a buon fine...
        http_response_code(201); // response code 201 = created
 
        // creo un oggetto JSON costituito dalla coppia message: testo-del-messaggio
        echo json_encode(array("message" => "Product was created"));
    }
    else{ // se la creazione è fallita...
        http_response_code(503); // response code 503 = service unavailable
 
        // creo un oggetto JSON costituito dalla coppia message: testo-del-messaggio
        echo json_encode(array("message" => "Unable to create product"));
    }
}
else { // se i dati sono incompleti
    http_response_code(400); // response code 400 = bad request
	// creo un oggetto JSON costituito dalla coppia message: testo-del-messaggio
    // uso l’operatore ternario con empty() per evitare l’errore sulla stampa di un valore inesistente
    echo json_encode(array("message" => "Unable to create product. Data is incomplete:"
		. " nome=" . (empty($data->name) ? "null" : $data->name) . " prezzo=" . (empty($data->price) ? "null" : $data->price) . " descrizione=" . (empty($data->description) ? "null" : $data->description) . " cat_id=" . (empty($data->cat_id) ? "null" : $data->cat_id)));
}
?>