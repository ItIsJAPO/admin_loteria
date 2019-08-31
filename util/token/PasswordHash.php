<?php

namespace util\token;

class PasswordHash {

	private $itoa64;
	private $random_state;
	private $portable_hashes;
	private $iteration_count_log2;

	function __construct() {
		$this->portable_hashes = true;
		$this->iteration_count_log2 = 8;
		$this->random_state = microtime() . uniqid(rand(), TRUE); // removed getmypid() for compatibility reasons
		$this->itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	}

	private function get_random_bytes( $count ) {
		$output = '';

		if ( @is_readable('/dev/urandom') && ($fh = @fopen('/dev/urandom', 'rb')) ) {
			$output = fread($fh, $count);
			fclose($fh);
		}

		if ( strlen($output) < $count ) {
			$output = '';

			for ( $i = 0; $i < $count; $i += 16 ) {
				$this->random_state = md5(microtime() . $this->random_state);
				$output .= pack('H*', md5($this->random_state));
			}

			$output = substr($output, 0, $count);
		}

		return $output;
	}

	private function encode64( $input, $count ) {
		$i = 0;
		$output = '';

		do {
			$value = ord($input[$i++]);
			$output .= $this->itoa64[$value & 0x3f];

			if ( $i < $count ) {
				$value |= ord($input[$i]) << 8;
			}

			$output .= $this->itoa64[($value >> 6) & 0x3f];

			if ( $i++ >= $count ) {
				break;
			}

			if ( $i < $count ) {
				$value |= ord($input[$i]) << 16;
			}

			$output .= $this->itoa64[($value >> 12) & 0x3f];

			if ( $i++ >= $count ) {
				break;
			}

			$output .= $this->itoa64[($value >> 18) & 0x3f];

		} while ( $i < $count );

		return $output;
	}

	private function gensalt_private( $input ) {
		$output = '$P$';

		$output .= $this->itoa64[min(13, 30)];
		$output .= $this->encode64($input, 6);

		return $output;
	}

	private function crypt_private( $password, $setting ) {
		$output = '*0';

		if ( substr($setting, 0, 2) == $output ) {
			$output = '*1';
		}

		$id = substr($setting, 0, 3);

		# We use "$P$", phpBB3 uses "$H$" for the same thing
		if ( $id != '$P$' && $id != '$H$' ) {
			return $output;
		}

		$count_log2 = strpos($this->itoa64, $setting[3]);

		if ( $count_log2 < 7 || $count_log2 > 30 ) {
			return $output;
		}

		$count = 1 << $count_log2;

		$salt = substr($setting, 4, 8);
		
		if ( strlen($salt) != 8 ) {
			return $output;
		}

		$hash = md5($salt . $password, TRUE);

		do {
			$hash = md5($hash . $password, TRUE);
		} while (--$count);

		$output = substr($setting, 0, 12);
		$output .= $this->encode64($hash, 16);

		return $output;
	}

	private function gensalt_extended( $input ) {
		$count_log2 = min(16, 24);

		# This should be odd to not reveal weak DES keys, and the
		# maximum valid value is (2**24 - 1) which is odd anyway.
		$count = (1 << $count_log2) - 1;

		$output = '_';
		$output .= $this->itoa64[$count & 0x3f];
		$output .= $this->itoa64[($count >> 6) & 0x3f];
		$output .= $this->itoa64[($count >> 12) & 0x3f];
		$output .= $this->itoa64[($count >> 18) & 0x3f];

		$output .= $this->encode64($input, 3);

		return $output;
	}

	private function gensalt_blowfish( $input ) {
		# This one needs to use a different order of characters and a
		# different encoding scheme from the one in encode64() above.
		# We care because the last character in our encoded string will
		# only represent 2 bits.  While two known implementations of
		# bcrypt will happily accept and correct a salt string which
		# has the 4 unused bits set to non-zero, we do not want to take
		# chances and we also do not want to waste an additional byte
		# of entropy.
		$itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		$output = '$2a$';
		$output .= chr(ord('0') + $this->iteration_count_log2 / 10);
		$output .= chr(ord('0') + $this->iteration_count_log2 % 10);
		$output .= '$';

		$i = 0;

		do {
			$c1 = ord($input[$i++]);
			$output .= $itoa64[$c1 >> 2];
			$c1 = ($c1 & 0x03) << 4;

			if ( $i >= 16 ) {
				$output .= $itoa64[$c1];
				break;
			}

			$c2 = ord($input[$i++]);
			$c1 |= $c2 >> 4;
			$output .= $itoa64[$c1];
			$c1 = ($c2 & 0x0f) << 2;

			$c2 = ord($input[$i++]);
			$c1 |= $c2 >> 6;
			$output .= $itoa64[$c1];
			$output .= $itoa64[$c2 & 0x3f];
		} while (1);

		return $output;
	}

	public function generateHash( $password ) {
		if ( strlen( $password ) > 4096 ) {
			return '*';
		}

		$random = $this->get_random_bytes(6);
		$hash = $this->crypt_private($password, $this->gensalt_private($random));

		if ( strlen($hash) == 34 ) {
			return $hash;
		}

		# Returning '*' on error is safe here, but would _not_ be safe
		# in a crypt(3)-like function used _both_ for generating new
		# hashes and for validating passwords against existing hashes.
		return '*';
	}

	public function validPassword( $password, $password_hash ) {
		if ( strlen( $password ) > 4096 ) {
			return false;
		}

		$hash = $this->crypt_private($password, $password_hash);
		
		return $hash === $password_hash;
	}
}