function somenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;   
    if((tecla>47 && tecla<58)) return true;
    else{
    	if (tecla===8 || tecla===0) return true;
	else  return false;
    }
}

function floatSql(value) {    
    var floatSqlValue = value.replace(".", "");
    floatSqlValue = value.replace(",", ".");    
    return floatSqlValue;    
}

function uploadImagemPerfil(form,url_action,url_redirect,id_elemento_retorno,html_exibe_carregando,html_erro_http) {
    if (ge('imagemperfil').value === '') {
        alert("Selecione uma imagem!");
    } else {
        micoxUpload(form,url_action,id_elemento_retorno,html_exibe_carregando,html_erro_http);
        alert("Imagem do perfil atualizada!");
        if (url_redirect !== "") {
            ajax.doGet( url_redirect, 'viewers', true );
        }
    }
}

function executarFuncaoComConfirmacao(mensagem, url) {
    if (confirm(mensagem)) {
        ajax.doGet( url, 'viewers', true );
        return true;
    } else {
        return false;
    }    
}

function getModuleURL() {
    var url   = window.location.search.replace("?", "");
    var module = url.split("=");
    var action = module[1];
    return action;
}

function mascaraCampo(tipo, campo, teclaPress) {
	if (window.event)
	{
		var tecla = teclaPress.keyCode;
	} else {
		tecla = teclaPress.which;
	}

	var s = new String(campo.value);
	// Remove todos os caracteres à seguir: ( ) / - . e espaço, para tratar a string denovo.
	s = s.replace(/(\.|\(|\)|\/|\-| )+/g,'');

	tam = s.length + 1;

	if ( tecla !== 9 && tecla !== 8 ) {
		switch (tipo)
		{
		case 'CPF' :
			if (tam > 3 && tam < 7)
				campo.value = s.substr(0,3) + '.' + s.substr(3, tam);
			if (tam >= 7 && tam < 10)
				campo.value = s.substr(0,3) + '.' + s.substr(3,3) + '.' + s.substr(6,tam-6);
			if (tam >= 10 && tam < 12)
				campo.value = s.substr(0,3) + '.' + s.substr(3,3) + '.' + s.substr(6,3) + '-' + s.substr(9,tam-9);
			if (tam > 12)				
				campo.value = campo.value.substr(0,campo.value.length-1);							
		break;

		case 'CNPJ' :

			if (tam > 2 && tam < 6)
				campo.value = s.substr(0,2) + '.' + s.substr(2, tam);
			if (tam >= 6 && tam < 9)
				campo.value = s.substr(0,2) + '.' + s.substr(2,3) + '.' + s.substr(5,tam-5);
			if (tam >= 9 && tam < 13)
				campo.value = s.substr(0,2) + '.' + s.substr(2,3) + '.' + s.substr(5,3) + '/' + s.substr(8,tam-8);
			if (tam >= 13 && tam < 15)
				campo.value = s.substr(0,2) + '.' + s.substr(2,3) + '.' + s.substr(5,3) + '/' + s.substr(8,4)+ '-' + s.substr(12,tam-12);
			if (tam > 15)				
				campo.value = campo.value.substr(0,campo.value.length-1);			
				
		break;

		case 'TEL' :
			if (tam > 2 && tam < 4)
				campo.value = '(' + s.substr(0,2) + ') ' + s.substr(2,tam);
			if (tam >= 7 && tam < 11)
				campo.value = '(' + s.substr(0,2) + ') ' + s.substr(2,4) + '-' + s.substr(6,tam-6);
			if (tam > 11)				
				campo.value = campo.value.substr(0,campo.value.length-1);			
		break;

		case 'DATA' :
			if (tam > 2 && tam < 4)
				campo.value = s.substr(0,2) + '/' + s.substr(2, tam);
			if (tam > 4 && tam < 11)
				campo.value = s.substr(0,2) + '/' + s.substr(2,2) + '/' + s.substr(4,tam-4);
			if (tam > 9)				
				campo.value = campo.value.substr(0,campo.value.length-1);			
				
		break;
		
		case 'CEP' :
			if (tam > 5 && tam < 7)
				campo.value = s.substr(0,5) + '-' + s.substr(5, tam);
			if (tam > 9)				
				campo.value = campo.value.substr(0,campo.value.length-1);			
				
		break;
		}
	}
}