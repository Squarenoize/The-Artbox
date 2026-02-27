<?php

class Verification {

    public function verifyText($text, $maxLength) {
        if (empty($text)) {
            return false;
        }
        $text = trim($text);
        if (strlen($text) > $maxLength) {
            return false;
        }
        return $text;
    }

    public function verifyImage($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($file['type'], $allowedTypes) && $file['size'] > 0 && $file['error'] === UPLOAD_ERR_OK) {
        }else {
            return false;
        }
        $sizeLimit = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $sizeLimit) {
            return false;
        }
        return true;
    }
}