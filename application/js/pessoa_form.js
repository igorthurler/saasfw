function processarFormPessoa(formulario, chave, modo)
{
    if (!validar_pessoa())
    {
        return false;
    }    
    
    return processarForm(formulario, chave, modo);
}

function validar_pessoa()
{
    return $('#frmPessoa').validate({
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
            bairro: {
                required: true,
                maxlength: 50
            },            
            estado: "required",
            cidade: "required",
            email: {
                required: true,
                maxlength: 100,
                email: true
            }
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
            estado: "Selecione um valor",
            cidade: "Selecione um valor",
            bairro: {
                required: "Informe um estado",
                maxlength: "O valor informado deve conter no máximo 50 caracteres"
            },                        
            email: {
                required: "Informe um valor",
                maxlength: "O valor informado deve conter no máximo 100 caracteres",
                email: "Informe um email válido"
            }
        }
    }).form();
}

/**
 * Retorna os dados de uma pessoa a partir do número do documento(CPF ou CNPJ) informado.
 * @param {string} documento Número do documento (CPF ou CNPJ)
 */
function buscarDadosDaPessoa(documento) {

    if (documento === "") {
            return false;
    }

    documento = documento.replace(/[^0-9]+/g,'');
    
    var url = 'includes/PfwController.php?app=pessoa&action=buscar&documento=' + 
            encodeURIComponent(documento);	
    
    var req = ajax.GetXMLHttp();    
    
    req.open('GET',url,true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");	
        
    req.onreadystatechange = function() {        
        if(req.readyState === 4 && req.status === 200)
        {               
			
            if (ge('id_pessoa') !== null) ge('id_pessoa').value = '';
            if (ge('nome') !== null) ge('nome').value = '';
            if (ge('cep') !== null) ge('cep').value = '';
            if (ge('logradouro') !== null) ge('logradouro').value = '';
            if (ge('numero') !== null) ge('numero').value = '';
            if (ge('complemento') !== null) ge('complemento').value = '';
            if (ge('bairro') !== null) ge('bairro').value = '';
            if (ge('estado') !== null) ge('estado').value = '';
            if (ge('cidade') !== null) ge('cidade').value = '';
            if (ge('telefone1') !== null) ge('telefone1').value = '';
            if (ge('telefone2') !== null) ge('telefone2').value = '';
            if (ge('Email') !== null) ge('Email').value = '';
            if (ge('senha') !== null) ge('senha').value = '';
            if (ge('senhaatual') !== null) ge('senhaatual').value = '';				

            var valores = eval(req.responseText);			                            

            if (valores[0] !== null) {  
                if (ge('id_pessoa') !== null) ge('id_pessoa').value = valores[0].id_pessoa;
                if (ge('documentoatual') !== null) ge('documentoatual').value = valores[0].documento;
                if (ge('nome') !== null) ge('nome').value = valores[0].nome;
                if (ge('cep') !== null) ge('cep').value = valores[0].cep;
                if (ge('logradouro') !== null) ge('logradouro').value = valores[0].logradouro;
                if (ge('numero') !== null) ge('numero').value = valores[0].numero;
                if (ge('complemento') !== null) ge('complemento').value = valores[0].complemento;
                if (ge('bairro') !== null) ge('bairro').value = valores[0].bairro;
                if (ge('estado') !== null) ge('estado').value = valores[0].estado;
                if (ge('telefone1') !== null) ge('telefone1').value = valores[0].telefone1;
                if (ge('telefone2') !== null) ge('telefone2').value = valores[0].telefone2;
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
 * Retorna as cidades de um determinado estado.
 * @param {int} estado Id do estado utilizado para retorno das cidades
 * @param {bool} assinc Se true, o método será executado de forma assíncrona.
 */
function preencherCidades(estado, assinc) {
    ge('cidade').options.length = 0;
    ge('cidade').options[0] = new Option('Selecione uma cidade', '');

    var url = 'includes/PfwController.php?app=pessoa&action=buscar&estado=' + 
            encodeURIComponent(estado);	
    
    var req = ajax.GetXMLHttp();
    req.open('GET',url,assinc);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");	
    req.onreadystatechange = function() {        
        if(req.readyState === 4 && req.status === 200)
        {                   
            var valores = eval(req.responseText);			
            
            var cidade = ge('cidade');

            for (var i = 1; i <= valores.length; i++) {
                cidade.options[i] = new Option(valores[i].nome, valores[i].id);
            }                                                           
        }
    };      
        
    req.send(null);    
}