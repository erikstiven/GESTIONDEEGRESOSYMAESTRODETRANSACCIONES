<?php

require ("_Ajax.comun.php"); // No modificar esta linea
/* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
  // S E R V I D O R   A J A X //
  :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */

/* * ******************************************* */
/* FCA01 :: GENERA INGRESO TABLA PRESUPUESTO  */
/* * ******************************************* */

function genera_cabecera_formulario($sAccion = 'nuevo', $aForm = '') {
    //Definiciones
    global $DSN_Ifx, $DSN;

    if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

    $oIfx = new Dbo ( );
    $oIfx->DSN = $DSN_Ifx;
    $oIfx->Conectar();

    $fu = new Formulario;
    $fu->DSN = $DSN;

    $ifu = new Formulario;
    $ifu->DSN = $DSN_Ifx;

    $oReturn = new xajaxResponse ( );

    //variables de sesion
    $idempresa = $_SESSION['U_EMPRESA'];

    //variables del formulario
    $empresa = $aForm['empresa'];

    if (empty($empresa)) {
        $empresa = $idempresa;
    }
    switch ($sAccion) {
        case 'nuevo':
			$ifu->AgregarCampoTexto('codTransaccion', 'Codigo|left', true, '', 150, 150, true);
			$ifu->AgregarCampoLista('tipoMovimineto', 'Movimiento|left',true,'', 150, 150, true);
			$ifu->AgregarOpcionCampoLista('tipoMovimineto', 'DEBITO', 'DB');
			$ifu->AgregarOpcionCampoLista('tipoMovimineto', 'CREDITO', 'CR');
            $ifu->AgregarCampoTexto('descripcion', 'Descripci&oacuten|left', true, '', 430, 150, true);
            $ifu->AgregarComandoAlEscribir('descripcion', 'form1.descripcion.value=form1.descripcion.value.toUpperCase()');
            $ifu->AgregarCampoTexto('cuenta', 'Cuenta|left',false, '', 150, 150, true);
            $ifu->AgregarComandoAlEscribir('cuenta', 'form1.cuenta.value=form1.cuenta.value.toUpperCase()');
            $ifu->AgregarCampoListaSQL('tipoComprobante', 'Tipo de Comprobante|left', 'select tcmp_cod_tcmp, tcmp_des_tcmp from saetcmp order by tcmp_des_tcmp',false, 150, 150, true);
            $ifu->AgregarCampoListaSQL('codTipoRet', 'Retenci&oacuten|left',"select tret_cod, tret_det_ret from saetret WHERE tret_cod_empr = '$idempresa' order by tret_det_ret",false, 150, 150, true);
    }
    $table_op .='<table class="table table-bordered table-striped table-condensed" style="width: 100%; margin-bottom: 0px;" >
					<tr> 
						<td colspan="4" align="center" class="bg-primary">CONFIGURACI&OacuteN MAESTRO DE TRANSACCIONES</td>
					</tr>
                    <tr>
                        <td colspan = "4">    
							<div class="btn-group">
								<div class="btn btn-primary btn-sm" onclick="genera_cabecera_formulario();">
									<span class="glyphicon glyphicon-file"></span>
										Nuevo
								</div>
								<div class="btn btn-primary btn-sm" onclick="guardar();" id = "guardar">
									<span class="glyphicon glyphicon-floppy-disk"></span>
									Guardar
								</div>	
							</div>
                        </td>                   
                    </tr>
                    <tr class="msgFrm">
                        <td colspan="4" align="center">Los campos con * son de ingreso obligatorio</td>
                    </tr>
                    <tr>
                        <td>' . $ifu->ObjetoHtmlLBL('codTransaccion') . '</td>	
                        <td>' . $ifu->ObjetoHtml('codTransaccion') . '</td>
                       	<td><label for="anticipo">Anticipo</td> 	
					    <td><input type="checkbox" name="anticipo" id="anticipo" value="S"/></td>
                    </tr>
					 <tr>
                        <td>' . $ifu->ObjetoHtmlLBL('tipoMovimineto') . '</td>
                        <td>' . $ifu->ObjetoHtml('tipoMovimineto') . ' </td>
                        <td>' . $ifu->ObjetoHtmlLBL('descripcion') . '</td>
                        <td>' . $ifu->ObjetoHtml('descripcion') . ' </td>						
					</tr>
					<tr>
						<td>' . $ifu->ObjetoHtmlLBL('cuenta') . '</td>
                        <td>' . $ifu->ObjetoHtml('cuenta') . '
        					<div id ="cuenta" class="btn btn-primary btn-sm" onclick="buscar_cuentas(id)">
						         <span class="glyphicon glyphicon-list-alt"><span>
						    </div>
                        </td>
						<td>' . $ifu->ObjetoHtmlLBL('tipoComprobante') . '</td>
						<td>' . $ifu->ObjetoHtml('tipoComprobante') . '					
                    </tr>
					<tr>
						<td>' . $ifu->ObjetoHtmlLBL('codTipoRet') . '</td>
						<td>' . $ifu->ObjetoHtml('codTipoRet') . '</td>
						<td><label>Remesas Transito</td> 	
					    <td><input type="checkbox" name="remesas_sn" id="remesas_sn" value="S"/></td>
					</tr>
                    <tr>
						<td><label>Cheque Protestado</td> 	
					    <td><input type="checkbox" name="protestado_sn" id="protestado_sn" value="S"/></td>
                        <td></td>
						<td></td>
						
					</tr>
                  </table>';
    $table_op .= '</fieldset>';

    $oReturn->assign("divFormularioTransacciones", "innerHTML", $table_op);

    return $oReturn;
}

function guardar($aForm = '') {
    //Definiciones
    global $DSN, $DSN_Ifx;

    if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

    $oCon = new Dbo ( );
    $oCon->DSN = $DSN;
    $oCon->Conectar();

    $oIfx = new Dbo ( );
    $oIfx->DSN = $DSN_Ifx;
    $oIfx->Conectar();

    $oReturn = new xajaxResponse ( );
    //variables de sesion
    $idempresa = $_SESSION['U_EMPRESA'];
	$idsucursal = $_SESSION['U_SUCURSAL'];
    $array = ($_SESSION['ARRAY_PINTA']);
    $usuario_web = $_SESSION['U_ID'];
	
    //variables formulario
    $codTransaccion   = $aForm['codTransaccion'];
	$tipoMovimineto   = $aForm['tipoMovimineto'];
	$descripcion   	  = $aForm['descripcion'];
	$cuenta  		  = $aForm['cuenta'];
	$tipoComprobante  =	$aForm['tipoComprobante'];
    $codTipoRet  	  = $aForm['codTipoRet'];
	$anticipo   	  = $aForm['anticipo'];
	$remesas_sn   	  = $aForm['remesas_sn'];
    $protestado_sn   	  = $aForm['protestado_sn'];
    if(empty($protestado_sn)){
        $protestado_sn='N';
    }

	if (empty($codTipoRet)){
		$codTipoRet = null;
	} 
	if (empty($tipoComprobante)){
		$tipoComprobante = null;
	} 
	if (empty($anticipo) || $anticipo == ""){
		$anticipo = '0';
	}else{
		$anticipo = '1';
	}

    if (empty($remesas_sn) || $remesas_sn == ""){
        $remesas_sn = '0';
    }else{
        $remesas_sn = '1';
    }




    //$oReturn->alert($continente);
	$sql = "select count(*)as transaccion 
			from saetran where tran_cod_tran = '$codTransaccion' 
			and tran_cod_empr = $idempresa
			and tran_cod_sucu = $idsucursal
			and tran_cod_modu = 3";
	$existe = consulta_string($sql,'transaccion', $oIfx,0);
    try {
        $oIfx->QueryT('BEGIN WORK;');		
		if ($existe == 0){
			$sql = "insert into saetran (tran_cod_tran,  tran_cod_modu, trans_tip_tran,    tran_des_tran,  tran_cod_empr, 
										 trans_tip_comp, tran_cod_tret, tran_cod_sucu, 	   tran_cod_cuen, tran_ant_tran, tran_rem_tran, tran_prot_tran)
								 values ('$codTransaccion',         3, '$tipoMovimineto', '$descripcion',  $idempresa,
										 '$tipoComprobante', '$codTipoRet', $idsucursal, '$cuenta', '$anticipo', '$remesas_sn','$protestado_sn')";
			$mensaje = "Datos Grabados";
		} else
		{
			$sql = "update saetran
					set tran_cod_tran = '$codTransaccion', tran_cod_modu = 3, trans_tip_tran = '$tipoMovimineto', tran_des_tran = '$descripcion',
					tran_cod_empr =  $idempresa, trans_tip_comp = '$tipoComprobante', tran_cod_tret = '$codTipoRet', tran_cod_sucu = '$idsucursal',
					tran_cod_cuen = '$cuenta', tran_ant_tran = '$anticipo', tran_rem_tran = '$remesas_sn', tran_prot_tran ='$protestado_sn'
					where tran_cod_tran = '$codTransaccion'
					and tran_cod_empr = '$idempresa'
					and tran_cod_modu = 3";
          //  $oReturn->alert($sql);
			$mensaje = "Datos Modificados";
		}
		//echo $sql; exit;
		$oIfx->QueryT($sql);
		$oReturn->alert($mensaje);
                $oReturn->script("recarga();"); 
       // $oReturn->script("genera_cabecera_formulario();"); 
		$oIfx->QueryT('COMMIT WORK;');
    } catch (Exception $e) {
        $oCon->QueryT('ROLLBACK WORK;');
        $oReturn->alert($e->getMessage());
    }
    return $oReturn;
}

function validar_cuentas($aForm = '', $cod_cuenta){
	//Definiciones
    global $DSN, $DSN_Ifx;

    if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
    $oIfx = new Dbo ( );
    $oIfx->DSN = $DSN_Ifx;
    $oIfx->Conectar();

    $oReturn = new xajaxResponse ( );
    //variables de sesion
    $idempresa = $_SESSION['U_EMPRESA'];
    $idsucursal = $_SESSION['U_SUCURSAL'];
    $array = ($_SESSION['ARRAY_PINTA']);
	//echo $cod_cuenta;
	
    try {
        $oIfx->QueryT('BEGIN');
        if ($cod_cuenta != ''){
            $sql = "select cuen_mov_cuen
					  from saecuen
					  where	cuen_cod_empr  = '$idempresa' and
					   		cuen_cod_cuen  = '$cod_cuenta'";
			//echo $sql; exit;
            $tipo_cuenta = consulta_string($sql,'cuen_mov_cuen', $oIfx,'');
			if($tipo_cuenta == 0){
                $mensaje = "Cuenta Contable debe ser de Movimiento";
				$oReturn->assign('cuenta', 'value','');
				$oReturn->alert($mensaje);
            }                    
        }
        
    } catch (Exception $e) {
        $oCon->QueryT('ROLLBACK');
        $oReturn->alert($e->getMessage());
    }
    return $oReturn;
}

/* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
/* PROCESO DE REQUEST DE LAS FUNCIONES MEDIANTE AJAX NO MODIFICAR */
$xajax->processRequest();
/* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
?>