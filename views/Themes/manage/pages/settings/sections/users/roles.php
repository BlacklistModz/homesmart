<?php

$url = URL .'users/';


?><div class="pal"><div class="setting-header cleafix">

<div class="rfloat">

	<a class="btn btn-blue" data-plugins="dialog" href="<?=$url?>add_roles"><i class="icon-plus mrs"></i><span><?=$this->lang->translate('Add New')?></span></a>

</div>

<div class="setting-title"><?=$this->lang->translate('Roles')?></div>
</div>

<section class="setting-section">
	<table class="settings-table admin"><tbody>
		<tr>
			<th class="name"><?=$this->lang->translate('Name')?></th>
			<th class="actions"><?=$this->lang->translate('Action')?></th>

		</tr>

		<?php foreach ($this->data as $key => $item) { ?>
		<tr>
			<td class="name"><?=$item['name']?></td>

			<td class="actions"><?php

			$dropdown = array();

			$dropdown[] = array(
                'text' => $this->lang->translate('Permission'),
                'href' => $url.'edit_permit/'.$item['id'].'?type=roles',
                'attr' => array('data-plugins'=>'dialog'),
            );

			$dropdown[] = array(
                'text' => $this->lang->translate('Delete'),
                'href' => $url.'del_roles/'.$item['id'],
                'attr' => array('data-plugins'=>'dialog'),
            );

            echo '<div class="whitespace group-btn">'.

            	'<a data-plugins="dialog" href="'.$url.'edit_roles/'.$item['id'].'" class="btn"><i class="icon-pencil"></i></a>'.

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