<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employees extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('api');
        $this->load->library('api_auth');
    }

    public function index()
    {
        if (!$this->api_auth->is_authenticated()) {
            return api_error(401, 'Unauthorized');
        }

        $employees = $this->db->select('id, employee_code, name, position_name, department_name, employment_status, is_active, updated_at')
            ->where('is_active', 1)
            ->order_by('employee_code', 'ASC')
            ->get('employees')->result_array();

        foreach ($employees as &$employee) {
            $employee['id'] = (int) $employee['id'];
            $employee['mappings'] = $this->db
                ->select('fingerprint_user_id, device_sn, is_active')
                ->where('employee_id', $employee['id'])
                ->where('is_active', 1)
                ->order_by('id', 'ASC')
                ->get('employee_fingerprint_mappings')->result_array();
        }
        unset($employee);

        return api_json_response(200, array('success' => true, 'data' => $employees));
    }

    public function mapping()
    {
        if (!$this->api_auth->is_authenticated()) {
            return api_error(401, 'Unauthorized');
        }

        $payload = api_request_json();
        if ($payload === null) {
            return;
        }
        foreach (array('employee_code', 'fingerprint_user_id') as $field) {
            if (!isset($payload[$field]) || !is_string($payload[$field]) || trim($payload[$field]) === '') {
                return api_error(422, $field . ' is required');
            }
        }

        $employee = $this->db->where('employee_code', trim($payload['employee_code']))
            ->where('is_active', 1)->get('employees')->row_array();
        if (!$employee) {
            return api_error(404, 'Employee not found');
        }

        $device_sn = isset($payload['device_sn']) && trim((string) $payload['device_sn']) !== ''
            ? trim($payload['device_sn']) : null;
        $query = $this->db->where('fingerprint_user_id', trim($payload['fingerprint_user_id']));
        if ($device_sn === null) {
            $query->where('device_sn IS NULL', null, false);
        } else {
            $query->where('device_sn', $device_sn);
        }
        $existing = $query->get('employee_fingerprint_mappings')->row_array();

        $values = array(
            'employee_id' => (int) $employee['id'],
            'fingerprint_user_id' => trim($payload['fingerprint_user_id']),
            'device_sn' => $device_sn,
            'is_active' => 1,
        );
        if ($existing) {
            $this->db->where('id', $existing['id'])->update('employee_fingerprint_mappings', $values);
            $mapping_id = (int) $existing['id'];
        } else {
            $this->db->insert('employee_fingerprint_mappings', $values);
            $mapping_id = (int) $this->db->insert_id();
        }

        return api_json_response(200, array(
            'success' => true,
            'data' => array_merge(array('id' => $mapping_id), $values),
        ));
    }
}
