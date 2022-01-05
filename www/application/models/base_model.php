<?PHP
class base_model extends CI_Model {

    public $select;
    public $idfield;
    public $table;
    public $full_table;

    public function __construct(){

		parent::__construct();
        $this->idfield = "";
        $this->table = "";

        //shortcuts
        $this->full_table = "`" . $this->db->database . "`." . $this->db->dbprefix . $this->table;
		$this->select = "SELECT * FROM " . $this->full_table . " ";

    }

    public function get($id=false) {

        $where = '';
        if($id) { $where = "WHERE " . $this->idfield . "='" . $id . "'"; }
        $query = $this->db->query($this->select . $where);
        if($query->num_rows()==0) { return false; } else {
            if($id) {
                return $query->row_array();
            } else {
                return $query->result_array();
            }
        }

    }

    public function get_plus($plus) {

        $query = $this->db->query($this->select . $plus);
        if($query->num_rows()==0) { return false; } else {
            return $query->result_array();
        }

    }

    public function get_by($where, $value=false) {

        if(!is_array($where)) { $where = array($where=>$value); }
        foreach($where as $field=>$value) {
            if(isset($query)) {
                $query .= " AND ";
            } else {
                $query = $this->select . "WHERE ";
            }
            if(is_null($value)) { if(substr($field, -1, 1)=="!") { $not = " NOT"; $field = substr($field, 0, -1); } else { $not = ""; } $query .= $field . " IS" . $not . " NULL"; }
            else { $query .= $field . "=" . $this->dvar($value); }
        }
        //echo $query;
        $query = $this->db->query($query); if($query->num_rows()!=0) { return $query->result_array(); } else { return false; }

    }

	public function update($data, $orig=[]) {
        if(isset($data[$this->idfield])) {

            if(count($orig) !== 0) {
                $update = [];
                foreach($orig as $key=>$value) {
                    if(isset($data[$key])) {
                        if($data[$key]!=$orig[$key]) {
                            $update[$key] = $data[$key];
                        }
                    }
                }
                $update[$this->idfield] = $data[$this->idfield];
                $data = $update;
            }


            $this->db->where( $this->idfield , $data[$this->idfield] );
            $this->db->update( $this->full_table , $data);
            return true;
        }
        return false;
	}

	public function set($data){
		$this->db->insert( $this->full_table , $data);
		return $this->db->insert_id();
	}

    public function delete($id){
        $this->db->where( $this->idfield , $id );
        $this->db->delete( $this->full_table );
        return true;
    }

    public function dvar($i) {
        if(is_null($i)) {
            return "null";
        }
        if(is_numeric($i)) {
            return $i;
        } else {
            if(substr($i, 0, 1)=="'" && substr($i, -1)=="'") {
                return $i;
            } else {
                return "'" . $i . "'";
            }
        }
    }

}

?>
