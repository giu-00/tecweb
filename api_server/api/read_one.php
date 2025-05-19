<?php
require 'cors.php';

// dichiaro il metodo consentito per la request
header("Access-Control-Allow-Methods: GET");
 
// includo le classi per la gestione dei dati
include_once '../dataMgr/Database.php';
include_once '../dataMgr/Product.php';

// creo una connessione al DBMS
$database = new Database();
$db = $database->getConnection();
 
// creo un'istanza di Prodotto
$product = new Product($db);

// leggo l'id nella richiesta (GET) e lo inserisco nella variabile di istanza id dell'oggetto $product 
// N.B. forma compatta di if: se $_GET['id'] è settata, la leggo, altrimenti invoco la funzione die() che "uccide" lo script
$id_toRead = isset($_GET['id']) ? $_GET['id'] : die();
$product->setId($id_toRead);
 
// invoco il metodo readOne() che restituisce le info del prodotto su cui viene invocato (l'id è già nella variabile id di $product!)
// N.B. la funzione readOne(), in realtà, non restituisce un risultato, bensì modifica l'oggetto su cui viene invocata (cioè il prodotto), a cui chiedo i dati... 
$product->readOne();
 
if($product->name!=null) { // se il prodotto esiste (il nome  non è nullo)...
    // costruisco un array associativo ($product_item) che rappresenta il prodotto...
    $product_item = array(
        "id" => $product->gertId(),
        "name" => $product->getName(),
        "description" => $product->getDescription(),
        "price" => $product->getPrice(),
        "category_id" => $product->getCategory_id(),
        "category_name" => $product->getCategory_name()
    );
    http_response_code(200); // response code 200 = tutto ok
    echo json_encode($product_item); // ... e lo restituisco nella response, dopo averlo trasformato in oggetto JSON
}
else { // se il nome del prodotto NON esiste
    http_response_code(404); // response code 404 = Not found
    // creo un oggetto JSON costituito dalla coppia message: testo-del-messaggio
    echo json_encode(array("message" => "Product does not exist"));
}
?>