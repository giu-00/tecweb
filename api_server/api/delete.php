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

// dato che alla chiamata dell'endpoint abbiamo passato l'id nell'URL, leggo l'id nella richiesta GET
// inserisco l'id nella variabile di istanza id dell'oggetto $product 
// N.B. forma compatta di if: se $_GET['id'] è settata, la leggo, altrimenti invoco la funzione die() che "uccide" lo script
$id_toDel = isset($_GET['id']) ? $_GET['id'] : die();
$product->setId($id_toDel);

// invoco il metodo delete() che cancella il prodotto indicato
if ($product->delete()) { // se va a buon fine...
    http_response_code(200); // response code 200 = tutto ok
    // creo un oggetto JSON costituito dalla coppia message: testo-del-messaggio
    echo json_encode(array("message" => "Product was deleted"));
    }
else { // se l'aggiornamento è fallito...
    http_response_code(503); // response code 503 = service unavailable
    // creo un oggetto JSON costituito dalla coppia message: testo-del-messaggio
    echo json_encode(array("message" => "Unable to delete product"));
}
?>