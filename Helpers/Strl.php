<?php namespace Helpers;

class Strl 
{
    
    public function __construct() {

    }
    public function str_split_unicode($str, $l = 0) {
        if ($l > 0) {
            $ret = array();
            $len = mb_strlen($str, "utf-8");
            for ($i = 0; $i < $len; $i += $l) {
                $ret[] = mb_substr($str, $i, $l, "utf-8");
            }
            return $ret;
        }
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function trim_all($string) {
        $string = trim($string);
        $string = str_replace(array(' ', '&nbsp;'), array('', ''), $string);
        return $string;
    }

    public function clean_string($string, $extra_char = true, $enie_char = true, $others_chars = false, $replace_char = '_', $space = true) {

        $string = trim($string);

        $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
        );

        $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
                array('ç', 'Ç'), array('c', 'C',), $string
        );

        if ($enie_char === true)
            $string = str_replace(
                    array('ñ', 'Ñ'), array('n', 'N'), $string
            );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        if ($extra_char === true)
            $string = str_replace(
                    array("\\", "¨", "º", "-", "~",
                "#", "@", "|", "!", "\"",
                "·", "$", "%", "&", "/",
                "(", ")", "?", "'", "¡",
                "¿", "[", "^", "`", "]",
                "+", "}", "{", "¨", "´",
                ">", "<", ";", ",", ":"), $replace_char, $string
            );
        if ($space === true)
            $string = str_replace(" ", $replace_char, $string);

        if ($others_chars === true)
            $string = str_replace(
                    array(".", ",", "_"), $replace_char, $string
            );

        return $string;
    }

    public function clean_string_rfc($string, $char) {

        $string = trim($string);

        $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Â', 'Ä'), $char, $string
        );

        $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'Ê', 'Ë'), $char, $string
        );

        $string = str_replace(
                array('í', 'ì', 'ï', 'î', 'Ï', 'Î'), $char, $string
        );

        $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ö', 'Ô'), $char, $string
        );

        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Û', 'Ü'), $char, $string
        );

        $string = str_replace(
                array('ç', 'Ç'), $char, $string
        );

        $string = str_replace(
                array('ñ', 'Ñ'), $char, $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
                array("\\", "¨", "º", "-", "~",
            "#", "@", "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "`", "]",
            "+", "}", "{", "¨", "´",
            ">", "<", ";", ",", ":", " "), $char, $string
        );

        return $string;
    }

    public function clean_cfdi($string) {

        $string = trim($string);

        $string = preg_replace('/\s\s+/', ' ', $string);
        $string = preg_replace('/\s{n,}/', ' ', $string);
        $string = preg_replace('/\t\t+/', ' ', $string);
        $string = preg_replace('/[\n|\r|\n\r]/i', ' ', $string);


        /* $string = str_replace(
        array('&', '"', '“', '”', '<', '>', '‘', "'", '´', '|')
        , array('&amp;', '&quot;', '&quot;', '&quot;', '&lt;', '&gt;', '&apos;', '&apos;', '&apos;', '/')
        , $string
        ); */
        return $string;
    }

    public function clean_n($string) {
        $string = trim(preg_replace('/[\n|\r|\n\r]/i', ' ', $string));
        return $string;
    }

    public function is_special_char($char) {
        if (in_array($char, array("\\", "¨", "º", "-", "~",
                    "#", "@", "|", "!", "\"",
                    "·", "$", "%", "&", "/",
                    "(", ")", "?", "'", "¡",
                    "¿", "[", "^", "`", "]",
                    "+", "}", "{", "¨", "´",
                    ">", "<", ";", ",", ":", '´', "'",
                    " ", 'ñ', 'Ñ', 'ç', 'Ç',
                    'ú', 'ù', 'ü', 'û', 'Û', 'Ü',
                    'ó', 'ò', 'ö', 'ô', 'Ö', 'Ô',
                    'í', 'ì', 'ï', 'î', 'Ï', 'Î',
                    'é', 'è', 'ë', 'ê', 'Ê', 'Ë',
                    'á', 'à', 'ä', 'â', 'ª', 'Â', 'Ä')))
            return true;
        return false;
    }

    public function clean_address($Direccion) {
        $Direccion = str_replace(
                array(', C.P. 0, ,  ,', ', C.P. , ,  ,'), '', $Direccion
        );
        return $Direccion;
    }

    public function special_upper_string($str) {
        setlocale(LC_CTYPE, 'es');
        return trim(strtr(strtoupper($str), "àèìòùáéíóúçñäëïöü", "ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ"));
    }

    public function special_lower_string($str) {
        setlocale(LC_CTYPE, 'es');
        return trim(strtr(strtolower($str), "ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ", "àèìòùáéíóúçñäëïöü"));
    }

    public function utf8_decode_trim($str) {
        return trim(utf8_encode($str));
    }

    public function is_rfc($str) {
        /*$str = trim(clean_string(special_upper_string($str)));*/
        $rfcCO = "/[A-Z&Ñ]{3,4}[0-9]{2}(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[01])[A-Z0-9]{2}[0-9A]$/";
        $rfcPM = "/[A-Z&Ñ]{3}[0-9]{2}(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[01])[A-Z0-9]{2}[0-9A]$/";
        $rfcPF = "/[A-Z&Ñ]{4}[0-9]{2}(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[01])[A-Z0-9]{2}[0-9A]$/";

        if ( preg_match($rfcCO, $str) ) {
            if ( preg_match($rfcPM, $str) || preg_match($rfcPF, $str) ) {
                return true;
            }
        }

        /*if (preg_match("/[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?$/", $str)) {
            if (preg_match("/[A-Z,Ñ,&]{4}[0-9]{6}[A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?$/", $str) && strlen($str) === 13) {
                return true;
            } else if (preg_match("/[A-Z,Ñ,&]{3}[0-9]{6}[A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?$/", $str) && strlen($str) === 12) {
                return true;
            }
        }*/

        return false;
    }

    public function is_curp($str) {
        $str = trim(clean_string(special_upper_string($str)));
        if ( preg_match("/[A-Z][AEIOUX][A-Z]{2}[0-9]{2}(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[01])[MH]([ABCMTZ]S|[BCJMOT]C|[CNPST]L|[GNQ]T|[GQS]R|C[MH]|[MY]N|[DH]G|NE|VZ|DF|SP)[BCDFGHJ-NP-TV-Z]{3}[0-9A-Z][0-9]$/", $str) )
            return true;

        /*if (preg_match("/[A-Z][A,E,I,O,U,X][A-Z]{2}[0-9]{2}[0-1][0-9][0-3][0-9][M,H][A-Z]{2}[B,C,D,F,G,H,J,K,L,M,N,Ñ,P,Q,R,S,T,V,W,X,Y,Z]{3}[0-9,A-Z][0-9]$/", $str))
            return true;*/

        return false;
    }

    public function array_search_recursive($needle, $haystack) {
        $path = array();
        foreach ($haystack as $id => $val) {

            if ($val === $needle) {
                $path[] = $id;

                break;
                # ^^this breaks out of loop when it finds needle
            } else if (is_array($val)) {
                $found = array_search_recursive($needle, $val);
                if (count($found) > 0) {
                    $path[$id] = $found;

                    break;
                    # ^^this breaks out of loop when recursive call found needle
                }
            }
        }
        return $path;
    }

    public function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds') {
        $sets = array();
        if (strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if (strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if (strpos($available_sets, 'd') !== false)
            $sets[] = '1234567890';
        if (strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';

        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];

        $password = str_shuffle($password);

        if (!$add_dashes)
            return $password;

        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while (strlen($password) > $dash_len) {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }
}
