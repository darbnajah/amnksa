@foreach($contracts as $contract)
    <!--@__if($company->factor && $contract->supplier_id > 0)-->
    <tr>
        <td class="table_actions">
            @if(!$contract->supplier_id)
                <button type="button" onclick="selectContractForEmployee({{ json_encode($contract) }})" class="btn btn-success btn-sm"><i class="fa fa-check"></i> اختر العقد</button>
            @else
                <button type="button" onclick="selectContractForEmployee({{ json_encode($contract) }})" class="btn btn-success btn-sm"><i class="fa fa-check"></i> اختر العقد</button>

            @endif
        </td>
        <td>{{ $contract->city }}</td>

    </tr>
@endforeach
