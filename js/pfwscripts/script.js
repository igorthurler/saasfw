// funcao encarregada de obter os dados do formulario e envia-lo para processamento
function processarForm( formulario, chave, modo )
{		
    var postData = '';
    postData = formData2QueryString( formulario );    
    ajax.doPost( 'includes/PfwController.php?app='+chave+'&action='+modo, postData, 'viewers', false, 'text' );
    return false;
}