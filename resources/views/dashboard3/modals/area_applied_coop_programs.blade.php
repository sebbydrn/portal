<div class="modal fade" tabindex="-1" role="dialog" id="area_applied_coop_programs_modal">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<h5 class="text-center mb-4">Area Applied For Seed Certification of Seed Cooperatives For Different Rice Programs (RS-CS) <br /> {{$semesterText}}</h5>
				
				<table class="table table-bordered table-sm" id="area_applied_coop_programs_tbl" style="width: 100%;">
					<thead>
						<tr class="text-center">
							<th style="width: 40%;" rowspan="2">Seed Cooperative</th>
							<th style="width: 50%;" colspan="5">Area Applied For Seed Certification (ha)</th>
						</tr>
						<tr class="text-center">
							<th style="width: 10%;">RCEF</th>
							<th style="width: 10%;">NRP</th>
							<th style="width: 10%;">GOLDEN RICE</th>
							<th style="width: 10%;">NONE</th>
							<th style="width: 10%;">OTHERS</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($rs_area_applied_coop_data_all) && $rs_area_applied_coop_data_all != null && !empty($rs_area_applied_coop_data_all))
							@foreach($rs_area_applied_coop_data_all as $item)
								<tr>
									<td>{{$item->cooperative}}</td>
									<td class="text-right">{{number_format($item->rcef, 4)}}</td>
									<td class="text-right">{{number_format($item->nrp, 4)}}</td>
									<td class="text-right">{{number_format($item->gr, 4)}}</td>
									<td class="text-right">{{number_format($item->none, 4)}}</td>
									<td class="text-right">{{number_format($item->others, 4)}}</td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>