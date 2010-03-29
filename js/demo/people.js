$(function() {

	$('#dlg-add-record').dialog( { autoOpen: false, modal: true, width: 500, minWidth: 500, maxWidth: 500 });
	$('#dlg-confirm').dialog( { autoOpen: false, width: 400, modal: true, resizable: false, buttons: {
		"Yes": function() {
			url = '/demo/people/delete/' + $('#confirm-record-id').val();
			$.getJSON(url, function(result) { refreshPeople(); });
			$(this).dialog("close");
		},
		"No": function() {
			$(this).dialog("close");
		}
	} });
	$('#dlg-reset').dialog( { autoOpen: false, width: 400, modal: true, resizable: false, buttons: {
		"Yes": function() {
		  	$.getJSON('/demo/people/reset', function(result) { refreshPeople(); });
			$(this).dialog("close");
		},
		"No": function() {
			$(this).dialog("close");
		}
	} });

	$('#btn-add-person').click(function() {
		$('#person-record').clearForm();
		$('#form-id').val('-1');
		$('#dlg-add-record').dialog('option', 'title', 'Add Person').dialog('open');
	});

	$('.people-item').live('click', function() {
		$('#person-record').clearForm();
		$('#form-id').val( $(this).attr('rel') );
 		$('#form-name').val( $(this).attr('data') );
		$('#form-description').val( $(this).attr('title') );
		$('#dlg-add-record').dialog('option', 'title', 'Edit Person').dialog('open');
	});

	$('.people-delete').live('click', function() {
		$('#confirm-record-id').val( $(this).attr('rel') );
		$('#confirm-description').html( $(this).attr('title') );
		$('#dlg-confirm').dialog('open');
	});

	$('#person-record').ajaxForm(function() {
		refreshPeople();
		$('#dlg-add-record').dialog('close');
	});

	$('#btn-refresh').click(function() {
		refreshPeople();
	});

	$('#btn-reset-db').click(function() {
		$('#dlg-reset').dialog('open');
	});

	$.getJSON('/demo/people/reset', function(result) { refreshPeople(); });
});

function refreshPeople() {
	$('#people-count').html('Refreshing...');
	$.getJSON(
		'/demo/people/list?cacheBuster=' + Math.random(),
		function(People) {
			
			$('#people-count').html('There are ' + People.length + ' records');

			$('#people-list').empty();

			$.each(People, function(i, item) {
				obj = $('<a>' + item.name + '</a>');	

				obj.attr('rel', item.id);
				obj.attr('data', item.name);
				obj.attr('title', item.description);
				obj.addClass('people-item');

				del = $('<a>x</a>');
				del.attr('rel', item.id);
				del.attr('title', item.name);
				del.addClass('ui-icon');
				del.addClass('ui-icon-trash');
				del.addClass('people-delete');

				wrp = $('<div></div>');
				wrp.addClass('people-wrapper');
				
				wrp.append(del);
				wrp.append(obj);
				
				wrp.appendTo($('#people-list'));			
			});
		}
	);
}


