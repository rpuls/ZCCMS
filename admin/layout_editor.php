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
	echo "<select id='svgSelect' class='form-control col-12'>";
	echo "<option>None (flat)</option>";
	foreach($svg_images as $svg) {
		$svg = substr($svg, 0, -4);
		echo "<option>$svg</option>";
	}
	echo "</select>";
	echo "<input id='svgColor' type='color'>";
	echo "</div>";
	//right panel (preview)

	echo "<div class='col-md-6 col-xs-12'>";
	echo "
		<style>
			.section {
				padding-top: 50px;
				padding-bottom: 66px;
				text-align-last: center;
				color: white;
				font-family: Arial, Helvetica, sans-serif;
				display: inline-grid;
			}

			.svg {
				max-width: 100%;
			}

			.svgBottom {
				-ms-transform: rotate(180deg);
				-webkit-transform: rotate(180deg); 
				transform: rotate(180deg);
			}

		</style>
		<div style=' font-weight:bold;'>Preview</div>
		<div class='card'>
			<div class='section'>
				<img class='svg' src='../assets/svg/spikes2.svg'/>
				
				<div id='sectionDiv' style='background-color: red;'>
					<h1>Section 2</h1>
					<p>styled with svg's</p>
				</div>

				<img class='svg svgBottom' src='../assets/svg/spikes2.svg'/>
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
	var fields = [];
	$('.layoutField').each(function(i, obj) {
		fields.push(obj);
	});

	$('select#svgSelect').on('change', function(){
		$('img.svg').attr('src', '../assets/svg/'+this.value+'.svg');
	});

	$('input#svgColor').on('change', function(){
		$('div#sectionDiv').css('background-color', this.value);
		$('#path1').css({ fill: '#ffffff' });
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