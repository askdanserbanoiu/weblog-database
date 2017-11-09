
$("div[id='pavatar']").click(function() {
    $("input[id='uploadImage']").click();
});

$(function() {
    $("input[id='uploadImage']").change(function (){
    	var ok = confirm('Confermi di voler cambiare l\'immagine?');
    	$("button[id='uploadImageButton']").click();
    	
    });
});
