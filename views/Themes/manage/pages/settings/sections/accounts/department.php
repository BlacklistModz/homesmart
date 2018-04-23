<?php

$url = URL .'employees/';


?><div class="pal">

<div class="setting-header cleafix">

<div class="rfloat">

	<a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add_department"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Add New')?></span></a>

</div>

<div class="setting-title"><?=$this->lang->translate('Department')?></div>
</div>

<section class="setting-section">
	<table class="settings-table admin"><tbody>
		<tr>
			<th class="name"><?=$this->lang->translate('Name')?></th>

			<?php foreach ($this->access as $key => $value) {
				echo '<th class="status">'.$value['name'].'</th>';
			}?>

			<th class="actions"><?=$this->lang->translate('Action')?></th>

		</tr>

		<?php foreach ($this->data as $key => $item) { ?>
		<tr>
			<td class="name">
				<h3><?=$item['name']?></h3>
				<?php if( !empty($item['notes']) ){ ?>
				<div class="fsm fcg"><?=$item['notes']?></div>
				<?php } ?>
			</td>
			
			<?php foreach ($this->access as $key => $value) {
				$item['access'] = !empty($item['access']) ? $item['access']: array();

			echo '<td class="status"><label class="checkbox"><input type="checkbox" name="'.$value['id'].'"'.( in_array($value['id'], $item['access']) ?' checked="1"' :'').'></label></td>';
			}?>

			<td class="actions"><?php

				$dropdown = array();

				$dropdown[] = array(
	                'text' => $this->lang->translate('Permission'),
	                'href' => $url.'edit_permit/'.$item['id'].'?type=department',
	                'attr' => array('data-plugins'=>'dialog'),
	            );

				$dropdown[] = array(
	                'text' => $this->lang->translate('Delete'),
	                'href' => $url.'del_department/'.$item['id'],
	                'attr' => array('data-plugins'=>'dialog'),
	            );

	            echo '<div class="whitespace group-btn">'.

	            	'<a data-plugins="dialog" href="'.$url.'edit_department/'.$item['id'].'" class="btn"><i class="icon-pencil"></i></a>'.

	            	'<a data-plugins="dropdown" class="btn btn-no-padding" data-options="'.$this->fn->stringify( array(
	                        'select' => $dropdown,
	                        'settings' =>array(
	                            'axisX'=> 'right',
	                            'parent'=>'.setting-main'
	                        ) 
	                    ) ).'"><i class="icon-ellipsis-v"></i></a>'.

	            '</div>';
			
			?></td>

		</tr>
		<?php } ?>
	</tbody></table>
</section>
</div>