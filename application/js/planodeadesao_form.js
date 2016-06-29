// funcao encarregada de obter os dados do formulario e envia-lo para processamento
function processarFormPlanoDeAdesao(formulario, chave, modo)
{    
    if (!validar_planoDeAdesao())
    {
        return false;
    }

    return processarForm(formulario, chave, modo);
}

function validar_planoDeAdesao()
{
    return $('#frmPlanoDeAdesao').validate({
        rules: {
            duracao:{
                required: true,
                number: true,
                min: 1
            },
            descricao:{
                required: true,
                maxlength: 100
            },
            gratis: "required",
            quantusuario: "required"
        },
        show: {when: {event: 'none'}, ready: true},
        hide: {when: {event: 'keydown'}},
        // Define as mensagens de erro para cada regra
        messages: {
            duracao:{
                required: "Informe um valor",
                number: "O valor informado deve ser numérico",
                min: "O valor mínimo e 1"
            },
            descricao:{
                required: "Informe um valor",
                maxlength: "O valor máximo é 100 caracteres"
            },
            gratis: "Informe um valor",
	    quantusuario: "Informe um valor"
        }
    }).form();
}