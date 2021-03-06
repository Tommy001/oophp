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

/**
 * Create a navigation bar / menu, with submenu.
 *
 * @param string $menu for the navigation bar.
 * @return string as the html for the menu.
 */
 
 
function get_navbar($menu) {
    // Keep default options in an array and merge with incoming options that can override the defaults.
    $default = array(
      'id'          => null,
      'class'       => null,
      'wrapper'     => 'nav',
      'create_url'  => function ($url) {
        return $url;
      },
    );
    $menu = array_replace_recursive($default, $menu);
 
    // Function to create urls
    $createUrl = $menu['create_url'];
 
    // Create the ul li menu from the array, use an anonomous recursive function that returns an array of values.
    $createMenu = function ($items, $callback) use (&$createMenu, $createUrl) {
 
        $html = null;
        $hasItemIsSelected = false;
 
        foreach ($items as $item) {
 
            // has submenu, call recursivly and keep track on if the submenu has a selected item in it.
            $submenu        = null;
            $selectedParent = null;
 
            if (isset($item['submenu'])) {
                list($submenu, $selectedParent) = $createMenu($item['submenu']['items'], $callback);
                $selectedParent = $selectedParent
                    ? "selected-parent"
                    : null;
            }
 
            // Check if the current menuitem is selected
            $selected = $callback($item['url'])
                ? "selected"
                : null;
 
            // Is there a class set for this item, then use it
            $class = isset($item['class']) && ! is_null($item['class'])
                ? $item['class']
                : null;
 
            // Prepare the class-attribute, if used
            $class = ($selected || $selectedParent || $class)
                ? " class='{$selected}{$selectedParent}{$class}' "
                : null;
 
            // Add the menu item
            $url = $createUrl($item['url']);
            $html .= "\n<li{$class}><a href='{$url}' title='{$item['title']}'>{$item['text']}</a>{$submenu}</li>\n";
 
            // To remember there is selected children when going up the menu hierarchy
            if ($selected) {
                $hasItemIsSelected = true;
            }
        }
 
        // Return the menu
        return array("\n<ul>$html</ul>\n", $hasItemIsSelected);
    };
        // Call the anonomous function to create the menu, and submenues if any.
    list($html, $ignore) = $createMenu($menu['items'], $menu['callback']);
 
 
    // Set the id & class element, only if it exists in the menu-array
    $id      = isset($menu['id'])    ? " id='{$menu['id']}'"       : null;
    $class   = isset($menu['class']) ? " class='{$menu['class']}'" : null;
    $wrapper = $menu['wrapper'];
 
    return "\n<{$wrapper}{$id}{$class}>{$html}</{$wrapper}>\n";
}
