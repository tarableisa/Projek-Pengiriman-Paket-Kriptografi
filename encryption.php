<?php
class Encryption {
    private $key = "YourSecretKey123"; 
    private $caesarShift = 3; 
    
    // Caesar Cipher Encryption
    private function caesarEncrypt($text, $shift) {
        $result = "";
        $textLength = strlen($text);
        
        for ($i = 0; $i < $textLength; $i++) {
            $char = $text[$i];
            
            // Encrypt only alphanumeric characters
            if (ctype_alpha($char)) {
                $isUpperCase = ctype_upper($char);
                $charCode = ord($char);
                $baseCode = $isUpperCase ? ord('A') : ord('a');
                
                // Apply Caesar shift
                $shiftedCharCode = (($charCode - $baseCode + $shift) % 26) + $baseCode;
                $result .= chr($shiftedCharCode);
            } elseif (ctype_digit($char)) {
                // Shift digits
                $result .= (string)((intval($char) + $shift) % 10);
            } else {
                // Keep non-alphanumeric characters as-is
                $result .= $char;
            }
        }
        
        return $result;
    }
    
    // Caesar Cipher Decryption
    private function caesarDecrypt($text, $shift) {
        return $this->caesarEncrypt($text, 26 - $shift);
    }
    
    public function superEncrypt($data) {
        $firstKey = substr(hash('sha256', $this->key), 0, 32);
        $secondKey = substr(hash('sha256', $firstKey), 0, 32);
        
        // Caesar Cipher encryption first
        $caesarEncrypted = $this->caesarEncrypt($data, $this->caesarShift);
        
        // First AES encryption
        $encrypted = openssl_encrypt($caesarEncrypted, 'AES-256-CBC', $firstKey, 0, str_repeat("0", 16));
        
        // Second AES encryption
        $finalEncrypted = openssl_encrypt($encrypted, 'AES-256-CBC', $secondKey, 0, str_repeat("0", 16));
        
        return base64_encode($finalEncrypted);
    }
    
    public function superDecrypt($encrypted) {
        $firstKey = substr(hash('sha256', $this->key), 0, 32);
        $secondKey = substr(hash('sha256', $firstKey), 0, 32);
        
        $encrypted = base64_decode($encrypted);
        
        // First decryption
        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $secondKey, 0, str_repeat("0", 16));
        
        // Second decryption
        $aesDecrypted = openssl_decrypt($decrypted, 'AES-256-CBC', $firstKey, 0, str_repeat("0", 16));
        
        // Caesar Cipher decryption last
        $finalDecrypted = $this->caesarDecrypt($aesDecrypted, $this->caesarShift);
        
        return $finalDecrypted;
    }
    
    // Steganography implementation
    public function hideMessage($image, $message) {
        // Convert message to binary
        $binary = '';
        for($i = 0; $i < strlen($message); $i++) {
            $binary .= str_pad(decbin(ord($message[$i])), 8, '0', STR_PAD_LEFT);
        }
        
        // Load image and get dimensions
        $img = imagecreatefromstring($image);
        $width = imagesx($img);
        $height = imagesy($img);
        
        // Hide binary data in least significant bits
        $binary_length = strlen($binary);
        $index = 0;
        
        for($y = 0; $y < $height && $index < $binary_length; $y++) {
            for($x = 0; $x < $width && $index < $binary_length; $x++) {
                $rgb = imagecolorat($img, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                
                // Modify least significant bit
                $r = $r & 0xFE | $binary[$index++];
                if($index < $binary_length) $g = $g & 0xFE | $binary[$index++];
                if($index < $binary_length) $b = $b & 0xFE | $binary[$index++];
                
                $color = imagecolorallocate($img, $r, $g, $b);
                imagesetpixel($img, $x, $y, $color);
            }
        }
        
        // Output image
        ob_start();
        imagepng($img);
        $steg_image = ob_get_clean();
        imagedestroy($img);
        
        return $steg_image;
    }
}
?>