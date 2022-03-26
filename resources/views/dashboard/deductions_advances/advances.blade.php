<?php
    $advance_total_debit = 0;
    $advance_total_credit = 0;
?>
<div class="table-responsive">
<table class="table table-hover deductions_advances_table" id="advances_table">
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
    @if($advances)
        <?php foreach($advances as $advance){
            $advance_total_debit += $advance->debit;
            $advance_total_credit += $advance->credit;
        ?>
            <tr row_id="{{ $advance->id }}" type="advance">
                <td class="table_actions">
                    <?php if($advance->debit > 0): ?>
                        @if(auth()->user()->can('deductions_advances_delete'))
                        <button type="button" class="btn_remove btn btn-danger" onclick="removeDeductionAdvanceRow('{{ route('dashboard.deductions_advances.delete', $advance->id) }}')"><i class="fa fa-times"></i></button>
                        @endif
                    <?php endif; ?>
                </td>
                <td class="td_dt">{{ $advance->dt }}</td>
                <td class="td_label">{{ $advance->label }}</td>
                <td class="td_debit currency">{{ ($advance->debit > 0)? \App\Helper\Helper::nFormat($advance->debit) : null }}</td>
                <td class="td_credit currency">{{ ($advance->credit > 0)? \App\Helper\Helper::nFormat($advance->credit) : null }}</td>
            </tr>
        <?php } ?>
    @endif
    </tbody>
    <tfoot>
    <tr>
        <th colspan="3">المجموع</th>
        <th id="advance_total_debit" class="currency"><?= \App\Helper\Helper::nFormat($advance_total_debit) ?></th>
        <th id="advance_total_credit" class="currency"><?= \App\Helper\Helper::nFormat($advance_total_credit) ?></th>
    </tr>
    <tr>
        <th colspan="3">الباقي</th>
        <th id="advance_total_rest" colspan="2" class="currency"><?= \App\Helper\Helper::nFormat($advance_total_debit - $advance_total_credit) ?></th>
    </tr>
    </tfoot>
</table>
</div>
