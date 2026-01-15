<?php
	
	include_once('../../Include/config.inc.php');
	include_once(path(DIR_INCLUDE).'conexiones/db_conexion.php');
	include_once(path(DIR_INCLUDE).'comun.lib.php');

	if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
    global $DSN_Ifx, $DSN;

	$oIfx = new Dbo;
    $oIfx->DSN = $DSN_Ifx;
    $oIfx->Conectar();

    //varibales de sesion
    $idempresa = $_SESSION['U_EMPRESA'];
    $idsucursal = $_SESSION['U_SUCURSAL'];
    
    if (isset($_REQUEST['nomGrupo'])){
        $nomGrupo = $_REQUEST['nomGrupo'];
		if(($nomGrupo!='0')&&($nomGrupo!='')){
			$con_nom=" where (gact_des_gact like upper ('%$nomGrupo%'))";
		}else{
			$con_nom=null;
		}
	}
    else{
        $con_nom = null;
	}
    //lectura sucia
    //////////////

    $tabla = '';	
		$sql = "select tran_cod_tran, trans_tip_tran, tran_des_tran, tran_ant_tran, trans_tip_comp, tran_cod_tret, tran_cod_cuen
				from saetran
				where tran_cod_empr = $idempresa
				and tran_cod_sucu = $idsucursal
				and tran_cod_modu = 4
				$con_nom";
				//echo $sql;exit;
	$i=1;
    if($oIfx->Query($sql)){
    	if($oIfx->NumFilas() > 0){
    		do{
                $tran_cod_tran  = $oIfx->f('tran_cod_tran');
                $trans_tip_tran = $oIfx->f('trans_tip_tran');
				$tran_des_tran  = $oIfx->f('tran_des_tran');
				$tran_ant_tran  = $oIfx->f('tran_ant_tran');
				$trans_tip_comp = $oIfx->f('trans_tip_comp');
				$tran_cod_tret  = $oIfx->f('tran_cod_tret');
				$tran_cod_cuen  = $oIfx->f('tran_cod_cuen');
				
                $tran_des_tran   = str_replace("'", " ", $tran_des_tran);
				$img = '<div align=\"center\"> <div class=\"btn btn-success btn-sm\" onclick=\"seleccionaItem(\'' . $tran_cod_tran . '\',\'' . $trans_tip_tran . '\',\'' . $tran_des_tran . '\',\'' . $tran_ant_tran . '\',\'' . $trans_tip_comp . '\', \'' . $tran_cod_tret . '\', \'' . $tran_cod_cuen . '\')\"><span class=\"glyphicon glyphicon-ok\"><span></div> </div>';
    			//echo $nomPais;exit;
				$tabla.='{
				  "codigo":"'.$tran_cod_tran.'",
				  "tipo":"'.$trans_tip_tran.'",
				  "descripcion":"'.$tran_des_tran.'",
				  "selecciona":"'.$img.'"
				},';
				$i++;
			}while($oIfx->SiguienteRegistro());
    	}
	}

	
	$oIfx->Free();

	//eliminamos la coma que sobra
	$tabla = substr($tabla,0, strlen($tabla) - 1);

	echo '{"data":['.$tabla.']}';
	
?>