<!doctype html>
<html lang="es-es">
<head>
	<meta charset="UTF-8">
	
	<link rel="shortcut icon" href="./images/iconoFinanCli.png" >
	<title>Script TEF</title>
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.op2.css" >
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-theme.min.op2.css" >	
	<link rel="stylesheet" href="./css/fontawesome.min.css">
	<link rel="stylesheet" href="./css/all.css">
	<link rel="stylesheet" type="text/css" href="./css/jquery-ui.css">
	<link rel="stylesheet" href="./utiles/CodeMirror/doc/docs.css">	
	<link rel="stylesheet" href="./utiles/CodeMirror/lib/codemirror.css">
	<link rel="stylesheet" href="./utiles/CodeMirror/addon/hint/show-hint.css">	
	
	<script type="text/javascript" src="./js/jquery.js"></script>
	<script type="text/JavaScript" src="./js/bootstrap.min.op2.js" ></script>
	<script type="text/javascript" src="./js/jquery-ui.js"></script>
	<script type="text/JavaScript" src="./js/bootstrap-multiselect.js" ></script>	
	<script type="text/JavaScript" src="./js/moment.op2.js" ></script>	
	<script src="./utiles/CodeMirror/lib/codemirror.js"></script>
	<script src="./utiles/CodeMirror/addon/edit/matchbrackets.js"></script>
	<script src="./utiles/CodeMirror/mode/sql/sql.js"></script>	
	<script src="./utiles/CodeMirror/addon/hint/show-hint.js"></script>
	<script src="./utiles/CodeMirror/addon/hint/sql-hint.js"></script>
	<script src="./utiles/CodeMirror/addon/search/search.js"></script>
	<script src="./utiles/CodeMirror/addon/search/searchcursor.js"></script>	
	
	<link rel="stylesheet" href="./css/fondo.op2.css">
	<link rel="stylesheet" href="./css/estilos.op2.css">
	
</head>

<body>
	<div class="panel-group" style="width: 980px; height: 800px; padding-top:30px; padding-left: 100px;">				
		<div class="panel panel-default">
			<div id="panel-title-header" class="panel-heading">
				<h3 class="panel-title">Parametros Marca</h3>
			</div>
			<div class="panel-body">
				<form id="formulariocefc" role="form" onsubmit="generarScriptSQL(); return false;">		
					<div class="form-group form-inline">
						<label class="control-label" for="codigomarca">Codigo: </label>
						<div class="form-group" id="numerocreditv">
							<input class="form-control input-sm" id="codigomarcai" name="codigomarcai" type="text" maxlength="11" />
						</div>
							&nbsp;&nbsp;<label class="control-label" for="descripcionmarca">Descripción: </label>
						<div class="form-group" id="descripcionmarca">
							<input class="form-control input-sm" id="descripcionmarcai" name="descripcionmarcai" type="text" maxlength="255" />
						</div>		
						&nbsp;&nbsp;<label class="control-label" for="codigonodon">Descripción: </label>
						<div class="form-group" id="codigonodon">
							<select class="form-control input-sm" name="codigonodoni" id="codigonodoni" style="width:190px;">			 
								<option value="1">VISA</option>
								<option value="2">FIRST DATA</option>
							</select>
						</div>	
					</div>
					<div class="form-group form-inline">
						<input type="button" class="btn btn-primary pull-right" name="btnGenerarScriptDB" id="btnGenerarScriptDB" value="Generar Script" onClick="generarScriptSQL();" style="margin-right:15px;" />									
					</div>					
				</form>
			</div>
		</div></br>		
		<div class="panel panel-default">
			<div id="panel-title-header" class="panel-heading">
				<h3 class="panel-title">Script SQL</h3>
			</div>
			<div class="panel-body">	
				<div class="form-group form-inline">
					<div class="form-group" id="resultadogeneracionscriptsql">
						<textarea class="form-control input-sm" id="scripsqltef" name="scripsqltef">-- SQL Mode for CodeMirror
						SELECT SQL_NO_CACHE DISTINCT
								@var1 AS `val1`, @'val2', @global.'sql_mode',
								1.1 AS `float_val`, .14 AS `another_float`, 0.09e3 AS `int_with_esp`,
								0xFA5 AS `hex`, x'fa5' AS `hex2`, 0b101 AS `bin`, b'101' AS `bin2`,
								DATE '1994-01-01' AS `sql_date`, { T "1994-01-01" } AS `odbc_date`,
								'my string', _utf8'your string', N'her string',
								TRUE, FALSE, UNKNOWN
							FROM DUAL
							-- space needed after '--'
							# 1 line comment
							/* multiline
							comment! */
							LIMIT 1 OFFSET 0;
						</textarea></br>					
					</div>
				</div>
				<div class="form-group form-inline">
					<input type="button" class="btn btn-primary pull-right" name="btnAplicarDB" id="btnAplicarDB" value="Aplicar en DB" onClick="ejecutarConsultaSQL();" style="margin-right:15px;" />									
				</div>				
			</div>
		</div>
	</div>
</body>
<script>
	window.onload = function() {
	  var mime = 'text/x-mariadb';

	  window.editor = CodeMirror.fromTextArea(document.getElementById('scripsqltef'), {
		mode: mime,
		indentWithTabs: true,
		smartIndent: true,
		lineNumbers: true,
		matchBrackets : true,
		autofocus: true,
		readOnly: true,
		extraKeys: {"Ctrl-Space": "autocomplete"},
		hintOptions: {tables: {
		  users: ["name", "score", "birthDate"],
		  countries: ["name", "population", "size"]
		}}
	  });
	};
</script>
</html>