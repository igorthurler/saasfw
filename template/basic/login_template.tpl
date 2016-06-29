<!DOCTYPE html>
<html lang="pt_br">
    <head>
        <title>{TITULO}</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- Bootstrap core CSS -->
		<link href="template/sb-admin/css/bootstrap.css" rel="stylesheet">	
        <link href="template/sb-admin/css/bootstrap-responsive.min.css" rel="stylesheet">             
		
        <link href="template/sb-admin/css/sb-admin.css" rel="stylesheet">
        
        <script>
            $(document).ready(function(){
                $('#formLogin').html5form();    
            });
        </script>
        
        <!--[if lt IE 9]>
        <link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
        <script src="template/default/js/html5shiv.js"></script>
        <![endif]--> 
    </head>
    <body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                {FORM_LOGIN}
            </div>
        </div>
    </div> 
    <!-- /container -->    

    </body>
</html>