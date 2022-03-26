@foreach($contracts as $contract)
    <!--@__if($company->factor && $contract->supplier_id > 0)-->
    <li>
        @if(!$contract->supplier_id)
            <button type="button" onclick="selectContractForPaie(this, {{ json_encode($contract) }})" class="btn btn-success btn-xs"><i class="fa fa-check"></i> {{ $contract->city }}</button>
        @else
            <button type="button" onclick="selectContractForPaie(this, {{ json_encode($contract) }})" class="btn btn-success btn-xs"><i class="fa fa-check"></i> {{ $contract->city }}</button>

        @endif



    </li>
@endforeach
