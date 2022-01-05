<?PHP
require_once('base_model.php');
class proton_mot_model extends base_model {

    public $select;
    public $idfield;
    public $table;
    public $full_table;

    public function __construct(){

		parent::__construct();
        $this->idfield = "motId";
        $this->table = "mot_game";

        //shortcuts
        $this->full_table = "`" . $this->db->database . "`." . $this->db->dbprefix . $this->table;
		$this->select = "SELECT * FROM " . $this->full_table . " ";

    }

}

?>
