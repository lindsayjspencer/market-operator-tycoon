<?PHP

require_once APPPATH . 'helpers/EncryptionHelper.php';

class EnvironmentHelper {

	public function get($key) {

		// get the value from the environment
		$environmentVariable = $_ENV[$key];

		// using encryption
		$encryptionHelper = new EncryptionHelper();
		// decrypt the value
		$decryptedValue = $encryptionHelper->decrypt($environmentVariable);
		// return the value
		return $decryptedValue;
		
		// use raw value for now
		// return $environmentVariable;
	}

}

?>