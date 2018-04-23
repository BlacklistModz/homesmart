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

        $updatedStr = $this->fn->q('time')->stamp( $item['updated'] );

        $image = !empty($item['image_url']) ? $this->fn->imageBox($item['image_url'], 100): '';

        $tr .= '<tr class="'.$cls.'" data-id="'.$item['user_id'].'">'.

            '<td class="category">'.$item['forum_name'].'</td>'.

            '<td class="image"><a href="'.URL.'topics/edit/'.$item['id'].'">'.$image.'</a></td>'.

            '<td class="name">'.
                '<div class="ellipsis"><a title="'.$item['name'].'" class="fwb" href="'.URL.'topics/edit/'.$item['id'].'" data-plugins="dialog">'.$item['name'].'</a></div>'.
                '<div class="date-float fsm fcg">เพิ่มเมื่อ: '. ( $item['created'] != '0000-00-00 00:00:00' ? $this->fn->q('time')->live( $item['created'] ):'-' ) .'</div>'.

                '<div class="date-float fsm fcg">แก้ไขล่าสุด: '. ( $item['updated'] != '0000-00-00 00:00:00' ? $this->fn->q('time')->live( $item['updated'] ):'-' ) .'</div>'.

            '</td>'.

            '<td class="email">'.
                '<div class="ui-card_number"><span class="ui-status" style="background-color: '.$item['status_arr']['color'].'">'.$item['status_arr']['name'].'</span></div>'.
            '</td>'.

            '<td class="actions whitespace">'.
                '<span class="mrs"><a href="'.URL.'cms/news/set/'.$item['id'].'" class="btn btn-no-padding btn-blue"><i class="icon-pencil"></i></a></span>'.
                '<span class="mrs"><a data-plugins="dialog" href="'.URL.'topics/del/'.$item['id'].'" class="btn btn-no-padding btn-red"><i class="icon-trash"></i></a></span>'.
            '</td>'.

        '</tr>';
    }
}

$table = '<table><tbody>'. $tr. '</tbody>'.$tr_total.'</table>';