<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Home_model extends CI_Model
{

    public function insert($data)
    {
        $inserted = $this->db->insert('tblusers', $data);
        return $inserted;
    }


    public function get_gp_data($start, $length, $column, $value, $orderColumn, $orderDir)
    {
        $this->db->select('id,STATE_NAME, District, Block, GP_Name, GP_Code, Latitude, Longitude, DoT_Nodal_Officer_Name, DoT_Nodal_Contact_No, No_of_FTTH_Connections')
            ->from('network_details');

        if (!empty($column) && !empty($value)) {
            $this->db->like($column, $value);
        }

        $this->db->order_by($orderColumn, $orderDir)
            ->limit($length, $start);

        return $this->db->get()->result();
    }

    public function count_filtered_gp($column, $value)
    {
        $this->db->from('network_details');

        if (!empty($column) && !empty($value)) {
            $this->db->like($column, $value);
        }

        return $this->db->count_all_results();
    }



    public function count_all_gp()
    {
        return $this->db->count_all('network_details');
    }

    public function count_search_gp($search)
    {
        $this->db->from('network_details');

        if (!empty($search)) {
            $this->db->group_start()
                ->like('STATE_NAME', $search)
                ->or_like('District', $search)
                ->or_like('Block', $search)
                ->or_like('GP_Name', $search)
                ->or_like('GP_Code', $search)
                ->group_end();
        }

        return $this->db->count_all_results();
    }

    public function get_column_names_as_empty_object()
    {
        $fields = $this->db->list_fields('network_details');

        // Remove 'id'
        $fields = array_filter($fields, fn($f) => $f !== 'id');

        $obj = new stdClass();
        foreach ($fields as $field) {
            $obj->$field = null;
        }

        return ['totalRecords' => [$obj]];
    }

    public function gp_wise_edit($id) {
        return $this->db->get_where('network_details', ['id' => $id])->row_array(); // Use row_array
    }


    public function Add($data)
    {
        return $this->db->insert('network_details', $data);
    }

    public function Update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('network_details', $data);
    }

    public function get_all_gp_data()
    {
        return $this->db->get('network_details')->result(); // Replace 'gp_table' with your actual table name
    }


    // CSV to SQL conversion
    public function create_and_insert_from_csv($table_name, $csv_path) {
        $this->load->database();

        // Get first row (header) to define columns
        $file = fopen($csv_path, 'r');
        if (!$file) {
            return "Unable to read the CSV file.";
        }

        $columns = fgetcsv($file);
        if (!$columns) {
            return "CSV has no header row.";
        }

        // Clean and prepare column names
        $columns = array_map(function($col) {
            return preg_replace('/[^A-Za-z0-9]+/', '_', strtolower(trim($col)));
        }, $columns);

        // Drop table if exists
        $this->db->query("DROP TABLE IF EXISTS `$table_name`");

        // Build CREATE TABLE SQL
        $create_sql = "CREATE TABLE `$table_name` (";
        foreach ($columns as $col) {
            $create_sql .= "`$col` TEXT, ";
        }
        $create_sql = rtrim($create_sql, ', ') . ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

        // Execute create table
        if (!$this->db->query($create_sql)) {
            return "Failed to create table: " . $this->db->error()['message'];
        }

        // Build LOAD DATA INFILE SQL
        $escaped_path = addslashes($csv_path);
        $col_list = implode(',', array_map(function($col) { return "`$col`"; }, $columns));
        $load_sql = "
            LOAD DATA LOCAL INFILE '$escaped_path'
            INTO TABLE `$table_name`
            FIELDS TERMINATED BY ',' 
            OPTIONALLY ENCLOSED BY '\"'
            LINES TERMINATED BY '\n'
            IGNORE 1 ROWS
            ($col_list)
        ";

        // Enable LOCAL INFILE if needed
        $this->db->query("SET GLOBAL local_infile = 1");

        if (!$this->db->query($load_sql)) {
            return "LOAD DATA failed: " . $this->db->error()['message'];
        }

        return true;
    }


    public function createTableAndInsertData($filePath, $tableName)
    {
        $handle = fopen($filePath, "r");
        if ($handle === FALSE) return false;

        $fields = fgetcsv($handle); // Get headers
        if (!$fields) return false;

        $columns = [];

        // Add ID column first
        $columns[] = "`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY";

        // Sanitize and add columns from CSV headers
        foreach ($fields as $field) {
            $cleaned = strtolower(preg_replace('/[^a-z0-9_]/i', '_', $field));
            $columns[] = "`$cleaned` TEXT";
        }

        // Create table SQL
        $createSQL = "CREATE TABLE IF NOT EXISTS `$tableName` (" . implode(",", $columns) . ")";
        $this->db->query($createSQL);

        // Insert CSV data (skip header)
        while (($data = fgetcsv($handle)) !== FALSE) {
            // Escape values for safe SQL insertion
            $escaped = array_map(function($item) {
                return $this->db->escape($item);
            }, $data);

            // Build INSERT query (exclude `id` as it auto-increments)
            $insertSQL = "INSERT INTO `$tableName` VALUES (NULL, " . implode(",", $escaped) . ")";
            $this->db->query($insertSQL);
        }

        fclose($handle);
        return true;
    }


    public function validatelogin($emailid, $password, $status)
    {
        $query = $this->db->where(['emailId' => $emailid, 'userPassword' => $password]);
        $account = $this->db->get('tblusers')->row();

        if ($account != NULL) {
            if ($account->isActive == $status) {
                return $account->id;
            } else {
                // no redirect here, handle from controller
                return NULL;
            }
        }
        return NULL;
    }


    //////////////////////  Others ////////////////////
    public function salary_details()
    {
        $this->db->from('network_details');

        if (!empty($column) && !empty($value)) {
            $this->db->like($column, $value);
        }

        return $this->db->count_all_results();
    }

    public function vender_salary()
    {
        return $this->db->get('vender_salary')->result(); // Replace 'gp_table' with your actual table name
    }

    public function manual_columns()
    {
        $sql = "SELECT COLUMN_NAME
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = 'vender_salary'
                AND TABLE_SCHEMA = DATABASE()
                AND COLUMN_COMMENT = 'Manual'";
        
        return $this->db->query($sql)->result();
    }


    public function insert_user($data) {
            return $this->db->insert('tblusers', $data);
    }



}
