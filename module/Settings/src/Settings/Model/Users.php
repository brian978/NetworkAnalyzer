<?php
/**
 * NetworkAnalyzer
 *
 * @link      https://github.com/brian978/NetworkAnalyzer
 * @copyright Copyright (c) 2013
 * @license   Creative Commons Attribution-ShareAlike 3.0
 */

namespace Settings\Model;

class Users
{
    /**
     * Processes the password has so it returns the salt from it
     *
     * @param string $hash
     * @return array
     */
    public static function processPasswordHash($hash)
    {
        $salt    = '';
        $hashCut = 0;

        if (is_string($salt) && strlen($hash) > 3)
        {
            $hashCutLen = substr($hash, -1, 1);
            $hashCut    = substr($hash, -$hashCutLen - 1, $hashCutLen);
            $saltLen    = substr($hash, -$hashCutLen - 3, 2);
            $salt       = substr($hash, $hashCut, $saltLen);
        }

        return array(
            'salt' => $salt,
            'hashCut' => $hashCut
        );
    }

    /**
     * Hashes the password with a new salt or an existing one
     *
     * @param string $password
     * @param array  $data
     * @return string
     */
    public static function generatePasswordHash($password, $data = array())
    {
        if (empty($data))
        {
            $salt    = substr(sha1(uniqid('', true) . time()), 0, rand(10, 20));
            $saltLen = strlen($salt);
            $hashCut = rand(4, $saltLen);
        }
        else
        {
            $salt    = $data['salt'];
            $saltLen = strlen($salt);
            $hashCut = $data['hashCut'];
        }

        $hashCutLen = strlen((string)$hashCut);
        $hash       = hash('sha512', $password . $salt);
        $hash       = substr($hash, 0, $hashCut) . $salt . substr($hash, $hashCut) . $saltLen . $hashCut . $hashCutLen;

        return $hash;
    }
}