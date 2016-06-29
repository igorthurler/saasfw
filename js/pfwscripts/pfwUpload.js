/* funçõezinhas padrão pra facilitar */
function $m(quem){
 //apelido só pra não ficar repetindo o document.getElementById
 return document.getElementById(quem)
}

function remove(quem){
 quem.parentNode.removeChild(quem);
}

function addEvent(obj, evType, fn){
 //o velho do elcio.com.br/crossbrowser
    if (obj.addEventListener)
        obj.addEventListener(evType, fn, true)
    if (obj.attachEvent)
        obj.attachEvent("on"+evType, fn)
}

function removeEvent( obj, type, fn ) {
  if ( obj.detachEvent ) {
    obj.detachEvent( 'on'+type, fn );
  } else {
    obj.removeEventListener( type, fn, false ); }
} 

/* a que faz o serviço pesado */
function pfwUpload(form,url_action,id_elemento_retorno,html_exibe_carregando,url_redirect){

 //testando se passou o ID ou o objeto mesmo
 form = typeof(form)=="string"?$m(form):form;
 
 var erro="";
 if(form==null || typeof(form)=="undefined"){ erro += "O form passado no 1o parâmetro não existe na página.\n";}
 else if(form.nodeName!="FORM"){ erro += "O form passado no 1o parâmetro da função não é um form.\n";}
 if($m(id_elemento_retorno)==null){ erro += "O elemento passado no 3o parâmetro não existe na página.\n";}
 if(erro.length>0) {
  alert("Erro ao chamar a função pfwUpload:\n" + erro);
  return;
 }

 //criando o iframe
 var iframe = document.createElement("iframe");
 iframe.setAttribute("id","pfw-temp");
 iframe.setAttribute("name","pfw-temp");
 iframe.setAttribute("width","0");
 iframe.setAttribute("height","0");
 iframe.setAttribute("border","0");
 iframe.setAttribute("style","width: 0; height: 0; border: none;");
 /* Não usei display:none pra esconder o iframe
    pois tem uma lenda que diz que o NS6 ignora
    iframes que tenham o display:none */
 
 //adicionando ao documento
 form.parentNode.appendChild(iframe);
 window.frames['pfw-temp'].name="pfw-temp"; //ie sucks

 //funfando no chrome graças ao Jhone turra
 var iframeId = document.getElementById('pfw-temp');
 var carregou = function() {
    //window.document.getElementById( id_elemento_retorno ).innerHTML = iframeId.contentDocument.body.innerHTML;
    if (url_redirect !== '') {
        ajax.doGet( url_redirect, 'viewers', true );
    }
 }
 addEvent( $m('pfw-temp'),"load", carregou)
 
 //setando propriedades do form
 form.setAttribute("target","pfw-temp");
 form.setAttribute("action",url_action);
 form.setAttribute("method","post");
 form.setAttribute("enctype","multipart/form-data");
 form.setAttribute("encoding","multipart/form-data");
 //submetendo
 form.submit();
 
 //se for pra exibir alguma imagem ou texto enquanto carrega
 if(html_exibe_carregando.length > 0){
  $m(id_elemento_retorno ).innerHTML = html_exibe_carregando;
 }
 
}