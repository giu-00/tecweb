<?php
require 'cors.php';

// specifico il formato del contenuto (JSON)
header("Content-Type: application/json; charset=UTF-8");

// includo le classi per la gestione dei dati
include_once '../dataMgr/Database.php';
include_once '../dataMgr/Product.php';
 
// creo una connessione al DBMS
$database = new Database();
$db = $database->getConnection();
 
// creo un'istanza di Prodotto
$product = new Product($db);

// invoco il metodo read() che restituisce l'elenco dei prodotti
$stmt = $product->read(); // N.B. $stmt è un recordset!

// if($stmt->rowCount()>0) { // se ci sono dei prodotti...
if ($stmt) { // se ci sono dei prodotti...
    // creo una coppia "products: [lista-di-prodotti]"
    $products_list = array();
    $products_list["products"] = array();

    foreach ($stmt as $row) { // la funzione fetch (libreria PDO) con parametro PDO::FETCH_ASSOC invocata su un PDOStatement, restituisce un record ($row), in particolare un array le cui chiavi sono i nomi delle colonne della tabella 
		// costruisco un array associativo ($product_item) che rappresenta ogni singolo prodotto...
        $product_item = array(
            "id" => $row['id'],
            "name" => $row['nome'],
            "description" => $row['descrizione'],
            "price" => $row['prezzo'],
            "category_id" => $row['cat_id'],
            "category_name" => $row['nomecat']
        );
		// ... e lo aggiungo al fondo di lista-di-prodotti
        array_push($products_list["products"], $product_item); // la funzione array_push inserisce al fondo dell'array $products_list["products"] l'elemento che consiste nell'array ($product_item)
    }
 
    // error_log("read.php: prodotti letti e aggiunti all'array");
    // error_log("Contenuto products_list: ".print_r($products_list, true));

    http_response_code(200); // imposto il response code 200 = tutto ok

	// trasformo la coppia products: [lista-di-prodotti] in un oggetto JSON vero e proprio e lo invio in HTTP response
    echo json_encode($products_list);
}
else { // se NON ci sono  prodotti...
    http_response_code(404); // imposto il response code 404 = Not found
    // creo un oggetto JSON costituito dalla coppia message: testo-del-messaggio
    echo json_encode(array("message" => "No products found"));
}
?>