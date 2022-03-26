@foreach($contracts as $contract)
    <tr>
        <td class="table_actions">
            <button type="button" onclick="selectContract({{ $contract }}, '{{ url()->to('dashboard/invoices/bulletins_by_contract') }}')" class="btn btn-success btn-sm"><i class="fa fa-check"></i> اختر العقد</button>
        </td>
        <td>{{ $contract->code }}</td>
        <td>{{ $contract->city }}</td>
        <td>{{ $contract->address }}</td>
    </tr>
@endforeach
