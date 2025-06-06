<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if (! $this->session->userdata('uid'))
            redirect('Login');
        $this->load->model('Home_model');
    }

    public function index()
    {
        $result = $this->Home_model->get_column_names_as_empty_object();
        $column_names = [];

        if (!empty($result['totalRecords']) && isset($result['totalRecords'][0])) {
            $column_names = array_keys((array) $result['totalRecords'][0]);
        }

        $data['column_names'] = $column_names;

        $this->load->view('user_details', $data);
    }

    public function All_GP_data()
    {
        $request = $_POST;

        $column = $request['column'] ?? '';
        $value = $request['value'] ?? '';
        $start = $request['start'] ?? 0;
        $length = $request['length'] ?? 10;
        $orderColumn = $request['order'][0]['column'] ?? 0;
        $orderDir = $request['order'][0]['dir'] ?? 'desc';

        $columns = [
            0 => 'STATE_NAME',
            1 => 'District',
            2 => 'Block',
            3 => 'GP_Name',
            4 => 'GP_Code',
            5 => 'Latitude',
            6 => 'Longitude',
            7 => 'DoT_Nodal_Officer_Name',
            8 => 'DoT_Nodal_Contact_No',
            9 => 'No_of_FTTH_Connections'
        ];

        $orderColumnName = $columns[$orderColumn];

        $totalRecords = $this->Home_model->count_all_gp();
        $filteredRecords = $this->Home_model->count_filtered_gp($column, $value);
        // $SearchRecords = $this->Home_model->count_search_gp($searchValue);
        $data = $this->Home_model->get_gp_data($start, $length, $column, $value, $orderColumnName, $orderDir);

        $output = [];
        $i = $start + 1;
        foreach ($data as $row) {
            $output[] = [
                'sl_no' => $i++,
                'STATE_NAME' => $row->STATE_NAME,
                'District' => $row->District,
                'Block' => $row->Block,
                'GP_Name' => $row->GP_Name,
                'GP_Code' => $row->GP_Code,
                'Latitude' => $row->Latitude,
                'Longitude' => $row->Longitude,
                'DoT_Nodal_Officer_Name' => $row->DoT_Nodal_Officer_Name,
                'DoT_Nodal_Contact_No' => $row->DoT_Nodal_Contact_No,
                'No_of_FTTH_Connections' => $row->No_of_FTTH_Connections,
                'id' => $row->id,
            ];
        }

        echo json_encode([
            "draw" => intval($request['draw']),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $output
        ]);
    }


    public function Add_Gp_data()
    {
        $this->load->library('form_validation');

        // Validation rules
        $this->form_validation->set_rules('Zone', 'Zone', 'required');
        $this->form_validation->set_rules('STATE_NAME', 'State Name', 'required');
        $this->form_validation->set_rules('District', 'District', 'required');
        $this->form_validation->set_rules('Block', 'Block', 'required');
        $this->form_validation->set_rules('GP_Name', 'GP Name', 'required');
        $this->form_validation->set_rules('GP_Code', 'GP Code', 'required');
        $this->form_validation->set_rules('Latitude', 'Latitude', 'required');
        $this->form_validation->set_rules('Longitude', 'Longitude', 'required');
        $this->form_validation->set_rules('DoT_Nodal_Officer_Name', 'DoT Nodal Officer Name', 'required');
        $this->form_validation->set_rules('DoT_Nodal_Contact_No', 'DoT Nodal Contact No', 'required|numeric|min_length[10]');
        $this->form_validation->set_rules('No_of_FTTH_Connections', 'No of FTTH Connections', 'required|numeric');
        $this->form_validation->set_rules('Avail_FPO', 'Availability of FPO', 'required');
        $this->form_validation->set_rules('Avail_PACS', 'Availability of PACS', 'required');
        $this->form_validation->set_rules('Avail_PanchayatBhawan', 'Availability of Panchayat Bhawan', 'required');
        $this->form_validation->set_rules('Avail_ClinicForMentallyChallenged', 'Availability of Clinic for Mentally Challenged', 'required');
        $this->form_validation->set_rules('Avail_SoilTestingCenter', 'Availability of Soil Testing Center', 'required');
        $this->form_validation->set_rules('Avail_FertilizerShop', 'Availability of Fertilizer Shop', 'required');
        $this->form_validation->set_rules('Avail_PoultaryDev', 'Availability of Poultry Development', 'required');
        $this->form_validation->set_rules('Avail_GoataryDev', 'Availability of Goatary Development', 'required');
        $this->form_validation->set_rules('Avail_PigeryDev', 'Availability of Pigery Development', 'required');
        $this->form_validation->set_rules('Avail_VeterinaryHospital', 'Availability of Veterinary Hospital', 'required');
        $this->form_validation->set_rules('Avail_FishFarming', 'Availability of Fish Farming', 'required');
        $this->form_validation->set_rules('Avail_AquacultureExtensionFacility', 'Availability of Aquaculture Extension Facility', 'required');
        $this->form_validation->set_rules('Avail_Bank', 'Availability of Bank', 'required');
        $this->form_validation->set_rules('Avail_PostOffice', 'Availability of Post Office', 'required');
        $this->form_validation->set_rules('Avail_PDS', 'Availability of PDS', 'required');
        $this->form_validation->set_rules('Avail_PublicLibrary', 'Availability of Public Library', 'required');
        $this->form_validation->set_rules('Avail_CottageSmallScaleUnits', 'Availability of Cottage/Small Scale Units', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
            return;
        }

        $id = $this->input->post('id');

        $data = array(
            'Zone' => $this->input->post('Zone'),
            'STATE_NAME' => $this->input->post('STATE_NAME'),
            'District' => $this->input->post('District'),
            'Block' => $this->input->post('Block'),
            'GP_Name' => $this->input->post('GP_Name'),
            'GP_Code' => $this->input->post('GP_Code'),
            'Latitude' => $this->input->post('Latitude'),
            'Longitude' => $this->input->post('Longitude'),
            'DoT_Nodal_Officer_Name' => $this->input->post('DoT_Nodal_Officer_Name'),
            'DoT_Nodal_Contact_No' => $this->input->post('DoT_Nodal_Contact_No'),
            'No_of_FTTH_Connections' => $this->input->post('No_of_FTTH_Connections'),
            'Avail_FPO' => $this->input->post('Avail_FPO'),
            'Avail_PACS' => $this->input->post('Avail_PACS'), // Removed sha1
            'Avail_PanchayatBhawan' => $this->input->post('Avail_PanchayatBhawan'),
            'Avail_ClinicForMentallyChallenged' => $this->input->post('Avail_ClinicForMentallyChallenged'),
            'Avail_SoilTestingCenter' => $this->input->post('Avail_SoilTestingCenter'),
            'Avail_FertilizerShop' => $this->input->post('Avail_FertilizerShop'),
            'Avail_PoultaryDev' => $this->input->post('Avail_PoultaryDev'),
            'Avail_GoataryDev' => $this->input->post('Avail_GoataryDev'),
            'Avail_PigeryDev' => $this->input->post('Avail_PigeryDev'),
            'Avail_VeterinaryHospital' => $this->input->post('Avail_VeterinaryHospital'),
            'Avail_FishFarming' => $this->input->post('Avail_FishFarming'),
            'Avail_AquacultureExtensionFacility' => $this->input->post('Avail_AquacultureExtensionFacility'),
            'Avail_Bank' => $this->input->post('Avail_Bank'),
            'Avail_PostOffice' => $this->input->post('Avail_PostOffice'),
            'Avail_PDS' => $this->input->post('Avail_PDS'),
            'Avail_PublicLibrary' => $this->input->post('Avail_PublicLibrary'),
            'Avail_CottageSmallScaleUnits' => $this->input->post('Avail_CottageSmallScaleUnits')
        );

        if (empty($id)) {
            $this->Home_model->Add($data);
            echo "Successfully Added";
        } else {
            $this->Home_model->Update($id, $data); // Fixed order
            echo "Successfully Updated";
        }
    }



    public function Edit_Gp_data($id) {
        $data = $this->Home_model->gp_wise_edit($id);
        echo json_encode($data); // Return JSON to frontend
    }


    public function export_to_csv()
    {
        // Fetch data
        $data = $this->Home_model->get_all_gp_data();

        // Set headers for CSV file download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="gp_data.csv"');
        header('Cache-Control: max-age=0');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Write header row
        fputcsv($output, [
            'SL NO', 'State', 'District', 'Block', 'GP Name', 'GP CODE',
            'Latitude', 'Longitude', 'Nodal Officer', 'Contact No.', 'FTTH'
        ]);

        // Write data rows
        $sl_no = 1;
        foreach ($data as $gp) {
            fputcsv($output, [
                $sl_no++,
                $gp->STATE_NAME,
                $gp->District,
                $gp->Block,
                $gp->GP_Name,
                $gp->GP_Code,
                $gp->Latitude,
                $gp->Longitude,
                $gp->DoT_Nodal_Officer_Name,
                $gp->DoT_Nodal_Contact_No,
                $gp->No_of_FTTH_Connections
            ]);
        }

        fclose($output);
        exit;
    }


    //csv Function
    public function csv_view()
    {
        $this->load->view('csv_upload');
    }

    // public function upload_csv() {
    //     if (isset($_FILES['csv_name']['name']) && $_FILES['csv_name']['error'] == 0) {
    //         $file_name = $_FILES['csv_name']['name'];
    //         $temp_path = $_FILES['csv_name']['tmp_name'];

    //         // Sanitize table name from file name
    //         $table_name = pathinfo($file_name, PATHINFO_FILENAME);
    //         $table_name = preg_replace('/[^A-Za-z0-9]+/', '_', $table_name); // replace spaces/symbols with _
    //         $table_name = strtolower($table_name);

    //         // Move the uploaded file to a permanent location
    //         $upload_path = FCPATH . 'uploads/';
    //         if (!is_dir($upload_path)) {
    //             mkdir($upload_path, 0755, true);
    //         }
    //         $new_path = $upload_path . $file_name;
    //         move_uploaded_file($temp_path, $new_path);

    //         // Call model to create table and load data
    //         $result = $this->Home_model->create_and_insert_from_csv($table_name, $new_path);

    //         if ($result === true) {
    //             echo "Table `$table_name` created and data inserted successfully.";
    //         } else {
    //             echo "Error: " . $result;
    //         }

    //     } else {
    //         echo "File upload failed.";
    //     }
    // }

    public function uploadCsv()
    {
        if (isset($_FILES['csv_name']) && $_FILES['csv_name']['error'] == 0) {
            $fileTmpPath = $_FILES['csv_name']['tmp_name'];
            $fileName = $_FILES['csv_name']['name'];

            // Clean table name
            $tableName = strtolower(pathinfo($fileName, PATHINFO_FILENAME));
            $tableName = preg_replace('/[^a-z0-9]+/', '_', $tableName); // only letters, numbers, underscores

            $result = $this->Home_model->createTableAndInsertData($fileTmpPath, $tableName);

            if ($result) {
                $this->session->set_flashdata('message', "Table '$tableName' created and CSV data inserted successfully!");
            } else {
                $this->session->set_flashdata('message', "Something went wrong!");
            }
            redirect('Home/csv_view');
        } else {
            echo "File upload error.";
        }
    }

    public function dynamics_input()
    {
        $data['vender_salary'] = $this->Home_model->vender_salary();
        $data['manual_columns'] = $this->Home_model->manual_columns();
        // echo '<pre>';print_r($data);die('hiis');
        $this->load->view('dynamic_example_details', $data);
    }


}
