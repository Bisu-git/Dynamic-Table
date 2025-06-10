<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dynamic_table extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if (! $this->session->userdata('uid'))
            redirect('Login');
        $this->load->model('Schema_model');
    }

    public function index() {
        $data['tables'] = $this->Schema_model->get_all_tables();
        $this->load->view('dynamic_table', $data);
    }

    public function fetch_table_data() 
    {
        $table = $this->input->post('table');
        $columns = $this->Schema_model->get_table_columns($table);
        $rows = $this->Schema_model->get_table_rows($table);

        $html = '<table class="table table-bordered table-striped"><thead><tr>';
        
        // Column headers
        foreach ($columns as $col) {
            $html .= '<th>' . $col->Field . '</th>';
        }
        $html .= '<th>Action</th>'; // Add Action column
        $html .= '</tr></thead><tbody>';


        // $html = '<table class="table">';
        // $html .= '<thead class="thead-dark"><tr>';
        // foreach ($columns as $col) {
        //     $html .= '<th>' . $col->Field . '</th>';
        // }
        // $html .= '<th>Action</th>'; // Add Action column
        // $html .= '</tr></thead><tbody>';

        // Row data
        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($columns as $col) {
                $field = $col->Field;
                $html .= '<td>' . $row->$field . '</td>';
            }

            // Add Edit button with data attributes
            $html .= '<td>
                <button class="btn btn-sm btn-primary edit-btn" 
                        data-id="' . $row->id . '" 
                        data-table="' . $table . '">Edit</button>
                <button class="btn btn-sm btn-danger delete-btn ms-2" 
                    data-id="' . $row->id . '" 
                    data-table="' . $table . '">Delete</button>
            </td>';

            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        echo $html;
    }


    

    public function update_row() {
        $table = $this->input->post('table');
        $id = $this->input->post('id');
        $data = $this->input->post();
        unset($data['id'], $data['table']);

        $this->db->where('id', $id);
        $this->db->update($table, $data);
        // echo $this->db->last_query();
        echo "success";
    }

    public function insert_row() {
        $table = $this->input->post('table');
        $columns = $this->Schema_model->get_table_columns($table);
        $data = [];

        foreach ($columns as $col) {
            $field = $col->Field;
            if ($field !== 'id') {
                $data[$field] = $this->input->post($field);
            }
        }

        $this->db->insert($table, $data);
        echo "success";
    }

    public function get_row_data() {
        $id = $this->input->post('id');
        $table = $this->input->post('table');

        $columns = $this->Schema_model->get_column_names($table);
        $row = $this->Schema_model->get_row_by_id($table, $id);
        // echo '<pre>';
        // print_r($columns);
        // print_r($row);
        // die('Hii');

        echo json_encode(['columns' => $columns, 'row' => $row]);
    }

    public function create_add_input_modal()
    {
        $table = $this->input->post('table');
        $columns = $this->Schema_model->get_table_columns($table);
        // echo '<pre>';print_r($columns);die('Hii');

        $html = '';
        foreach ($columns as $col) {
            if ($col->Field !== 'id') {
                $isCalculated = (strpos($col->Comment, 'Calculated') === 0);
                $dataAttr = $isCalculated ? 'data-calculated="true" data-expression="' . htmlspecialchars($col->Comment) . '"' : '';

                $readonly = $isCalculated ? 'readonly' : '';
                $oninput = !$isCalculated ? 'oninput="calculate_values()"' : '';

                $html .= '<div class="mb-3">
                            <label class="form-label">' . ucfirst(str_replace('_', ' ', $col->Field)) . '</label>
                            <input type="text" id="' . $col->Field . '" class="form-control" name="' . $col->Field . '" 
                                ' . $dataAttr . ' ' . $readonly . ' ' . $oninput . '>
                        </div>';
            }
        }

        echo $html;
    }


    public function del_row_data() 
    {
        $id = $this->input->post('id');
        $table = $this->input->post('table');

        $deleted = $this->Schema_model->delete_row_by_id($table, $id);

        echo $deleted ? 'success' : 'error';
    }




    
}
