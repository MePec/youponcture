<?php
    class CheckValues {

        // Le login peut contenir des caractères alphaumériques et des tirets unique, sa longueur est comprise entre 3 et 15.
        function checkLogin($login) {
            if (preg_match ( '/^[\w\d-]{3,15}$/', $login)){
                return true;
            } else {
                return false;
            }
        }


        // Le password doit faire 8 caractères minimum, contenir au moins une majuscule, une miniscule et un chiffre, tout les caractères sont autorisés.
        function checkPwd($pwd) {
            if (preg_match ( '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $pwd)){
                return true;
            } else {
                return false;
            }
        }

        // Verifie la syntaxe de l'adresse mail et l'existance du MX du domaine
        function checkEmail($email) {

            $domain = NULL;

            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
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
