$(document).ready(function() {

	// show list of product when the user clicks on a 'read products' button
	$(document).on('click', '.read-products-button', function(e) { // first par = event; second (optional) par = selector (in this case the class); last par = event handler
		e.preventDefault();
		showProducts();
	}); 

});
 
// function to show list of products
function showProducts() {
	// get data from the read service
	sendRequest("read.php", data => { // data = coppia products: lista-prodotti (array di oggetti JSON)
		// html for listing products
		let read_products_html=search_products_form();
		read_products_html+=create_product_button();
		read_products_html+=products_table(data.products);
		
		// inject to 'page-content' of our app
		$("#page-content").html(read_products_html);

		// chage page title
		changePageTitle("Fumetti");
	});
} // showProducts
