<?php

echo "<div id='sh0.5'>";
echo "<div style='max-width:770px; margin:4px 4px 4px 4px; padding:5px; z-index:90; position:relative;' id='blue' class='visible-xsX alert alert-info'>";	
echo "<div style='border-bottom:solid; border-bottom-width:1px; font-weight:bold;'>Webzite layout</div>";
echo "<div class='row'>"; //row start
//left panel (editor)

echo "<div class='col-md-6 col-xs-12'>";
$svg_images = array_filter(scandir('../assets/svg'), function($item) {
    if(pathinfo($item, PATHINFO_EXTENSION) == 'svg'){
        return $item;
    }
});
echo "<div style=' font-weight:bold;'>Chose edge style</div>";
echo "<select class='form-control col-12'>";
echo "<option>None (flat)</option>";
foreach($svg_images as $svg) {
    $svg = substr($svg, 0, -4);
    echo "<option>$svg</option>";
}
echo "</select>";
echo "</div>";
//right panel (preview)

echo "<div class='col-md-6 col-xs-12'>";
echo "bbbbbb bbbbb bbbbbbb bbbb bbb bbbbbb bbbbbbb bbbbbbbbb bbbbbbbbbbb bbbb bbbbbbb bbbbbbbb bbbbb bbbbbbbb bbb bbbbb
bbbbbb bbbbb bbbbbbb bbbb bbb bbbbbb bbbbbbb bbbbbbbbb bbbbbbbbbbb bbbb bbbbbbb bbbbbbbb bbbbb bbbbbbbb bbb bbbbb
bbbbbb bbbbb bbbbbbb bbbb bbb bbbbbb bbbbbbb bbbbbbbbb bbbbbbbbbbb bbbb bbbbbbb bbbbbbbb bbbbb bbbbbbbb bbb bbbbb
bbbbbb bbbbb bbbbbbb bbbb bbb bbbbbb bbbbbbb bbbbbbbbb bbbbbbbbbbb bbbb bbbbbbb bbbbbbbb bbbbb bbbbbbbb bbb bbbbb
bbbbbb bbbbb bbbbbbb bbbb bbb bbbbbb bbbbbbb bbbbbbbbb bbbbbbbbbbb bbbb bbbbbbb bbbbbbbb bbbbb bbbbbbbb bbb bbbbb
bbbbbb bbbbb bbbbbbb bbbb bbb bbbbbb bbbbbbb bbbbbbbbb bbbbbbbbbbb bbbb bbbbbbb bbbbbbbb bbbbb bbbbbbbb bbb bbbbb
bbbbbb bbbbb bbbbbbb bbbb bbb bbbbbb bbbbbbb bbbbbbbbb bbbbbbbbbbb bbbb bbbbbbb bbbbbbbb bbbbb bbbbbbbb bbb bbbbb
bbbbbb bbbbb bbbbbbb bbbb bbb bbbbbb bbbbbbb bbbbbbbbb bbbbbbbbbbb bbbb bbbbbbb bbbbbbbb bbbbb bbbbbbbb bbb bbbbb
bbbbbb bbbbb bbbbbbb bbbb bbb bbbbbb bbbbbbb bbbbbbbbb bbbbbbbbbbb bbbb bbbbbbb bbbbbbbb bbbbb bbbbbbbb bbb bbbbb
bbbbbb bbbbb bbbbbbb bbbb bbb bbbbbb bbbbbbb bbbbbbbbb bbbbbbbbbbb bbbb bbbbbbb bbbbbbbb bbbbb bbbbbbbb bbb bbbbb
bbbbbb bbbbb bbbbbbb bbbb bbb bbbbbb bbbbbbb bbbbbbbbb bbbbbbbbbbb bbbb bbbbbbb bbbbbbbb bbbbb bbbbbbbb bbb bbbbb
bbbbbb bbbbb bbbbbbb bbbb bbb bbbbbb bbbbbbb bbbbbbbbb bbbbbbbbbbb bbbb bbbbbbb bbbbbbbb bbbbb bbbbbbbb bbb bbbbb";
echo "</div>";

echo "</div>";	//row end
echo "</div>";	//blue box div end
echo "</div>\n"; //show/hide div end

?>