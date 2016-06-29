// funcao encarregada de obter os dados do formulario e envia-lo para processamento
function processarFormCancelamento(formulario, chave, modo)
{    
    if (!validar_cancelamento())
    {
        return false;
    }

    return processarForm(formulario, chave, modo);
}

function validar_cancelamento()
{
    return $('#frmCancelamento').validate({
        rules: {
            motivo:"required"
        },
        show: {when: {event: 'none'}, ready: true},
        hide: {when: {event: 'keydown'}},
        // Define as mensagens de erro para cada regra
        messages: {
            motivo:"Informe um valor"
        }
    }).form();
}

$(function(){
        $("body").on("click", "#btn_modal", function(){
                $("#cancelar").modal("show");
        });
});   