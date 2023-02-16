@extends('backend.layouts.app')
<style>
	.align_text
	{
     text-align:center;
	}
	</style>
@php  setlocale(LC_MONETARY,"en_US");  @endphp
@section('content')
	<div class="aiz-titlebar text-left mt-2 mb-3">
		<div class=" align-items-center">
	       <h1 class="h3">{{translate('Profit and Loss')}}</h1>
		</div>
	</div>
	<form class="" id="sort_products" action="" method="GET">
		<div class="card-header row gutters-5">
			<div class="col-md-2 ml-auto d-flex style="margin-left: 0 !important; max-width:28% !important;">
				<input type="hidden" name="startrangedate" class="startrangedate" value="">
				<input type="hidden" name="endrangedate" class="endrangedate" value="">
				<div id="reportrange" style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; width: 100%">
					<i class="las la-calendar"></i>
                      <i class="fa fa-calendar"></i>&nbsp;
                      <span></span> <i class="fa fa-caret-down"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <i class="las la-angle-down"></i>
				</div>
				<button type="submit" id="warehouse_type" name="warehouse_type" class="d-none calendar_submit"><i class="las la-search aiz-side-nav-icon" ></i></button>
				<!-- <input type="submit" value="search" name="btn"> -->
			</div>
		</div>
		<div class="row profit_boxs">
			<div class="col-md-4">
				<div class="sales sales_orang">
					<p>Sales ({{$salescount}})</p>
					<i class="las la-star"></i>
					<div class="row price">
						<div class="col-md-6">
							<div class="prise-box">
								<h2 class="align_text">{{money_format("%(#1n",$totalselprice->memoSubTotal)."\n"}}</h2>
								<p>Total Sales</p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="prise-box">
								<h2 class="align_text">{{money_format("%(#1n",$totalselprice->unit_price)."\n"}}</h2>
								<p>Total Profit</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="sales invoice_green">
					<p>Invoice ({{$invoicecount}})</p>
					<i class="las la-heart"></i>
					<div class="row price">
						<div class="col-md-6">
							<div class="prise-box">
								<h2 class="align_text">{{money_format("%(#1n",$TotalInvoiceprice->memoSubTotal)."\n"}}</h2>
								<p>Total Sales</p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="prise-box">
								<h2 class="align_text">{{money_format("%(#1n",$TotalInvoiceprice->unit_price)."\n"}}</h2>
								<p>Total Profit</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="sales trade_red">
					<p>Trade ({{$tradecount}})</p>
					<i class="las la-random"></i>
					<div class="row price">
						<div class="col-md-6">
							<div class="prise-box">
								<h2 class="align_text">{{money_format("%(#1n",$totalTradePrice->memoSubTotal)."\n"}}</h2>
								<p>Total Sales</p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="prise-box">
								<h2 class="align_text">{{money_format("%(#1n",$totalTradePrice->unit_price)."\n"}}</h2>
								<p>Total Profit</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="sales trade_green">
					<p>TradeNGD ({{$tradengdcount}})</p>
					<i class="las la-dollar-sign"></i>
					<div class="row price">
						<div class="col-md-6">
							<div class="prise-box">
								<h2 class="align_text">{{money_format("%(#1n",$totaltradengdprice->memoSubTotal)."\n"}}</h2>
								<p>Total Sales</p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="prise-box">
								<h2 class="align_text">{{money_format("%(#1n",$totaltradengdprice->unit_price)."\n"}}</h2>
								<p>Total Profit</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
@endsection
@section('script')
<script type="text/javascript">
$(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MM / DD / YYYY') + ' - ' + end.format('MM / DD / YYYY'));
        $('.startrangedate').val(start.format('MM / DD / YYYY'));
        $('.endrangedate').val(end.format('MM / DD / YYYY'));
        
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
    // $('#date_reng').trigger('click');
	$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
		$('.calendar_submit').trigger('click');
	});
});
</script>
@endsection

