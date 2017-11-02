<?php

echo "<div id='sh0.5'>";
echo "<div style='max-width:770px; margin:4px 4px 4px 4px; padding:5px; z-index:90; position:relative;' id='blue' class='visible-xsX alert alert-info'>";	
echo "<div style='border-bottom:solid; border-bottom-width:1px; font-weight:bold;'>Webzite layout</div>";



echo "<div class='row'>"; //row start
//left panel (editor)
echo "
	<div class='col-md-6 col-xs-12'>
		<table class='table'>
			<thead>
				<tr>
					<th>Order</th>
					<th>Edge style</th>
					<th>Color</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><select class='form-control'></select></td>
					<td><select id='svgSelect' class='form-control'></select></td>
					<td><input id='svgColor' type='color' class='form-control' style='padding: 2px;'></td>
				</tr>
			</tbody>
		</table>
	</div>
";
//right panel (preview)

echo "<div class='col-md-6 col-xs-12'>";
echo "
	<style>
		.previwBox {
			width: 100%;
			display: flex;
			word-wrap: break-word;
			background-color: #fff;
			background-clip: border-box;
			border: 1px solid rgba(0, 0, 0, 0.125);
			border-radius: 0.25rem;
		}

		.section {
			width: 100%;
			padding-top: 20px;
			padding-bottom: 20px;
			text-align-last: center;
			color: white;
			font-family: Arial, Helvetica, sans-serif;
			display: inline-grid;
		}

		.svg {
			width: auto;
			height: auto;
			fill: #FFFF00;
		}
	

	</style>

	<div style=' font-weight:bold;'>Preview</div>
	<div class='previwBox'>
		<div class='section'>

			<svg class='svg' version='1.1' 
				id='spikes' 
				xmlns='http://www.w3.org/2000/svg' 
				xmlns:xlink='http://www.w3.org/1999/xlink' 
				viewBox='0 0 2000 150' 
				xml:space='preserve'>				
				<path id='path' d='M2000 150L2000 75L0 75L0 150L2000 150Z'/>
			</svg>

			<div id='sectionDiv' style='background-color: red;'>
				<h1>Section 2</h1>
				<p>styled with svg's</p>
			</div>

		</div>
	</div>
";
echo "</div>";

echo "</div>";	//row end
echo "<button id='ajaxTest'>ajax test</button><span id='ajaxResponse'></span>";
echo "</div>";	//blue box div end
echo "</div>\n"; //show/hide div end


//javascript
echo "
<script>
var svgContentArray = [];
";

$getSvgSQL = "SELECT * FROM  `tblSvgs`";
$res = mysqli_query($mysqli_link, $getSvgSQL);
while($svg = mysqli_fetch_assoc($res)) {
	$name = $svg['Name'];
	$path = $svg['Path'];
	echo "svgContentArray.push({ name : '$name', path : '$path'});";
}

echo "
$(document).ready(function() {
	$.each(svgContentArray, function (i, item) {
		$('#svgSelect').append($('<option>', { 
			value: i,
			text : item.name 
		}));
	});
});

// remove or finish.
var fields = [];
$('.layoutField').each(function(i, obj) {
	fields.push(obj);
});

$('select#svgSelect').on('change', function(){
	$('path#path').attr('d', svgContentArray[this.value].path);
});

$('input#svgColor').on('change', function(){
	$('div#sectionDiv').css('background-color', this.value);
	$('path#path').css('fill', this.value);
});

$('button#ajaxTest').click(function(){
	var data = {}
	data.action = 'layout';
	data.svg = $('select#svgSelect').val();
	$('span#ajaxResponse').text('pending...');
	$.ajax({
		url: 'ajax_processor.php',
		type: 'post',
		data: data,
		success: function(data){
			$('span#ajaxResponse').text(data);
		},
		error: function (){
			$('span#ajaxResponse').text('error :(');
		}
	});
});
</script>
";

?>