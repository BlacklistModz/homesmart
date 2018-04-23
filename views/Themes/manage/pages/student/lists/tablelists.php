<?php
//print_r($this->results['lists']); die;
$tr = "";
$tr_total = "";

if( !empty($this->results['lists']) ){ 
    //print_r($this->results); die;

    $seq = 0;
    foreach ($this->results['lists'] as $i => $item) { 
        // $item = $item;
        $cls = $i%2 ? 'even' : "odd";
        // set Name
        //print_r($item);
        /*$updatedTime = strtotime( $item['updated'] );
        $updatedStr = date('j/m/Y', $updatedTime);
        $updatedStr .= '<div class="fsm fcg">' .date('H:i:s').'</div>';*/

        //$updatedStr = $this->fn->q('time')->stamp( $item['updated'] );

        $image = '';
        if( !empty($item['image_url']) ){
            $image = '<div class="avatar lfloat mrm"><img class="img" src="'.$item['image_url'].'" alt="'.$item['fullname'].'"></div>';
        }
        else{
            $image = '<div class="avatar lfloat no-avatar mrm"><div class="initials"><i class="icon-user"></i></div></div>';
        }

        $subtext = '';
        if( !empty($item['faculty_name']) ){
            $subtext .= !empty($subtext) ? ', ':'';
            $subtext.= 'คณะ: '.$item['faculty_name'];
        }
        if( !empty($item['major_name']) ){
            $subtext .= !empty($subtext) ? ', ':'';
            $subtext.= 'สาขาวิชา: '.$item['major_name'];
        }
        
        $option = '<option'.($item['user_display']=='enabled' ? ' selected="1"' : '').' value="enabled">เปิดใช้งาน</option>';
        $option .= '<option'.($item['user_display']=='disabled' ? ' selected="1"' : '').' value="disabled">ปิดใช้งาน</option>';

        $select = '<select class="inputtext" data-plugins="_update" data-options="'.$this->fn->stringify(array('url' => URL. 'student/setData/'.$item['user_id'].'/user_display')).'">'.$option.'</select>';

 
//print_r($item['id']); die;
        $tr .= '<tr class="'.$cls.'" data-id="'.$item['user_id'].'">'.

            // '<td class="check-box"><label class="checkbox"><input id="toggle_checkbox" type="checkbox" value="'.$item['id'].'"></label></td>'.

            '<td class="number">'.$item['user_login'].'</td>'.

            '<td class="name">'.

                '<div class="anchor clearfix">'.
                    $image.
                    
                    '<div class="content"><div class="spacer"></div><div class="massages">'.

                        '<div class="fullname"><a class="fwb" href="'.URL .'cms/student/'.$item['id'].'">'.$item['fullname'].'</a></div>'.

                        '<div class="subname fsm fcg meta">'.$subtext.'</div>'.

                    '</div>'.
                '</div></div>'.

            '</td>'.

            '<td class="status">'.(!empty($item['year_name']) ? $item['year_name'] : '-').'</td>'.

            '<td class="actions">'.$select.'</td>'.

        '</tr>';
        
    }
  
}

$table = '<table><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';