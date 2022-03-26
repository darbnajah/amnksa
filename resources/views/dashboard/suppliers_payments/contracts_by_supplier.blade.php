@foreach($contracts as $contract)
    <tr>
        <td class="table_actions">
            <button type="button" onclick="selectContractForSupplierPayment({{ json_encode($contract) }})" class="btn btn-success btn-sm"><i class="fa fa-check"></i> اختر العقد</button>
        </td>
        <td>{{ $contract->customer_name }}</td>
        <td>{{ $contract->city }}</td>
        <td class="currency">{{ \App\Helper\Helper::nFormat($contract->contract_total) }}</td>
        <td class="currency">{{ \App\Helper\Helper::nFormat($contract->supplier_commission) }}</td>
        <td>
            @if($contract->status == 1)
                <label class="label label-success"><i class="fa fa-check"></i> ساري</label>
            @else
                <label class="label label-danger"><i class="fa fa-warning"></i> مفسوخ</label>
            @endif
        </td>
    </tr>
@endforeach
