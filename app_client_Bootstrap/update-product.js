$(document).ready(function() {

    // (*1*) show (filled out) html form when the user clicks on the 'update product' button
    $(document).on('click', '.update-product-button', function(e) { // first par = event; second (optional) par = selector (in this case the class); last par = event handler
		e.preventDefault();

        // get product id
		const id = $(this).attr('data-id'); // this = clicked button; read the value of data-id attribute
		
		sendRequest("read_one.php?id="+id, data => {
			let update_product_html=back_to_products_button();
			update_product_html+=`
				<!-- build 'update product' html form -->
				<form id='update-product-form' action='#' method='post' border='0'>
					<table class='table table-hover table-responsive table-bordered'>
						<!-- name field -->
						<tr>
							<td>Nome</td>
							<td><input value=\"` + data.name + `\" type='text' name='name' class='form-control' required /></td>
						</tr>
						<!-- price field -->
						<tr>
							<td>Prezzo</td>
							<td><input value=\"` + data.price + `\" type='number' min='1' name='price' class='form-control' required /></td>
						</tr>
						<!-- description field -->
						<tr>
							<td>Descrizione</td>
							<td><textarea name='description' class='form-control' required>` + data.description + `</textarea></td>
						</tr>
						<!-- category id field *NB* PER NOI LA CATEGORIA NON E' UN MENU (CI MANCA l'API PER AVERE LA LISTA DELLE CATEGORIE !!! -->
						<tr>
							<td>ID Categoria</td>
							<td><input value=\"` + data.category_id + `\" type='number' name='cat_id' class='form-control' required /></td>
						</tr>
						<tr>
							<!-- hidden 'product id' to identify which record to update -->
							<td><input value=\"` + id + `\" name='id' type='hidden' /></td>
							<!-- button to submit form -->
							<td>
								<button type='submit' class='btn btn-info'>
									<span class='fa fa-edit'></span> Aggiorna fumetto
								</button>
							</td>
						</tr>
					</table>
				</form>`;				

			// inject to 'page-content' of our app
			$("#page-content").html(update_product_html);
 
			// chage page title
			changePageTitle("Aggiorna fumetto");
		});
    });
	
    // (*2*) send updated product data to the update service when the user submit the form
	$(document).on('submit', '#update-product-form', function(e) {
		e.preventDefault();

		// get form data
		const form_data = JSON.stringify( Object.fromEntries( new FormData( this ) ) );

		// for debugging...
		console.log("FORM DATA: "+form_data);

		sendRequest("update.php", showProducts, "PUT", form_data);
		});
});