// funcao encarregada de obter os dados do formulario e envia-lo para processamento
function processarFormConfigAdmin(formulario, chave, modo)
{  
    if (!validar_configadmin())
    {
        return false;
    }

    return processarForm(formulario, chave, modo);
}

function validar_configadmin()
{
    return $('#frmConfigAdmin').validate({
        rules: {
            diasDeToleranciaParaPagamento: "required",
            diasCobrancaPagamentos: "required"
        },
        show: {when: {event: 'none'}, ready: true},
        hide: {when: {event: 'keydown'}},
        // Define as mensagens de erro para cada regra
        messages: {
            diasDeToleranciaParaPagamento: "Informe um valor",
            diasCobrancaPagamentos: "Informe um valor"
        }
    }).form();
}