// funcao encarregada de obter os dados do formulario e envia-lo para processamento
function processarFormModulo(formulario, chave, modo)
{
    if (!validar_modulo())
    {
        return false;
    }

    return processarForm(formulario, chave, modo);
}

function validar_modulo()
{
    return $('#frmModulo').validate({
        rules: {
            identificador: "required",
            descricao: "required"
        },
        show: {when: {event: 'none'}, ready: true},
        hide: {when: {event: 'keydown'}},
        // Define as mensagens de erro para cada regra
        messages: {
            identificador: "Informe um valor",
            descricao: "Informe um valor"
        }
    }).form();
}     