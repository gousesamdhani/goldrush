<div class="life_insurance_banner">
	<div class="tc">
		<div class="insurance_banner_title f300 mt100 pt40">Personal-connected insurance means</div>
		<div class="insurance_sub_title f700">you pay less</div>
		<div class="insurance_banner_title f300">for living well.</div>
		<div class="btn_holder mt50">
			<a href="#" class="quote_btn pl50 pr50 f700 ptm pbm" id="get_quote1_v2b">Get a Quote</a>
			<div class="mt30">
				<a href="#" class="learn_btn pl50 pr50 f700 ptm pbm" id="get_quote1_v2b">Learn More</a>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<div class="container pb50" style="margin-top: 50px; padding-top: 75px; width: 1000px;">
	<div class="first_border_height"></div>
	<div class="life_insurance">
		<div class="min_left pb50 border_right">
			<div class="f34 f700 mt80 pt40">
				Today...
			</div>
			<div class="f26 text-left w400">
				The average term life insurance policy gives you a monthly price that you pay for the set term.
			</div>
			<div class="mt30 pb50">
				<a href="#" class="quote_btn pl50 pr50 f700 ptm pbm" id="get_quote1_v2b">try sureify</a>
			</div>
		</div>
		<div class="min_right">
			<center>
				<div class="today_graph mt10 pb45 mlxl">
					
				</div>
			</center>
		</div>
		<div class="clear"></div>
	</div>
</div>
<div class="policy_bg mt80 pb60">
	<div class="container" style="margin-top: 20px; padding-top: 55px; width: 1000px;">
		<div class="f34 f700 color_ash text-center">
			Healthy Life = Big Savings
		</div>
		<div class="f26 color_ash text-center mt10">
			A healthy life allows Sureify to give you money back each month. If your devices show healthy improvements, we reward you with savings - because you deserve it!
		</div>
		<div class="mt30 pb50 text-center">
			<a href="#" class="quote_btn pl50 pr50 f700 ptm pbm" id="get_quote1_v2b">start saving</a>
		</div>
		<div class="bigsavings_graph">

		</div>
	</div>
</div>
<div class="container pb40" style="margin-top: 20px; padding-top: 75px; width: 1000px;">
	<div class="break_down mt40">
		<div class="min_left">
			<div class="f34 f700 color_ash">The breakdown</div>
		</div>
		<div class="min_right">
			<div class="f22 fw100 color_ash">
				You start with a personal base rate, and we give you money back based on healthy behaviors. The next month's fee is the newly underwritten total.
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<div class="breakdown_graph mt60">
	</div>
	<div class="mt60 pb20 text-center">
		<a href="#" class="quote_btn pl50 pr50 f700 ptm pbm" id="get_quote1_v2b">get your rate</a>
	</div>
</div>



</div>



<script type="text/javascript">
	$(document).ready(function () {
		var home = new Home();
		home.goToProtection();

		home.getProtection();
		home.getEstimate('v<?= $version ?>-get-estimate');

		var dropdown = new Dropdown('custom_dropdown');

	});
</script>