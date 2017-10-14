<!--------------------------------------------------------------- ERROR MODAL --------------------------------------------------------------->
<div class="modal fade" id="<?php JQadd($J, "Error_Modal") ?>" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" align="center">Προσοχή!</h4>
			</div>
			<div class="modal-body">
				<table style="height:100%!important;width:100%!important;border:0">
					<tbody>
						<tr style="height:20px"></tr>
						<tr>
							<td align="center">
								<div class="alert alert-danger">
									<span>
										<div class="row" id="<?php JQadd($J, "Error_Modal_Msg") ?>">
										</div>
									</span>
								</div>
							</td>
						</tr>
						<tr style="height:10px"></tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Κλείσιμο</button>
			</div>
		</div>
	</div>
</div>
<!-------------------------------------------------------------------------------------------------------------------------------------------->



