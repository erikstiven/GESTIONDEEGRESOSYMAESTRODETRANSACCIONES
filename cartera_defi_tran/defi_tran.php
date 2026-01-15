<? /* * ***************************************************************** */ ?>
<? /* NO MODIFICAR ESTA SECCION */ ?>
<? include_once('../_Modulo.inc.php'); ?>
<? include_once(HEADER_MODULO); ?>
<? if ($ejecuta) { ?>
    <? /*     * ***************************************************************** */ ?>
    	
    <!--CSS--> 
	<link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/bootstrap-3.3.7-dist/css/bootstrap.css" media="screen">
	<link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>css/bootstrap-3.3.7-dist/css/bootstrap.min.css" media="screen">
	<link rel="stylesheet" type="text/css" href="<?=$_COOKIE["JIREH_INCLUDE"]?>js/treeview/css/bootstrap-treeview.css" media="screen"> 

    <!--Javascript--> 
    
    <script src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/jquery.dataTables.min.js"></script>
    <script src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/dataTables.bootstrap.min.js"></script>          
    <script src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/dataTables/bootstrap.js"></script>
    <script type="text/javascript" language="JavaScript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>js/treeview/js/bootstrap-treeview.js"></script>
    <script type="text/javascript" language="javascript" src="<?=$_COOKIE["JIREH_INCLUDE"]?>css/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
	<script src="js/lenguajeusuario_.js"></script>   


    <script>

        function genera_cabecera_formulario() {
            xajax_genera_cabecera_formulario('nuevo', xajax.getFormValues("form1"));
        }

        function consultar() {
            empresa = document.getElementById('empresa').value;
            if (empresa != '') {
                xajax_consultar(xajax.getFormValues("form1"));
            } else {
				document.getElementById("form1").reset();
            }
        }
        
        function guardar(){
            if(ProcesarFormulario() == true){
                xajax_guardar(xajax.getFormValues("form1"));
            }
        }

        function eliminar(){
            if(ProcesarFormulario() == true){
                xajax_eliminar(xajax.getFormValues("form1"));
            }
        }
		
	
		function seleccionaItem(tran_cod_tran, trans_tip_tran, tran_des_tran, tran_ant_tran, trans_tip_comp, tran_cod_tret, tran_cod_cuen, tran_rem_tran, tran_prot_tram){
			//alert(tran_ant_tran);
			document.getElementById("codTransaccion").value = tran_cod_tran;
			document.getElementById("tipoMovimineto").value = trans_tip_tran;
			document.getElementById("descripcion").value = tran_des_tran;
			document.getElementById("cuenta").value = tran_cod_cuen;
			document.getElementById("tipoComprobante").value = trans_tip_comp;
			document.getElementById("codTipoRet").value = tran_cod_tret;
			document.getElementById("anticipo").checked = tran_ant_tran;
			document.getElementById("remesas_sn").checked = tran_rem_tran;
            if(tran_prot_tram=='S'){
                document.getElementById("protestado_sn").checked = tran_prot_tram;
            }
            else{
                document.getElementById("protestado_sn").checked = false; 
            }


		}

		function recarga(){
  
			var table = $('#example').DataTable();
			table.ajax.reload(null, false);
     	
		}
        function buscar_cuentas(id){
            $("#myModal").modal("show");
            var table = $('#tabla_cuentas').DataTable();
            table.destroy();
            var cuenta = document.getElementById('cuenta').value;
            listar_cuentas_contables(id,cuenta);
        }

        function bajar_cuentas(cuenta,id) {			
            document.getElementById(id).value = cuenta;
            $("#myModal").modal("hide");
			//VALIDAR SI CTA ES DE MOVIMIENTO
			xajax_validar_cuentas(xajax.getFormValues("form1"), cuenta);

        }
    </script>
    <!--DIBUJA FORMULARIO FILTRO-->
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <body>
        <form id="form1" name="form1" action="javascript:void(null);">
			<div class="col-md-4"> 
				<table id="example" class="table table-striped table-bordered table-hover table-condensed"  style="width: 100%;" align="center">
						<thead>
							<tr>
								<td colspan="5" class="bg-primary">LISTA DE TRANSACCIONES CUENTAS POR COBRAR</td>
							</tr>
							<tr class="info">
								<td>Codigo</td>	
								<td>Tipo</td>
								<td>Descripcion</td>
								<td>Seleccionar</td>
							</tr>
						</thead>
						<tbody id='tb_data'>
						</tbody>
					</table>        
			</div> 		
			<div class="col-md-8"> 
				<table align="center" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td align="center">
							<div id="divFormularioTransacciones"></div>
						</td>
					</tr>
				</table>
			</div>
            <div id="cuentas_contables"> 
				<div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">LISTA CUENTAS CONTABLES</h4>
                            </div>
                            <div class="modal-body">
                                <table id="tabla_cuentas" class="table table-striped table-bordered table-hover table-condensed"  style="width: 100%;" align="center">
                                    <thead>
                                    <tr>
                                        <td colspan="5" class="bg-primary">LISTA CUENTAS CONTABLES</td>
                                    </tr>
                                    <tr class="info">
                                        <td>Codigo</td>
                                        <td>Descripcion</td>
                                        <td>Seleccionar</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">CERRAR</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </body>
    <script>genera_cabecera_formulario();/*genera_detalle();genera_form_detalle();*/</script>
    <? /*     * ***************************************************************** */ ?>
    <? /* NO MODIFICAR ESTA SECCION */ ?>
<? } ?>
<? include_once(FOOTER_MODULO); ?>
<? /* * ***************************************************************** */ ?>
