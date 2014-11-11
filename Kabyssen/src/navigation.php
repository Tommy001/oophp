<?php
/* call_user_func anropar den funktion som anges i 1:a argumentet
    det andra argumentet innehåller alla meny-arrayer
    returen innehåller meny-arrayerna med class='selected' på klickat menyval
*/
function GenerateMenu($menu, $class) {
    if(isset($menu['callback'])) {
      $items = call_user_func($menu['callback'], $menu['items']);
    }
    $html = "<nav class='$class'>\n";
    foreach($items as $item) {
      $html .= "<a href='{$item['url']}' class='{$item['class']}'>{$item['text']}</a>\n";
    }
    $html .= "</nav>\n";
    return $html;
}

/*  ome menyn Me är klickad på så är url=me.php och om det faktiskt anropade filnamnet är detsamma som finns i meny-arrayens url så hänger vi på class=selected i just det menyvalet
*/
function modifyNavbar($items) {
    foreach($items as $key => $item) {
        if(basename($_SERVER['SCRIPT_FILENAME']) == $item['url']) {
           $items[$key]['class'] .= 'selected';
        }
    }   
    return $items;
}
