// funcao encarregada de obter os dados do formulario e envia-lo para processamento
function processarFormContrato(formulario, chave, modo)
{
    if (!validar_contrato())
    {
        return false;
    }

    return processarForm(formulario, chave, modo);
}

function validar_contrato()
{
    return $('#frmContrato').validate({
        rules: {
            documento: {
                required: true,
                maxlength: 14,
                number: true
            },
            nome: {
                required: true,
                maxlength: 100
            },
            logradouro: {
                required: true,
                maxlength: 100
            },            
            numero: {
                required: true,
                maxlength: 20
            },            
            bairro: {
                required: true,
                maxlength: 50
            },            
            estado: {
                required: true
            },			
            cidade: {
                required: true
            },	
            cep: {
                required: true
            },            
            email: {
                required: true,
                maxlength: 100,
                email: true
            },            
            politicadepreco:"required",
            formadepagamento:"required",
            tipodepagamento:"required"
        },
        show: {when: {event: 'none'}, ready: true},
        hide: {when: {event: 'keydown'}},
        // Define as mensagens de erro para cada regra
        messages: {                            
            documento: {
                required: "Informe um valor",
                maxlength: "O valor informado deve conter no máximo 14 caracteres",
                number: "O valor informado deve conter apenas caracteres numéricos"
            },
            nome: {
                required: "Informe um valor",
                maxlength: "O valor informado deve conter no máximo 100 caracteres"
            },
            logradouro: {
                required: "Informe um valor",
                maxlength: "O valor informado deve conter no máximo 100 caracteres"
            },            
            numero: {
                required: "Informe um valor",
                maxlength: "O valor informado deve conter no máximo 20 caracteres"
            },            
            bairro: {
                required: "Informe um valor",
                maxlength: "O valor informado deve conter no máximo 50 caracteres"
            },              
            estado: {
                required: "Selecione um valor"
            },						
            cidade: {
                required: "Selecione um valor"
            },	
            cep: {
                required: "Informe um valor"
            },              			
            email: {
                required: "Informe um valor",
                maxlength: "O valor informado deve conter no máximo 100 caracteres",
                email: "Informe um valor válido"
            },            
            politicadepreco:"Informe um valor",
            formadepagamento:"Informe um valor",
            tipodepagamento:"Informe um valor"
        }
    }).form();
}

function buscarDadosContratante(documento) 
{
   
    if (documento === "") {
            return false;
    }

    documento = documento.replace(/[^0-9]+/g,'');
    
    var url = 'includes/PfwController.php?app=contrato&action=buscarDados&documento=' + 
            encodeURIComponent(documento);	
    
    var req = ajax.GetXMLHttp();    
    
    req.open('GET',url,true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");	
   
    req.onreadystatechange = function() {        
    
        if(req.readyState === 4 && req.status === 200)
        {               

            if (ge('id_pessoa') !== null) ge('id_pessoa').value = '';
            if (ge('id_contratante') !== null) ge('id_contratante').value = '';
            if (ge('nome') !== null) ge('nome').value = '';
			if (ge('cep') !== null) ge('cep').value = '';
			if (ge('logradouro') !== null) ge('logradouro').value = '';
			if (ge('numero') !== null) ge('numero').value = '';
			if (ge('complemento') !== null) ge('complemento').value = '';
			if (ge('bairro') !== null) ge('bairro').value = '';
            if (ge('estado') !== null) ge('estado').value = '';
            if (ge('cidade') !== null) ge('cidade').value = '';
            if (ge('Email') !== null) ge('Email').value = '';                            
            
            var valores = eval(req.responseText);			                    
            
            if (valores[0] !== null) {              
                if (ge('id_pessoa') !== null) ge('id_pessoa').value = valores[0].id_pessoa;
                if (ge('id_contratante') !== null) ge('id_contratante').value = valores[0].id_contratante;
                if (ge('documentoatual') !== null) ge('documentoatual').value = valores[0].documento;
                if (ge('nome') !== null) ge('nome').value = valores[0].nome;				
				if (ge('cep') !== null) ge('cep').value = valores[0].cep;
				if (ge('logradouro') !== null) ge('logradouro').value = valores[0].logradouro;
				if (ge('numero') !== null) ge('numero').value = valores[0].numero;
				if (ge('complemento') !== null) ge('complemento').value = valores[0].complemento;
				if (ge('bairro') !== null) ge('bairro').value = valores[0].bairro;				
                if (ge('estado') !== null) ge('estado').value = valores[0].estado;
                if (ge('Email') !== null) ge('Email').value = valores[0].email;
                if (ge('cidade') !== null) {
                    preencherCidades(valores[0].estado, false);                    
                    ge('cidade').value = valores[0].cidade;                    
                }
            }                                
                        
        }
        
    };  
                               
    req.send(null);  
}

/**
 * Define a forma e o tipo de pagamento configurado para contrato grátis
 * @param {int} estado Id da política de preço selecionada
 * @param {bool} assinc Se true, o método será executado de forma assíncrona.
 */
function definirFormaTipoPgtoContratoGratis(politica) {
    if (politica === "") {
            return false;
    }	
	
	//alert('Forma de pagamento definida para a política ' + politica);
	//alert('Tipo de pagamento definido para a política ' + politica);
	
    var url = 'includes/PfwController.php?app=ConfigAdmin&action=buscarFormaPgtoGratis&politica=' + 
            encodeURIComponent(politica);	
    
    var req = ajax.GetXMLHttp();
    
    req.open('GET',url,false);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");	
   
    req.onreadystatechange = function() {        
    
        if(req.readyState === 4 && req.status === 200)
        {   
			ge('formadepagamento').value = '';
			ge('tipodepagamento').value = '';
			var valores = eval(req.responseText);
			if (valores[0] !== null) {
				if (valores[0].formadepagamento != 0) {
					ge('formadepagamento').value = valores[0].formadepagamento;
				}
				if (valores[0].tipodepagamento != 0) {
					ge('tipodepagamento').value = valores[0].tipodepagamento;
				}
			}
        }
        
    };  
	                             
    req.send(null);  			
}