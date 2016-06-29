window.onload = function() {	
    window.setInterval('atualizarTelaPrincipal()', 2000);
    ajax.init( 'includes/PfwController.php?app=dashboard', 'viewers' );
};

function atualizarTelaPrincipal() {
    var url = 'application/usuario/buscadadosusuario.php';
    var req = ajax.GetXMLHttp();
    req.open('GET',url,true);	
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");	                
    req.onreadystatechange = function() {        
        if(req.readyState === 4 && req.status === 200)
        {               	
            var valores = eval(req.responseText);
            if (valores[0] !== null) {  
                if (ge('user_nome') !== null) ge('user_nome').innerHTML = valores[0].nome;
                if (ge('user_imagem') !== null) ge('user_imagem').src = valores[0].imagem;                                                
            }
        }
        
    };  
                               
    req.send(null);        
};