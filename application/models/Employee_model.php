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

    public function exists_by_code($employee_code)
    {
        return (bool) $this->db->where('LOWER(employee_code) =', strtolower(trim($employee_code)))->count_all_results($this->table);
    }

    public function exists_by_nip($nip)
    {
        return (bool) $this->db->where('LOWER(nip) =', strtolower(trim($nip)))->count_all_results($this->table);
    }

    public function exists_by_nik($nik)
    {
        return (bool) $this->db->where('LOWER(nik) =', strtolower(trim($nik)))->count_all_results($this->table);
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

    public function salary_details_with_names($employee_id)
    {
        return $this->db->select('pc.name, pc.component_type, epc.amount')
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
