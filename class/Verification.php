<?php

class Verification {
    public function verifyImage($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($file['type'], $allowedTypes) && $file['size'] <= 5 * 1024 * 1024) {
            return true;
        }
        return false;
    }

    public function verifyText($text) {
        return !empty(trim($text));
    }
}