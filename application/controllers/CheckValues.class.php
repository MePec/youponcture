
<?php
class CheckValues {

    function checkLogin($login) {
        if (preg_match ( '/^[\w\d-]{3,15}$/', $login)){
            return true;
        } else {
            return false;
        }
    }

    function checkPwd($pwd) {
        if (preg_match ( '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $pwd)){
            return true;
        } else {
            return false;
        }
    }
}
?>
