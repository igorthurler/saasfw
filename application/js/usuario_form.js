function processarFormUsuario(formulario, chave, modo)
{
    if (!validar_usuario())
    {
        return false;
    }    
    
    return processarForm(formulario, chave, modo);
}

function validar_usuario()
{
    return $('#frmUsuario').validate({
        rules: {
            nome: {
                required: true,
                maxlength: 100
            },    
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
            nome: {
                required: "Informe um valor",
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