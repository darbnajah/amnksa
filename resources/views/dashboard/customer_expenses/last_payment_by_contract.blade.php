<?php if($balance):
    $label = json_decode($balance->label);
    $contract = isset($label->contract_obj)? json_decode($label->contract_obj) : null;

?>
<tr>
    <td style="white-space: nowrap">{{ $balance->dt }}</td>
    <td>
        <span>سداد مستحق عن شهر </span>
        <span>{{ \App\Helper\Helper::monthNameAr($label->month_id) }}</span>
        @if($contract)
            - <span>العميل: {{ $contract->customer_name }}</span>
            - <span>المدينة: {{ $contract->city }}</span>
        @endif
    </td>
    <td class="currency">{{ \App\Helper\Helper::nFormat($balance->debit) }}</td>
</tr>

<?php endif ?>
