<div class="modal-body">
			 <!-- 模態框內容 -->	
				<div>
					<label>代碼</label>
					<input type = "number" name = 'number' 
					@if(old('number'))
						value = "{{old('number')}}"
					@else
						value = '{{$data->number}}'
					@endif
					>
					<div id = 'numberinfo' style = 'color:darkred'>
						
					</div>
				</div>
				<div>
					<label>狀態名稱</label>
					<input type = "test" name = 'status' 
					@if(old('status'))
						value = "{{old('status')}}"
					@else
						value = '{{$data->status}}'
					@endif
					>
					<div id = 'statusinfo' style = 'color:darkred'>
					</div>
				</div>					
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" 
						data-dismiss="modal" id = 'Editcancel' onclick = "resetall('edit')">取消
				</button>
				<input type = 'reset' value = "重置"  id = 'editreset' style = 'display:none' >
				<input type = 'submit' value = "確認送出"  class="btn btn-primary" onclick = 'update({{$data->id}})'>
			</div>

