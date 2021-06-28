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

        // Start /FRST Hack to add Freight product code for MYOB
        $lines[$n]['order_id'] = $order->order_id;
        $lines[$n]['order_date'] = $order->modified->format('d-m-Y');

        // Update to get Client->salesrep info not order->salesrep
        $lines[$n]['Sales Person First Name'] = @$salesrep->firstname;
        $lines[$n]['Sales Person Last Name'] = @$salesrep->lastname;
        $lines[$n]['Item Number'] = '/FRST';
        $lines[$n]['Item Description'] = 'Total Items/Cartons Shipped x  Sometimes freight companies may not deliver all your cartons together. Check cartons shipped against what you have received and contact us within 7 days if any discrepancies so we can follow up where the balance is!';
        $lines[$n]['Quantity'] = 1;
        $lines[$n]['Stdprice'] = 0;
        $lines[$n]['Invprice'] = 0;
        $lines[$n]['Co./Last Name'] = $order->client->name;
        $lines[$n]['address1'] = $order->client->address1;
        $lines[$n]['address2'] = $order->client->address2;
        $lines[$n]['address3'] = $order->client->address3;
        $lines[$n]['city'] = $order->client->city;
        $lines[$n]['state'] = $order->client->state;
        $lines[$n]['postcode'] = (int) $order->client->postcode;
        $lines[$n]['parent_company'] = !is_null($order->client->parentGroup) ? $order->client->parentGroup->name : '';

        $lines[$n]['order_contact'] = !is_null($order->order_contact) ? $order->order_contact : ''; 
        $lines[$n]['freight_charge'] = $order->freight_charge;

        $lines[$n]['invoice_delivery_method'] = !is_null($order->client->invoice_delivery_method) ? $order->client->invoice_delivery_method : 'P';

        // End of /FRST hack



        foreach ($order->items as $item) {
            // dump($this->client->name);
            // dump($this->client->parentClient);
            // dump($this->salesrep->name);
            // dump($item->product->product_code);
            $n++;

            $lines[$n]['order_id'] = $order->order_id;
            $lines[$n]['order_date'] = $order->modified->format('d-m-Y');

            // Update to get Client->salesrep info not order->salesrep
            $lines[$n]['Sales Person First Name'] = @$salesrep->firstname;
            $lines[$n]['Sales Person Last Name'] = @$salesrep->lastname;
            $lines[$n]['Item Number'] = $item->product->product_code;
            $lines[$n]['Item Description'] = $item->product->description . '- '. $item->product->size . ' '. $item->product->color_name;// added because MYOB no longer populates blank descriptions
            $lines[$n]['Quantity'] = $item->qty;
            $lines[$n]['Stdprice'] = $item->product->price;
            $lines[$n]['Invprice'] = $item->price;
            $lines[$n]['Co./Last Name'] = $order->client->name;
            $lines[$n]['address1'] = $order->client->address1;
            $lines[$n]['address2'] = $order->client->address2;
            $lines[$n]['address3'] = $order->client->address3;
            $lines[$n]['city'] = $order->client->city;
            $lines[$n]['state'] = $order->client->state;
            $lines[$n]['postcode'] = (int) $order->client->postcode;
            $lines[$n]['parent_company'] = !is_null($order->client->parentGroup) ? $order->client->parentGroup->name : '';

            $lines[$n]['order_contact'] = !is_null($order->order_contact) ? $order->order_contact : ''; 
            $lines[$n]['freight_charge'] = $order->freight_charge;

            $lines[$n]['invoice_delivery_method'] = !is_null($order->client->invoice_delivery_method) ? $order->client->invoice_delivery_method : 'P';

           
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

    function br2spc( $input ) { // remove <br> tags , eg from descriptions
        return preg_replace('/<br\s?\/?>/ius', " ", str_replace("\n","",str_replace("\r","", htmlspecialchars_decode($input))));
    }

    protected function qc($str)
    {
        //$str = $this->clean($str);
        // quote commas in csv
        $str = trim($str); //remove any newlines
        $str = preg_replace('/\s+/', ' ', $str);
        //$str = str_replace(',','","',$str); // changed 21/09/2015 because commas in addresses were screwing things up on export.
        return $this->br2spc(str_replace(',', ';', $str));
    }

    function clean($string) {
     
        return preg_replace('/[^A-Za-z0-9\-\!\.]/', ' ', $string); // Removes special chars.
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
            $o .= $this->qc($l['city']) . ' ' . $l['state'] . ' ' . $l['postcode'] . ','; // Addr 1 - line 3
            $o .= $this->qc('Australia').','; // Addr 1 - line 4
        } else {
            $o .= $this->qc($l['Co./Last Name']) . ','; // Co./Last Name
            $o .= ','; // First Name
            $o .= $this->qc($l['Co./Last Name']) . ','; // Addr 1 - line 1
            $o .= $this->qc($l['address1'] . ' ' . $l['address2']) . ','; // Addr 1 - line 2
            $o .= $this->qc($l['city'])  . ' ' . $l['state'] . ' ' . $l['postcode'] . ','; // Addr 1 - line 3
            $o .= $this->qc('Australia').','; // Addr 1 - line 4
        }

        $o .= ','; // Inclusive
        $o .= ','; // Invoice #
        $o .= date('d/m/Y').','; // Date - order date dd/mm/yyyy, leave blank and myob put
        $o .= $this->qc(substr($l['order_contact'],0,15)) . ','; // Customer PO - insert order_contact
        $o .= ','; // Ship
        // $o .= 'P,'; // Invoice Delivery Method (Email, Print or Both)
        $o .= $l['invoice_delivery_method'] . ','; // Invoice delivery method E,P or B
        $o .= $this->qc($l['Item Number']) . ','; // Item Number
        $o .= $this->qc($l['Quantity']) . ','; // Quantity
        $o .= $this->qc($this->clean($l['Item Description'])).','; // Description

        // Check for stdPrice being zero eg /PARTS
        if(!$l['Stdprice'] > 0){
            // Strange pricing with stdprice = 0 or less eg /Parts
            $l['Stdprice'] = $l['Invprice'];
            $discount = 0;
            $discountPercent =0;
        } else {
            // Normal product pricing
            $discount = $l['Stdprice'] ? 1-($l['Invprice']/$l['Stdprice']) : '';
            $discountPercent = (float) number_format($discount * 100,3, '.', '');
        }
        
        
        

        
        $discount = $l['Stdprice'] ? 1-($l['Invprice']/$l['Stdprice']) : 0;
        $discountPercent = (float) number_format($discount * 100,3, '.', '');

        $stdPrice = number_format(($l['Stdprice']/100),3, '.', ''); //dollars.cents 00.000
        $itemPrice =  (float) number_format($stdPrice * (1-($discountPercent / 100)),3);
        $itemGST =  $itemPrice / 10;
        $lineGST = number_format($itemGST * $l['Quantity'],2, '.', '');


        $o .= '$' . $stdPrice . ','; // Price in dollars and cents MUST have $ sign eg $23.450 - ex gst
        $o .= $discountPercent . ','; // Discount

        // handle qty = zero
        $extprice = 0;
        if ($l['Quantity'] > 0) {
            $extprice =  ($itemPrice * $l['Quantity']) ; // dollars and cents
        }
        $o .= '$' .number_format($extprice , 2, '.', '').','; // line total

        $o .= ','; // Job
        $o .= 'Thank you for your order. We appreciate your business.' . ','; // Comment
        $o .= '' . ','; // journal memo

         
        $o .= $this->qc($l['Sales Person Last Name']) . ','; // Sales Person Last Name
        $o .= $this->qc($l['Sales Person First Name']) . ','; // Sales Person first Name
        $o .= ','; // Shipping Date
        $o .= ','; // Referral Source
        $o .= 'GST' . ','; // Tax code
       
        $o .= '$' .$lineGST . ','; // Gst amount for line
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
        $o .= ','; // bsbCan
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
