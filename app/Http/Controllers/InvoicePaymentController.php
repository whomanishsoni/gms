<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use App\Income;
use App\Invoice;
use App\BranchSetting;
use Illuminate\Http\Request;
use App\tbl_payment_records;
use App\IncomeHistoryRecord;

class InvoicePaymentController extends Controller
{
	/*public function __construct()
    {
        $this->middleware('auth');
    }*/

	// inclue required files for stripe
	public function initialize()
	{
		parent::initialize();
		require_once('vendor/Stripe/init.php');
		require_once('vendor/Stripe/lib/Stripe.php');
	}

	// stripe payment process 
	public function stripe(Request $request)
	{
		// dd($request->all());

		// \Log::debug('called');
		$invoice_id = $request->invoice_id;
		$stripeamount = $request->invoice_amount;
		$p_key = $request->p_key;
		$invoice_number = $request->invoice_no;
		$stripeToken = $request->stripeToken;
		// dd($stripeamount);

		/*For add selected branch_id for all user with admmin also*/
		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		$adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		$currentCustomer = Invoice::where('id', '=', $invoice_id)->first();
		
		$bramnchId = "";
		if (isAdmin(Auth::User()->role_id)) {
			$bramnchId = $adminCurrentBranch->branch_id;
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			$bramnchId = $currentCustomer->branch_id;
		} else {
			$bramnchId = $currentUser->branch_id;
		}

		try {
			$updatekey = DB::table('updatekey')->first();
			$s_key = $updatekey->secret_key;
			// $key = \Stripe\Stripe::setApiKey($s_key);
			$stripe = new \Stripe\StripeClient($s_key);

				$stripeamount = $request->invoice_amount;
					
				$amount_in_cents = (int) round($stripeamount * 100);
				// $amount_in_cents = round($stripeamount * 100);

                $invoice_number = $request->invoice_no;
                $amount = isset($input['amount']) ? $input['amount'] : 0;

				$redirectUrl = route('stripe.checkout.success', ['session_id' => '{CHECKOUT_SESSION_ID}']);
                // $redirectUrl = route('stripe.checkout.success').'?session_id={CHECKOUT_SESSION_ID}';
                $response =  $stripe->checkout->sessions->create([
                    'success_url' => $redirectUrl,
                    
                    'payment_method_types' => ['link', 'card'],
                    'line_items' => [
                        [
                            'price_data'  => [
                                'product_data' => [
                                    'name' => 'Invoice Payment',
                                ],
                                'unit_amount'  => $amount_in_cents,
                                'currency'     => 'USD',
                            ],
                            'quantity'    => 1
                        ],
                    ],
                    'mode' => 'payment',
                    // 'allow_promotion_codes' => true
                ]);

				// //if(!empty(Input::has('stripeToken'))){
					// if (!empty($stripeToken)) {
						
					// 	$priceId = 'price_1PtqEHB5KLR6gRCpdtRBk1Jb';
						
					// 	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
					// 		header('Content-Type: application/json');
						
					// 		// $YOUR_DOMAIN = 'http://localhost/invoice/list';
						
					// 		$input = json_decode(file_get_contents('php://input'), true);
					// 		$amount = isset($input['amount']) ? $input['amount'] : 0;
						
					// 		$YOUR_DOMAIN = 'http://localhost/garagemaster_web/public/checkout.php';
						
					// 		$checkout_session = $stripe->checkout->sessions->create([
					// 			'payment_method_types' => ['card'],
					// 			'line_items' => [[
					// 				'price_data' => [
					// 					'currency' => 'usd',
					// 					'product_data' => [
					// 						'name' => 'Invoice Payment',
					// 					],
					// 					'unit_amount' => $amount, // Make sure this is the correct amount in the smallest currency unit
					// 				],
					// 				'quantity' => 1,
					// 			]],
					// 			'mode' => 'payment',
					// 			'return_url' => $YOUR_DOMAIN . '/return.html?session_id={CHECKOUT_SESSION_ID}',

					// 			// 'success_url' => $YOUR_DOMAIN . '/success.html?session_id={CHECKOUT_SESSION_ID}',
					// 			'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
					// 		]);
						
					// 		echo json_encode(['sessionId' => $checkout_session->id]);
					// 		exit;
					// 	} else {
					// 		echo json_encode(['error' => 'Invalid request method']);
					// 		exit;
				// 	}

					// stripe payment code V2
						//------------------------	
						// $token  = $_POST['stripeToken'];
						// $charge = \Stripe\Charge::create(array(
						// 	"amount" => $stripeamount * 100,
						// 	// "currency" => "usd",
						// 	"currency" => strtolower(getCurrencyCode()),
						// 	"description" => $invoice_number,
						// 	"source" => $token,
						// ));
					//------------------------	

				// $string_json = json_encode($charge);
				// if (!empty($charge))  {
					
					$tbl_invoices = DB::table('tbl_invoices')->where('invoice_number', '=', $invoice_number)->first();
					$ids = $tbl_invoices->customer_id;
					$invoiceid = $tbl_invoices->id;
					$type = $tbl_invoices->type;
					$paid_amount = $tbl_invoices->paid_amount;
					$amount = $paid_amount + $stripeamount;

					if ($type == 0) {
						$type1 = 'service';
					} elseif ($type == 1) {
						$type1 = 'sales';
					} else {
						$type1 = '';
					}

					$nowdate = date("Y-m-d");
					$characterss = '0123456789';
					$codepay =  'P' . '' . substr(str_shuffle($characterss), 0, 6);
					$handle = fopen("test.txt", "w");
					fwrite($handle, $codepay);	

					if ($paymentSuccess) {
						
						$sql = DB::update("update tbl_invoices set paid_amount='$amount', payment_status='2', charge_id='' where invoice_number=$invoice_number");
					
						// $tbl_incomes->status = '2'; // Full Paid
					
					// $sql = DB::update("update tbl_invoices set paid_amount='$amount',payment_status='2',charge_id='' where invoice_number=$invoice_number");

					$tbl_incomes = new Income;
					$tbl_incomes->invoice_number = $invoice_number; 
					$tbl_incomes->payment_number = $codepay;
					$tbl_incomes->customer_id = $ids;
					$tbl_incomes->status = '2';
					$tbl_incomes->payment_type = 'Stripe';
					$tbl_incomes->date = $nowdate;
					$tbl_incomes->main_label = $type1;
					$tbl_incomes->branch_id = $bramnchId;
					$tbl_incomes->save();	

					$tbl_income_id = DB::table('tbl_incomes')->orderBy('id', 'DESC')->first();
					$tbl_incomes_id = $tbl_income_id->id;

					$tbl_income_history_records = new IncomeHistoryRecord;
					$tbl_income_history_records->tbl_income_id = $tbl_incomes_id;
					$tbl_income_history_records->income_amount = $stripeamount;
					$tbl_income_history_records->income_label = $type1;
					$tbl_income_history_records->branch_id = $bramnchId;
					$tbl_income_history_records->save();

					$tbl_payment_records = new tbl_payment_records;
					$tbl_payment_records->invoices_id = $invoiceid;
					$tbl_payment_records->amount = $stripeamount;
					$tbl_payment_records->payment_date = $nowdate;
					$tbl_payment_records->payment_type = 'Stripe';
					$tbl_payment_records->payment_number = $codepay;
					$tbl_payment_records->branch_id = $bramnchId;
					$tbl_payment_records->save();
				// } 	
				}
				else {
					return redirect()->back()->withErrors('Payment failed or was not completed.');
				}
				// return redirect('invoice/list')->with('message', 'Payment Added Successfully');
			
			// 	else {
			// 	return redirect('invoice/list')->with('message', 'Error! Something went wrong.');
			// }
		}
		 catch (\Stripe\Error\Card $e) {
			$body = $e->getJsonBody();
			$err  = $body['error'];
			$message = $err['message'];
			dd($e->getMessage());
		}
		return redirect($response['url']);
		// return redirect('invoice/list')->with('message', 'Error! Something went wrong.');
	}
}
