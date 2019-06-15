<?php
namespace App\Legacy\Traits;

trait CanExportOrder2
{

    public function export($id)
    {
        $o = '';
        
        $header = 'Co./Last Name,First Name,Addr 1 - Line 1,Addr 1 - Line 2,Addr 1 - Line 3,Addr 1 - Line 4,Inclusive,Invoice No.,Date,Customer PO,Ship Via,Delivery Status,Item Number,Quantity,Description,Price,Discount,Total,Job,Comment,Journal Memo,Salesperson Last Name,Salesperson First Name,Shipping Date,Referral Source,Tax Code,Tax Amount,Freight Amount,Freight Tax Code,Freight Tax Amount,Sale Status,Currency Code,Exchange Rate,Terms - Payment is Due,           - Discount Days,           - Balance Due Days,           - % Discount,           - % Monthly Charge,Amount Paid,Payment Method,Payment Notes,Name on Card,Card Number,Authorisation Code,BSB,Account Number,Drawer/Account Name,Cheque Number,Category,Location ID,Card ID,Record ID'. "\r\n";
        if (is_array($id)) {
            $n = 0;
            foreach ($id as $orderId) {
                if ($n > 0) {
                    // insert blank line TODO - adjust to new number of empty fields
                    $o .= ",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,\r\n";
                }
                $o .= $this->get_order_export_data($this->getOrderById($orderId));
                $n++;

            }
            $filename = "multiple_" . time();
        } else {

            $o = $this->get_order_export_data($order = $this->getOrderById($id));
            $filename = $order->order_id;
        }

        $o = "{}\r\n". $header . $o;

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
        // dd($salesrep);
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

            $lines[$n]['order_contact'] = !is_null($order->order_contact) ? $order->order_contact : '';
            $lines[$n]['freight_charge'] = $order->freight_charge;

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

        return  $o;

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

        //dd($l);

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
            $o .= $this->qc($l['address1'] . ' ' . $l['address2']) . ','; // Addr 1 - line 2
            $o .= $this->qc($l['city']) . ' ' . $l['postcode'] . ','; // Addr 1 - line 3
            $o .= 'Australia,'; // Addr 1 - line 4
        }

        $o .= ','; // Inclusive
        $o .= ','; // Invoice #
        $o .= ','; // Date - order date dd/mm/yyyy, leave blank and myob put
        $o .= $this->qc($l['order_contact']) . ','; // Customer PO - insert order_contact
        $o .= ','; // Ship
        $o .= 'P,'; // Delivery Status
        $o .= $this->qc($l['Item Number']) . ','; // Item Number
        $o .= $this->qc($l['Quantity']) . ','; // Quantity
        $o .= ','; // Description

        $discount = $l['Stdprice'] ? 1-($l['Invprice']/$l['Stdprice']) : '';

        $invPrice = (float) number_format($l['Invprice'] / 100, 2);
        $lineGST = ( $invPrice / 10 ) * $l['Quantity'];

        $o .= '$' . number_format($l['Stdprice']/100, 2, '.', '') . ','; // Price in dollars and cents MUST have $ sign eg $23.45 - ex gst
        $o .= $discount . ','; // Discount

        // handle qty = zero
        $extprice = 0;
        if ($l['Quantity'] > 0) {
            $extprice =  ($invPrice * $l['Quantity']) ; // dollars and cents
        }
        $o .= '$' .number_format($extprice , 2, '.', ''); // line total

        $o .= ','; // Job
        $o .= 'Thank you for your order. We appreciate your business.' . ','; // Comment
        $o .= '' . ','; // journal memo

         
        $o .= $this->qc($l['Sales Person Last Name']) . ','; // Sales Person Last Name
        $o .= $this->qc($l['Sales Person First Name']) . ','; // Sales Person first Name
        $o .= ','; // Shipping Date
        $o .= ','; // Referral Source
        $o .= 'GST' . ','; // Tax code
       
        $o .= '$' .number_format($lineGST, 2, '.', '') . ','; // Gst amount for line
        $o .= number_format($l['freight_charge'], 2, '.', '') . ','; // Freight Amount
        $o .= 'GST,'; // Freight Tax Code
        $o .= number_format((float) $l['freight_charge'] / 10, 2, '.', '') . ','; // Freight GST Amount - set to 10% of Freight Amount field above
        $o .= 'O,'; // Sales Status O for order
        $o .= ','; // Currency Code
        $o .= ','; // Exchange rate
        $o .= '2,'; // terms
        $o .= '0,'; // discount days
        $o .= '30,'; // balance due days
        $o .= '0,'; // discount %
        $o .= '0,'; // monthly charge
        $o .= ','; // amount paid
        $o .= ','; // payment mthod
        $o .= ','; // payment notes
        $o .= ','; // name on card
        $o .= ','; // card number
        $o .= ','; // Authorization Code
        $o .= ','; // bsb
        $o .= ','; // account number
        $o .= ','; // drawer account name
        $o .= ','; // cheque number
        $o .= ','; // category
        $o .= ','; // location id
        $o .= ','; // card id
        $o .= ','; // record id


        
       
       
        $o .=  "\r\n"; // end of lime

        return $o;
    }
}
