<div class="modal-body">
			 <!-- 模態框內容 -->	
				<div>
					<label>帳號</label>
					<span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{{$data->name}}</span>
					<input type = 'hidden' name = 'name' value = {{$data->name}}>
				</div>
				<div>
					<label>原始密碼</label>
					<input type = "password" name = 'originalpass'>
					<div id = 'originalpass'>
					</div>
				</div>
				<div>
					<label>修改密碼</label>
					<input type = "password" name = 'pass'>
					<div id = 'editpass'>
					</div>
				</div>	
				<div>
					<label>確認密碼</label>
					<input type = "password" name = 'repass'>
				</div>	
				<div>
					<label>狀態</label>
					@if($data->status)
						開啟<input type = "radio" name = 'status'  value = '0' >
						停止<input type = "radio" name = 'status' checked = 'true' value = '1'>
					@else
						開啟<input type = "radio" name = 'status' checked = 'true' value = '0' >
						停止<input type = "radio" name = 'status'  value = '1'>
					@endif
				</div>				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" 
						data-dismiss="modal" id = 'editcancel' >取消
				</button>
				<input type = 'reset' value = "重置"  class="btn btn-primary" id = 'editreset' style = 'display:none'>
				<input type = 'submit' value = "確認送出"  class="btn btn-primary" onclick = 'update({{$data->id}})'>
			</div>