<?php

class FechasUtils {
    
    /**
     * Devuelve fecha del tipo Lunes, 23 de Enero de 1543
     * Basado en http://elticus.com/?contenido=41
     * Posteriores adaptaciones
     * @param integer $timestamp
     * @return string
     */
    public static function formatearFecha($timestamp=null, $formato="completo") {

        if (empty($timestamp)) $timestamp = time();
        $ano = date('Y',$timestamp);
        $mes = date('n',$timestamp);
        $dia = date('j',$timestamp);
        $hora = date('H', $timestamp);
        $minuto = date('i', $timestamp);
        $diasemana = date('w',$timestamp);
        $diassemanaN = array("Domingo","Lunes","Martes","Miércoles",
                     "Jueves","Viernes","Sábado");
        $diassemanaNC = array("Dom.","Lun.","Mar.","Mie.",
                     "Jue.","Vie.","Sáb.");
        $mesesMayus=array(1=>"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                     "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mesesMayus3=array(1=>"Ene","Feb","Mar","Abr","May","Jun","Jul",
                     "Ago","Sep","Oct","Nov","Dic");
        $mesesMinus=array(1=>"enero","febrero","marzo","abril","mayo","junio","julio",
                     "agosto","septiembre","octubre","noviembre","diciembre");

        if ($formato == "h")        //19:55
            return date("H:i", $timestamp);
        elseif ($formato == "dm")   //17 de Junio
            return $dia." de ".$mesesMayus[$mes];
        elseif ($formato == "ddm")  //Lunes 17 de Junio
            return $diassemanaN[$diasemana]." $dia de ". $mesesMayus[$mes];
        elseif ($formato == "dmh")  //17/06 19:55
            return date("d/m H:i", $timestamp);
        elseif ($formato == "amd")  //2013-06-17
            return date("Y-m-d", $timestamp);
        elseif ($formato == "fh")
            return date("H:i", $timestamp) . " del " . $diassemanaN[$diasemana].", $dia de ". $mesesMayus[$mes] ." de $ano";
        elseif ($formato == "fhm")
            return date("H:i", $timestamp) . " del " . strtolower($diassemanaN[$diasemana]).", $dia de ". $mesesMinus[$mes] ." de $ano";
        elseif ($formato == "diames")
            return "$dia de ". $mesesMinus[$mes];
        elseif ($formato == "barras")
            return date("d/m/Y", $timestamp);
        elseif ($formato == "diamesanno")
            return "$dia de ". $mesesMinus[$mes] . " de $ano";
        elseif ($formato == "diasemanamesanno")
            return $diassemanaN[$diasemana].", $dia de ". $mesesMinus[$mes] . " de $ano";
        elseif ($formato == "diasemanames")
            return $diassemanaN[$diasemana].", $dia de ". $mesesMinus[$mes];
        elseif ($formato == "messolominusculas")
            return $mesesMinus[$mes];
        elseif ($formato == "messolomayusculas")
            return $mesesMayus[$mes];
        elseif ($formato == "messolomayusculasyano")
            return $mesesMayus[$mes] . " de " . $ano;
        elseif ($formato == 'diasemanaycifra')
            return $diassemanaN[$diasemana].", ". date("d/m/Y", $timestamp);
        elseif ($formato == 'diasemanacortoycifra')
            return $diassemanaNC[$diasemana]." ". date("d/m/Y", $timestamp);
        elseif ($formato == 'span')
            return "<span class='diaL'>" . $diassemanaN[$diasemana]. "</span> " .
                   "<span class='diaN'>$dia</span> " .
                   "<span class='mes'>". $mesesMayus[$mes] ."</span> ".
                   "<span class='anyo'>$ano</span> ";
        elseif ($formato == 'span2')
            return "<span class='dia'>$dia</span> " .
                 "<span class='mes'>". $mesesMayus[$mes] ."</span> ".
                 "<span class='ano'>$ano</span> ";
        elseif ($formato == 'span3')
            return "<span class='dia'>$dia</span> " .
                 "<span class='mes'>". $mesesMayus3[$mes] ."</span> ".
                 "<span class='ano'>$ano</span> " .
                 "<span class='hora'>$hora:$minuto</span> " ;
        else
            return $diassemanaN[$diasemana].", $dia de ". $mesesMayus[$mes] ." de $ano";
    }

    public static function getOptionsForSelect($tipo, $actual=null) {
        $mesesMayus=array(1=>"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                     "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mesesMayus3=array(1=>"Ene","Feb","Mar","Abr","May","Jun","Jul",
                     "Ago","Sep","Oct","Nov","Dic");
        $mesesMinus=array(1=>"enero","febrero","marzo","abril","mayo","junio","julio",
                     "agosto","septiembre","octubre","noviembre","diciembre");
        $retVal = "";
        if ($tipo == 'dia') {
            if (!$actual) $actual = date("d");
            for($x=1; $x<=31; $x++) {
                $retVal .= "<option value='$x'";
                if ((int)$actual == $x) $retVal .= " selected='selected' ";
                $retVal .= ">$x</option>\n";
            }
        } elseif ($tipo == 'mes') {
            if (!$actual) $actual = date("m");
            foreach($mesesMayus as $k => $v) {
                $retVal .= "<option value='$k'";
                if ((int)$actual == $k) $retVal .= " selected='selected' ";
                $retVal .= ">$v</option>\n";
            }
        } elseif ($tipo == 'anno') {
            if (!$actual) $actual = date("Y");
            for($x=2007; $x<=date("Y"); $x++) {
                $retVal .= "<option value='$x'";
                if ((int)$actual == $x) $retVal .= " selected='selected' ";
                $retVal .= ">$x</option>\n";
            }
        }
        return $retVal;
    }

    // Obtiene todas fechas entre dos fechas dadas, ambas inclusive
    public static function getDatesInterval($begin,$end){
        $end = $end->modify( '+1 day' ); 
        $interval = new DateInterval('P1D');        
        $daterange = new DatePeriod($begin, $interval ,$end);
        $end = $end->modify( '-1 day' ); 

        $fechas = array();
        
        foreach($daterange as $date){
            array_push($fechas, $date->format("Y-m-d"));
        }
        
        return $fechas;
    }

    public static function getYearsAgo($date, $since = 'today'){
        $from = new DateTime($date);
        $to   = new DateTime($since);
        
        return $from->diff($to)->y;
    }
    
}

?>
