<?php

/**
 * Funciones encargadas de modificar el valor de las cadenas de texto
 *
 * @package    PL_String
 * @author     Empírika
 * @copyright  2013 Empírika
 * @version    1.0.0
 */
class PL_String {

    public static function getSlug($cadena) {
        $letrasAcentuadas = array(":","'", '"', "Á", "É", "Í", "Ó", "Ú", "Ü", "á", "é", "í", "ó", "ú", "ü", "à", "è", "ì", "ò", "ù", "À", "È", "Ì", "Ò", "Ù", "Ñ", "ñ", "Ç", "ç", " ", "ô");
        $letrasSinAcentos = array("","", "", "a", "e", "i", "o", "u", "u", "a", "e", "i", "o", "u", "u", "a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n", "n", "c", "c", "-", "o");
        $cadena = html_entity_decode($cadena);
        $cadena = mb_convert_case(trim($cadena), MB_CASE_LOWER, "UTF-8");
        $cadena = str_replace($letrasAcentuadas, $letrasSinAcentos, $cadena);
        
        // Eliminamos guiones repetidos
        $cadena = preg_replace("/-+/", "-", $cadena);
        $conservar = '0-9a-z-'; // juego de caracteres a conservar
        $regex = sprintf('~[^%s]++~i', $conservar); // case insensitive
        $cadena = preg_replace($regex, '', $cadena);
        
        if (substr($cadena, -1) == '-') { // Si se nos queda una - al final
            $cadena = substr($cadena, 0, -1);
        }
        if (substr($cadena, 0, 1) == '-') { // Si se nos queda una - al principio
            $cadena = substr($cadena, 1);
        }
        
        return $cadena;
    }     
     
    
    public static function sliceText($texto, $longitud, $tags = null, $ending = " [...]") {
        if (mb_strlen($texto) <= $longitud) {
            return $texto;
        }

        if ($tags) {
            $extracto = mb_substr($texto, 0, $longitud);
        } else {
            $html = array('&nbsp;','&Aacute;','&Eacute;','&Iacute;','&Oacute;','&Uacute;','&aacute;','&eacute;','&iacute;','&oacute;','&uacute;','&Ntilde;','&ntilde;','&ldquo;','&rdquo;','&hellip;');
            $caracteres = array(' ','Á','É','Í','Ó','Ú','á','é','í','ó','ú','Ñ','ñ','"','"','...');
            $extracto = mb_substr(str_replace($html, $caracteres, strip_tags($texto)), 0, $longitud);
        }

        if (mb_substr($extracto, -1) != " ") {
            $piezas = explode(" ", $extracto);
            array_pop($piezas);
            $extracto = implode(" ", $piezas);
        }
        
        
        
        return $extracto . $ending;
    }
    
    public static function ucwordsText($string){
        return ucwords(strtolower(str_replace(array('Á','É','Í','Ó','Ú','Ñ'),array('á','é','í','ó','ú','ñ'),$string)));
    }
    
    public static function getMetaKeywords($text) {
        // Limpiamos el texto
        $text = strip_tags($text);
        $text = strtolower($text);
        $text = trim($text);
        $text = preg_replace('/[^a-zA-Z0-9 -]/', ' ', $text);
        
        // Quitamos las preposiciones
        $text = str_replace(array(' a ',' ante ',' bajo ',' cabe ',' con ',' contra ',' de ',' desde ',' durante ',' en ',' entre ',' hacia ',' hasta ',' mediante ',' para ',' por ',' según ',' sin ',' so ',' sobre ',' tras ',' versus ',' vía '),' ',$text);
        
        // Extraemos las palabras
        $match = explode(" ", $text);
        
        // Contamos las palabras para elegir las más repetidas
        $count = array();
        if (is_array($match)) {
            foreach ($match as $key => $val) {
                if (strlen($val)> 3) {
                    if (isset($count[$val])) {
                        $count[$val]++;
                    } else {
                        $count[$val] = 1;
                    }
                }
            }
        }
        
        // Ordenamos los totales
        arsort($count);
        $count = array_slice($count, 0, 10);
        return implode(", ", array_keys($count));
    }

    public static function getMetaDescription($text) {
        $text = strip_tags($text);
        $text = trim($text);
        $text = substr($text, 0, 247);
        return $text."...";
    }
    
    public static function sliceTextToChar($texto, $longitud, $char = '. ', $tags = null, $ending = "...") {
        if (mb_strlen($texto) <= $longitud) {
            return $texto;
        }

        if ($tags) {
            $extracto = mb_substr($texto, 0, $longitud);
        } else {
            $html = array('&nbsp;','&Aacute;','&Eacute;','&Iacute;','&Oacute;','&Uacute;','&aacute;','&eacute;','&iacute;','&oacute;','&uacute;','&Ntilde;','&ntilde;','&ldquo;','&rdquo;','&hellip;');
            $caracteres = array(' ','Á','É','Í','Ó','Ú','á','é','í','ó','ú','Ñ','ñ','"','"','...');
            $extracto = mb_substr(str_replace($html, $caracteres, strip_tags($texto)), 0, $longitud);
        }

        if (mb_substr($extracto, -1) != " ") {
            $piezas = explode(" ", $extracto);
            array_pop($piezas);
            $extracto = implode(" ", $piezas);
        }
        
        $pieces = explode($char,$extracto);
        $extracto_return = '';
        if(count($pieces) == 1){
            $extracto_return = $extracto.$ending;
        }else{            
            for($i = 0;$i < count($pieces)-1; $i++){
                $extracto_return .= $pieces[$i].$char;                
            }
        }
        
        return $extracto_return;
    }
    
    /**
     * Strip_tags with content deletion     
     * @return string
     */
    public static function strip_tags_content($text, $tags = '', $invert = FALSE) {
        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
        $tags = array_unique($tags[1]);

        if(is_array($tags) AND count($tags) > 0) {
          if($invert == FALSE) {
            return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
          }
          else {
            return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
          }
        }
        elseif($invert == FALSE) {
          return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
        }
        return $text;
    }
    
    /**
     * Strip_tags_attributes 
     * @return string
     */
    public static function strip_tags_attributes($text)
    {
       $text = preg_replace("/<([^\s>]+)[^>]+\/>/i", "<\\1 />", $text);
       $text = preg_replace("@<([^\s>]+)[^>]+[^\/]>@si", "<\\1>", $text);
       return $text;
    }

    /**
     * Strip_tags with content deletion by id or class   
     * @return string
     */
    public static function strip_selected_tags_by_id_or_class($array_of_id_or_class, $text)
    {
       $name = implode('|', $array_of_id_or_class);
       $regex = '#<(\w+)\s[^>]*(class|id)\s*=\s*[\'"](' . $name .
                ')[\'"][^>]*>.*</\\1>#isU';
       return(preg_replace($regex, '', $text));
    }
    
    /**
     * Limpia básica (trim y strip_tags). NO ESCAPA para SQL
     * @param string $s
     * @return string
     */
    public static function sanitizeString($s) {
        return trim(strip_tags($s));
    }
}
