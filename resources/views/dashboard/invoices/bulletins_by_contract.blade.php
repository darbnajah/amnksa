@if($bulletins)
    @foreach($bulletins as $bulletin)
    <tr row_id="" extra="0" row_nb_days="{{ $nb_days }}">
        <td class="table_actions">
            <button type="button" class="btn_remove btn btn-danger" onclick="removeInvoiceRow(this)"><i class="fa fa-times"></i></button>
        </td>
        <td class="td_label">
            <input type="text" class="form-control" value="{{ $bulletin->label }}" onkeyup="calcInvoiceRow(this)">
        </td>
        <td class="td_cost">
            <input type="text" class="form-control currency" value="{{ $bulletin->cost }}" onkeyup="calcInvoiceRow(this)">
        </td>
        <td class="td_nb">
            <input type="text" class="form-control text-center" value="{{ $bulletin->nb }}" onkeyup="calcInvoiceRow(this)">
        </td>
        <td class="td_nb_days">
            <input type="text" class="form-control text-center" value="{{ $nb_days }}" onkeyup="calcInvoiceRow(this)">
        </td>
        <td class="td_total">
            <input type="text" class="form-control currency" value="0.00" readonly>
        </td>
    </tr>
    @endforeach
@endif
