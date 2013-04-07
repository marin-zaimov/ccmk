<?php

// all password business logic should be encapsulated in this helper file

/**
* class PasswordHelper
*/
class PasswordHelper
{
        public function hashPassword($pw, $salt)
        {
                return sha1($pw . $salt);
        }

        public function generateRandomHashedPassword($salt)
        {
                $passwordAndSalt = self::generateRandomPassword() . $salt;
                return sha1($passwordAndSalt);
        }

        public static function generateRandomPassword($minimumLength = 9, $maximumLength = 15)
        {
                $length = rand($minimumLength, $maximumLength);


                $letters = 'bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ1234567890';
                $vowels = 'aeiouAEIOU';
                //make sure there is a lower case, an upper case and a number for the first 3 characters
                $code = ''.$letters[rand(0,20)].$letters[rand(21,41)].$letters[rand(41,51)];
                for ($i = 3; $i < $length; ++$i){
                        if($i % 2 && rand(0,10) > 4 || !($i % 2) && rand(0,10) > 9)
                                $code .= $vowels[rand(0,9)];
                        else
                                $code .= $letters[rand(0,51)];
                }

                return $code;
        }

        public static function generateRandomSalt($length = 10)
        {
            return substr(md5(uniqid(rand(), true)), 0, $length);
        }

        public static function isValidPasswordPattern($pw)
        {
                if(self::hasCharacterRequirements($pw) && self::isProperLength($pw))
                {
                        return true;
                }
                else {
                        return false;
                }
        }

        public static function hasCharacterRequirements($pw)
        {
                if(preg_match('/[a-z]/', $pw) && preg_match('/[A-Z]/', $pw) && preg_match('/[0-9]/', $pw)) {
                        return true;
                }
                else {
                        return false;
                }
        }

        public static function isProperLength($pw)
        {
                if((strlen($pw) < 9) || (strlen($pw) > 32)) {
                        return false;
                }
                else {
                        return true;
                }
        }
}
