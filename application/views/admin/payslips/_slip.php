<style>
.slip-preview { font-family: Arial, sans-serif; color: #263238; }
.slip-preview .slip-head { border-bottom: 3px solid #3157c8; padding-bottom: 14px; margin-bottom: 18px; }
.slip-preview .brand { color: #3157c8; font-size: 24px; font-weight: 700; letter-spacing: .5px; }
.slip-preview .subtitle { color: #607d8b; font-size: 13px; }
.slip-preview .employee-box { background: #f4f7ff; border-radius: 8px; padding: 14px 18px; margin-bottom: 18px; }
.slip-preview .employee-box small { color: #78909c; display: block; font-size: 11px; text-transform: uppercase; }
.slip-preview .employee-box strong { font-size: 15px; }
.slip-preview table { width: 100%; border-collapse: collapse; }
.slip-preview th { background: #3157c8; color: #fff; padding: 9px; text-align: left; font-size: 12px; }
.slip-preview td { border-bottom: 1px solid #e3e8ef; padding: 9px; font-size: 13px; }
.slip-preview .amount { text-align: right; white-space: nowrap; }
.slip-preview .summary { margin-top: 20px; margin-left: auto; width: 300px; }
.slip-preview .summary td { border: 0; padding: 5px 0; }
.slip-preview .net { color: #3157c8; font-size: 18px; font-weight: 700; border-top: 2px solid #3157c8 !important; }
</style>
<div class="slip-preview">
    <div class="slip-head d-flex justify-content-between align-items-start">
        <div><div class="brand">SLIP GAJI</div><div class="subtitle">Dokumen pembayaran gaji karyawan</div></div>
        <div class="text-right"><strong><?= html_escape($period_label) ?></strong><div class="subtitle">Periode pembayaran</div></div>
    </div>
    <div class="employee-box row">
        <div class="col-md-4"><small>Nama Karyawan</small><strong><?= html_escape($employee['name']) ?></strong></div>
        <div class="col-md-3"><small>Employee Code</small><strong><?= html_escape($employee['employee_code']) ?></strong></div>
        <div class="col-md-3"><small>Jabatan</small><strong><?= html_escape($employee['position_name'] ?: '-') ?></strong></div>
        <div class="col-md-2"><small>Departemen</small><strong><?= html_escape($employee['department_name'] ?: '-') ?></strong></div>
    </div>
    <div class="row">
        <div class="col-md-6"><h6 class="font-weight-bold text-success">Pendapatan</h6><table><thead><tr><th>Komponen</th><th class="amount">Nominal</th></tr></thead><tbody><?php foreach ($earnings as $row): ?><tr><td><?= html_escape($row['component_name']) ?></td><td class="amount"><?= number_format((float) $row['amount'], 0, ',', '.') ?></td></tr><?php endforeach; ?></tbody></table></div>
        <div class="col-md-6"><h6 class="font-weight-bold text-danger">Pengurang</h6><table><thead><tr><th>Komponen</th><th class="amount">Nominal</th></tr></thead><tbody><?php foreach ($deductions as $row): ?><tr><td><?= html_escape($row['component_name']) ?></td><td class="amount"><?= number_format((float) $row['amount'], 0, ',', '.') ?></td></tr><?php endforeach; ?></tbody></table></div>
    </div>
    <table class="summary"><tr><td>Total Pendapatan</td><td class="amount"><?= number_format((float) $total_earning, 0, ',', '.') ?></td></tr><tr><td>Total Pengurang</td><td class="amount"><?= number_format((float) $total_deduction, 0, ',', '.') ?></td></tr><tr><td class="net">Take Home Pay</td><td class="amount net"><?= number_format((float) $total_earning - (float) $total_deduction, 0, ',', '.') ?></td></tr></table>
    <div class="text-right mt-4"><a class="btn btn-primary" target="_blank" href="<?= site_url('admin/payslips/print/' . (int) $employee['id'] . '?period=' . rawurlencode($period)) ?>"><i class="fas fa-print"></i> Cetak Slip</a></div>
</div>
