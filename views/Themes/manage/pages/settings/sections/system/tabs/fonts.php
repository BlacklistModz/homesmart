<ul class="listsdata-table-lists mtl">
		<li class="head">
			<div class="content"><label class="label">Fonts</label></div>
			<div class="text"><label class="label">ตัวอย่าง</label></div>
		</li>

		<?php foreach ($this->results['lists'] as $key => $value) { 

			$checked = false;
			if( !empty($this->system['font']['id']) ){

				if( $this->system['font']['id']==$value['id'] ){
					$checked = true;
				}
			}

		?>
			
		<li>
			<div class="content">

				<label class="radio">
					<input type="radio" name="font" value="<?=$value['id']?>"<?=$checked ? ' checked="1"':'' ?> onChange="change(this);">
					<?=$value['name']?>
				</label>
				
			</div>

			<div class="text">

				<style>@import url('https://fonts.googleapis.com/css?family=<?=$value['name']?>');</style>
				<div style="<?=$value['specify']?>"><?=$value['text_preview']?></div>

			</div>
		</li>
		<?php } ?>

</ul>
<script type="text/javascript">
	
	function change( elem ) {
		
		Event.showMsg({text: 'กำลังโหลด...', load: 1, auto: 1});
		$.post( Event.URL + 'system/set/', {font: $(elem).val()}, function () {
			Event.showMsg({text: 'บันทึกเรียบร้อย', load: 1, auto: 1});
		}, 'json');

	}
</script>