<?php
require 'cors.php';

// specifico il formato della risposta (json)
header("Content-Type: application/json; charset=UTF-8");
 
// includo le classi per la gestione dei dati
include_once '../dataMgr/Database.php';
include_once '../dataMgr/Product.php';

// creo una connessione al DBMS
$database = new Database();
$db = $database->getConnection();
 
// creo un'istanza di Prodotto
$product = new Product($db);

// leggo le keywords (parametro s) nella richiesta (GET) 
// N.B. forma compatta di if: se $_GET['id'] è settata, la leggo, altrimenti a $keywords assegno la stringa vuota
$keywords = isset($_GET["s"]) ? $_GET["s"] : "";
 
// invoco il metodo search() che restituisce la lista dei prodotti che soddisfano la query
$stmt = $product->search($keywords); // N.B. $stmt è un recordset!

$products_list = array();
// creo una coppia products: [lista-di-prodotti]
$products_list["products"] = array();
// while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // la funzione fetch (libreria PDO) con parametro PDO::FETCH_ASSOC invocata su un PDOStatement, restituisce un record ($row), in particolare un array le cui chiavi sono i nomi delle colonne della tabella 
foreach ($stmt as $row) {
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
    array_push($products_list["products"], $product_item); // la funzione array_push inserisce al fondo di un array ($products_list["products"]) i parametri che seguono l'array ($product_item)
}
// trasformo la coppia products: [lista-di-prodotti] in un oggetto JSON vero e proprio e lo invio in HTTP response
echo json_encode($products_list);
?>