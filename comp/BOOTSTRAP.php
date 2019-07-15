<?php
namespace comp;

class BOOTSTRAP{

    public static function inputKey($nm_comp, $data){
        return '<input type="hidden" name="'.$nm_comp.'" id="'.$nm_comp.'" value="'.$data.'">';
    }

    public static function inputText($nm_comp, $type, $data, $opt){
        return '<input type="'.$type.'" id="'.$nm_comp.'" name="'.$nm_comp.'" value="'.$data.'" '.$opt.'>';
    }

    public static function inputTextArea($nm_comp, $data, $opt){
        return '<textarea id="'.$nm_comp.'" name="'.$nm_comp.'" '.$opt.'>'.$data.'</textarea>';
    }

    public static function inputSelect($nm_comp, $data, $val, $opt){
        $dataArr = array();
        $option = array();
        
        // Cek Data Group
        foreach ($data as $key => $value) {
            $group = isset($value['group']) ? $value['group'] : '';
            if(!empty($group)){
                $dataArr[$group]['detail'][$key] = $value;
            }else{
                $dataArr[$key] = $value;
            }
        }

        foreach ($dataArr as $key => $value) {
            if(isset($value['detail'])){ // Jek jika data group
                $option[] = '<optgroup label="'.$key.'">';
                foreach ($value['detail'] as $keys => $values) {
                    $text = isset($values['text']) ? $values['text'] : '';
                    $desc = isset($values['desc']) ? $values['desc'] : '';
                    if(is_array($val)) $selected = (in_array($key, $val)) ? 'selected' : ''; // Multiple Select
                    else $selected = ($key == $val) ? 'selected' : '';
                    $option[] = '<option data-subtext="'.$desc.'" data-subgroup="true" value="'.$keys.'" '.$selected.'>'.$text.'</option>';
                }
                $option[] = '</optgroup>';
            }else{
                $text = isset($value['text']) ? $value['text'] : '';
                $desc = isset($value['desc']) ? $value['desc'] : '';
                if(is_array($val)) $selected = (in_array($key, $val)) ? 'selected' : ''; // Multiple Select
                else $selected = ($key == $val) ? 'selected' : '';
                $option[] = '<option data-subtext="'.$desc.'" data-subgroup="" value="'.$key.'" '.$selected.'>'.$text.'</option>';
            }
        }

        $elmt = '<select id="'.$nm_comp.'" name="'.$nm_comp.'" '.$opt.'>'.implode('', $option).'</select>';
        return $elmt;        
    }
	
	public static function inputRadio($nm_comp, $data, $val){
		foreach ($data as $key => $value){
            if($key == $val)
                $option[] = '<label style="color: #000; cursor: pointer; margin: 10px;"><input type="radio" id="'.$nm_comp.'" name="'.$nm_comp.'" value="'.$key.'" checked> '.$value['text'].'</label>';
            else
                $option[] = '<label style="color: #000; cursor: pointer; margin: 10px;"><input type="radio" id="'.$nm_comp.'" name="'.$nm_comp.'" value="'.$key .'"> '.$value['text'].'</label>';
        }
        $set = implode('', $option);
        return $set;
	}
	
	public static function inputCheckbox($nm_comp, $data, $val){
		$val = (isset($val)) ? explode(',', $val) : array();
		foreach ($data as $key => $value){
			if(in_array($value, $val))
				$option[] = '<label style="color: #000; cursor: pointer; margin: 5px;"><input type="checkbox" id="'.$nm_comp.'" name="'.$nm_comp.'" value="'.$value.'" checked> '.$value.'</label>';
			else
                $option[] = '<label style="color: #000; cursor: pointer; margin: 5px;"><input type="checkbox" id="'.$nm_comp.'" name="'.$nm_comp.'" value="'.$value.'"> '.$value.'</label>';
		}
        $set = implode('', $option);
        return $set;
	}

    public static function errMsg($str, $class){
        return '<div class="alert alert-'.$class.' alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
					<strong>'.$str.'</strong>
				</div>'."\n";
    }

    public static function pagging($page, $batas, $jumlah){
        /**
         * Format Page :
         * [1] => (jika total page = 1, maka tidak ditampilkan)
         * [1] 2 3
         * [1] 2 3 ... next last
         * first prev ... [2] 3 4 ... next last
         * first prev ... [8] 9 10
         */

        // Template Paggination in Bootstrap
        $page_number = '<li class="pagging page-item" number-page="{number}" style="cursor: pointer;"><span class="page-link">{page}</span></li>';
        $page_active = '<li class="pagging page-item active" number-page="{number}"><span class="page-link">{page}</span></li>';
        $paginations = '<hr><ul class="pagination">{pagging}</ul>';
        $pagging = array();

        $total_pages = ceil($jumlah / $batas);
        $prev_number = ($page > 1) ? $page - 1 : 1;
        $next_number = ($page < $total_pages) ? $page + 1 : $total_pages;

        $btn_first = str_replace(array('{number}','{page}'), array(1,'&laquo;'), $page_number);
        $btn_lasts = str_replace(array('{number}','{page}'), array($total_pages,'&raquo;'), $page_number);
        $btn_prev = str_replace(array('{number}','{page}'), array($prev_number,'&lsaquo;'), $page_number);
        $btn_next = str_replace(array('{number}','{page}'), array($next_number,'&rsaquo;'), $page_number);
        $btn_dots = str_replace(array('{number}','{page}', 'active'), array('','...', 'disabled'), $page_active);
        $btn_active = str_replace(array('{number}','{page}'), array('', $page), $page_active);
        
        if($total_pages > 1){

            if($page > 3){
                array_push($pagging, $btn_first);
                array_push($pagging, $btn_prev);
                array_push($pagging, $btn_dots);
            }

            for ($i = ($page - 2); $i < $page; $i++) { 
                if($i < 1) continue;
                array_push($pagging, str_replace(array('{number}','{page}'), array($i, $i), $page_number));
            }
            
            array_push($pagging, $btn_active);

            for ($i = ($page + 1); $i < ($page + 3); $i++) { 
                if($i > $total_pages) break;
                array_push($pagging, str_replace(array('{number}','{page}'), array($i, $i), $page_number));
            }

            if(($page + 2) < $total_pages) array_push($pagging, $btn_dots);
            
            if($page < ($total_pages - 2)){
                array_push($pagging, $btn_next);
                array_push($pagging, $btn_lasts);
            }
        }

        $paginations = str_replace('{pagging}', implode($pagging), $paginations);
        return $paginations;
    }

}

?>
