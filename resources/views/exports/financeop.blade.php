<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<body>
    <table>
        <thead style="background-color:#c8c8c8; padding: 5px; font-size: 9px;">
            <tr>
                <td style="width: 150px; text-align: center; font-weight: bold;">Order Date</td>
                <td style="width: 150px; text-align: center; font-weight: bold;">OP No.</td>
                <td style="width: 500px; text-align: center; font-weight: bold;">Customer</td>
                <td style="width: 400px; text-align: center; font-weight: bold;">Reference No.</td>
                <td style="width: 150px; text-align: center; font-weight: bold;">OP Amount</td>
                <td style="width: 150px; text-align: center; font-weight: bold;">OR No.</td>
                <td style="width: 160px; text-align: center; font-weight: bold;">OR Amount</td>
                <td style="width: 160px; text-align: center; font-weight: bold;">Credited to Wallet</td>
                <td style="width: 160px; text-align: center; font-weight: bold;">Payment Method</td>
                <td style="width: 200px; text-align: center; font-weight: bold;">Created By</td>
            </tr>
        </thead>
        <tbody>
        @foreach($lists as $list)
            <tr style="text-align: center; font-size: 9px; color: #072388;">
                <td style="text-align: center;">{{$list['date']}}</td>
                <td style="text-align: center;">{{$list['opnumber']}}</td>
                <td style="text-align: center;">{{$list['customer']}}</td>
                <td style="text-align: center;">
                    @foreach($list['references'] as $index=>$a)
                        {{$a['itemable']['code']}}@if(!$loop->last), @endif
                    @endforeach
                </td>
                <td style="text-align: center;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{number_format(trim(str_replace(',','',$list['opamount']),'₱ '),2,".",",")}}</td>
                <td style="text-align: center;">{{$list['ornumber']}}</td>
                <td style="text-align: center;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{number_format(trim(str_replace(',','',$list['oramount']),'₱ '),2,".",",")}}</td>
                <td style="text-align: center;">{{$list['excess']}}</td>
                <td style="text-align: center;">{{$list['payment']}}</td>
                <td style="text-align: center;">{{$list['created']}}</td>
            </tr>
        @endforeach
        </tbody>

    </table>
</body>
</html>
