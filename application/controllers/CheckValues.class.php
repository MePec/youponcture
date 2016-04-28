<?php
    class CheckValues {

        // Verifie qu'il n'y ait pas d'espace dans la chaine
        public static function checkNoSpace($sting) {
            return preg_match ( '/^\S+$/', $sting);
        }

        // Verifie que un nom: support l'aphabet + caractères accentués + ponctuation des noms
        public static function checkName($name) {
            return preg_match ( "/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", $name);
        }

        // Verifie une chaine Alphanumérique + les espaces et les accents
        public static function checkIsAlphaNumWithSpace($string) {
            return preg_match ( "/^[0-9a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-|\s]+$/u", $string);
        }

        // Verifie une chaine alpahnumérique simple
        public static function checkIsAlphaNum($string) {
            return preg_match ( "/^[0-9a-zA-Z]+$/u", $string);
        }

        // Le login peut contenir des caractères alphaumériques et des tirets unique, sa longueur est comprise entre 3 et 15.
        public static function checkLogin($login) {
            return preg_match ( '/^[\w\d-]{3,15}$/', $login);
        }


        // Le password doit faire 8 caractères minimum, contenir au moins une majuscule, une miniscule et un chiffre, tout les caractères sont autorisés sauf les éspaces.
        public static function checkPwd($pwd) {
            return preg_match ( '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $pwd);
        }

        // Verifie la syntaxe de l'adresse mail
        public static function checkEmail($email) {

            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                return true;
            } else {
                return false;
            }
        }

        // Verifie la syntaxe de l'adresse mail et l'existance du MX du domaine
        public static function checkEmailwithMX($email) {

            $domain = '';

            if(self::checkEmail($email)){
                $domain = substr(strrchr($email, "@"), 1);
                if(checkdnsrr($domain, 'MX')){
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
?>
