<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    {FAV_ICON}
    
    <title>{TITULO}</title>	
	
    {METADATA}    
    
    <!-- Bootstrap core CSS 
    <link href="template/sb-admin/css/bootstrap.css" rel="stylesheet">
    -->
    
    <!-- Add custom CSS here -->
    <link href="template/sb-admin/css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="template/sb-admin/font-awesome/css/font-awesome.min.css">	
	
    <!-- JavaScript     
    <script src="template/sb-admin/js/bootstrap.js"></script>	
    <script src="template/sb-admin/js/jquery-1.10.2.js"></script>
    -->
    
    <!-- SB Admin Scripts - Include with every page 
    <script src="template/sb-admin/js/sb-admin.js"></script>		        
	-->
    
  </head>

  <body>

    <div id="wrapper">

      <!-- Sidebar -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{REDIRECIONAR}">{LOGO_SISTEMA}</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
		
          {MENU_PRINCIPAL}  
		  
          <ul class="nav navbar-nav navbar-right navbar-user">
              {PLUGIN_MAIN_PAGE}
              
            <li class="dropdown user-dropdown">                
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">               
                <i class="fa fa-user"></i> {USUARIO} <b class="caret"></b>
              </a>
              {MENU_USUARIO}
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>

      <div id="page-wrapper">
	  
		<div class="row">        
			<div class="col-lg-12">
				<div id='viewers'></div> <!-- Exibe tabela com dados cadastrados -->			
			</div>
		</div>
		
	  <div class="row-fluid">     	  
		<div class="span12">
			<p class="text-right">
				{RODAPE}
            </p>                                
        </div> 
      </div>		
		
      </div><!-- /#page-wrapper -->
	  
    </div><!-- /#wrapper -->

  </body>
</html>