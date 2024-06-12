<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */

namespace Base\Mib;

use Base\Mib\URL,
    Base\Mib\Numeros;

class DataHora {
    
    public static function contador(){
        global $contadorInicio;
        return  microtime(true) - $contadorInicio;
    }

    public static function dataGMT($numDTFutura){
        if(is_numeric($numDTFutura)){
            $strDataGMT = gmdate("D, d M Y H:i:s", time() + ($numDTFutura)) . " GMT";//(24 * 60 * 60)) . " GMT");//adiciona 1 dia ao tempo de expiração
        }else{
            $strDataGMT = gmdate("D, d M Y H:i:s", time() + (60+3*60*60)) . " GMT";
        }
        return $strDataGMT;
    }

    public static function dataExtenso($data="", $formato = '%d de %B de %Y - %A'){
        if(empty($data)){
            $data = time();
        }
        return utf8_encode(strftime($formato,$data));
    }

    public static function horaExtenso($data=""){
        if(empty($data)){
            $data = time();
        }
        return utf8_encode(strftime('%T',$data));
    }

    public static function segundo(){
        return date("s");
    }

    public static function minuto(){
        return date("i");
    }

    public static function hora(){
        return date("H");
    }

    public static function dia(){
        return date("d");
    }

    public static function semana(){
        return Numeros::numCasas(date("w"),2);
    }

    public static function mes(){
        return date("m");
    }

    public static function ano(){
        return date("Y");
    }

    public static function diaNoite($intHora){
        if(is_numeric($intHora)){
            if($intHora>=6 && $intHora<=17){
                return 1;
            }else{
                return 2;
            }
        }else{
            return 3;
        }
    }

    public static function semanaNome($intNum){
        
        $strSemana = self::$arrSemana[(int) $intNum];
        if($intNum>0 && $intNum<6){
            $strSemana.= " - Feira";
        }
        return $strSemana;
    }

    public static function mesNome($intNum){
        return self::$arrMeses[((int) $intNum)-1];
    }

    public static function mesNomeSemAcento($intNum){
        return self::$arrMes[((int) $intNum)-1];
    }

    public static function horaMySQL($strHora=""){
        if(empty($strHora)){
            return "00:00:02";
        }else if(!preg_match(self::$erDataHoraBra,$strHora)){
            return "00:00:01";
        }else{
            $arrHora = explode(" ",$strHora);
            return $arrHora[1];
        }
    }

    public static function mesNomeParaNum($nome, $arr=""){
        if($arr == ""){
            $arr = self::$arrMes;
        }
        $retorno = array_search($nome, $arr);
        return (is_numeric($retorno))?Numeros::numCasas($retorno + 1,2): "01";
    }

    public static function dataParaData($strData){
        $strData = explode("-",$strData);
        return "{$strData[2]}/{$strData[1]}/{$strData[0]}";
    }

    public static function dataDiferenca($strTipo,$strDataIni,$strDataFim){
        if($strDataIni!="now"&&!(preg_match(self::$erDataNum,$strDataIni)||preg_match(self::$erDataHoraNum,$strDataIni))||$strDataFim!="now"&&!(preg_match(self::$erDataNum,$strDataFim)||preg_match(self::$erDataHoraNum,$strDataFim))){
            return false;
        }
        if($strDataIni!="now"){
            if(!checkdate(substr($strDataIni,4,2),substr($strDataIni,6,2),substr($strDataIni,0,4))){
                return false;
            }
        }
        if($strDataFim!="now"){
            if(!checkdate(substr($strDataFim,4,2),substr($strDataFim,6,2),substr($strDataFim,0,4))){
                return false;
            }
        }
        $data = date("U",strtotime($strDataFim)) - date("U",strtotime($strDataIni));
        if($strDataIni=="now"&&$strDataFim!="now"&&date("I")){
            $data += 3600;
        }else if($strDataIni!="now"&&$strDataFim=="now"&&date("I")){
            $data -= 3600;
        }
        $arrData = array(
                    "s" => $data,
                    "m" => floor($data / 60),
                    "h" => floor($data / 60 / 60),
                    "D" => floor($data / 60 / 60 / 24)
                    );
        return (is_null($arrData[$strTipo]))?0:$arrData[$strTipo];
    }

    public static function dataParaEscrito($strDataNum){

        $arrData = "";
        $arrHora = "";


        $strData = "DD de MM de AAAA";
        if(preg_match(self::$erDataSql, $strDataNum)){
            $strData = str_replace("DD", substr($strDataNum,-2), $strData);
            $strData = str_replace("MM", self::mesNome(substr($strDataNum,5,2)), $strData);
            $strData = str_replace("AAAA", substr($strDataNum,0,4), $strData);
        }
        elseif(preg_match(self::$erDataBra, $strDataNum)){
            $arrData = explode("/", $strDataNum);
            $strData = str_replace("DD", Numeros::numCasas($arrData[0],2), $strData);
            $strData = str_replace("MM", self::mesNome($arrData[1]), $strData);
            $strData = str_replace("AAAA", $arrData[2], $strData);
        }elseif(preg_match(self::$erDataSql, $strDataNum)){
            $arrData = explode("-", $strDataNum);
            $strData = str_replace("DD", Numeros::numCasas($arrData[2],2), $strData);
            $strData = str_replace("MM", self::mesNome($arrData[1]), $strData);
            $strData = str_replace("AAAA", $arrData[0], $strData);
        }elseif(preg_match(self::$erDataHoraNum, $strDataNum)){
            $arrHora = explode(" ", $strDataNum);
            $arrData = $arrHora[0];
            $strData = str_replace("DD", substr($arrData,-2), $strData);
            $strData = str_replace("MM", self::mesNome(substr($arrData,5,2)), $strData);
            $strData = str_replace("AAAA", substr($arrData,0,4), $strData);
            $strData = str_replace("hh:mm:ss", $arrHora[1], $strData);
        }elseif(preg_match(self::$erDataHoraBra, $strDataNum)){
            $arrHora = explode(" ", $strDataNum);
            $arrData = explode("/", $arrHora[0]);
            $strData = str_replace("DD", Numeros::numCasas($arrData[0],2), $strData);
            $strData = str_replace("MM", self::mesNome($arrData[1]), $strData);
            $strData = str_replace("AAAA", $arrData[2], $strData);
            $strData = str_replace("hh:mm:ss", $arrHora[1], $strData);
        }elseif(preg_match(self::$erDataHoraSql, $strDataNum)){
            $arrHora = explode(" ", $strDataNum);
            $arrData = explode("-", $arrHora[0]);
            $strData = str_replace("DD", Numeros::numCasas($arrData[2],2), $strData);
            $strData = str_replace("MM", self::mesNome($arrData[1]), $strData);
            $strData = str_replace("AAAA", $arrData[0], $strData);
            $strData = str_replace("hh:mm:ss", $arrHora[1], $strData);
        }elseif(preg_match(self::$erDataHoraNum2, $strDataNum)){
            $arrHora = explode(" ", $strDataNum);
            $arrData = $arrHora[0];
            $strData = str_replace("DD", substr($arrData,-2), $strData);
            $strData = str_replace("MM", self::mesNome(substr($arrData,5,2)), $strData);
            $strData = str_replace("AAAA", substr($arrData,0,4), $strData);
            $strData = str_replace("hh:mm:ss", substr($arrHora[1],0,2).":".substr($arrHora[1],2,2).":".substr($arrHora[1],-2), $strData);
        }else{
            $strData = false;
        }

        if($strData != false){
            $strDataVerifica = $strDataNum;
            if(is_array($arrHora)){
                $strDataVerifica = $arrHora[0];
            }
            $strDataVerifica = self::dataParaNum($strDataVerifica,0);
            if(!checkdate(substr($strDataVerifica,4,2), substr($strDataVerifica,6,2), substr($strDataVerifica,0,4))){
                $strData = false;
            }
        }

        return $strData;

    }

    public static function dataParaNum($strData, $intTipo){

        $arrData = "";
        $arrHora = "";

        if(!is_numeric($intTipo)){
            $intTipo = 0;
        }
        
        if(preg_match(self::$erDataBra, $strData)){
            $arrData = explode("/", $strData);
            $intData = (int) ($arrData[2] . Numeros::numCasas($arrData[1],2) . Numeros::numCasas($arrData[0],2));
        }elseif(preg_match(self::$erDataSql, $strData)){
            $arrData = explode("-", $strData);
            $intData = (int) ($arrData[0] . Numeros::numCasas($arrData[1],2) . Numeros::numCasas($arrData[2],2));
        }elseif(preg_match(self::$erDataHoraBra, $strData)){
            $arrHora = explode(" ", $strData);
            $arrData = explode("/", $arrHora[0]);
            $intData = (int) ($arrData[2] . Numeros::numCasas($arrData[1],2) . Numeros::numCasas($arrData[0],2));
        }elseif(preg_match(self::$erDataHoraSql, $strData)){
            $arrHora = explode(" ", $strData);
            $arrData = explode("-", $arrHora[0]);
            $intData = (int) ($arrData[0] . Numeros::numCasas($arrData[1],2) . Numeros::numCasas($arrData[2],2));
        }elseif(preg_match(self::$erDataNum, $strData) || preg_match(self::$erDataHoraNum, $strData) || preg_match(self::$erDataHoraNum2, $strData)){
            $intData = $strData;
        }else{
            $intData = false;
        }

        if ($intData!=false && !checkdate(substr($intData,4,2), substr($intData,6,2), substr($intData,0,4))) {
            $intData = false;
        }

        if($intTipo==1 && $intData!=false){
            if(is_array($arrHora)){
                $intData.= " " . $arrHora[1];
            }
        }

        return $intData;
        
    }

    public static function numParaData($intNum, $intTipo){

        $arrData = "";
        $arrHora = "";

        if(!is_numeric($intTipo)){
            $intTipo = 0;
        }

        $retorno = false;

        if(preg_match(self::$erDataNum, $intNum)){
            if($intTipo==1){
                //Data Basileira DD/MM/AAAA
                $strData = Numeros::numCasas(substr($intNum,-2),2)."/".Numeros::numCasas(substr($intNum,4,2),2)."/".substr($intNum,0,4);
            }elseif($intTipo==2){
                //Data Americana MM/DD/AAAA
                $strData = Numeros::numCasas(substr($intNum,4,2),2)."/".Numeros::numCasas(substr($intNum,-2),2)."/".substr($intNum,0,4);
            }else{
                //Data SQL AAAA-MM-DD
                $strData = substr($intNum,0,4)."-".Numeros::numCasas(substr($intNum,4,2),2)."-".Numeros::numCasas(substr($intNum,-2),2);
            }
            $retorno = $strData;
        }elseif(preg_match(self::$erDataHoraNum, $intNum)){
            $arrHora = explode(" ", $intNum);
            $intNum = $arrHora[0];
            if($intTipo==1){
                //Data Basileira DD/MM/AAAA
                $strData = Numeros::numCasas(substr($intNum,-2),2)."/".Numeros::numCasas(substr($intNum,4,2),2)."/".substr($intNum,0,4);
            }elseif($intTipo==2){
                //Data Americana MM/DD/AAAA
                $strData = Numeros::numCasas(substr($intNum,4,2),2)."/".Numeros::numCasas(substr($intNum,-2),2)."/".substr($intNum,0,4);
            }else{
                //Data SQL AAAA-MM-DD
                $strData = substr($intNum,0,4)."-".Numeros::numCasas(substr($intNum,4,2),2)."-".Numeros::numCasas(substr($intNum,-2),2);
            }
            $retorno = $strData . " " . $arrHora[1];
        }

        if($retorno != false){
            if(!checkdate(substr($intNum,4,2), substr($intNum,6,2), substr($intNum,0,4))){
                $retorno = false;
            }
        }

        return $retorno;

    }

    public static function menuMesAno($data=''){
        $anoInicial = 2013;
        $mesInicial = 12;

        $mesLink = "<a href=\"num/\">mes</a>";
        $mesNum = self::mes()-1;
        $anoNum = self::ano();
        if($data instanceof \DateTime){
            $anoNum = $data->format("Y");
        }

        $menu = "<form name=\"menuano\" id=\"menuano\" method=\"get\">\n";
        $menu .= "<label>Ano: <select name=\"ano\">\n";
        for($ano = self::ano(); $ano>=$anoInicial; $ano--){
            $select = "";
            if($anoNum == $ano){
                $select = " selected=\"selected\"";
            }
            $menu.= "<option value=\"$ano\"$select>$ano</option>\n";
        }
        $menu.= "<option value=\"$anoInicial\">Antigas</option>\n";
        $menu .= "</select></label>\n<input type=\"submit\" value=\"Mudar Ano\" />\n<ul>\n";

        foreach(self::$arrMes as $num => $mes){
            $mesTexto = substr(ucfirst($mes),0,3);
            $menu .= "<li>";
            if(($mesNum>=$num && $anoNum == self::ano()) || ($anoNum < self::ano() && $anoNum != 2013)){
                $menu .= str_replace("num", URL::getInstance()->getDir() ."noticias/".$anoNum."/".$mes, str_replace("mes", $mesTexto, $mesLink));
            }else if($anoNum == 2013){
                if($num == 11){
                    $menu .= str_replace("num",  URL::getInstance()->getDir()  ."noticias/".$anoNum."/".$mes, str_replace("mes", $mesTexto, $mesLink));
                }else{
                    $menu .= $mesTexto;
                }
            }else{
                $menu .= $mesTexto;
            }
            $menu .= "</li>\n";
        }

        $menu .= "</ul>\n</form>\n";

        return  $menu;
    }

    public static function dataIntervalo($_ano, $_mes){
        $ano=$_ano;
        $mes=$_mes;
        $dia=0;
        if(empty($ano)){
            $ano = self::ano();
        }
        if(empty($mes)){
            if($ano<self::ano()){
                $mes = self::mesNomeSemAcento(12);
            }else{
                $mes = self::mesNomeSemAcento(self::mes());
            }
        }
        $mes = self::mesNomeParaNum($mes);
        $dataSelecionada = new \DateTime($ano."-".$mes."-01");
        $dia = $dataSelecionada->format('t');
        if($mes==self::mes() && $ano==self::ano()){
            $dia = self::dia();
        }

        $dataFim = new \DateTime($ano.'-'.$mes.'-'.$dia);

        if(empty($_mes)){
            $dia = 31;
        }

        $data = new \DateTime($dataFim->format('Y-m-d'));
        $data->sub(new \DateInterval('P'.($dia-1).'D'));
        $dataIni = $data;
        return [
            'ini'=>$dataIni,
            'fim'=>$dataFim,
            'ano'=>$ano,
            'mes'=>$mes,
            'dia'=>$dia
        ];
    }
    
    public static function getVersao(){
        return self::$versao;
    }

    
    public static $arrSemana = array(
                            "Domingo",
                            "Segunda",
                            "Terça",
                            "Quarta",
                            "Quinta",
                            "Sexta",
                            "Sábado"
                        );

    public static $arrMeses = array(
                            "Janeiro",
                            "Fevereiro",
                            "Março",
                            "Abril",
                            "Maio",
                            "Junho",
                            "Julho",
                            "Agosto",
                            "Setembro",
                            "Outubro",
                            "Novembro",
                            "Dezembro"
                        );
    public static $arrMes = array(
                            "janeiro",
                            "fevereiro",
                            "marco",
                            "abril",
                            "maio",
                            "junho",
                            "julho",
                            "agosto",
                            "setembro",
                            "outubro",
                            "novembro",
                            "dezembro"
                        );

    public static $erHora = "/^([01]?[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/";
    public static $erDataNum = "/^[0-9]{4}([0][1-9]|1[0-2])([0][1-9]|[12][0-9]|3[01])$/";
    public static $erDataHoraNum = "/^[0-9]{4}([0][1-9]|1[0-2])([0][1-9]|[12][0-9]|3[01]) ([01]?[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/";
    public static $erDataHoraNum2 = "/^[0-9]{4}([0][1-9]|1[0-2])([0][1-9]|[12][0-9]|3[01]) ([01]?[0-9]|2[0-3])([0-5][0-9])([0-5][0-9])$/";
    public static $erDataBra = "/^([0]?[1-9]|[12][0-9]|3[01])\/([0]?[1-9]|1[0-2])\/([0-9]{2}|[0-9]{4})$/";
    public static $erDataSql = "/^[0-9]{4}-([0]?[1-9]|1[0-2])-([0]?[1-9]|[12][0-9]|3[01])$/";
    public static $erDataHoraBra = "/^([0]?[1-9]|[12][0-9]|3[01])\/([0]?[1-9]|1[0-2])\/([0-9]{2}|[0-9]{4}) ([01]?[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/";
    public static $erDataHoraSql = "/^[0-9]{4}-([0]?[1-9]|1[0-2])-([0]?[1-9]|[12][0-9]|3[01]) ([01]?[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/";

    private static $versao = "1.2";
    
}

?>