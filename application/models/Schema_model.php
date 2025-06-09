<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schema_model extends CI_Model {

    // public function createDynamicTableTsi($table_name, $columns) {
    //     $correc_table = str_replace(' ', '_', $table_name);
    //     $table_name = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);
    //     $query = "CREATE TABLE `$table_name` (
    //         `id` INT AUTO_INCREMENT PRIMARY KEY,";
    //     foreach ($columns as $col) {

    //         $col_name = str_replace('>', 'Greater_', $col['name']);
    //         $col_name = str_replace(' ', '_', $col_name);
    //         $col_name = preg_replace('/[^a-zA-Z0-9_]/', '', $col_name);
    //         $data_type = $this->db->escape_str($col['type']);
    //         $query .= "`$col_name` $data_type,";
    //     }
    //     $query = rtrim($query, ',') . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    //     return $this->db->query($query);
    // }

    public function createDynamicTable($table_name, $columns) 
    {
        // Sanitize and normalize table name
        $table_name = str_replace(' ', '_', $table_name);
        $table_name = preg_replace('/[^a-zA-Z0-9_]/', '', $table_name);

        // Start query with auto-increment ID
        $query = "CREATE TABLE `$table_name` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,";

        // Loop through each column
        foreach ($columns as $col) {
            $col_name = str_replace('>', 'Greater_', $col['name']);
            $col_name = str_replace(' ', '_', $col_name);
            $col_name = preg_replace('/[^a-zA-Z0-9_]/', '', $col_name);

            $data_type = $this->db->escape_str($col['type']);
            $comment = isset($col['comment']) ? $this->db->escape_str($col['comment']) : '';

            // Append column with comment
            $query .= "`$col_name` $data_type";

            if (!empty($comment)) {
                $query .= " COMMENT '$comment'";
            }

            $query .= ",";
        }

        // Remove last comma and close query
        $query = rtrim($query, ',') . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        // Execute and return result
        return $this->db->query($query);
    }


    public function get_all_tables() {
        $query = $this->db->query("SHOW TABLES");
        $tables = [];
        foreach ($query->result_array() as $row) {
            $tables[] = array_values($row)[0];
        }
        return $tables;
    }

    public function get_columns($table_name) {
        return $this->db->query("SHOW COLUMNS FROM `$table_name`")->result_array();
    }

    public function alter_table($data) {
        $table = $data['table_name'];

        if (isset($data['new_column_name'])) {
            $data['new_column_name'] = preg_replace('/[^A-Za-z0-9]+/', '_', trim($data['new_column_name']));
        }

        if ($data['action_type'] === 'edit') {
            $sql = "ALTER TABLE `$table` CHANGE `{$data['column_name']}` `{$data['new_column_name']}` {$data['new_column_type']}";
        } elseif ($data['action_type'] === 'delete') {
            $sql = "ALTER TABLE `$table` DROP COLUMN `{$data['column_name']}`";
        } elseif ($data['action_type'] === 'add') {
            $sql = "ALTER TABLE `$table` ADD `{$data['new_column_name']}` {$data['new_column_type']} AFTER `{$data['after_column']}`";
        }

        return $this->db->query($sql);
    }





    // public function get_table_columns($table) {
    //     return $this->db->query("SHOW COLUMNS FROM `$table`")->result();
    // }
    public function get_table_columns($table) {
        return $this->db->query("
            SELECT 
                COLUMN_NAME AS Field,
                COLUMN_TYPE AS Type,
                COLUMN_COMMENT AS Comment
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME = '$table'
        ")->result();
    }


    // public function get_table_rows($table) {
    //     return $this->db->query("SELECT * FROM `$table` LIMIT 100")->result();
    // }

    public function get_table_rows($table) {
        return $this->db->query("SELECT * FROM `$table`")->result();
    }



    /////////////////// Dynamic Add And Edit //////////////////////
    // public function get_column_names($table) {
        
    //     $query = $this->db->query("SHOW COLUMNS FROM `$table`");
    //     return array_column($query->result_array(), 'Field');
    // }

    public function get_column_names($table) {
        $query = $this->db->query("SHOW FULL COLUMNS FROM `$table`");
        return $query->result(); 
    }


    public function get_row_by_id($table, $id) {
        return $this->db->get_where($table, ['id' => $id])->row_array();
    }


    public function delete_row_by_id($table, $id) 
    {
        return $this->db->where('id', $id)->delete($table);
    }


    


}
