$(document).ready(function() {	
	$('#confirmDelete').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget);
	  var mail = button.data('mail');
	  var modal = $(this);
	  modal.find('#confirmDeleteMail').text(mail);
	  modal.find('#maillinglistbundle_abonne_mail').val(mail);
	});
	
	$('.btn-edit').click(function() {
		nom = $(this).data('nom');
		$('#maillinglistbundle_mailinglist_nom').val(nom);
		$("[name='maillinglistbundle_mailinglist']").submit();
    });

 	$('.jpi_table_data_table').DataTable({
 	    "language": {
 		"url": "https://cdn.datatables.net/plug-ins/3cfcc339e89/i18n/French.json"
 	    },
 	   "columnDefs": [{ "orderable": false, "targets": [1] }]
 	});
});
