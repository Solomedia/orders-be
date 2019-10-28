jQuery(($) => {
	const table = $('#orders_table').DataTable({
		select: true,
	});
	
	$('#button').on('click', (e) => {
		const preId = table.rows( { selected: true } ).data();
		const idsToShow = Array.from(preId.map((id) => id[0]));
		
		if (!idsToShow.length) {
			alert('You need to select at least ONE order to cancel');
			return;
		}
		
		$('#validationModalValue').text('').text(idsToShow)
		$('#validationModal').modal({
  			keyboard: false,
		});
	});
	
	$('#validationModalButton').on('click', (e) => {
		e.preventDefault();
		const preId = table.rows( { selected: true } ).data();
		const idsToShow = Array.from(preId.map((id) => id[0]));
        /*var orID = $('#orders_table tbody tr').map(function() {
        	return $(this).attr('order-id');
        }).get();*/
        var m_url = $('.form-cancel').attr('m_action');
        $.ajax({
        	type: 'POST',
        	url: m_url,
        	data: {
        		order: idsToShow
        	},
        	beforeSend: function() {
        		$('#validationModal .modal-dialog .modal-content .modal-footer button').addClass('loadmt');
        	},
        	success: function(data) {
        		setTimeut(function() {
        		$('#validationModal .modal-dialog .modal-content .modal-footer button').removeClass('loadmt');
        		location.reload();
        		}, 200);
        	},
        	error: function() {
        		alert('error');
        		location.reload();
        	}
        });
    });

	$('body').on('click', '.cnl', function(e) {
		e.preventDefault();
		var atd = $(this).attr('btn-id');
		var m_url = $('.form-cancel').attr('action');
		$.ajax({
			type: 'POST',
			url: m_url,
			data: {
				order_id: atd
			},
			success: function(data) {
				if(data == '200') {
					location.reload();
				}
			},
			error: function() {
				alert('error');
			}
		});
	});

	$('.filter-cbdate').on('submit', function(e) {
		e.preventDefault();
		var status = $('.filter-cbdate [name="status"]').val(),
			from = $('.filter-cbdate [name="from"]').val(),
			to = $('.filter-cbdate [name="to"]').val(),
			url = $(this).attr('action');
			//alert('From:' + from + ' - - To:' +to);
			window.location.href = url+'admin.php?page=cancelled-order-dt&status='+status+'&from='+from+'&to='+to;
			//alert(url+'admin.php?page=cancelled-order-dt&from='+from+'&to'+to);
	});
});