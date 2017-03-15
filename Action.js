function newComer(){
	account = 'Tina';
	$.post(
		"Action.php",
		{
		'action': 0,
		'account': account
		}
	).done(function(data){
		
	});
}

function getClip(){
	$.post(
		"Action.php",
		{
		'action': 1,
		'start': start,
		'end': end,
		'channel_number': channel_number,
		'user_index': user_index
		}
	).done(function(data){
		
	});
}

function showClip(){
	$.post(
		"Action.php",
		{
		'action': 2
		}
	).done(function(data){
		
	});
}