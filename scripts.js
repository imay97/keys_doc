function auth(){
	$.ajax({
		url: 'server.php',
		type: 'POST',
		data: 'type=auth'+'&session='+$.cookie('PHPSESSID'),
		success: function(data){
			result = jQuery.parseJSON(data)
			$('body').html(result['response'])
		}
	});
}

function login(){
	$.ajax({
		url: 'server.php',
		type: 'POST',
		data: 'type=login'+'&login='+$('#login').val()+'&password='+$('#pswd').val(),
		success: function(data){
			result = jQuery.parseJSON(data)
			if(result['error'] == 'no'){
				$('body').html(result['response'])
				$.cookie('PHPSESSID', result['sess'])
			}
			else $('#error').html(result['error'])
		}
	});
}

function users(){
	$.ajax({
		url: 'server.php',
		type: 'POST',
		data: 'type=users'+'&sess='+$.cookie('PHPSESSID'),
		success: function(data){
			result = jQuery.parseJSON(data)
			if(result['error'] == 'no'){
				$('body').html(result['response'])
			}
			else $('#error').html(result['error'])
		}
	});
}

function db(){
	$.ajax({
		url: 'server.php',
		type: 'POST',
		data: 'type=db'+'&sess='+$.cookie('PHPSESSID'),
		success: function(data){
			result = jQuery.parseJSON(data)
			if(result['error'] == 'no'){
				$('body').html(result['response'])
			}
			else $('#error').html(result['error'])
		}
	});
}

function show_add_db(){
	$.ajax({
		url: 'server.php',
		type: 'POST',
		data: 'type=show_add_db'+'&sess='+$.cookie('PHPSESSID'),
		success: function(data){
			result = jQuery.parseJSON(data)
			if(result['error'] == 'no'){
				$('body').append(result['response'])
			}
			else $('#error').html(result['error'])
		}
	});
}

function add_db(){
	mass = []
	for (var i = 0; i < 7; i++) {
		mass[i] = $('#'+(i+1)).val()
	}
	$.ajax({
		url: 'server.php',
		type: 'POST',
		data: 'type=add_db'+'&sess='+$.cookie('PHPSESSID')+'&mass='+mass,
		success: function(data){
			result = jQuery.parseJSON(data)
			if(result['error'] == 'no'){
				$('body').html(result['response'])
				alert(result['alert'])
			}
			else $('#error').html(result['error'])
		}
	});
}

function del_db(id){
	$.ajax({
		url: 'server.php',
		type: 'POST',
		data: 'type=del_db'+'&sess='+$.cookie('PHPSESSID')+'&id='+id,
		success: function(data){
			result = jQuery.parseJSON(data)
			if(result['error'] == 'no'){
				$('body').html(result['response'])
				alert(result['alert'])
			}
			else $('#error').html(result['error'])
		}
	});
}

function show_mod_db(id){
	$.ajax({
		url: 'server.php',
		type: 'POST',
		data: 'type=show_mod_db'+'&sess='+$.cookie('PHPSESSID')+'&id='+id,
		success: function(data){
			result = jQuery.parseJSON(data)
			if(result['error'] == 'no'){
				$('body').append(result['response'])
			}
			else $('#error').html(result['error'])
		}
	});
}

function mod_db(id){
	mass = []
	for (var i = 0; i < 9; i++) {
		mass[i] = $('#'+(i+9)).val()
	}
	$.ajax({
		url: 'server.php',
		type: 'POST',
		data: 'type=mod_db'+'&sess='+$.cookie('PHPSESSID')+'&id='+id+'&mass='+mass,
		success: function(data){
			result = jQuery.parseJSON(data)
			if(result['error'] == 'no'){
				$('body').html(result['response'])
				alert(result['alert'])
			}
			else $('#error').html(result['error'])
		}
	});
}

function show_add_user(){
	$.ajax({
		url: 'server.php',
		type: 'POST',
		data: 'type=show_add_user'+'&sess='+$.cookie('PHPSESSID'),
		success: function(data){
			result = jQuery.parseJSON(data)
			if(result['error'] == 'no'){
				$('body').append(result['response'])
			}
			else $('#error').html(result['error'])
		}
	});
}

function add_user(){
	$.ajax({
		url: 'server.php',
		type: 'POST',
		data: 'type=add_user'+'&sess='+$.cookie('PHPSESSID')+'&login='+$('#login').val()+'&pswd='+$('#pswd').val()+'&name='+$('#name').val(),
		success: function(data){
			result = jQuery.parseJSON(data)
			if(result['error'] == 'no'){
				$('body').html(result['response'])
				alert(result['alert'])
			}
			else $('#error').html(result['error'])
		}
	});
}

function del_user(id){
	$.ajax({
		url: 'server.php',
		type: 'POST',
		data: 'type=del_user'+'&sess='+$.cookie('PHPSESSID')+'&id='+id,
		success: function(data){
			result = jQuery.parseJSON(data)
			if(result['error'] == 'no'){
				$('body').html(result['response'])
				alert(result['alert'])
			}
			else $('#error').html(result['error'])
		}
	});
}

function add_admin(){
	$.ajax({
		url: 'server.php',
		type: 'POST',
		data: 'type=add_admin'+'&login='+$('#login').val()+'&pswd='+$('#pswd').val()+'&name='+$('#name').val(),
		success: function(data){
			result = jQuery.parseJSON(data)
			if(result['error'] == 'no'){
				$('body').html(result['response'])
				alert(result['alert'])
				$.cookie('PHPSESSID', result['sess'])
			}
			else $('#error').html(result['error'])
		}
	});
}

function exit(){
	$.cookie('PHPSESSID', '')
	location.reload()
}

function sort(){
	v = $('#sort').val()
	$('.tr').css('display', 'none')
	$('.tr').css('display', 'block')
}
