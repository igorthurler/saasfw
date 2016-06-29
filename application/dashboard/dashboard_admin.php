<?php

$mensagens = array();

$driver = Utilitarios::retornarDriver();   

$driver->connect();   

/* Verifica se existe configurações administrativas */

$sql = "select count(id) as existe
          from ConfiguracaoAdmin";

$result = $driver->fetchAssoc($sql);   
   
$existe = ($result[0]['existe'] > 0);

if (! $existe) {
	$mensagens[] = 'Defina as configurações administrativas do sistema.';
}

/* Verifica se existe módulos ativos */

$sql = "select count(id) as existe
          from Modulo
		 where cancelamento is null";

$result = $driver->fetchAssoc($sql);   
   
$existe = ($result[0]['existe'] > 0);		 

if (! $existe) {
	$mensagens[] = 'Não existe nenhum módulo ativo cadastrado.';
}

if (count($mensagens) > 0) {
	echo '<div class="row">';
	echo '<div class="col-lg-12">';
	echo '    <div class="alert alert-danger">';	
	for ($i = 0; $i <= count($mensagens) - 1; $i++) {
		echo "<p>{$mensagens[$i]}</p>";
	}
	echo '    </div>';
	echo '  </div>';
	echo '</div>';
}

/*Contratantes*/
$sql = "select count(c.id) as quantidade
          from Contratante c";
   
$infoContratantes = $driver->fetchAssoc($sql);   
   
$totalDeContratantes = $infoContratantes[0]['quantidade'];

/*Contratos ativos*/
$sql = "select count(id) as quantidade
          from Contrato
		 where dataDeFinalizacao is null
           and cancelamento is null
		   and dataDeAtivacao is not null";

$infoContratos = $driver->fetchAssoc($sql);   

$totalDeContratos = $infoContratos[0]['quantidade'];

/*Pagamentos em atraso*/
$sql = "select count(id) as quantidade
          from Pagamento
         where dataDePagamento is null
           and dataDeVencimento < now()";

$infoPagamentos = $driver->fetchAssoc($sql);   

$totalDePagamentosEmAtraso = $infoPagamentos[0]['quantidade'];

$driver->disconnect();   

unset($driver);
?>

<div class="row">
    <div class="col-lg-3">
        <div class="panel panel-info">
          <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-6">
                        <i class="fa fa-comments fa-5x"></i>
                  </div>
                  <div class="col-xs-6 text-right">
                        <p class="announcement-heading"><?php echo $totalDeContratantes; ?></p>
                        <p class="announcement-text">Total de contratantes</p>
                  </div>
                </div>
          </div>
          <a href="javascript:void(0);" onclick="ajax.init( 'includes/PfwController.php?app=contratante&action=listar', 'viewers' );">
                <div class="panel-footer announcement-bottom">
                  <div class="row">
                        <div class="col-xs-6">
                          visualizar contratantes
                        </div>
                        <div class="col-xs-6 text-right">
                          <i class="fa fa-arrow-circle-right"></i>
                        </div>
                  </div>
                </div>
          </a>
        </div>
    </div>
	
    <div class="col-lg-3">
        <div class="panel panel-warning">
          <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-6">
                        <i class="fa fa-check fa-5x"></i>
                  </div>
                  <div class="col-xs-6 text-right">
                        <p class="announcement-heading"><?php echo $totalDeContratos; ?></p>
                        <p class="announcement-text">Contratos ativos</p>
                  </div>
                </div>
          </div>
          <a href="javascript:void(0);" onclick="ajax.init( 'includes/PfwController.php?app=contrato&action=listar', 'viewers' );">
                <div class="panel-footer announcement-bottom">
                  <div class="row">
                        <div class="col-xs-6">
                          visualizar contratos
                        </div>
                        <div class="col-xs-6 text-right">
                          <i class="fa fa-arrow-circle-right"></i>
                        </div>
                  </div>
                </div>
          </a>
        </div>
    </div>	
    
    <div class="col-lg-3">
        <div class="panel panel-success">
          <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-6">
                        <i class="fa fa-money fa-5x"></i>
                  </div>
                  <div class="col-xs-6 text-right">
                        <p class="announcement-heading"><?php echo $totalDePagamentosEmAtraso; ?></p>
                        <p class="announcement-text">Pagamentos em atraso</p>
                  </div>
                </div>
          </div>            
          <a href="javascript:void(0);" onclick="ajax.init( 'includes/PfwController.php?app=contrato&action=exibirPagamentosEmAtraso', 'viewers' );">
                <div class="panel-footer announcement-bottom">
                  <div class="row">
                        <div class="col-xs-6">
                          <?php echo "visualizar pagamentos"?>
                        </div>
                        <div class="col-xs-6 text-right">
                          <i class="fa fa-arrow-circle-right"></i>
                        </div>
                  </div>
                </div>
          </a>
        </div>
    </div>	
    
</div>