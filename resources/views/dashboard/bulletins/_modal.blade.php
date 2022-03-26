<?php
$total_amount = 0;

if($bulletins) {
    ?>
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>البيان</th>
            <th>العدد</th>
            <th>التكلفة الشهرية</th>
            <th>المجموع</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($bulletins as $bulletin){
        $amount = $bulletin->nb * $bulletin->cost;
        $total_amount += $amount;
        ?>
        <tr>
            <td>{{ $bulletin->label }}</td>
            <td class="text-center">{{ $bulletin->nb }}</td>
            <td class="currency">{{ number_format($bulletin->cost, 2, '.', ' ') }}</td>
            <td class="currency">{{ number_format($amount, 2, '.', ' ') }}</td>
        </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="3">المجموع</th>
            <th class="currency">{{ number_format($total_amount, 2, '.', ' ') }}</th>
        </tr>
        </tfoot>
    </table>
</div>

<?php } ?>
