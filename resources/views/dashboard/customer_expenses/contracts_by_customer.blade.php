@foreach($contracts as $contract)
    <!--@__if($company->factor && $contract->supplier_id > 0)-->
    <tr>
        <td class="table_actions">
            @if(!$contract->supplier_id)
                <button type="button" onclick="selectContractForCustomerExpense({{ json_encode($contract) }})" class="btn btn-success btn-sm"><i class="fa fa-check"></i> اختر العقد</button>
            @else
                <button type="button" onclick="selectContractForCustomerExpense({{ json_encode($contract) }})" class="btn btn-success btn-sm"><i class="fa fa-check"></i> اختر العقد</button>

            @endif
        </td>
        <td>{{ $contract->customer_name }}</td>
        <td>{{ $contract->city }}</td>
        <td class="currency">{{ \App\Helper\Helper::nFormat($contract->contract_total) }}</td>
        <td class="currency bold">{{ \App\Helper\Helper::nFormat($contract->supplier_commission) }}</td>
        <td>
            @if($contract->status == 1)
                <label class="label label-success"><i class="fa fa-check"></i> ساري</label>
            @else
                <label class="label label-danger"><i class="fa fa-warning"></i> مفسوخ</label>
            @endif
        </td>
    </tr>

    <!--
    @_elseif(!$company->factor && $contract->supplier_id == NULL)
        <tr>
            <td class="table_actions">
                @if(!$contract->supplier_id)
                    <button type="button" onclick="selectContractForPayment({{ json_encode($contract) }})" class="btn btn-success btn-sm"><i class="fa fa-check"></i> اختر العقد</button>
                @else
                    <button type="button" onclick="selectContractForSupplierPayment({{ json_encode($contract) }})" class="btn btn-success btn-sm"><i class="fa fa-check"></i> اختر العقد</button>

                @endif
            </td>
            <td>{{ $contract->customer_name }}</td>
            <td>{{ $contract->city }}</td>
            <td class="currency">{{ \App\Helper\Helper::nFormat($contract->contract_total) }}</td>
            <td class="currency bold">{{ (!$contract->seller_id)? null : \App\Helper\Helper::nFormat($contract->seller_commission) }}</td>
            <td>
                @if($contract->status == 1)
                    <label class="label label-success"><i class="fa fa-check"></i> ساري</label>
                @else
                    <label class="label label-danger"><i class="fa fa-warning"></i> مفسوخ</label>
                @endif
            </td>
        </tr>
    @_endif
    -->
@endforeach
