<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<body>
    <table>
        <thead style="background-color:#c8c8c8; padding: 5px; font-size: 9px;">
            <tr>
                <td style="width: 150px; text-align: center; font-weight: bold;">TSR Code</td>
                <td style="width: 300px; text-align: center; font-weight: bold;">Customer</td>
                <td style="width: 400px; text-align: center; font-weight: bold;">Address</td>
                <td style="width: 150px; text-align: center; font-weight: bold;">Payment Type</td>
                <td style="width: 150px; text-align: center; font-weight: bold;">OR Number</td>
                <td style="width: 150px; text-align: center; font-weight: bold;">Subtotal</td>
                <td style="width: 150px; text-align: center; font-weight: bold;">Discount</td>
                <td style="width: 150px; text-align: center; font-weight: bold;">Gratis</td>
                <td style="width: 150px; text-align: center; font-weight: bold;">Total</td>
            </tr>
        </thead>
        <tbody>
        @foreach($lists as $list)
            <tr>
                <td style="{{ $list['code'] == '-' ? 'text-align: center;' : '' }}">{{$list['code']}}</td>
                <td style="text-align: center;">{{$list['customer']}}</td>
                <td style="text-align: center;">{{$list['address']}}</td>
                <td style="text-align: center;">{{$list['type']}}</td>
                <td style="text-align: center;">{{($list['ornumber']) ? $list['ornumber'] : '-'}}</td>
                <td style="text-align: center;">{{$list['subtotal']}}</td>
                <td style="text-align: center;">{{($list['discount'] == '₱0.00') ? '-' : $list['discount']}}</td>
                <td style="text-align: center;">{{$list['gratis']}}</td>
                <td style="text-align: center;">{{$list['total']}}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            @php
                $totalSubtotal = 0;
                $totalDiscount = 0;
                $totalAmount = 0;
                $totalGratis = 0;

                foreach ($lists as $list) {
                    $subtotal = str_replace(['₱ ', '₱', ',', ' '], '', $list['subtotal']);
                    $discount = str_replace(['₱ ', '₱', ',', ' '], '', $list['discount']);
                    $total = str_replace(['₱ ', '₱', ',', ' '], '', $list['total']);
                    $gratis = str_replace(['₱ ', '₱', ',', ' '], '', $list['gratis']);

                    $totalSubtotal += floatval($subtotal);
                    $totalDiscount += floatval($discount);
                    $totalAmount += floatval($total);
                    $totalGratis += floatval($gratis);
                }
            @endphp
            <tr style="font-weight: bold; text-align: center;">
                <td colspan="5"></td>
                <td style="text-align: center; font-weight: bold;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{ number_format($totalSubtotal, 2, ".", ",") }}</td>
                <td style="text-align: center; font-weight: bold;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{ number_format($totalDiscount, 2, ".", ",") }}</td>
                
                <td style="text-align: center; font-weight: bold;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{ number_format($totalGratis, 2, ".", ",") }}</td>
                <td style="text-align: center; font-weight: bold;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{ number_format($totalAmount, 2, ".", ",") }}</td>
                
            </tr>
        </tfoot>
    </table>
</body>
</html>
