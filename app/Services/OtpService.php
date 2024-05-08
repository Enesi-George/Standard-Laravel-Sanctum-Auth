<?php

namespace App\Services;

class OtpService
{
    /**
     * Generate a random OTP token.
     *
     * @param int $length
     * @return string
     */
    public function generateToken($length = 6)
    {
        // Generate a random OTP token with the specified length
        $characters = '0123456789';
        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $token;
    }
}
