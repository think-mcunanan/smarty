<?php
/* SVN FILE: $Id$ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs
 * @since         CakePHP(tm) v .0.10.0.1233
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * @package       cake
 * @subpackage    cake.cake.libs
 */
class Security extends CakeObject {
/**
 * Default hash method
 *
 * @var string
 * @access public
 */
	var $hashType = null;
/**
  * Singleton implementation to get object instance.
  *
  * @return object
  * @access public
  * @static
  */
	function &getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] = new Security;
		}
		return $instance[0];
	}
/**
  * Get allowed minutes of inactivity based on security level.
  *
  * @return integer Allowed inactivity in minutes
  * @access public
  * @static
  */
	function inactiveMins() {
		$_this =& Security::getInstance();
		switch (Configure::read('Security.level')) {
			case 'high':
				return 10;
			break;
			case 'medium':
				return 100;
			break;
			case 'low':
			default:
				return 300;
				break;
		}
	}
/**
  * Generate authorization hash.
  *
  * @return string Hash
  * @access public
  * @static
  */
	function generateAuthKey() {
		if (!class_exists('String')) {
			App::import('Core', 'String');
		}
		return Security::hash(CakeString::uuid());
	}
/**
 * Validate authorization hash.
 *
 * @param string $authKey Authorization hash
 * @return boolean Success
 * @access public
 * @static
 * @todo Complete implementation
 */
	function validateAuthKey($authKey) {
		return true;
	}
/**
 * Create a hash from string using given method.
 * Fallback on next available method.
 *
 * @param string $string String to hash
 * @param string $type Method to use (sha1/sha256/md5)
 * @param boolean $salt If true, automatically appends the application's salt
 * 				  value to $string (Security.salt)
 * @return string Hash
 * @access public
 * @static
 */
	function hash($string, $type = null, $salt = false) {
		$_this =& Security::getInstance();

		if ($salt) {
			if (is_string($salt)) {
				$string = $salt . $string;
			} else {
				$string = Configure::read('Security.salt') . $string;
			}
		}

		if (empty($type)) {
			$type = $_this->hashType;
		}
		$type = strtolower($type);

		if ($type == 'sha1' || $type == null) {
			if (function_exists('sha1')) {
				$return = sha1($string);
				return $return;
			}
			$type = 'sha256';
		}

		if ($type == 'sha256' && function_exists('mhash')) {
			return bin2hex(mhash(MHASH_SHA256, $string));
		}

		if (function_exists('hash')) {
			return hash($type, $string);
		}
		return md5($string);
	}
/**
 * Sets the default hash method for the Security object.  This affects all objects using
 * Security::hash().
 *
 * @param string $hash Method to use (sha1/sha256/md5)
 * @access public
 * @return void
 * @static
 * @see Security::hash()
 */
	function setHash($hash) {
		$_this =& Security::getInstance();
		$_this->hashType = $hash;
	}
/**
 * Encrypts/Decrypts a text using the given key.
 *
 * @param string $text Encrypted string to decrypt, normal string to encrypt
 * @param string $key Key to use
 * @return string Encrypted/Decrypted string
 * @access public
 * @static
 */
	function cipher($text, $key) {
		if (empty($key)) {
			trigger_error(__('You cannot use an empty key for Security::cipher()', true), E_USER_WARNING);
			return '';
		}

		$_this =& Security::getInstance();
		if (!defined('CIPHER_SEED')) {
			//This is temporary will change later
			define('CIPHER_SEED', '76859309657453542496749683645');
		}
		srand(CIPHER_SEED);
		$out = '';

		for ($i = 0; $i < strlen($text); $i++) {
			for ($j = 0; $j < ord(substr($key, $i % strlen($key), 1)); $j++) {
				$toss = rand(0, 255);
			}
			$mask = rand(0, 255);
			$out .= chr(ord(substr($text, $i, 1)) ^ $mask);
		}
		return $out;
	}

	// The following AES Encrypt and Decrypt function was added from the cakephp 3.0.0
	// https://github.com/cakephp/cakephp/blob/3.0.0/src/Utility/Security.php#L169
	// AES encryption / decryption was added to system because cipher encryption didn't work properly in PHP 7.x and above

	/**
     * Encrypt a value using AES-256.
     * 
     * *Caveat* You cannot properly encrypt/decrypt data with trailing null bytes.
     * Any trailing null bytes will be removed on decryption due to how PHP pads messages
     * with nulls prior to encryption.
     *
     * @param string $plain The value to encrypt.
     * @param string $key The 256 bit/32 byte key to use as a cipher key.
     * @param string|null $hmacSalt The salt to use for the HMAC process. Leave null to use Security.salt.
     * @return string Encrypted data.
     * @throws \InvalidArgumentException On invalid data or key.
     */
    public static function encrypt($plain, $key, $hmacSalt = null)
    {
        self::_checkKey($key, 'encrypt()');

        if ($hmacSalt === null) {
            $hmacSalt = Configure::read('Security.salt');
		}
		
        // Generate the encryption and hmac key.
        $key = substr(hash('sha256', $key . $hmacSalt), 0, 32);
		
		if (!extension_loaded('openssl')) {
			throw new InvalidArgumentException(
				'No compatible crypto engine available. ' .
				'Load the openssl extensions'
			);
		}

		$ciphertext = self::aesEncrypt($plain, $key);
        $hmac = hash_hmac('sha256', $ciphertext, $key);
        return $hmac . $ciphertext;
    }

    /**
     * Check the encryption key for proper length.
     *
     * @param string $key Key to check.
     * @param string $method The method the key is being checked for.
     * @return void
     * @throws \InvalidArgumentException When key length is not 256 bit/32 bytes
     */
    protected static function _checkKey($key, $method)
    {
        if (strlen($key) < 32) {
            throw new InvalidArgumentException(
                sprintf('Invalid key for %s, key must be at least 256 bits (32 bytes) long.', $method)
            );
        }
    }

	public static function aesEncrypt($plain, $key, $hmacSalt = null)
    {
        $method = 'AES-256-CBC';
        $ivSize = openssl_cipher_iv_length($method);

        $iv = openssl_random_pseudo_bytes($ivSize);
        return $iv . openssl_encrypt($plain, $method, $key, true, $iv);
    }

    /**
     * Decrypt a value using AES-256.
     *
     * @param string $cipher The ciphertext to decrypt.
     * @param string $key The 256 bit/32 byte key to use as a cipher key.
     * @param string|null $hmacSalt The salt to use for the HMAC process. Leave null to use Security.salt.
     * @return string Decrypted data. Any trailing null bytes will be removed.
     * @throws InvalidArgumentException On invalid data or key.
     */
    public static function decrypt($cipher, $key, $hmacSalt = null)
    {
        self::_checkKey($key, 'decrypt()');
        if (empty($cipher)) {
            throw new InvalidArgumentException('The data to decrypt cannot be empty.');
        }
        if ($hmacSalt === null) {
            $hmacSalt = Configure::read('Security.salt');
        }

        // Generate the encryption and hmac key.
        $key = substr(hash('sha256', $key . $hmacSalt), 0, 32);

        // Split out hmac for comparison
        $macSize = 64;
        $hmac = substr($cipher, 0, $macSize);
        $cipher = substr($cipher, $macSize);

        $compareHmac = hash_hmac('sha256', $cipher, $key);
        if ($hmac !== $compareHmac) {
            return false;
        }

		if (!extension_loaded('openssl')) {
			throw new InvalidArgumentException(
				'No compatible crypto engine available. ' .
				'Load the openssl extensions'
			);
		}
        return self::aesDecrypt($cipher, $key);
	}

    /**
     * Decrypt a value using AES-256.
     *
     * @param string $cipher The ciphertext to decrypt.
     * @param string $key The 256 bit/32 byte key to use as a cipher key.
     * @return string Decrypted data. Any trailing null bytes will be removed.
     * @throws \InvalidArgumentException On invalid data or key.
     */
    public static function aesDecrypt($cipher, $key)
    {
        $method = 'AES-256-CBC';
        $ivSize = openssl_cipher_iv_length($method);

        $iv = substr($cipher, 0, $ivSize);

        $cipher = substr($cipher, $ivSize);
        return openssl_decrypt($cipher, $method, $key, true, $iv);
    }
}
?>