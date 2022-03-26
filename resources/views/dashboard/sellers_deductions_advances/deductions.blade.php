<?php
$deduction_total_debit = 0;
$deduction_total_credit = 0;
?>
<div class="table-responsive">
    <table class="table table-hover deductions_advances_table" id="deductions_table">
        <thead>
        <tr>
            <th></th>
            <th>التاريخ</th>
            <th width="35%">البيان</th>
            <th>مدين</th>
            <th>دائن</th>
        </tr>
        </thead>
        <tbody>
        @if($deductions)
            <?php foreach($deductions as $deduction){
            $deduction_total_debit += $deduction->debit;
            $deduction_total_credit += $deduction->credit;
            ?>
            <tr row_id="{{ $deduction->id }}" type="deduction">
                <td class="table_actions">
                    <?php if($deduction->debit > 0): ?>
                        @if(auth()->user()->can('sellers_deductions_advances_delete'))
                        <button type="button" class="btn_remove btn btn-danger" onclick="removeDeductionAdvanceRow('{{ route('dashboard.sellers_deductions_advances.delete', $deduction->id) }}')"><i class="fa fa-times"></i></button>
                        @endif
                    <?php endif; ?>
                </td>
                <td class="td_dt">{{ $deduction->dt }}</td>
                <td class="td_label">{{ $deduction->label }}</td>
                <td class="td_debit currency">{{ ($deduction->debit > 0)? \App\Helper\Helper::nFormat($deduction->debit) : null }}</td>
                <td class="td_credit currency">{{ ($deduction->credit > 0)? \App\Helper\Helper::nFormat($deduction->credit) : null }}</td>
            </tr>
            <?php } ?>
        @endif
        </tbody>
        <tfoot>
        <tr>
            <th colspan="3">المجموع</th>
            <th id="deduction_total_debit" class="currency"><?= \App\Helper\Helper::nFormat($deduction_total_debit) ?></th>
            <th id="deduction_total_credit" class="currency"><?= \App\Helper\Helper::nFormat($deduction_total_credit) ?></th>
        </tr>
        <tr>
            <th colspan="3">الباقي</th>
            <th id="deduction_total_rest" colspan="2" class="currency"><?= \App\Helper\Helper::nFormat($deduction_total_debit - $deduction_total_credit) ?></th>
        </tr>
        </tfoot>
    </table>
</div>
