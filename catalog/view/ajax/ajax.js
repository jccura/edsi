
function getSponsorInformation(username) {
	
	$.ajax({
		url: 'getsponsorinfo/'+username,
		type: 'get',
		dataType: 'json',
		success: function(json) {	 					
			if (json['success']) {				
				$('#sponsor_id').val(json['sponsor_id']);
				$('#sponsor_name').val(json['name']);
				$('#sponsor_id_no').val(json['sponsor_id_no']);
				$(".sponsor_info").removeClass("hidden");
			} else {
				$('#sponsor_id').val("0");
				$('#sponsor_name').val("");
				$('#sponsor_id_no').val("");
				$(".sponsor_info").addClass("hidden");
			}	
		}
	});
}

function getUserInformation(username) {
	
	$.ajax({
		url: 'getuserinfo/'+username,
		type: 'get',
		dataType: 'json',
		success: function(json) {	 					
			if (json['success']) {				
				$('#user_id').val(json['user_id']);
				$('#user_name').val(json['name']);
				$('#id_no').val(json['id_no']);
				$(".user_info").removeClass("hidden");
			} else {
				$('#user_id').val("0");
				$('#user_name').val("");
				$('#id_no').val("");				
				$(".user_info").addClass("hidden");
			}	
		}
	});
}

function getBankerInformation(username) {
	
	$.ajax({
		url: 'getbankerinfo/'+username,
		type: 'get',
		dataType: 'json',
		success: function(json) {	 					
			if (json['success']) {				
				$('#user_id').val(json['user_id']);
				$('#user_name').val(json['name']);
				$('#id_no').val(json['id_no']);
				$('#email').val(json['email']);
				$('#contact').val(json['contact']);
				$(".user_info").removeClass("hidden");
			} else {
				$('#user_id').val("0");
				$('#user_name').val("");
				$('#id_no').val("");
				$('#email').val("");
				$('#contact').val("");
				$(".user_info").addClass("hidden");
			}	
		}
	});
}

function numberWithCommas(x) {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

