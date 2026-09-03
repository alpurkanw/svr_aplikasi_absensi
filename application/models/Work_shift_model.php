<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Work_shift_model extends CI_Model
{
    protected $table = 'work_shifts';

    public function find_for_employee($employee_id, $date)
    {
        return $this->db->select('s.*')
            ->from($this->table . ' s')
            ->join('employee_shift_assignments a', 'a.shift_id = s.id')
            ->where('a.employee_id', (int) $employee_id)
            ->where('a.effective_from <=', $date)
            ->group_start()
            ->where('a.effective_until IS NULL', null, false)
            ->or_where('a.effective_until >=', $date)
            ->group_end()
            ->where('s.is_active', 1)
            ->order_by('a.effective_from', 'DESC')
            ->get()->row_array();
    }
}
