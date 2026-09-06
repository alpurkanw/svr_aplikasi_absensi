<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fingerprint_templates extends CI_Controller
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

        $rows = $this->db->select('e.employee_code, e.name AS employee_name, t.finger_slot, t.template_blob, t.device_sn')
            ->from('employee_fingerprint_templates t')
            ->join('employees e', 'e.id = t.employee_id')
            ->where('e.is_active', 1)
            ->where('t.is_active', 1)
            ->order_by('e.employee_code', 'ASC')
            ->order_by('t.finger_slot', 'ASC')
            ->get()->result_array();

        $templates = array();
        foreach ($rows as $row) {
            $templates[] = array(
                'employee_code' => $row['employee_code'],
                'employee_name' => $row['employee_name'],
                'finger_slot' => (int) $row['finger_slot'],
                'device_sn' => $row['device_sn'],
                'template_base64' => base64_encode($row['template_blob']),
            );
        }

        return api_json_response(200, array('success' => true, 'data' => $templates));
    }

    public function save()
    {
        if (!$this->api_auth->is_authenticated()) {
            return api_error(401, 'Unauthorized');
        }

        $payload = api_request_json();
        if ($payload === null) {
            return;
        }

        foreach (array('employee_code', 'finger_slot', 'template_base64') as $field) {
            if (!isset($payload[$field]) || (is_string($payload[$field]) && trim($payload[$field]) === '')) {
                return api_error(422, $field . ' is required');
            }
        }

        $employee = $this->db->where('employee_code', trim($payload['employee_code']))
            ->where('is_active', 1)->get('employees')->row_array();
        if (!$employee) {
            return api_error(404, 'Employee not found');
        }

        $finger_slot = (int) $payload['finger_slot'];
        if ($finger_slot < 1 || $finger_slot > 3) {
            return api_error(422, 'finger_slot must be between 1 and 3');
        }

        $template_blob = base64_decode((string) $payload['template_base64'], true);
        if ($template_blob === false || strlen($template_blob) === 0) {
            return api_error(422, 'template_base64 is invalid');
        }
        if (strlen($template_blob) > 16 * 1024 * 1024) {
            return api_error(422, 'Fingerprint template is too large');
        }

        $values = array(
            'employee_id' => (int) $employee['id'],
            'finger_slot' => $finger_slot,
            'template_blob' => $template_blob,
            'device_sn' => isset($payload['device_sn']) && trim((string) $payload['device_sn']) !== ''
                ? trim($payload['device_sn']) : null,
            'is_active' => 1,
        );
        $existing = $this->db->where('employee_id', $values['employee_id'])
            ->where('finger_slot', $finger_slot)
            ->get('employee_fingerprint_templates')->row_array();
        if ($existing) {
            $this->db->where('id', $existing['id'])->update('employee_fingerprint_templates', $values);
            $template_id = (int) $existing['id'];
        } else {
            $this->db->insert('employee_fingerprint_templates', $values);
            $template_id = (int) $this->db->insert_id();
        }

        return api_json_response(200, array('success' => true, 'data' => array(
            'id' => $template_id,
            'employee_code' => $employee['employee_code'],
            'finger_slot' => $finger_slot,
        )));
    }

    public function delete()
    {
        if (!$this->api_auth->is_authenticated()) {
            return api_error(401, 'Unauthorized');
        }
        $payload = api_request_json();
        if ($payload === null || !isset($payload['employee_code'])) {
            return api_error(422, 'employee_code is required');
        }
        $employee = $this->db->where('employee_code', trim($payload['employee_code']))
            ->get('employees')->row_array();
        if (!$employee) {
            return api_error(404, 'Employee not found');
        }
        $this->db->where('employee_id', (int) $employee['id'])->delete('employee_fingerprint_templates');
        return api_json_response(200, array('success' => true));
    }
}
