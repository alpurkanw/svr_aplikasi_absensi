<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Presensi_model extends CI_Model
{
    protected $table = 'presensi';

    public function find_by_event_id($client_event_id)
    {
        return $this->db->where('client_event_id', $client_event_id)->get($this->table)->row_array();
    }

    public function find_by_id($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }

    public function create_idempotent(array $data)
    {
        $existing = $this->find_by_event_id($data['client_event_id']);
        if ($existing) {
            return array('created' => false, 'row' => $existing);
        }

        $this->db->trans_begin();
        $this->db->insert($this->table, $data);
        $insert_error = $this->db->error();

        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            if ((int) $insert_error['code'] === 1062) {
                $existing = $this->find_by_event_id($data['client_event_id']);
                if ($existing) {
                    return array('created' => false, 'row' => $existing);
                }
            }
            throw new RuntimeException('Database error while saving attendance');
        }

        $id = $this->db->insert_id();
        $this->db->trans_commit();
        return array('created' => true, 'row' => $this->find_by_id($id));
    }

    public function list_attendance($page, $limit, $user_id, $start_date, $end_date)
    {
        if ($user_id !== null && $user_id !== '') {
            $this->db->where('user_id', $user_id);
        }
        if ($start_date !== null) {
            $this->db->where('timestamp >=', $start_date . ' 00:00:00');
        }
        if ($end_date !== null) {
            $this->db->where('timestamp <=', $end_date . ' 23:59:59');
        }

        $total = $this->db->count_all_results($this->table, false);
        $rows = $this->db->order_by('timestamp', 'DESC')
            ->order_by('id', 'DESC')
            ->limit($limit, ($page - 1) * $limit)
            ->get()
            ->result_array();

        return array('items' => $rows, 'total' => (int) $total);
    }

    public function list_by_date($date)
    {
        return $this->db->select('p.*, e.name AS employee_name')
            ->from($this->table . ' p')
            ->join('employees e', 'e.employee_code = p.user_id', 'left')
            ->where('p.timestamp >=', $date . ' 00:00:00')
            ->where('p.timestamp <=', $date . ' 23:59:59')
            ->order_by('p.timestamp', 'ASC')
            ->order_by('p.id', 'ASC')
            ->get()
            ->result_array();
    }
}
