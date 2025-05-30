<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use App\Color;
use App\Vehicle;
use App\Product;
use App\Invoice;
use App\Setting;
use App\Branch;
use App\SalePart;
use App\Updatekey;
use App\PaymentMethod;
use App\BranchSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SalesPartcontroller extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	//sales list
	// public function index()
	// {

		// $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// // $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		// if (!isAdmin(Auth::User()->role_id)) {
		// 	if (getUsersRole(Auth::user()->role_id) == 'Customer') {
		// 		$sales = SalePart::where('product_id', '!=', '<>')->groupby('bill_no')->where('customer_id', '=', Auth::User()->id)->orderBy('id', 'DESC')->get();
		// 	} elseif (getUsersRole(Auth::user()->role_id) == 'Employee') {
		// 		$sales = SalePart::where([['branch_id', $currentUser->branch_id]])->groupby('bill_no')->where('product_id', '!=', '<>')->orderBy('id', 'DESC')->get();
		// 	} elseif (getUsersRole(Auth::user()->role_id) == 'Support Staff' || getUsersRole(Auth::user()->role_id) == 'Accountant' || getUsersRole(Auth::user()->role_id) == 'Branch Admin') {
   
		// 		$sales = SalePart::where('product_id', '!=', '<>')->where('branch_id', '=', $currentUser->branch_id)->groupby('bill_no')->orderBy('id', 'DESC')->get();
		// 	} else {
		// 		$sales = SalePart::where('product_id', '!=', '<>')->where('branch_id', '=', $currentUser->branch_id)->groupby('bill_no')->orderBy('id', 'DESC')->get();
		// 	}
		// } else {
		// 	$sales = SalePart::where('product_id', '!=', '<>')->groupby('bill_no')->orderBy('id', 'DESC')->get();
		// }
	// 	return view('sales_part.list', compact('sales'));
	// }
    public function index(Request $request)
	{ 
		if ($request->ajax()) {
			$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::id()]])->first();
			$query = SalePart::query()->leftJoin('users as customer', 'tbl_sale_parts.customer_id', '=', 'customer.id')
			->leftJoin('users as assignee', 'tbl_sale_parts.salesmanname', '=', 'assignee.id')
				->select(
					'tbl_sale_parts.id',
					'tbl_sale_parts.branch_id',
					'tbl_sale_parts.bill_no',
					'tbl_sale_parts.customer_id',
					'tbl_sale_parts.salesmanname',
					'assignee.name as assign_to',
					'tbl_sale_parts.date',
					'customer.name as customer_name',
				);
	
			
			// Apply role-based filters
			if (!isAdmin($currentUser->role_id)) {
				$userRole = getUsersRole($currentUser->role_id);
		
				if ($userRole === 'Customer') {
					$query->where('customer_id', '=', $currentUser->id)
						->where('product_id', '!=', '<>'); // Ensures valid products
				} elseif ($userRole === 'Employee') {
					$query->where('tbl_sale_parts.branch_id', '=', $currentUser->branch_id)
						->where('product_id', '!=', '<>')
						->groupBy('bill_no');
		
					if (Gate::allows('salespart_owndata')) {
						$query->where('create_by', '=', $currentUser->id);
					}
				} elseif (in_array($userRole, ['Support Staff', 'Accountant', 'Branch Admin'])) {
					$query->where('tbl_sale_parts.branch_id', '=', $currentUser->branch_id)
						->where('product_id', '!=', '<>')
						->groupBy('bill_no');
		
					if (Gate::allows('salespart_owndata')) {
						$query->where('create_by', '=', $currentUser->id);
					}
				} else {
					$query->where('branch_id', '=', $currentUser->branch_id)
						->where('product_id', '!=', '<>')
						->groupBy('bill_no');
				}
			} else {
				// Admin sees all sales
				
			}
	
			// Search functionality
			$searchValue = $request->input('search.value');
			if ($searchValue) {
				$query->where(function ($q) use ($searchValue) {
					$q->where('bill_no', 'LIKE', "%{$searchValue}%")
						->orWhere('assignee.name', 'LIKE', "%{$searchValue}%")
						->orWhere('customer.name', 'LIKE', "%{$searchValue}%");
				});
			}
	
			// Get the total filtered records
			$filteredRecords = $query->count();
	
			// Pagination and ordering
			$columns = ['id', 'bill_no', 'customer_id', 'salesmanname', 'date'];
			$data = $query->orderBy(
					$columns[$request->input('order.0.column', 0)],
					$request->input('order.0.dir', 'asc')
				)
				->offset($request->input('start', 0))
				->limit($request->input('length', 10))
				->get();
	
			// Prepare response
			$response = [
				'draw' => intval($request->input('draw')),
				'recordsTotal' => SalePart::count(),
				'recordsFiltered' => $filteredRecords,
				'data' => $data->map(function ($sale) {
					$actionDropdown = '
					<div class="dropdown_toggle">
						<img src="' . asset('public/img/list/dots.png') . '" class="btn dropdown-toggle border-0" type="button" id="dropdownMenuButtonaction" data-bs-toggle="dropdown" aria-expanded="false">
						<ul class="dropdown-menu heder-dropdown-menu action_dropdown shadow py-2" aria-labelledby="dropdownMenuButtonaction">';

				// Role-based filtering
				if (getUserRoleFromUserTable(Auth::User()->id) == 'admin' || getUserRoleFromUserTable(Auth::User()->id) == 'supportstaff' || getUserRoleFromUserTable(Auth::User()->id) == 'accountant' || getUserRoleFromUserTable(Auth::User()->id) == 'employee' || getUserRoleFromUserTable(Auth::User()->id) == 'Customer' || getUserRoleFromUserTable(Auth::User()->id) == 'branch_admin') {
					$sales_invoice = getInvoiceNumbers($sale->id);

					// Create Invoice option
					if ($sales_invoice == 'No data') {
						if (Gate::allows('salespart_add')) {
							$actionDropdown .= '
								<li>
									<a href="' . url('invoice/sale_part_invoice/add/' . $sale->id) . '" class="dropdown-item">
										<img src="' . asset('public/img/list/create.png') . '" class="me-3">' . trans('message.Create Invoice') . '
									</a>
								</li>';
						}
					} else {
						// View Invoices option
						$actionDropdown .= '
							<li>
								<button type="button" data-bs-toggle="modal" data-bs-target="#myModal" saleid="' . $sale->id . '" invoice_number="' . $sales_invoice . '" url="' . url('/sales_part/list/modal') . '" class="dropdown-item save">
									<img src="' . asset('public/img/list/Vector.png') . '" class="me-3">' . trans('message.View Invoices') . '
								</button>
							</li>';
					}

					// Edit option
					if (auth()->user()->can('salespart_edit')) {
						$actionDropdown .= '
							<li>
								<a href="' . url('sales_part/edit/' . $sale->id) . '" class="dropdown-item">
									<img src="' . asset('public/img/list/Edit.png') . '" class="me-3">' . trans('message.Edit') . '
								</a>
							</li>';
					}
				}

				$actionDropdown .= '</ul></div>';

					return [
						'id' => $sale->id,
						'bill_no' => '<a href="#" class="salespartsave" data-id="' . $sale->id . '">' . $sale->bill_no . '</a>',
						'customer_name' => getCustomerName($sale->customer_id)?: trans('message.Not Added'),
						'date' => date(getDateFormat(), strtotime($sale->date)),
						'salesman_name' => getAssignedName($sale->salesmanname)?: trans('message.Not Added'),
					'action' => $actionDropdown,

					];
				}),
			];
	
			return response()->json($response);
		}
	
		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		if (!isAdmin(Auth::User()->role_id)) {
			if (getUsersRole(Auth::user()->role_id) == 'Customer') {
				$sales = SalePart::where('product_id', '!=', '<>')->groupby('bill_no')->where('customer_id', '=', Auth::User()->id)->orderBy('id', 'DESC')->get();
			} elseif (getUsersRole(Auth::user()->role_id) == 'Employee') {
				$sales = SalePart::where([['branch_id', $currentUser->branch_id]])->groupby('bill_no')->where('product_id', '!=', '<>')->orderBy('id', 'DESC')->get();
			} elseif (getUsersRole(Auth::user()->role_id) == 'Support Staff' || getUsersRole(Auth::user()->role_id) == 'Accountant' || getUsersRole(Auth::user()->role_id) == 'Branch Admin') {
   
				$sales = SalePart::where('product_id', '!=', '<>')->where('branch_id', '=', $currentUser->branch_id)->groupby('bill_no')->orderBy('id', 'DESC')->get();
			} else {
				$sales = SalePart::where('product_id', '!=', '<>')->where('branch_id', '=', $currentUser->branch_id)->groupby('bill_no')->orderBy('id', 'DESC')->get();
			}
		} else {
			$sales = SalePart::where('product_id', '!=', '<>')->groupby('bill_no')->orderBy('id', 'DESC')->get();
		}
		return view('sales_part.list', compact('sales'));
	}
	//sales add form
	public function addsales()
	{
		$characters = '0123456789';
		$code =  'SP' . '' . substr(str_shuffle($characters), 0, 6);

		$color = DB::table('tbl_colors')->where('soft_delete', '=', 0)->get()->toArray();
		$customer = DB::table('users')->where([['role', '=', 'Customer'], ['soft_delete', '=', 0]])->get()->toArray();
		$taxes = DB::table('tbl_account_tax_rates')->where('soft_delete', '=', 0)->get()->toArray();
		$payment = DB::table('tbl_payments')->where('soft_delete', '=', 0)->get()->toArray();


		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();

		if (isAdmin(Auth::User()->role_id)) {
			$branchDatas = Branch::get();
			$employee = DB::table('users')->where([['role', 'Employee'], ['soft_delete', 0]])->get()->toArray();
			$brand = DB::table('tbl_products')->where([['category', '=', 1], ['soft_delete', '=', 0]])->get()->toArray();
			$manufacture_name = DB::table('tbl_product_types')->where('soft_delete', '=', 0)->get()->toArray();
			// } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			// 	$branchDatas = Branch::get();
			// 	$employee = DB::table('users')->where('role', '=', 'Employee')->where('soft_delete', '=', 0)->get()->toArray();
			// 	$brand = DB::table('tbl_products')->where([['category', '=', 1], ['soft_delete', '=', 0]])->get()->toArray();
			// 	$manufacture_name = DB::table('tbl_product_types')->where('soft_delete', '=', 0)->get()->toArray();
		} else {
			$branchDatas = Branch::where('id', $currentUser->branch_id)->get();
			$employee = DB::table('users')->where([['role', 'Employee'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();
			$brand = DB::table('tbl_products')->where([['category', '=', 1], ['soft_delete', '=', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();
			$manufacture_name = DB::table('tbl_product_types')->where('soft_delete', '=', 0)->get()->toArray();
		}


		$tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'salepart'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

		return view('sales_part.add', compact('customer', 'employee', 'code', 'color', 'taxes', 'payment', 'brand', 'manufacture_name', 'tbl_custom_fields', 'branchDatas'));
	}

	//color add
	public function coloradd(Request $request)
	{
		$color_name = $request->c_name;
		$colors = DB::table('tbl_colors')->where('color', '=', $color_name)->count();
		if ($colors == 0) {
			$color = new Color;
			$color->color = $color_name;
			$color->save();
			echo $color->id;
		} else {
			return '01';
		}
	}

	//color delete
	public function colordelete(Request $request)
	{
		$id = $request->colorid;
		//$color = DB::table('tbl_colors')->where('id','=',$id)->delete();
		$color = DB::table('tbl_colors')->where('id', '=', $id)->update(['soft_delete' => 1]);
	}

	//get chassis
	public function getchasis(Request $request)
	{
		$modelname = $request->modelname;
		$vehicle_id = $request->vehicle_id;
		$sales = DB::table('tbl_sales')->where('vehicle_id', '!=', $vehicle_id)->get()->toArray();
		$count = DB::table('tbl_sales')->where('vehicle_id', '!=', $vehicle_id)->count();
		if ($count > 0) {
			foreach ($sales as $sale) {
				$ve_id[] = $sale->vehicle_id;
				$csno[] = $sale->chassisno;
			}
			$data = DB::table('tbl_vehicles')->whereNotIn('id', $ve_id)->where('modelname', $modelname)->get()->toArray();
		} else {
			$data = DB::table('tbl_vehicles')->where('modelname', '=', $modelname)->get()->toArray();
		}
?>
		<?php foreach ($data as $datas) { ?>
			<option value="<?php echo $datas->chassisno; ?>"><?php echo $datas->chassisno; ?></option>
		<?php	} ?>
		<?php
	}

	//get vehicle data
	public function getrecord(Request $request)
	{
		$vid = $request->vehicale_id;
		$v_record = DB::table('tbl_vehicles')->where('id', '=', $vid)->first();
		$record = json_encode($v_record);
		echo $record;
	}

	//get model name
	public function getmodel_name(Request $request)
	{
		$brand_name = $request->vehicale_id;

		$data = DB::table('tbl_products')->where([['id', '=', $brand_name], ['soft_delete', '=', 0]])->first();
		$purchase = DB::table('tbl_purchase_history_records')->where([['product_id', $brand_name], ['soft_delete', '=', 0]])->where('category', 1)->get();

		$s = [];
		$sp = [];
		foreach ($purchase as $purchases) {
			$s[] = $purchases->qty;
		}
		$sums = array_sum($s);
		$purchase_p = DB::table('tbl_sale_parts')->where('product_id', $brand_name)->get();
		foreach ($purchase_p as $purchasesd) {
			$sp[] = $purchasesd->quantity;
		}
		$sumsd = array_sum($sp);
		if ($sums >= $sumsd || $sumsd == 0) {
			if ($sumsd == 0) {
				$diff = $sums;
			} else {
				$diff = $sums - $sumsd;
			}
		} else {
			$diff = "not available";
		}
		return array('price' => $data->price, 'qty' => $diff);
	}

	//get tax per
	public function gettaxespercentage(Request $request)
	{
		$t_name = $request->t_name;
		if (!empty($t_name)) {
			$t_record = DB::table('tbl_account_tax_rates')->where('taxname', '=', $t_name)->first();
			$tax = $t_record->tax;
			echo $tax;
		} else {
			echo 0;
		}
	}

	// free services
	public function getservices(Request $request)
	{
		$interval = $request->interval;
		$date_gape = $request->date_gape;
		$no_service = $request->no_service;
		$characters = '0123456789';
		$code =  'C' . '' . substr(str_shuffle($characters), 0, 6);
		$new_interval = $interval;

		$new_interval_array = array();
		$no_service_arry = array();
		$get_service_data = date('Y-m-d');

		$addmonth = (int)$interval;
		$addday = (int)$date_gape;
		for ($j = 1; $j <= $no_service; $j++) {

			$no_service_date = date('Y-m-d', strtotime("+" . $addmonth . " months", strtotime($get_service_data)));
			$no_service_date_gap = date('Y-m-d', strtotime("+" . $addday . " days", strtotime($no_service_date)));

			$get_service_data = $no_service_date;
			$codes = $code . $j;
			$no_service_arry[$get_service_data] = ("$j Service");
		?>
			<table class="table" align="center" style="width:80%;">
				<tr class="data_of_type">
					<td class="text-center"><?php echo $j; ?></td>
					<td class="text-center"><input type="text" class="form-control first_width" value="<?php echo $no_service_date . '  To  ' . $no_service_date_gap; ?>" name="service[service_date][]"></td>
					<td class="text-center"><input type="text" class="form-control second_width" name="service[service_text][]" value="<?php echo $no_service_arry[$get_service_data]; ?>"></td>
					<td class="text-center"><input type="text" class="form-control second_width" name="service[service_job][]" value="<?php echo $codes; ?>" readonly></td>
				</tr>
			</table>
		<?php
		}
	}

	//get taxes
	public function gettaxes(Request $request)
	{
		$id = $request->row_id;
		$ids = $id + 1;
		$rowid = 'row_id_' . $ids;

		$taxes = DB::table('tbl_account_tax_rates')->get()->toArray();
		?>
		<tr id="<?php echo $rowid; ?>">
			<input type="hidden" value="<?php echo $ids; ?>" name="account[tr_id][]" />
			<td><select name="account[tax_name][]" url="<?php echo url('sales/add/gettaxespercentage'); ?>" class="form-control tax_name" row_did="<?php echo $ids; ?>" data-id="<?php echo $ids; ?>" required="">
					<option value="0">Select Tax</option><?php foreach ($taxes as $tax) { ?><option value="<?php echo $tax->taxname; ?>"><?php echo $tax->taxname; ?></option> <?php } ?>
				</select>
			</td>
			<td>
				<input type="text" name="account[tax][]" class="form-control tax" value="" id="tax_<?php echo $ids; ?>" readonly="true">
			</td>
			<td>
				<span class="trash_account" data-id="<?php echo $ids; ?>"><i class="fa fa-trash"></i> Delete</span>
			</td>
		</tr>
		<?php
	}

	//get qty
	public function getqty(Request $request)
	{
		$qty = $request->qty;
		$price = $request->price;
		echo $qty;
		echo $price;
	}

	//sales store
	public function store(Request $request)
	{
		// dd("hello");
		// dd($request->product->Manufacturer_id);

		$this->validate($request, [
			'qty' => 'numeric',
			// 'price' => 'numeric',
		]);

		if (getDateFormat() == 'm-d-Y') {
			$s_date = date('Y-m-d', strtotime(str_replace('-', '/', $request->date)));
		} else {
			$s_date = date('Y-m-d', strtotime($request->date));
		}

		$products = $request->product;
		if (!empty($products)) {
			foreach ($products['product_id'] as $key => $value) {
				//$Manufacturer_id = $products['Manufacturer_id'][$key];
				$Product_id = $products['product_id'][$key];
				$qty = $products['qty'][$key];
				$price = $products['price'][$key];
				$total_price = $products['total_price'][$key];
				$manufacturer_id = $products['Manufacturer_id'][$key];
				// dd($total_price);
				$sales = new SalePart;
				$sales->customer_id = $request->cus_name;
				$sales->bill_no = $request->bill_no;
				$sales->date = $s_date;
				$sales->quantity = $qty;
				$sales->price = $price;
				$sales->total_price = $total_price;
				$sales->salesmanname = $request->salesmanname;
				$sales->product_id = $Product_id;
				$sales->product_type_id = $manufacturer_id;
				$sales->branch_id = $request->branch;

				//custom field save
				$custom = $request->custom;
				$custom_fileld_value = array();
				$custom_fileld_value_jason_array = array();
				if (!empty($custom)) {
					foreach ($custom as $key => $value) {
						if (is_array($value)) {
							$add_one_in = implode(",", $value);
							$custom_fileld_value[] = array("id" => "$key", "value" => "$add_one_in");
						} else {
							$custom_fileld_value[] = array("id" => "$key", "value" => "$value");
						}
					}

					$custom_fileld_value_jason_array['custom_fileld_value'] = json_encode($custom_fileld_value);

					foreach ($custom_fileld_value_jason_array as $key1 => $val1) {
						$salesPartData = $val1;
					}
					$sales->custom_field = $salesPartData;
				}
				$sales->save();
				return redirect('sales_part/list')->with('message', 'Part Sell Added Successfully');
			}
		}
	}

	//modal view for sales
	public function view(Request $request)
	{
		$page_action = $request->page_action;
		// dd('called');
		if (!empty($request->saleid)) {
			// dd("Hello");
			$id = $request->saleid;
			$invoice_number = $request->invoice_number;
			$auto_id = $request->auto_id;
		} else {
			// dd("Hello");
			$id = $request->serviceid;
			$auto_id = $request->auto_id;
		}

		$viewid = $id;
		$sales = SalePart::where('id', '=', $viewid)->first();
		// dd($sales);
		$saless = SalePart::where('bill_no', '=', $sales->bill_no)->get();
		$salesp = SalePart::select(DB::raw("SUM(total_price) AS total_price,bill_no,quantity,date,product_id,price ,customer_id,id,salesmanname"))->where('bill_no', '=', $sales->bill_no)->get();
		$salesps = SalePart::select(DB::raw("SUM(total_price) AS total_price,bill_no,quantity,date,product_id,price ,customer_id,id,salesmanname"))->where('bill_no', '=', $sales->bill_no)->first();

		$v_id = $sales->product_id;
		$vehicale = Product::where('id', '=', $v_id)->first();
		if ($request->saleid) {
			$invioce = Invoice::where([['sales_service_id', $id], ['invoice_number', $invoice_number]])->first();
		} else {
			$invioce = Invoice::where('id', $auto_id)->first();
		}
		$taxes = null;
		if (!empty($invioce->tax_name)) {
			$taxes = explode(', ', $invioce->tax_name);
		}
		$discount = null;
		if ($invioce->discount !== null) {
			$discount = $invioce->discount;
		}

		$logo = Setting::first();
		$updatekey = Updatekey::first();
		$s_key = $updatekey->secret_key;
		$p_key = $updatekey->publish_key;

		//For Custom Field Data
		$tbl_custom_fields_salepart = DB::table('tbl_custom_fields')->where([['form_name', '=', 'salepart'], ['always_visable', '=', 'yes']])->get()->toArray();

		//Custom Field Data of User Table (For Customer Module)
		$tbl_custom_fields_customers = DB::table('tbl_custom_fields')->where([['form_name', '=', 'customer'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

		$html = view('invoice.sales_partinvoicemodel')->with(compact('page_action', 'discount', 'viewid', 'vehicale', 'sales', 'logo', 'invioce', 'taxes', 'p_key', 'saless', 'salesp', 'salesps', 'tbl_custom_fields_salepart', 'tbl_custom_fields_customers'))->render();

		return response()->json(['success' => true, 'html' => $html]);
	}

	//modal print for sales
	public function print(Request $request)
	{
		$page_action = $request->page_action;
		// dd('called');
		if (!empty($request->saleid)) {
			// dd("Hello");
			$id = $request->saleid;
			$invoice_number = $request->invoice_number;
			$auto_id = $request->auto_id;
		} else {
			// dd("Hello");
			$id = $request->serviceid;
			$auto_id = $request->auto_id;
		}
		$viewid = $id;
		$sales = SalePart::where('id', '=', $viewid)->first();
		// dd($sales);
		$saless = SalePart::where('bill_no', '=', $sales->bill_no)->get();
		$salesp = SalePart::select(DB::raw("SUM(total_price) AS total_price,bill_no,quantity,date,product_id,price ,customer_id,id,salesmanname"))->where('bill_no', '=', $sales->bill_no)->get();
		$salesps = SalePart::select(DB::raw("SUM(total_price) AS total_price,bill_no,quantity,date,product_id,price ,customer_id,id,salesmanname"))->where('bill_no', '=', $sales->bill_no)->first();

		$v_id = $sales->product_id;
		$vehicale = Product::where('id', '=', $v_id)->first();
		if ($request->saleid) {
			$invioce = Invoice::where([['sales_service_id', $id], ['invoice_number', $invoice_number]])->first();
		} else {
			$invioce = Invoice::where('id', $auto_id)->first();
		}
		$taxes = null;
		if (!empty($invioce->tax_name)) {
			$taxes = explode(', ', $invioce->tax_name);
		}
		$discount = null;
		if ($invioce->discount !== null) {
			$discount = $invioce->discount;
		}

		$logo = Setting::first();
		$updatekey = Updatekey::first();
		$s_key = $updatekey->secret_key;
		$p_key = $updatekey->publish_key;

		//For Custom Field Data
		$tbl_custom_fields_salepart = DB::table('tbl_custom_fields')->where([['form_name', '=', 'salepart'], ['always_visable', '=', 'yes']])->get()->toArray();

		//Custom Field Data of User Table (For Customer Module)
		$tbl_custom_fields_customers = DB::table('tbl_custom_fields')->where([['form_name', '=', 'customer'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

		return view('invoice.sales_partinvoicemodel_print')->with(compact('page_action', 'discount', 'viewid', 'vehicale', 'sales', 'logo', 'invioce', 'taxes', 'p_key', 'saless', 'salesp', 'salesps', 'tbl_custom_fields_salepart', 'tbl_custom_fields_customers'))->render();
	}

	// sale part delete
	public function destroy($id)
	{
		$salesp = DB::table('tbl_sale_parts')->find($id);
		//$sales = DB::table('tbl_sale_parts')->where('bill_no','=',$salesp->bill_no)->delete();
		$sales = DB::table('tbl_sale_parts')->where('bill_no', '=', $salesp->bill_no)->update(['soft_delete' => 1]);

		return redirect('sales_part/list')->with('message', 'Part Sell Deleted Successfully');
	}

	// sale part delete
	public function sale_part_destroy(Request $request)
	{
		$id = $request->procuctid;
		//$sales = DB::table('tbl_sale_parts')->where('id','=',$id)->delete();
		$sales = DB::table('tbl_sale_parts')->where('id', '=', $id)->update(['soft_delete' => 1]);

		//return redirect('sales_part/list')->with('message','Part Sell Deleted Successfully');
	}

	//sales edit form
	public function edit($id)
	{
		$editid = $id;

		$currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		if (isAdmin(Auth::User()->role_id)) {
			$branchDatas = Branch::get();
			$employee = DB::table('users')->where([['role', 'Employee'], ['soft_delete', 0]])->get()->toArray();
			$sales = SalePart::where([['id', $id]])->first();
			$brand = Product::where([['category', 1], ['soft_delete', 0]])->get();
			$stock = SalePart::where([['bill_no', $sales->bill_no], ['soft_delete', '=', 0]])->get();
		} elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
			$branchDatas = Branch::where('id', $currentUser->branch_id)->get();
			$employee = DB::table('users')->where('role', '=', 'Employee')->where('soft_delete', '=', 0)->get()->toArray();
			$sales = SalePart::where('id', '=', $id)->first();
			$brand = Product::where([['category', 1], ['soft_delete', '=', 0]])->get();
			$stock = SalePart::where([['bill_no', $sales->bill_no], ['soft_delete', '=', 0]])->get();
		} else {
			$branchDatas = Branch::where('id', $currentUser->branch_id)->get();
			$employee = DB::table('users')->where([['role', 'Employee'], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get()->toArray();
			$sales = SalePart::where([['id', $id], ['branch_id', $currentUser->branch_id]])->first();
			$brand = Product::where([['category', 1], ['soft_delete', 0], ['branch_id', $currentUser->branch_id]])->get();
			$stock = SalePart::where([['bill_no', $sales->bill_no], ['branch_id', $currentUser->branch_id], ['soft_delete', '=', 0]])->get();
		}

		$customer = User::where([['role', '=', 'Customer'], ['soft_delete', '=', 0], ['id', $sales->customer_id]])->get();
		$vehicale = Vehicle::where('soft_delete', '=', 0)->get();
		$color = Color::where('soft_delete', '=', 0)->get();
		$payment = PaymentMethod::get();
		$manufacture_name = DB::table('tbl_product_types')->where('soft_delete', '=', 0)->get()->toArray();

		//Custom Field Data
		$tbl_custom_fields = DB::table('tbl_custom_fields')->where([['form_name', '=', 'salepart'], ['always_visable', '=', 'yes'], ['soft_delete', '=', 0]])->get()->toArray();

		return view('sales_part.edit', compact('sales', 'editid', 'vehicale', 'customer', 'payment', 'color', 'employee', 'brand', 'stock', 'tbl_custom_fields', 'manufacture_name', 'branchDatas'));
	}

	//sales update
	public function update(Request $request, $id)
	{
		//dd($request->all());

		/*$this->validate($request, [
			'qty' => 'numeric',
			//'price' => 'numeric',
	    ]);*/

		if (getDateFormat() == 'm-d-Y') {
			$s_date = date('Y-m-d', strtotime(str_replace('-', '/', $request->date)));
		} else {
			$s_date = date('Y-m-d', strtotime($request->date));
		}
		$products = $request->product;
		if (!empty($products)) {

			// Fetch existing records
			$existingRecords = SalePart::where('id', '!=', '')->get();
			$existingTrIds = $existingRecords->pluck('id')->toArray();

			foreach ($products['tr_id'] as $key => $tr_id) {
				$Product_id = $products['product_id'][$key];
				$qty = $products['qty'][$key];
				$price = $products['price'][$key];
				$total_price = $products['total_price'][$key];
				// $purchase_hiatory_id = $products['tr_id'][$key];
				// $p_id = DB::table('tbl_sale_parts')->find($id);
				$manufacturer_id = $products['Manufacturer_id'][$key];

				// Check if tr_id exists in existing records
				$index = array_search($tr_id, $existingTrIds);

				// If tr_id exists, update the record
				if ($index !== false) {
					$sales = $existingRecords[$index];
					// $sales = SalePart::find($id);
					$sales->customer_id = $request->cus_name;
					$sales->bill_no = $request->bill_no;
					$sales->date = $s_date;
					$sales->quantity = $qty;
					$sales->price = $price;
					$sales->total_price = $total_price;
					$sales->salesmanname = $request->salesmanname;
					$sales->product_id = $Product_id;
					$sales->product_type_id = $manufacturer_id;
					$sales->branch_id = $request->branch;

					//Custom Field Data
					$custom = $request->custom;
					$custom_fileld_value = array();
					$custom_fileld_value_jason_array = array();
					if (!empty($custom)) {
						foreach ($custom as $key => $value) {
							if (is_array($value)) {
								$add_one_in = implode(",", $value);
								$custom_fileld_value[] = array("id" => "$key", "value" => "$add_one_in");
							} else {
								$custom_fileld_value[] = array("id" => "$key", "value" => "$value");
							}
						}

						$custom_fileld_value_jason_array['custom_fileld_value'] = json_encode($custom_fileld_value);

						foreach ($custom_fileld_value_jason_array as $key1 => $val1) {
							$salesPartData = $val1;
						}
						$sales->custom_field = $salesPartData;
					}
					$sales->save();
					// Remove the tr_id from existingTrIds array to keep track of processed records
					unset($existingTrIds[$index]);
				} else {
					// If tr_id doesn't exist, create a new record
					$sales = new SalePart;
					$sales->customer_id = $request->cus_name;
					$sales->bill_no = $request->bill_no;
					$sales->date = $s_date;
					$sales->quantity = $qty;
					$sales->price = $price;
					$sales->total_price = $total_price;
					$sales->salesmanname = $request->salesmanname;
					$sales->product_id = $Product_id;
					$sales->product_type_id = $manufacturer_id;
					$sales->branch_id = $request->branch;

					//Custom Field Data
					$custom = $request->custom;
					$custom_fileld_value = array();
					$custom_fileld_value_jason_array = array();
					if (!empty($custom)) {
						foreach ($custom as $key => $value) {
							if (is_array($value)) {
								$add_one_in = implode(",", $value);
								$custom_fileld_value[] = array("id" => "$key", "value" => "$add_one_in");
							} else {
								$custom_fileld_value[] = array("id" => "$key", "value" => "$value");
							}
						}

						$custom_fileld_value_jason_array['custom_fileld_value'] = json_encode($custom_fileld_value);

						foreach ($custom_fileld_value_jason_array as $key1 => $val1) {
							$salesPartData = $val1;
						}
						$sales->custom_field = $salesPartData;
					}
					$sales->save();
				}
			}
			// Delete records with tr_ids not present in $products
			if (!empty($existingTrIds)) {
				SalePart::whereIn('id', $existingTrIds)->where('bill_no', $request->bill_no)->update(['soft_delete' => 1]);
			}
		}
		return redirect('sales_part/list')->with('message', 'Part Sell Updated Successfully');
	}

	// get product name
	public function getproductname(Request $request)
	{
		$id = $request->row_id;
		$ids = $id + 1;
		$rowid = 'row_id_' . $ids;

		$product = DB::table('tbl_products')->where([['category', '=', 1], ['soft_delete', '=', 0]])->get()->toArray();
		$manufacture_name = DB::table('tbl_product_types')->where('soft_delete', '=', 0)->get()->toArray();

		$html = view('sales_part.newproduct')->with(compact('id', 'ids', 'rowid', 'product', 'manufacture_name'))->render();
		return response()->json(['success' => true, 'html' => $html]);
	}

	//productitem
	public function productitem(Request $request)
	{
		$id = $request->m_id;

		$tbl_products = DB::table('tbl_products')->where([['product_type_id', '=', $id], ['soft_delete', '=', 0]])->get()->toArray();

		if (!empty($tbl_products)) {   ?>
			<option value="">--Select Product--</option>
			<?php
			foreach ($tbl_products as $tbl_productss) { ?>
				<option value="<?php echo  $tbl_productss->id; ?>"><?php echo $tbl_productss->name; ?></option>
			<?php
			}
		} else {
			?>
			<option value="">--Select Product--</option>
<?php
		}
	}

	// get total price for product
	public function getAvailableProduct(Request $request)
	{
		$productid = $request->productid;
		$Currentstock = getStockCurrent($productid);

		// $currentUser = User::where([['soft_delete', 0], ['id', '=', Auth::User()->id]])->orderBy('id', 'DESC')->first();
		// $adminCurrentBranch = BranchSetting::where('id', '=', 1)->first();
		// $branchId = "";
		// if (isAdmin(Auth::User()->role_id)) {
		// 	$branchId = $adminCurrentBranch->branch_id;
		// } elseif (getUsersRole(Auth::user()->role_id) == 'Customer') {
		// 	$branchId = "";
		// } else {
		// 	$branchId = $currentUser->branch_id;
		// }
		//$cellstock = DB::table('tbl_service_pros')->where('product_id','=',$productid)->get()->toArray();
		// $cellstock = DB::table('tbl_service_pros')
		// 	->join('tbl_services', 'tbl_service_pros.service_id', '=', 'tbl_services.id')
		// 	->where('tbl_services.branch_id', '=', $branchId)
		// 	->where('tbl_service_pros.product_id', '=', $productid)->get()->toArray();

		// $celltotal = 0;
		// foreach ($cellstock as $cellstocks) {
		// 	$cell_stock = $cellstocks->quantity;
		// 	$celltotal += $cell_stock;
		// }


		// //$salepart_stocks = DB::table('tbl_sale_parts')->where('product_id','=',$productid)->get()->toArray();
		// $salepart_stocks = DB::table('tbl_sale_parts')->where([['product_id', '=', $productid], ['branch_id', '=', $branchId]])->get()->toArray();
		// $salepart_total = 0;
		// foreach ($salepart_stocks as $salepart_stock) {
		// 	$salepart_stock = $salepart_stock->quantity;
		// 	$salepart_total += $salepart_stock;
		// }

		// $stockdata = DB::table('tbl_stock_records')
		// 	->join('tbl_products', 'tbl_stock_records.product_id', '=', 'tbl_products.id')
		// 	->join('tbl_purchase_history_records', 'tbl_products.id', '=', 'tbl_purchase_history_records.product_id')
		// 	->join('tbl_purchases', 'tbl_purchase_history_records.purchase_id', '=', 'tbl_purchases.id')
		// 	->where('tbl_products.id', '=', $productid)
		// 	->where('tbl_stock_records.branch_id', '=', $branchId)
		// 	->get()->toArray();

		// $fullstock = 0;
		// if (!empty($stockdata)) {
		// 	foreach ($stockdata as $stockdatas) {
		// 		$fullstock += $stockdatas->qty;
		// 	}
		// }

		// $total_salepart_service_stock = $celltotal + $salepart_total;
		// $Currentstock = $fullstock - $total_salepart_service_stock;

		$qty = $request->qty;
		if ($qty > $Currentstock) {
			//echo 1;
			return response()->json(['success' => 1, 'currentStock' => $Currentstock]);
		}
	}
}
