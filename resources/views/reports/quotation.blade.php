<!doctype html>
<html lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
        html * {
            font-family:Arial, Helvetica, sans-serif;
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
        input[type=checkbox] {
            transform: scale(.7);
        }
         .a {
            width: 55px; 
            height: 55px;
         }
         label {
        display: block;
        padding-left: 15px;
        text-indent: -15px;
        }
        input {
        width: 13px;
        height: 13px;
        padding: 0;
        margin:0;
        vertical-align: bottom;
        position: relative;
        top: -5px;
        left: 7px;
        *overflow: hidden;
        
        }
        .spec_table td {
margin-left: -20px;
font-size: .9em;
line-height: 1.1em;
border-top: none !important;
}

.page-break {
  page-break-after: always;
}

    </style>
    </head>

    <body>
        <?php 
            $lists = json_encode($configuration); 
            $lists = json_decode($configuration, true);   

            $quotation = json_encode($quotation); 
            $quotation = json_decode($quotation, true);   

            $samples = json_encode($samples); 
            $samples = json_decode($samples, true);   
            
        ?>

        <div style="font-family:Arial;">
            <img src="{{ asset('images/logo-sm.png') }}" alt="tag" style="position: absolute; top: -4; left: 90; width: 50px; height: 50px;">
            <center style="font-size: 10px; margin-bottom: 0px; text-transform: uppercase;">{{$configuration['name']}}</center>
            <center style="font-size: 11px; margin-bottom: 0px; font-weight: bold;">REGIONAL STANDARDS AND TESTING LABORATORIES</center>
            <center style="font-size: 11px;">Pettit Baracks, Zamboanga City | (062) 991-1024</center>
            <br/>
            
            <center style="margin-top: 8px; font-size: 12px; color:#000; font-weight: bold; padding: 2px;">QUOTATION</center>
            <center style="font-size: 10px; background-color: #1fdf62; color:#fff; font-weight: bold; padding: 2px;">CUSTOMER COPY</center>
        </div>

        <table style="border: 1px solid black; font-size: 10px; margin-top: 15px;">
            <tbody>
                <tr>
                    <td width="25%">Quotation Code : </td>
                    <td width="25%"><span style="font-weight: bold; color: #072388;">{{$quotation['code']}}</span></td>
                    <td width="25%">Date and Time :</td>
                    <td width="25%"><span style="color: #072388;">{{$quotation['created_at']}}</span></td>
                </tr>
            </tbody>
        </table>

        <table style="border: 1px solid black; font-size: 10px; margin-top: 10px;">
            <tbody>
                <tr>
                    <td width="25%">Customer : </td>
                    <td colspan="3" width="75%"><span style="font-weight: bold; text-transform: uppercase; color: #072388;">{{$quotation['customer']['name']}}</span></td>
                </tr>
                <tr>
                    <td width="25%">Address : </td>
                    <td colspan="3" width="75%"><span style="text-transform: uppercase; color: #072388;">{{$quotation['customer']['address']['name']}}</span></td>
                </tr>
                <tr>
                    <td width="25%">Contact Number : </td>
                    <td width="25%"><span style="color: #072388;">{{$quotation['customer']['contact_no']}}</span></td>
                    <td width="25%">Email : </td>
                    <td width="25%"><span style="color: #072388;">{{$quotation['customer']['email']}}</span></td>
                </tr>
            </tbody>
        </table>
        <h6 style="font-size: 10px; margin-top: 12px;">1.TESTING OR CALIBRATION SERVICES</h6>
        <table style="border: 1px solid black; font-size: 10px; margin-top: -22px;">
            <thead style="background-color:#c8c8c8; padding: 5px; font-size: 9px;">
                <tr>    
                    <th style="vertical-align: middle;" width="20%">Sample</th>
                    <th style="vertical-align: middle;" width="25%">Test/Calibration Requested</th>
                    <th style="vertical-align: middle;" width="25%">Test/Calibration Method</th>
                    <th style="vertical-align: middle;" width="10%">No. of Samples</th>
                    <th style="vertical-align: middle;" width="10%">Unit Cost</th>
                    <th style="vertical-align: middle;" width="10%">Total</th>
                </tr>
            </thead>
            <tbody>
            @foreach($group as $index=>$sample)
                <tr style="text-align: center; font-size: 9px; color: #072388;">
                    <td style="text-align: left;">{{$sample['samplename']}}</td>
                    <td>{{$sample['testname']}}</td>
                    <td>{{$sample['method']}}</td>
                    <td>{{$sample['count']}}</td>
                    <td style="text-align: right;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{trim(str_replace(',','',$sample['fee']),'₱ ')}}</td>
                    <td style="text-align: right;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{number_format(trim(str_replace(',','',$sample['fee']),'₱ ')*$sample['count'],2,".",",")}}</td>
                </tr>
                @if(isset($sample['additional']))
                <tr style="text-align: center; font-size: 9px; color: #072388;">
                    <td colspan="3" style="text-align: left;">{{$sample['additional']['name']}}</td>
                    <td>{{$sample['additional']['quantity']}}</td>
                    <td style="text-align: right;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{trim($sample['additional']['fee'],'₱ ')}}</td>
                    <td style="text-align: right;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{number_format(trim(str_replace(',','',$sample['additional']['fee']),'₱ ')*$sample['additional']['quantity'],2,".",",")}}</td>
                </tr>
                @endif
            @endforeach
            @if(isset($service))
                <tr style="text-align: center; font-size: 9px; color: #072388;">
                    <td style="text-align: left;" colspan="3">{{$service['name']}} ({{$service['description']}})</td>
                    <td>{{$service['quantity']}}</td>
                    <td style="text-align: right;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{number_format(trim(str_replace(',','',$service['fee']),'₱ '),2,".",",")}}</td>
                    <td style="text-align: right;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{number_format(trim(str_replace(',','',$service['fee']),'₱ ')*$service['quantity'],2,".",",")}}</td>
                </tr>
            @endif
            </tbody>
            <tfoot style="text-align: center; padding: 3px; font-weight: bold; background-color:#c8c8c8;">
                <tr>
                    <td colspan="4"></td>
                    <td style="font-size: 8px;">SUBTOTAL</td>
                    <td style="text-align: right;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{number_format(trim(str_replace(',','',$quotation['subtotal']),'₱ '),2,".",",")}}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td style="font-size: 8px;">DISCOUNT</td>
                    <td style="text-align: right;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{number_format(trim(str_replace(',','',$quotation['discount']),'₱ '),2,".",",")}}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td style="font-size: 8px;">TOTAL</td>
                    <td style="text-align: right;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{number_format(trim(str_replace(',','',$quotation['total']),'₱ '),2,".",",")}}</td>
                </tr>
            </tfoot>
        </table>
        <h6 style="font-size: 10px; margin-top: 12px;">2. DESCRIPTION OF THE SAMPLE(S) / REMARK(S)</h6>
        <table style="border: 1px solid black; font-size: 9px; margin-top: -22px;">
            <tbody>
                <tr>
                    <td>
                        <ul style="margin-left: -30px; list-style: none;">
                            @foreach($descs as $desc)
                                <li>&#62; {{$desc['name']}} : <i>{{$desc['customer_description']}}</i>, {{$desc['description']}}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
        <h6 style="font-size: 10px; margin-top: 12px;">3. TERMS AND CONDITION</h6>
        <table style="border: 1px solid black; font-size: 9px; margin-top: -22px;">
            <tbody>
                <tr>
                    <td>
                        <ul style="margin-left: -20px;">
                            @foreach($quotation['terms'] as $index=>$term)
                            <li>{{$term}}</li>
                            @endforeach
                            <!-- <li>DOST IX Trust Fund 1952101052 Landbank of the Philippines.</li>
                            <li>Cash payment should be made directly to the cashier or deposit to DOST IX account.</li>
                            <li>This quotation is valid only until <b>{{$quotation['due_at']}}</b></li> -->
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
        <h6 style="font-size: 10px; margin-top: 12px;">4. SIGNATORIES</h6>
        <table style="text-align: center; border: 1px solid black; font-size: 10px; margin-top: -22px;">
            <tbody>
                <tr>
                    <td style="min-height: 50px; padding: 20px; border-bottom-style: hidden;"></td>
                    <td style="min-height: 50px; padding: 20px; border-bottom-style: hidden;"></td>
                    <td style="min-height: 50px; padding: 20px; border-bottom-style: hidden;"></td>
                </tr>
                <tr>
                    <td width="33.3%"><span style="font-weight: bold; font-size: 11px; color: #072388; text-transform: uppercase;">{{$quotation['conforme']}}</span><hr style="margin-top: 0px; margin-bottom: 1px; border: .1px solid black; width: 80%;">Customer / Authorized Representative </br> <span style="font-size:9px; color: #606060;">(Received by)</span></td>
                    <td width="33.3%"><span style="font-weight: bold; font-size: 11px; color: #072388; text-transform: uppercase;">{{$user}}</span><hr style="margin-top: 0px; margin-bottom: 1px; border: .1px solid black; width: 80%;">Laboratory Personnel </br> <span style="font-size:9px; color: #606060;">(Prepared by)</span></td>
                    <td width="33.3%"><span style="font-weight: bold; font-size: 11px; color: #072388; text-transform: uppercase;">{{$manager}}</span><hr style="margin-top: 0px; margin-bottom: 1px; border: .1px solid black; width: 80%;">Technical Manager </br> <span style="font-size:9px; color: #606060;">(Approved by)</span></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>

<!-- style="border-bottom-style: hidden;" -->
