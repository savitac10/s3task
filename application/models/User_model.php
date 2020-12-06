<?php 

class User_model extends CI_Model {

    protected $User_table_name = "users";
    protected $File_table_name = "files";

    /**
     * Insert User Data in Database
     * @param: {array} userData
     */
    public function insert_user($userData) {
        try {
            $this->db->trans_begin();
            $this->db->insert($this->User_table_name, $userData);
            $last_id = $this->db->insert_id();
            if (!$this->db->affected_rows()) {
                throw new Exception("Error Processing Request", 1);
            }
            $this->db->trans_commit();
            return $last_id;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', $e->getMessage());
            return false;
        }
    }

    /**
     * Check User Login in Database
     * @param: {array} userData
     */
    public function check_login($userData) {

        try {
        /**
         * First Check Email is Exists in Database
         */
            $query = $this->db->get_where($this->User_table_name, array('email' => $userData['email']));
            if ($this->db->affected_rows() > 0) {
                $password = $query->row('password');
                /**
                 * Check Password Hash 
                 */
                if (password_verify($userData['password'], $password) === TRUE) {
                    /**
                     * Password and Email Address Valid
                     */
                    return [
                        'status' => TRUE,
                        'data' => $query->row(),
                    ];
                } else {
                    return ['status' => FALSE,'data' => FALSE];
                }
            }
        } catch (Exception $e) {
            return ['status' => FALSE,'data' => FALSE, 'message' => $e->getMessage()];
        }
    }

    /**
     * Insert File Data in Database
     * @param: {array} fileData
     */
    public function insert_file($fileData) {
        try {
            $this->db->trans_begin();
            $this->db->insert($this->File_table_name, $fileData);
            $last_id = $this->db->insert_id();
            if (!$this->db->affected_rows()) {
                throw new Exception("Error Processing Request", 1);
            }
            $this->db->trans_commit();
            return $last_id;
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return false;
        }
        return $this->db->insert($this->File_table_name, $fileData);
    }

    /**
     * Get Files List in Database
     * @param: {array} params
     */
    public function get_files($params) {
        return $this->db->select('id,user_id,file_link,created_at')->where('user_id',$params['user_id'])->order_by('id','desc')->get($this->File_table_name)->result();
    }
}
