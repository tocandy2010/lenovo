	<div class="modal-body">
	<!-- 模態框內容 -->	
		<div>
			<label>標題</label>
			<input type = "text" name = 'title' placeholder = '文字上限為50' value = '{{$data->title}}'>
			<input type = 'hidden' name = 'id' value = '{{$data->id}}'>
			<span id = 'titleedit' style = 'color:red'>
						
			</span>
		</div>
		<div>
			<label>廣告連結</label>
			<input type = "text" name = 'href' placeholder = '請輸入一組連結' value = '{{$data->href}}'>
			<span id = 'hrefedit' style = 'color:red'>
			</span>
		</div>	
		<div>
			<label>順位</label>
			<input type = "number" name = 'orderby' placeholder = '數字越小越前面' value = '{{$data->orderby}}'>
			<span id = 'orderbyedit' style = 'color:red'>
			</span>
		</div>	
		<div>
			<label>上傳圖片</label>
			<span id = 'imgedit' style = 'color:red'>
			</span>
			<input type = "file" name = 'img' >
		</div>				
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" 
			data-dismiss="modal" id = 'editcancel' >取消
		</button>
		<input type = 'reset' value = "重置"  class="btn btn-primary" id = 'editreset' style = 'display:none'>
		<input type = 'submit' value = "確認送出"  class="btn btn-primary" onclick = 'editupdate(this,{{$data->id}})'>
	</div>





