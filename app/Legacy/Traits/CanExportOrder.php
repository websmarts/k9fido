<?php
namespace App\Legacy\Traits;

trait CanExportOrder
{

    public function export($order)
    {
        $o = '';
        $header = 'Co./Last Name,First Name,Addr 1 - Line 1,           - Line 2,           - Line 3,           - Line 4,Inclusive,Invoice #,Date,Customer PO,Ship Via,Delivery Status,Item Number,Quantity,Description,Price,Inc-Tax Price,Discount,Total,Inc-Tax Total,Job,Comment,Journal Memo,Salesperson Last Name,Salesperson First Name,Shipping Date,Referral Source,Tax Code,Non-GST Amount,GST Amount,LCT Amount,Freight Amount,Inc-Tax Freight Amount,Freight Tax Code,Freight Non-GST Amount,Freight GST Amount,Freight LCT Amount,Sale Status,Currency Code,Exchange Rate,Terms - Payment is Due,           - Discount Days,           - Balance Due Days,           - % Discount,           - % Monthly Charge,Amount Paid,Payment Method,Payment Notes,Name on Card,Card Number,Expiry Date,Authorisation Code,BSB,Account Number,Drawer/Account Name,Cheque Number,Category,Location ID,Card ID,Record ID' . "\r\n";

        $o = $this->get_order_export_data($order);
        $filename = $order->order_id;

        $o = $header . $o;

        // echo '<pre>CanExportOrder Trait';
        // dd($o);

        // output as csv file.
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="TO_' . $filename . '.txt"');
        echo $o;
        exit;

    }

    protected function get_order_export_data($order)
    {

        $o = '';
        $lines = [];
        $n = 0;

        $salesrep = $order->client->myrep();
        // dd($salesrep->firstname);
        foreach ($order->items as $item) {
            // dump($this->client->name);
            // dump($this->client->parentClient);
            // dump($this->salesrep->name);
            // dump($item->product->product_code);

            $lines[$n]['order_id'] = $order->order_id;
            $lines[$n]['order_date'] = $order->modified->format('d-m-Y');

            // Update to get Client->salesrep info not order->salesrep
            $lines[$n]['Sales Person First Name'] = @$salesrep->firstname;
            $lines[$n]['Sales Person Last Name'] = @$salesrep->lastname;
            $lines[$n]['Item Number'] = $item->product->product_code;
            $lines[$n]['Quantity'] = $item->qty;
            $lines[$n]['Stdprice'] = $item->product->price;
            $lines[$n]['Invprice'] = $item->price;
            $lines[$n]['Co./Last Name'] = $order->client->name;
            $lines[$n]['address1'] = $order->client->address1;
            $lines[$n]['address2'] = $order->client->address2;
            $lines[$n]['address3'] = $order->client->address3;
            $lines[$n]['city'] = $order->client->city;
            $lines[$n]['postcode'] = $order->client->postcode;
            $lines[$n]['parent_company'] = !is_null($order->client->parentGroup) ? $order->client->parentGroup->name : '';

            $n++;
        }

        $lines = collect($lines);
        //dd($lines);
        foreach ($lines as $l) {
            $o .= $this->format_line($l);
        }
        // dd($o);
        // mark the order as exported
        // 'update system_orders set `exported`="yes" where id=' . $orderId;
        $order->exported = "yes";
        $order->save();

        return $o;

    }

    protected function qc($str)
    {
        // quote commas in csv
        $str = trim($str); //remove any newlines
        $str = preg_replace('/\s+/', ' ', $str);
        // return str_replace(',','","',$str); // changed 21/09/2015 because commas in addresses were screwing things up on export.
        return str_replace(',', ';', $str);
    }

    protected function format_line($l)
    {

        $o = '';

        if (!empty($l['parent_company'])) {
            $o .= $this->qc($l['parent_company']) . ','; // Co./Last Name
            $o .= ','; // First Name
            $o .= $this->qc($l['Co./Last Name']) . ','; // Addr 1 - line 1
            $o .= $this->qc($l['address1'] . ' ' . $l['address2']) . ','; // Addr 1 - line 2
            $o .= $this->qc($l['city']) . ' ' . $l['postcode'] . ','; // Addr 1 - line 3
            $o .= ','; // Addr 1 - line 4
        } else {
            $o .= $this->qc($l['Co./Last Name']) . ','; // Co./Last Name
            $o .= ','; // First Name
            $o .= ','; // Addr 1 - line 1
            $o .= ','; // Addr 1 - line 2
            $o .= ','; // Addr 1 - line 3
            $o .= ','; // Addr 1 - line 4
        }

        $o .= ','; // Inclusive
        $o .= ','; // Invoice #
        $o .= ','; // Date - order date dd/mm/yyyy, leave blank and myob put
        $o .= ','; // Customer PO
        $o .= ','; // Ship
        $o .= ','; // Delivery Statu
        $o .= $this->qc($l['Item Number']) . ','; // Item Number
        $o .= $this->qc($l['Quantity']) . ','; // Quantity
        $o .= ','; // Description
        $o .= '$' . number_format($l['Stdprice'] / 100, 2, '.', '') . ','; // Price in dollars and cents MUST have $ sign eg $23.45 - ex gst

        // handle qty = zero
        $extprice = 0;
        if ($l['Quantity'] > 0) {
            $extprice = 1.1 * ($l['Stdprice'] * $l['Quantity']) / $l['Quantity']; // cents
        }

        $priceplusgst = number_format($extprice / 100, 2, '.', '');
        $o .= '$' . $priceplusgst . ','; // Price inc gst
        $o .= ','; // Discount

        $invprice = $l['Quantity'] * $l['Invprice'];
        $invprice_plusgst = 1.1 * $invprice;

        $o .= '$' . number_format($invprice / 100, 2, '.', '') . ','; // Total Price ex gst
        $o .= '$' . number_format($invprice_plusgst / 100, 2, '.', '') . ','; // Total Price inc gst
        $o .= ','; // Job
        $o .= 'Thank you for your order. We appreciate your business.' . ','; // Comment
        $o .= ','; // Journal Memo
        $o .= $this->qc($l['Sales Person Last Name']) . ','; // Sales Person First Name
        $o .= $this->qc($l['Sales Person First Name']) . ','; // Sales Person Last Name
        $o .= ','; // Shipping Date
        $o .= ','; // Referral Source
        $o .= 'GST' . ','; // Tax code
        $o .= ','; // Non GST Amount
        $o .= '$' . number_format(($invprice_plusgst - $invprice) / 100, 5, '.', '') . ','; // GST Amount provide at least 3 decimal places
        $o .= ','; // LCT Amount
        $o .= ','; // Freight Amount
        $o .= ','; // Inc Tax Freight Amount
        $o .= 'GST,'; // Freight Tax Code
        $o .= ','; // Freight Non Gst Amount
        $o .= ','; // Freight GST Amount
        $o .= ','; // Freight LCT Amount
        $o .= 'O,'; // Sales Status O for order
        $o .= ','; // Currency Code
        $o .= ','; // Exchange Rate
        $o .= ','; // Terms payment due
        $o .= ',,,,,,,,,,,,,,,,,,' . "\r\n"; // last 22 blank fields

        return $o;
    }
}
