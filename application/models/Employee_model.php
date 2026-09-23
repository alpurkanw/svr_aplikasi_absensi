<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employee_model extends CI_Model
{
    protected $table = 'employees';

    public function all($active_only = false)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->order_by('name', 'ASC')->get($this->table)->result_array();
    }

    public function find_by_code($employee_code)
    {
        return $this->db->where('employee_code', $employee_code)->get($this->table)->row_array();
    }

    public function find_by_id($id)
    {
        return $this->db->where('id', (int) $id)->get($this->table)->row_array();
    }

    public function save(array $data, $id = null)
    {
        if ($id === null) {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
        $this->db->where('id', (int) $id)->update($this->table, $data);
        return (int) $id;
    }

    public function salary_details($employee_id)
    {
        return $this->db->select('epc.component_id, epc.amount')
            ->from('employee_payroll_components epc')
            ->join('payroll_components pc', 'pc.id = epc.component_id')
            ->where('epc.employee_id', (int) $employee_id)
            ->where('pc.is_active', 1)
            ->where('epc.effective_from <=', date('Y-m-d'))
            ->group_start()
            ->where('epc.effective_until IS NULL', null, false)
            ->or_where('epc.effective_until >=', date('Y-m-d'))
            ->group_end()
            ->get()->result_array();
    }

    public function salary_bases(array $employee_ids, $potong_gapok, $effective_date = null)
    {
        $employee_ids = array_values(array_unique(array_map('intval', $employee_ids)));
        if (!$employee_ids) {
            return array();
        }

        $effective_date = $effective_date ?: date('Y-m-d');
        $rows = $this->db->select('epc.employee_id, SUM(epc.amount) AS total_salary', false)
            ->from('employee_payroll_components epc')
            ->join('payroll_components pc', 'pc.id = epc.component_id')
            ->where_in('epc.employee_id', $employee_ids)
            ->where('pc.component_type', 'EARNING')
            ->where('pc.is_active', 1);
        if ($potong_gapok) {
            $this->db->group_start()
                ->where_in('pc.code', array('GAPOK', 'GAJI_POKOK'))
                ->or_where('LOWER(pc.name)', 'gaji pokok')
                ->group_end();
        } else {
            $this->db->where('pc.calculation_type', 'FIXED');
        }
        $rows = $this->db
            ->where('epc.effective_from <=', $effective_date)
            ->group_start()
            ->where('epc.effective_until IS NULL', null, false)
            ->or_where('epc.effective_until >=', $effective_date)
            ->group_end()
            ->group_by('epc.employee_id')
            ->get()->result_array();

        $totals = array();
        foreach ($rows as $row) {
            $totals[(int) $row['employee_id']] = (float) $row['total_salary'];
        }
        return $totals;
    }

    public function salary_details_with_names($employee_id)
    {
        return $this->db->select('pc.name, pc.component_type, pc.sort_order, epc.amount')
            ->from('employee_payroll_components epc')
            ->join('payroll_components pc', 'pc.id = epc.component_id')
            ->where('epc.employee_id', (int) $employee_id)
            ->where('pc.is_active', 1)
            ->where('epc.effective_from <=', date('Y-m-d'))
            ->group_start()
            ->where('epc.effective_until IS NULL', null, false)
            ->or_where('epc.effective_until >=', date('Y-m-d'))
            ->group_end()
            ->order_by('pc.component_type', 'ASC')
            ->order_by('pc.sort_order', 'ASC')
            ->order_by('pc.id', 'ASC')
            ->get()->result_array();
    }

    public function save_salary_details($employee_id, array $amounts)
    {
        $today = date('Y-m-d');
        $this->db->trans_begin();
        $this->db->where('employee_id', (int) $employee_id)
            ->where('effective_until IS NULL', null, false)
            ->update('employee_payroll_components', array('effective_until' => date('Y-m-d', strtotime('-1 day'))));

        foreach ($amounts as $component_id => $amount) {
            $this->db->insert('employee_payroll_components', array(
                'employee_id' => (int) $employee_id,
                'component_id' => (int) $component_id,
                'amount' => max(0, (float) $amount),
                'effective_from' => $today,
            ));
        }
        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            throw new RuntimeException('Gagal menyimpan detail gaji');
        }
        $this->db->trans_commit();
    }
}
