
// algumas funcoes uteis
// obtem elemento dado sua id
function ge( id )
{
    return document.getElementById( id );
}

// obtem todos os elementos de uma determinada tag
function ges( tag )
{
    return document.getElementsByTagName( tag );
}

function exibirMensagem(container, mensagem)
{
    ge(container).innerHTML = mensagem;
}