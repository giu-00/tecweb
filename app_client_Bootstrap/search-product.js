$(document).ready(function() {
 
    // when the user clicks on a 'search products' button
    $(document).on('submit', '#search-product-form', function(e) { // first par = event; second (optional) par = selector  (in this case the id); last par = event handler
		e.preventDefault();

        // get search keywords (to be displayed in the search input field)
        var keywords = $(this).find("input[name='keywords']").val(); // this = submitted form; looking for descendant (i.e., form elements) of input elements with name='keywords' 

		sendRequest("search.php?s="+encodeURIComponent(keywords), data => {// data = coppia products: lista-prodotti (array di oggetti JSON)
			// html for listing products found
			let search_products_html=search_products_form();
			search_products_html+=back_to_products_button();
			search_products_html+=products_table(data.products);
			
			// inject to 'page-content' of our app
			$("#page-content").html(search_products_html);

			// chage page title
			changePageTitle("Cerca fumetti: " + keywords);
		});
    });
});