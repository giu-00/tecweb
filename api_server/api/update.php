<?php
require 'cors.php';

// definisco il formato della risposta (json)
header("Content-Type: application/json; charset=UTF-8");
 
// includo le classi per la gestione dei dati
include_once '../dataMgr/Database.php';
include_once '../dataMgr/Product.php';

// creo una connessione al DBMS
$database = new Database();
$db = $database->getConnection();
 
// creo un'istanza di Prodotto
$product = new Product($db);

// leggo i dati nel body della request (metodo POST/PUT/PATCH)
$data = json_decode(file_get_contents("php://input"));

// inserisco i valori nelle variabili di istanza dell'oggetto $product (compreso l'id che indica il prodotto da aggiornare!)
$product->setId($data->id);
$product->setName($data->name);
$product->setPrice($data->price);
$product->setDescription($data->description);
$product->setCategory_id($data->cat_id);

// invoco il metodo update() che aggiorna i dati del prodotto
if($product->update()) { // se va a buon fine...
    http_response_code(200); // response code 200 = tutto ok
    // creo un oggetto JSON costituito dalla coppia message: testo-del-messaggio
    echo json_encode(array("message" => "Product was updated"));
    }
else { // se l'aggiornamento è fallito...
    http_response_code(503); // response code 503 = service unavailable
    // creo un oggetto JSON costituito dalla coppia message: testo-del-messaggio
    echo json_encode(array("message" => "Unable to update product"));
}
?>