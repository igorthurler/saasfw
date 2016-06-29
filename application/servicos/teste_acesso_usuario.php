<?php
$config_path = '../..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

$wsdl = "http://localhost/saasfw/application/servicos/acesso_usuario.php?wsdl";

$client = new nusoap_client($wsdl);

$err = $client->getError();

if ($err) {
    echo "Erro no acesso <pre>" . $err . "</pre>"; 
}

$senha = md5('123');
$result = $client->call('acesso_usuario', array('identificador'=>'igorthurler@gmail.com','senha'=>$senha));

if ($client->fault) {
    echo "Falha <pre>" . $err . "</pre>"; 
} else {
    $err = $client->getError();
    if ($err) {
        echo "Erro <pre>" . $err . "</pre>"; 
    } else {
    	if ($result != 'ERRO') {
            $arrayUsuario = explode("|", $result);
    		echo $arrayUsuario[0] . '<br/>';
            echo $arrayUsuario[1] . '<br/>';
            echo $arrayUsuario[2];
    	} else {
    		echo "O usuário informado não foi autenticado.";
    	}
    }
}

echo '<h2>Requisição</h2>';
echo '<pre>'.htmlspecialchars($client->request).'</pre>';
echo '<h2>Resposta</h2>';
echo '<pre>'.htmlspecialchars($client->response).'</pre>';
// Exibe mensagens para debug
echo '<h2>Debug</h2>';
echo '<pre>'.htmlspecialchars($client->debug_str).'</pre>';