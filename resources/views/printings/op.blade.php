<!doctype html>
<html lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
        html * {
            font-family:Arial, Helvetica, sans-serif;
        }
        body {
            margin-top: 15px;
            margin-left: 40px;
            margin-right: 40px;
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
input[type=checkbox] { display: inline; }
input[type=checkbox]:before { font-family: DejaVu Sans; }
label {
  display: inline-block;
}

    </style>
    </head>

    <body>
        <div style="font-family:Arial;">
            <center style="font-size: 10px; margin-bottom: 0px; text-transform: uppercase;">DEPARTMENT OF SCIENCE AND TECHNOLOGY</center>
            <center style="font-size: 11px; margin-bottom: 0px; font-weight: bold;">REGIONAL STANDARDS AND TESTING LABORATORIES</center>
            <center style="font-size: 11px;">Pettit Baracks, Zamboanga City | (062) 991-1024</center>
            <br/>
            
        </div>

        <table style="border: 1px solid black; font-size: 11px; margin-top: 25px;">
            <tbody>
                <tr>
                    <td width="25%">Fund Cluster: </td>
                    <td width="25%">
                    <span style="display: inline-block; padding: 5px; border: 1px solid black; margin-bottom: -2px;"></span><span> 01 MDS</span>
                    </td>
                    <td width="25%">No.:</td>
                    <td width="25%"><span>{{$code}}</span></td>
                </tr>
                <tr>
                    <td width="25%"></td>
                    <td width="25%">
                    <span style="display: inline-block; padding: 5px; border: 1px solid black; margin-bottom: -2px;"></span><span> 07 TF</span>
                    </td>
                    <td width="25%">Date:</td>
                    <td width="25%"><span>{{$date}}</span></td>
                </tr>
            </tbody>
        </table>
        <center style="font-size: 12px; background-color: #000; color:#fff; font-weight: bold; padding: 3px; margin-top: 15px;">ORDER OF PAYMENT</center>
        <table style="border: 1px solid black; font-size: 11px; margin-top: 15px;">
            <tbody>
                <tr>
                    <td width="25%">The Collection Officer</td>
                    <td width="75%"><span style="font-weight: bold;">{{$cashier}}</span></td>
                </tr>    
                <tr>
                    <td width="25%">Cash Unit</td>
                    <td width="75%">Cashier</td>
                </tr>
            </tbody>
        </table>
        
        <table style="border: 1px solid black; margin-top: 15px;">
            <tbody>
                <tr>
                    <td style="padding: 10px; font-size: 9px;">
                        <span style="font-size: 12px;"><span style="margin-left: 80px;">Please issue Official Receipt in favor of</span> <span style="font-family:Arial, Helvetica, sans-serif; font-weight:bold; text-decoration: underline;">{{$customer}} </span> (<i>{{$address}}</i>) in the amount of <span style="font-family:Arial, Helvetica, sans-serif; font-weight:bold; text-decoration: underline;">{{$word}} (<span style="font-family: DejaVu Sans;">&#8369;</span>{{trim($total,'₱ ')}})</span> for payment of :</span> <br/><br/>
                        @foreach($lists as $index=>$item)
                            @foreach($item['itemable']['samples'] as $index2=>$ts)
                            <span style="font-size: 10px; line-height: .2; margin: 0; padding: 0;">{{$ts['name']}} (@foreach($ts['analyses'] as $index2=>$a){{$a['testservice']['testname']['name']}}@if(count($ts['analyses']) > 1),@endif @endforeach)@if(count($items) > 1)@if((count($items)-1) != $index),@endif @endif</span>
                            @endforeach
                        @endforeach
                    </td>
                </tr>    
            </tbody>
        </table>

        <table style="border: 1px solid black; font-size: 10px; margin-top: 15px;">
            <tbody>
            @foreach($items as $index=>$item)
                <tr style="font-size: 9px;">
                    <td width="15%" style="text-align: center;">Per Bill no.: </td>
                    <td width="35%">{{$item['name']}}</td>
                    <td width="15%" style="text-align: center;">Request Date:</td>
                    <td width="35%">{{$item['date']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <h6 style="font-size: 10px; margin-top: 12px;">Please deposit the collections under Bank Accounts:</h6>
        <table style="border: 1px solid black; font-size: 10px; margin-top: -22px;">
            <thead style="background-color:#c8c8c8; font-size: 9px;">
                <tr>    
                    <th width="30%">NO.</th>
                    <th width="40%">NAME OF BANK</th>
                    <th width="30%">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < 1; $i++)
                <tr style="font-size: 11px;">
                    <td style="font-weight: bold; text-align: center;">@if($i == 0)3402-2844-20 @endif</td>
                    <td style="text-align: center;">@if($i == 0)LANDBANK @endif</td>
                    <td style="text-align:center; ">@if($i == 0) <span style="font-family: DejaVu Sans;">&#8369;</span>{{trim($total,'₱ ')}} @endif</td>
                </tr>
                @endfor
            </tbody>
            <tfoot style="text-align: center; font-weight: bold; background-color:#c8c8c8; font-size: 11px;">
                <tr>
                    <td></td>
                    <td style="font-size: 11px;">TOTAL</td>
                    <td style="font-size: 11px;"><span style="font-family: DejaVu Sans;">&#8369;</span>{{trim($total,'₱ ')}}</td>
                </tr>
            </tfoot>
        </table>

        <table style="border-collapse: collapse; border: none; font-size: 10px; margin-top: 10px;">
            <tbody>
                <tr>
                    <td width="50%" style="border: none;"></td>
                    <td width="50%" style="text-align: center; padding-top: 50px; border: none;">
                        <span style="font-weight: bold; font-size: 12px; color: #000000; text-transform: uppercase;">Ingrid T. Abella-Colcol</span><hr style="margin-top: 0px; margin-bottom: 1px; border: .1px solid black; width: 80%;">Accountant III</br> <span style="font-size:9px; color: #606060;">(Authorized Signatory)</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>

