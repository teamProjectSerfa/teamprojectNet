<?php
class FormSanitizer {

    public static function sanitizeFormString($inputText) {
        $inputText = strip_tags($inputText);
        // pour enlever les espaces dans le champ nom de l'utilisateur
        $inputText = str_replace(" ", "", $inputText);
        //$inputText = trim($inputText);
        $inputText = strtolower($inputText);
        $inputText = ucfirst($inputText);
        return $inputText;
    }

    public static function sanitizeFormUsername($inputText) {
        $inputText = strip_tags($inputText);
        // pour enlever les espaces dans le champ nom d'utilisateur
        $inputText = str_replace(" ", "", $inputText);
        return $inputText;
    }

    public static function sanitizeFormPassword($inputText) {
        $inputText = strip_tags($inputText);
        return $inputText;
    }

    public static function sanitizeFormEmail($inputText) {
        $inputText = strip_tags($inputText);
        // pour enlever les espaces dans le champ email
        $inputText = str_replace(" ", "", $inputText);
        return $inputText;
    }

}
?>