<?php defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        /**
         * Load User Model
         */
        $this->load->model('User_model', 'UserModel');
        $this->form_validation->set_error_delimiters('<p class="invalid-feedback">', '</p>');
    }

    public function index()
    {
        $this->load->view('welcome_message');
    }

    /**
     * User Registration
     */
    public function registration()
    {
        if (!empty($this->session->userdata('user_id'))) {
            redirect('user/profile');
        }

        $this->form_validation->set_rules('name', 'Full Name', 'required');

        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|is_unique[users.email]', [
            'is_unique' => 'The %s already exists. Please use a different email',
        ]); // // Unique Field
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

        if ($this->form_validation->run() == false) {
            $data['page_title'] = "User Registration";
            $data['page']       = 'user/registration';
            $this->load->view('template', $data);
        } else {
            $insert_data = array(
                'name'       => $this->input->post('name', true),
                'email'      => $this->input->post('email', true),
                'password'   => password_hash($this->input->post('password', true), PASSWORD_DEFAULT),
                'created_at' => time(),
                'update_at'  => time(),
            );

            /**
             * Load User Model to insert user data
             */
            $result = $this->UserModel->insert_user($insert_data);

            if ($result == true) {

                $this->session->set_flashdata('success_flashData', 'You have registered successfully.');
                redirect('user/registration');

            } else {

                $this->session->set_flashdata('error_flashData', 'Invalid Registration.');
                redirect('user/registration');

            }
        }
    }

    /**
     * User Login
     */
    public function login()
    {
        if (!empty($this->session->userdata('user_id'))) {
            redirect('user/profile');
        }

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == false) {
            $data['page_title'] = "User Login";
            $data['page']       = 'user/login';
            $this->load->view('template', $data);
        } else {

            $login_data = array(
                'email'    => $this->input->post('email', true),
                'password' => $this->input->post('password', true),
            );
            /**
             * Load User Model to check the user
             */
            $result = $this->UserModel->check_login($login_data);

            if (!empty($result['status']) && $result['status'] === true) {

                /**
                 * Create Session
                 * -----------------
                 * First Load Session Library
                 */
                $session_array = array(
                    'user_id'    => $result['data']->id,
                    'user_name'  => $result['data']->name,
                    'user_email' => $result['data']->email,
                );

                $this->session->set_userdata($session_array);

                $this->session->set_flashdata('success_flashData', 'Login Success');
                redirect('user/profile');

            } else {

                $this->session->set_flashdata('error_flashData', 'Invalid Email/Password.');
                redirect('user/login');
            }
        }
    }

    /**
     * User profile
     */
    public function profile()
    {  
        /**
         * Load User Model to get the user files
         */
        $data['files'] = $this->UserModel->get_files(array('user_id' => $this->session->userdata('user_id')));
        $data['page_title'] = "User Profile";
        $data['page']       = 'user/profile';
        $this->load->view('template', $data);
    }

    public function file_upload()
    {
        /**
         * Call s3_upload function to uplpad the file in AWS s3
         */
        $result =$this->s3_upload($_FILES['doc_file']);
        if($result){
            /**
             * Load User Model to insert the upoaded file into DB
             */
            $insert_data = array(
                'user_id' => $this->session->userdata('user_id'),
                'file_link'=> $result,
                'created_at'=>date('y-m-d H:i:s'),
            );
            $upload_result = $this->UserModel->insert_file($insert_data);
            if($upload_result){
                $this->session->set_flashdata('success_flashData', 'File uploaded successfully');
                redirect('user/profile');
            }
            else{
                $this->session->set_flashdata('error_flashData', 'Failed to upload file');
                redirect('user/profile');
            }
        }
    }

    public function s3_upload($name = '')
    {
        // Loading aws configuration
        require FCPATH . 'application/third_party/vendor/autoload.php';

        //$name   = $_FILES['doc_file']

        // Creating Object for s3 client
        $client = new \Aws\S3\S3Client([
            'version'     => 'latest',
            'region'      => AWS_REGION,
            'credentials' => [
                'key'    => AWS_KEY,
                'secret' => AWS_SECRET,
            ],
        ]);

        // Wa want image extention for renaming.
        $filename = explode('.', $name["name"]);
        //echo "<pre>";print_r($filename);exit();
        // Here we are calling put object function for uploading data.
        try {
            $result = $client->putObject([
                'Bucket' => AWS_S3_NAME,
                'Key'    => "s3task-" . date('ymdhis') . mt_rand(10000, 999999) . "." . $filename[(count($filename) - 1)], // Renaming
                'Body'   => fopen($name["tmp_name"], 'r'), // Uplading
                'ACL'    => 'public-read',
            ]);
            $uploadFileName = $result->get('ObjectURL');

            //echo "<pre>";print_r($uploadFileName);exit();
        } catch (Exception $e) {
            $uploadFileName = false;
        }

        return $uploadFileName;
    }

    /**
     * User Logout
     */
    public function logout()
    {

        /**
         * Remove Session Data
         */
        $this->session->sess_destroy();

        redirect('user/login');
    }
}
