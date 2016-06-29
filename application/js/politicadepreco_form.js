// funcao encarregada de obter os dados do formulario e envia-lo para processamento
function processarFormPoliticaDePreco(formulario, chave, modo)
{    
    
    ajustarDadosParaPersistencia(formulario);
    
    if (!validar_politicaDePreco())
    {
        return false;
    }

    return processarForm(formulario, chave, modo);
}

function ajustarDadosParaPersistencia(formulario) {
    formulario.valor.value = floatSql(formulario.valor.value);
}

function validar_politicaDePreco()
{
    return $('#frmPoliticaDePreco').validate({
        rules: {
            data: "required",
            id_planoadesao: "required",
            valor:{
                required: true,
                number: true
            }
        },
        show: {when: {event: 'none'}, ready: true},
        hide: {when: {event: 'keydown'}},
        // Define as mensagens de erro para cada regra
        messages: {
            data: "Informe um valor",
            id_planoadesao: "Informe um valor",
            valor:{
                required: "Informe um valor",
                number: "Informe um valor numérico"
            }
        }
    }).form();
}