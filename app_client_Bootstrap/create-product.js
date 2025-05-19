$(document).ready(function() {

    // (*1*) show html form when the user clicks on the 'create product' button
    $(document).on('click', '.create-product-button', function(e) { // first par = event; second (optional) par = selector (in this case the class); last par = event handler
		e.preventDefault();

		// html for new product form
		// NB the "read products" button is useful to go back to the products list		
		let create_product_html=back_to_products_button();
		create_product_html+=`	
			<!-- 'create product' html form -->
			<form id='create-product-form' action='#' method='post' border='0'>
				<table class='table table-responsive table-bordered'>
					<!-- name field -->
					<tr>
						<td>Nome</td>
						<td><input type='text' name='name' class='form-control' required /></td>
					</tr>
					<!-- price field -->
					<tr>
						<td>Prezzo</td>
						<td><input type='number' min='1' name='price' class='form-control' required /></td>
					</tr>
					<!-- description field -->
					<tr>
						<td>Descrizione</td>
						<td><textarea name='description' class='form-control' required></textarea></td>
					</tr>
					<!-- category id field *NB* PER NOI LA CATEGORIA NON E' UN MENU (CI MANCA l'API PER AVERE LA LISTA DELLE CATEGORIE !!!) -->
					<tr>
						<td>ID Categoria</td>
						<td><input type='number' name='cat_id' class='form-control' required /></td>
					</tr>
					<!-- button to submit form -->
					<tr>
						<td></td>
						<td>
							<button type='submit' class='btn btn-primary'>
								<span class='fa fa-plus'></span> Crea fumetto
							</button>
						</td>
					</tr>
				</table>
			</form>`;

		// inject html to 'page-content' of our app
		$("#page-content").html(create_product_html);
 
		// chage page title
		changePageTitle("Crea fumetto");
	});

    // (*2*) send new product data to the create service when the user submit the form for the creation of a new product
	$(document).on('submit', '#create-product-form', function(e) { // first par = event; second (optional) par = selector (in this case the id); last par = event handler
		e.preventDefault();

		// get form data
		const form_data = JSON.stringify( Object.fromEntries( new FormData( this ) ) ); // this = the submitted form
		
		// for debugging...
		console.log("FORM DATA: "+form_data);
		
		sendRequest("create.php", showProducts, "POST", form_data);
	});
});