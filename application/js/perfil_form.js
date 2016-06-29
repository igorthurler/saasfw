// funcao encarregada de obter os dados do formulario e envia-lo para processamento
function processarFormPerfil(formulario, chave, modo)
{    
    if (!validar_perfil())
    {
        return false;
    }

    return processarForm(formulario, chave, modo);
}

function validar_perfil()
{
    return $('#frmPerfil').validate({
        rules: {
            descricao:{
                required: true,
                maxlength: 100
            }
        },
        show: {when: {event: 'none'}, ready: true},
        hide: {when: {event: 'keydown'}},
        // Define as mensagens de erro para cada regra
        messages: {
            descricao:{
                required: "Informe um valor",
                maxlength: "O valor máximo é 100 caracteres"
            }
        }
    }).form();
}