$(document).ready(function() {
 
   // (*1*) show confirmation pop-up window when the user clicks on the 'delete product' button
   $(document).on('click', '.delete-product-button',  function (e) { // first par = event; second (optional) par = selector (in this case the class); last par = event handler
		e.preventDefault();

        // get the product id
		const product_id = $(this).attr('data-id'); // this = clicked button; read the value of data-id attribute

		// Bootbox.js library is used for the confirmation pop-up window
		bootbox.confirm({
			title: "Attenzione!",
			message: "Sei sicuro di volere eliminare questo fumetto?",
			swapButtonOrder: true,
			buttons: {
				confirm: {
					label: '<span class="fa fa-check"></span> Conferma',
					className: 'btn-danger'
				},
				cancel: {
					label: '<span class="fa fa-times"></span> Annulla',
					className: 'btn-secondary'
				}
			},
			callback: function (result) {
				if (result) {
					// (*2*) send delete request to the delete service if the user confirmed
					sendRequest("delete.php?id="+product_id, showProducts, "DELETE");
				}
			}
		});
    });
});