$(document).ready(function(){
	var game_id = $("#id_game").val();
	var url = "/admin/game/creation";
	var asset = $("#asset").val();



	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
    	}
    })

	

});