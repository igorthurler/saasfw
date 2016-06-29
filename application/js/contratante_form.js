// funcao encarregada de obter os dados do formulario e envia-lo para processamento
function processarFormContratante(formulario, chave, modo)
{
    if (!validar_contratante())
    {
        return false;
    }

    return processarForm(formulario, chave, modo);
}

function validar_contratante()
{
    return $('#frmContratante').validate({
        rules: {
            alias: {
                required: true,
                maxlength: 32
            },		
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
            estado: {
                required: true
            },			
            cidade: {
                required: true
            },						
            email: {
                required: true,
                maxlength: 100,
                email: true
            },            
            senha: {
                maxlength: 50
            }
        },
        show: {when: {event: 'none'}, ready: true},
        hide: {when: {event: 'keydown'}},
        // Define as mensagens de erro para cada regra
        messages: { 
            alias: {
                required: "Informe um valor",
                maxlength: "O valor informado deve conter no máximo 32 caracteres"
            },				
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
            bairro: {
                required: "Selecione um valor",
                maxlength: "O valor informado deve conter no máximo 50 caracteres"
            },              
            estado: {
                required: "Selecione um valor"
            },						
            cidade: {
                required: "Selecione um valor"
            },									
            email: {
                required: "Informe um valor",
                maxlength: "O valor informado deve conter no máximo 100 caracteres",
                email: "Informe um email válido"
            },            
            senha: {
                maxlength: "O valor informado deve conter no máximo 50 caracteres"
            }
        }
    }).form();
}