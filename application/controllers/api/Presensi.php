<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Presensi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('api');
        $this->load->library('api_auth');
        $this->load->model('Presensi_model');
    }

    public function health()
    {
        api_json_response(200, array('status' => 'ok'));
    }

    public function connection()
    {
        if (!$this->api_auth->is_authenticated()) {
            return api_error(401, 'Unauthorized');
        }

        return api_json_response(200, array(
            'success' => true,
            'status' => 'ok',
            'message' => 'Connection authenticated',
        ));
    }

    public function upload()
    {
        if (!$this->api_auth->is_authenticated()) {
            return api_error(401, 'Unauthorized');
        }

        $payload = api_request_json();
        if ($payload === null) {
            return;
        }

        $validation = $this->validate_upload($payload);
        if ($validation !== true) {
            return api_error(422, $validation);
        }

        try {
            $result = $this->Presensi_model->create_idempotent(array(
                'user_id' => trim($payload['user_id']),
                'timestamp' => $this->normalize_timestamp($payload['timestamp']),
                'device_sn' => trim($payload['device_sn']),
                'template_hash' => $this->nullable_string($payload, 'template_hash'),
                'client_event_id' => trim($payload['client_event_id']),
            ));
        } catch (Throwable $exception) {
            log_message('error', 'Attendance database error: ' . $exception->getMessage());
            return api_error(500, 'Unable to save attendance');
        }

        $status = $result['created'] ? 201 : 200;
        $message = $result['created'] ? 'Attendance uploaded successfully' : 'Attendance already exists';
        return api_json_response($status, array(
            'success' => true,
            'message' => $message,
            'data' => $this->public_row($result['row']),
        ));
    }

    public function index()
    {
        if (!$this->api_auth->is_authenticated()) {
            return api_error(401, 'Unauthorized');
        }

        $page = max(1, (int) $this->input->get('page'));
        $limit = min(100, max(1, (int) ($this->input->get('limit') ?: 50)));
        $user_id = $this->input->get('user_id', true);
        $start_date = $this->input->get('start_date', true);
        $end_date = $this->input->get('end_date', true);

        foreach (array('start_date' => $start_date, 'end_date' => $end_date) as $name => $date) {
            if ($date !== null && $date !== '' && !$this->is_valid_date($date)) {
                return api_error(422, $name . ' must use YYYY-MM-DD format');
            }
        }

        try {
            $result = $this->Presensi_model->list_attendance($page, $limit, $user_id, $start_date ?: null, $end_date ?: null);
        } catch (Throwable $exception) {
            log_message('error', 'Attendance list database error: ' . $exception->getMessage());
            return api_error(500, 'Unable to retrieve attendance');
        }

        return api_json_response(200, array(
            'success' => true,
            'data' => $result['items'],
            'pagination' => array(
                'page' => $page,
                'limit' => $limit,
                'total' => $result['total'],
                'pages' => $result['total'] ? (int) ceil($result['total'] / $limit) : 0,
            ),
        ));
    }

    public function show($id)
    {
        if (!$this->api_auth->is_authenticated()) {
            return api_error(401, 'Unauthorized');
        }

        try {
            $row = $this->Presensi_model->find_by_id((int) $id);
        } catch (Throwable $exception) {
            log_message('error', 'Attendance lookup database error: ' . $exception->getMessage());
            return api_error(500, 'Unable to retrieve attendance');
        }
        if (!$row) {
            return api_error(404, 'Attendance not found');
        }

        return api_json_response(200, array('success' => true, 'data' => $this->public_row($row)));
    }

    private function validate_upload(array $payload)
    {
        foreach (array('user_id', 'timestamp', 'device_sn', 'client_event_id') as $field) {
            if (!isset($payload[$field]) || !is_string($payload[$field]) || trim($payload[$field]) === '') {
                return $field . ' is required';
            }
        }
        if (!$this->is_valid_timestamp($payload['timestamp'])) {
            return 'timestamp must use YYYY-MM-DD HH:MM:SS format';
        }
        if (strlen(trim($payload['user_id'])) > 100 || strlen(trim($payload['device_sn'])) > 150 || strlen(trim($payload['client_event_id'])) > 100) {
            return 'One or more fields exceed the maximum length';
        }
        if (isset($payload['template_hash']) && $payload['template_hash'] !== null && !is_string($payload['template_hash'])) {
            return 'template_hash must be a string or null';
        }
        if (isset($payload['template_hash']) && is_string($payload['template_hash']) && strlen($payload['template_hash']) > 255) {
            return 'template_hash exceeds the maximum length';
        }
        return true;
    }

    private function is_valid_timestamp($value)
    {
        $date = DateTime::createFromFormat('!Y-m-d H:i:s', $value);
        return $date && $date->format('Y-m-d H:i:s') === $value && $this->date_has_no_errors();
    }

    private function is_valid_date($value)
    {
        $date = DateTime::createFromFormat('!Y-m-d', $value);
        return $date && $date->format('Y-m-d') === $value && $this->date_has_no_errors();
    }

    private function date_has_no_errors()
    {
        $errors = DateTime::getLastErrors();
        return $errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0);
    }

    private function normalize_timestamp($value)
    {
        return DateTime::createFromFormat('!Y-m-d H:i:s', $value)->format('Y-m-d H:i:s');
    }

    private function nullable_string(array $payload, $key)
    {
        return isset($payload[$key]) && trim((string) $payload[$key]) !== '' ? trim($payload[$key]) : null;
    }

    private function public_row(array $row)
    {
        return array(
            'id' => (int) $row['id'],
            'user_id' => $row['user_id'],
            'timestamp' => $row['timestamp'],
            'device_sn' => $row['device_sn'],
            'client_event_id' => $row['client_event_id'],
        );
    }
}
