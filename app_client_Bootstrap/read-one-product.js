$(document).ready(function() {
 
    // when the user clicks on a 'read one' button
    $(document).on('click', '.read-one-product-button', function(e) { // first par = event; second (optional) par = selector (in this case the class); last par = event handler
		e.preventDefault();

        // get product id
		const id = $(this).attr('data-id'); // this = clicked button; read the value of data-id attribute

		sendRequest("read_one.php?id="+id, data => { //data = un prodotto (oggetto JSON)
			// html for single product info
			let read_one_product_html=back_to_products_button();
			read_one_product_html+=`
			<!-- product data will be shown in this table -->
			<table class='table table-bordered'>
				<!-- product name -->
				<tr>
					<td class='w-25 fw-bold'>Nome</td>
					<td class='w-75'>` + data.name + `</td>
				</tr>
				<!-- product price -->
				<tr>
					<td class='fw-bold'>Prezzo</td>
					<td>` + data.price + ` euro</td>
				</tr>
				<!-- product description -->
				<tr>
					<td class='fw-bold'>Descrizione</td>
					<td>` + data.description + `</td>
				</tr>
				<!-- product category name -->
				<tr>
					<td class='fw-bold'>Categoria</td>
					<td>` + data.category_name + `</td>
				</tr>
			</table>`;
			
			// inject html to 'page-content' of our app
			$("#page-content").html(read_one_product_html);
 
			// chage page title
			changePageTitle("Leggi fumetto");
		});
    });
});