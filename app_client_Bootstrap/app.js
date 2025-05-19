const BASEURL = "../api_server/api/";

// change page title
function changePageTitle(page_title){
    $('#page-title').text(page_title); // page title
    document.title = page_title; // window title
}

function sendRequest(api, callback, method="GET", body) {
    const fetchPromise = fetch(BASEURL + api, {
        method, // shorthand property name: dato che la variabile ha lo stesso nome della proprietà, equivale a "method: method"
        headers: body ? { 'Content-Type': 'application/json' } : undefined,
        body
    });
    fetchPromise
    .then( (response) => {
            if (!response.ok) {
                throw new Error(`HTTP error: ${response.status}`);
            }
            return response.json();
        })
    .then ( data => callback(data) )
    .catch ((error) => {
        msg = error.message || 'Errore sconosciuto';
        console.error(`Errore: ${msg}`);
    })
}

// funzioni comuni per generare la User interface
function search_products_form() {
    const searchForm = `
        <!-- search products form -->
        <form id='search-product-form' action='#' method='post'>
            <div class='input-group pull-left w-25'>
                <input type='text' name='keywords' class='form-control product-search-keywords' placeholder='Cerca fumetti...' />
                <span class='input-group-btn'>
                    <button type='submit' class='btn btn-default' type='button'>
                        <span class='fa fa-search'></span>
                    </button>
                </span>
            </div>
        </form>`;
    return searchForm;
}

function create_product_button() {
    const createButton = `
        <!-- create product button: when clicked, it will load the create product form -->
			<div id='create-product' class='btn btn-primary btn-sm pull-right mb-3 create-product-button'>
				<span class='fa fa-plus'></span> Inserisci nuovo fumetto
			</div>`;
    return createButton;
}

function back_to_products_button() {
    const backButton = `
        <!-- read products button: when clicked, it will show the product's list -->
        <div id='read-products' class='btn btn-primary btn-sm pull-right mb-3 read-products-button'>
            <span class='fa fa-arrow-left'></span> Torna a tutti i fumetti
        </div>`;
    return backButton;
}

function products_table(products) {
    let table = `
        <!-- start table -->
        <table class='table table-bordered table-hover'>
            <thead>
            <tr>
                <th class='w-50'>Titolo</th>
                <th class='text-end'>Prezzo</th>
                <th>Categoria</th>
                <th class='text-center'>Azioni</th>
            </tr>
            </thead><tbody>`;

    // loop through list of data
    $.each(products, function(key, val) {
        // creating new table row per record
        table+=`<tr>
            <td>` + val.name + `</td>
            <td class='text-end'>` + val.price + ` euro</td>
            <td>` + val.category_name + `</td>
            <!-- 'action' buttons -->
            <td class='text-center'>
                <div class='btn-group btn-group-sm'>
                <!-- read product button -->
                <button class='btn btn-primary me-2 read-one-product-button' data-id='` + val.id + `'>
                    <span class='fa fa-eye'></span> <small>Leggi</small>
                </button>
                <!-- edit button -->
                <button class='btn btn-info me-2 update-product-button' data-id='` + val.id + `'>
                    <span class='fa fa-edit'></span> <small>Modifica</small>
                </button>
                <!-- delete button -->
                <button class='btn btn-danger delete-product-button' data-id='` + val.id + `'>
                    <span class='fa fa-remove'></span> <small>Cancella</small>
                </button>
                </div>
            </td>
        </tr>`;
    });
    // end table
    table+=`</tbody></table>`;
    
    return table;
}
