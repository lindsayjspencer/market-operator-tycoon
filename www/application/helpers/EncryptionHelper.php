<?PHP

require_once APPPATH . 'vendor/autoload.php';

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;

class EncryptionHelper {
	
	public function loadEncryptionKeyFromConfig() {
		return Key::loadFromAsciiSafeString(getenv('ENC_KEY'));
	}

	public function encrypt($plaintext) {
		$key = $this->loadEncryptionKeyFromConfig();
		return Crypto::encrypt($plaintext, $key);
	}

	public function decrypt($ciphertext) {
		$key = $this->loadEncryptionKeyFromConfig();
		try {
			return Crypto::decrypt($ciphertext, $key);
		} catch (\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $e) {
			echo $e;
			return false;
		}
	}

}

?>