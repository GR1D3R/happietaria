<?php

namespace Tools;

use PDO;

class tSecurity
{
    const salt = "P@sSw0rD123321!@#";

    public static function randomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }



    public static function hashPassword($password, $salt2){
        $salt1 = hash("snefru",tSecurity::salt);
        $password_hash_I  = hash("ripemd320", $salt1 . $salt2 . $password);
        $password_hash_II = hash("sha512", $salt1 . $salt2 . $password_hash_I);
        return $password_hash_II;
    }
}