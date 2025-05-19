<?php
class Product {
	
	//connessione (inizializzata nel costruttore)
    private $conn;
 
    // proprietà dei prodotti
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
 
    // il construttore inizializza la variabile per la connessione al DB
    public function __construct($db) {
        $this->conn = $db;
    }

    public function getId() {
        return $this->id;
    }
    public function setId($id_par) {
        $this->id = $id_par;
    }
    public function getName() {
        return $this->name;
    }
    public function setName($name_par) {
        $this->name = $name_par;
    }
    public function getDescription() {
        return $this->description;
    }
    public function setDescription($description_par) {
        $this->description = $description_par;
    }
    public function getPrice(){
        return $this->price;
    }
    public function setPrice($price_par) {
        $this->price = $price_par;
    }
    public function getCategory_id(){
        return $this->category_id;
    }
    public function setCategory_id($category_id_par) {
        $this->category_id = $category_id_par;
    }
    public function getCategory_name(){
        return $this->category_name;
    }
    public function setCategory_name($category_name_par) {
        $this->category_name = $category_name_par;
    }

	// servizio di lettura di tutti i prodotti
	function read() {
		// estraggo tutti i prodotti 
		$query = "SELECT * FROM prodotti JOIN categorie ON prodotti.cat_id = categorie.idcat ORDER BY prodotti.nome;";
		// preparo la query
		$stmt = $this->conn->prepare($query); 
		// eseguo la query
		$stmt->execute(); // NB $stmt conterrà il risultato dell'esecuzione della query (in questo caso un recordset)

		return $stmt; 
	}

	// servizio di lettura dei dati di un prodotto, dato il suo id
	function readOne() {
		// estraggo il prodotto con l'id indicato
		$query = "SELECT * FROM prodotti JOIN categorie ON prodotti.cat_id = categorie.idcat WHERE prodotti.id = ?";
		// preparo la query
		$stmt = $this->conn->prepare($query);
		// invio il valore per il parametro
		$stmt->bindParam(1, $this->id);
		// eseguo la query
		$stmt->execute(); // NB $stmt conterrà il risultato dell'esecuzione della query (in questo caso un recordset con un solo elemento)

		// leggo la prima (e unica) riga del risultato della query
		$row = $stmt->fetch(PDO::FETCH_ASSOC); // la funzione fetch (libreria PDO) con parametro PDO::FETCH_ASSOC invocata su un PDOStatement, restituisce un record ($row), in particolare un array le cui chiavi sono i nomi delle colonne della tabella 
 
		if ($row) {
			// inserisco i valori nelle variabili d'istanza 
			$this->name = $row['nome'];
			$this->price = $row['prezzo'];
			$this->description = $row['descrizione'];
			$this->category_id = $row['cat_id'];
			$this->category_name = $row['nomecat'];
		}
		else {
			// se non trovo il prodotto, imposto i valori delle variabili d'istanza a null
			$this->name = null;
			$this->price = null;
			$this->description = null;
			$this->category_id = null;
			$this->category_name = null;
		}
		
		// la funzione readOne non restituisce un risultato, bensì modifica l'oggetto su cui viene invocata (cioè il prodotto)
	}

	// servizio di inserimento di un nuovo prodotto
	function create() {
		// inserisco il nuovo prodotto
		$query = "INSERT INTO prodotti SET
				  nome=:name, prezzo=:price, descrizione=:description, cat_id=:category_id";
		// preparo la query
		$stmt = $this->conn->prepare($query);

		// invio i valori per i parametri (NB i valori del nuovo prodotto sono nelle variabili d'istanza!!)
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":price", $this->price);
		$stmt->bindParam(":description", $this->description);
		$stmt->bindParam(":category_id", $this->category_id);
 
		// eseguo la query
		$stmt->execute(); // NB $stmt conterrà il risultato dell'esecuzione della query

		return $stmt;		
	}

	// servizio di aggiornamento dei dati di un prodotto
	function update() {
		// aggiorno i dati del prodotto con l'id indicato
		$query = "UPDATE prodotti SET
					nome = :n,
					prezzo = :p,
					descrizione = :d,
					cat_id = :c_id
					WHERE
					id = :i";
	
		// preparo la query
		$stmt = $this->conn->prepare($query);
 
		// invio i valori per i parametri (NB i nuovi valori del prodotto sono nelle variabili d'istanza!!)
		$stmt->bindParam(':n', $this->name);
		$stmt->bindParam(':p', $this->price);
		$stmt->bindParam(':d', $this->description);
		$stmt->bindParam(':c_id', $this->category_id);
		$stmt->bindParam(':i', $this->id);
 
		// eseguo la query
		$stmt->execute(); // NB $stmt conterrà il risultato dell'esecuzione della query

		return $stmt;
	}

	// servizio di cancellazione di un prodotto
	function delete() {
		// cancello il prodotto con l'id indicato
		$query = "DELETE FROM prodotti WHERE id = ?";
	
		// preparo la query
		$stmt = $this->conn->prepare($query);
	
		// invio il valore per il parametro
		$stmt->bindParam(1, $this->id);

		// eseguo la query
		$stmt->execute(); // NB $stmt conterrà il risultato dell'esecuzione della query

		return $stmt;
	}

	// servizio di ricerca prodotti per keyword
	function search($keywords) {
		// cerco i prodotti 
		$query = "SELECT * FROM prodotti JOIN categorie ON prodotti.cat_id = categorie.idcat
				  WHERE prodotti.nome LIKE ? OR prodotti.descrizione LIKE ? OR categorie.nomecat LIKE ?
				  ORDER BY prodotti.nome;";
		
		// preparo la query
		$stmt = $this->conn->prepare($query); 

		// aggiungo % prima e dopo le keywords per estrarre i testi che CONTENGONO le keywords (rif. SQL)
		$keywords = "%{$keywords}%"; 
	
		// invio i valori per i parametri
		$stmt->bindParam(1, $keywords);
		$stmt->bindParam(2, $keywords);
		$stmt->bindParam(3, $keywords);
 
		// eseguo la query
		$stmt->execute(); // NB $stmt conterrà il risultato dell'esecuzione della query (in questo caso un recordset)
	
		return $stmt;
	}	
}
?>