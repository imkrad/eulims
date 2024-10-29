<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accomplishment Report</title>
    <style>
        /* Styles for the footer */
        @page {
           
        }

        html * {
            font-family:Arial, Helvetica, sans-serif;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
        }

      

        table,
        td,
        th {
            border: .5px solid black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            padding: 3px;
            vertical-align: top;
        }
        td {
            padding: 3px;
            /* vertical-align: top; */
            /* text-align: center; */
        }
        label {
            display: block;
            padding-left: 15px;
            text-indent: -15px;
        }
        label {
            display: inline-block;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    
    <div class="content">
        <div style="font-family:Arial;">
        
            <center style="font-size: 10px; background-color: black; color:#fff; font-weight: bold; padding: 5px;">LIST OF TSR FOR {{$year}}</center>
        </div>

        <table style="border: 1px solid black; font-size: 10px;">
            <thead style="background-color:#c8c8c8; padding: 5px; font-size: 9px;">
                <tr>    
                    <th style="vertical-align: middle;" width="12%">TSR Code</th>
                    <th style="vertical-align: middle;" width="28%">Customer</th>
                    <th style="vertical-align: middle;" width="28%">Address</th>
                    <th style="vertical-align: middle;" width="8%">OR Number</th>
                    <th style="vertical-align: middle;" width="8%">Subtotal</th>
                    <th style="vertical-align: middle;" width="8%">Discount</th>
                    <th style="vertical-align: middle;" width="8%">Gratis</th>
                    <th style="vertical-align: middle;" width="8%">Total</th>
                </tr>
            </thead>
            <tbody>
            @foreach($lists as $index=>$list)
                <tr style="text-align: center; font-size: 9px; color: #072388;">
                    <td>{{$list['code']}}</td>
                    <td style="text-align: left;">
                        {{$list['customer']['customer_name']['name']}} {{($list['customer']['name'] == 'Main')  ? '' : ' - '.$list['customer']['name']}}
                    </td>
                    <td>
                    @php 
                            $address = ($list['customer']['address']['address'] != '' ||$list['customer']['address']['address'] != NULL) ? $list['customer']['address']['address'].', ' : '';
                            if($list['customer']['address']['municipality']['name'] == 'Zamboanga City' || $list['customer']['address']['municipality']['name'] == 'Isabela City'){
                                $a = '';
                            }else if($list['customer']['address']['province']['name'] == 'Sulu'){
                                $a = ', '.$list['customer']['address']['province']['name'];
                            }else{
                                $a = ', '.$list['customer']['address']['province']['name'];
                            }
                            $barangay = $list['customer']['address']['barangay']['name'];
                            $municipality = $list['customer']['address']['municipality']['name'];
                            $province = $list['customer']['address']['province']['name'];

                        @endphp
                        {{ $address.$list['customer']['address']['barangay']['name'].', '.$list['customer']['address']['municipality']['name'].$a}}
                    </td>
                    <td>{{($list['payment']['or_number']) ? $list['payment']['or_number'] : '-'}}</td>
                    <td><span style="font-family: DejaVu Sans;">&#8369;</span>{{number_format(trim(str_replace(',','',$list['payment']['subtotal']),'₱ '),2,".",",")}}</td>
                    <td><span style="font-family: DejaVu Sans;">&#8369;</span>{{number_format(trim(str_replace(',','',$list['payment']['discount']),'₱ '),2,".",",")}}</td>
                    <td><span style="font-family: DejaVu Sans;">&#8369;</span>{{($list['payment']['is_free'] != 1) ? '0.00' : number_format(trim(str_replace(',','',$list['payment']['discount']),'₱ '),2,".",",")}}</td>
                    <td><span style="font-family: DejaVu Sans;">&#8369;</span>{{number_format(trim(str_replace(',','',$list['payment']['total']),'₱ '),2,".",",")}}</td>
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
                        $subtotal = str_replace(['₱ ', '₱', ',', ' '], '', $list['payment']['subtotal']);
                        $discount = str_replace(['₱ ', '₱', ',', ' '], '', $list['payment']['discount']);
                        $total = str_replace(['₱ ', '₱', ',', ' '], '', $list['payment']['total']);
                        $gratis = ($list['payment']['is_free']) ? str_replace(['₱ ', '₱', ',', ' '], '', $list['payment']['discount']) : 0;

                        $totalSubtotal += floatval($subtotal);
                        $totalDiscount += floatval($discount);
                        $totalAmount += floatval($total);
                        $totalGratis += floatval($gratis);
                    }
                @endphp
                <tr style="font-weight: bold; text-align: center;">
                    <td colspan="4"></td>
                    <td><span style="font-family: DejaVu Sans;">&#8369;</span>{{ number_format($totalSubtotal, 2, ".", ",") }}</td>
                    <td><span style="font-family: DejaVu Sans;">&#8369;</span>{{ number_format($totalDiscount, 2, ".", ",") }}</td>
                    <td><span style="font-family: DejaVu Sans;">&#8369;</span>{{ number_format($totalGratis, 2, ".", ",") }}</td>
                    <td><span style="font-family: DejaVu Sans;">&#8369;</span>{{ number_format($totalAmount, 2, ".", ",") }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>