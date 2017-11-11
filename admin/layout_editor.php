<?php

echo "<div id='sh0.5'>";
	echo "<div style='max-width:770px; margin:4px 4px 4px 4px; padding:5px; z-index:90; position:relative;' id='blue' class='visible-xsX alert alert-info'>";	
	echo "<div style='border-bottom:solid; border-bottom-width:1px; font-weight:bold;'>Webzite layout</div>";
	
	$bgColor = "#f9e7da";
	$getBGColorSQL = "SELECT value FROM `tblTemplateElementSettings` WHERE `PlaceID` = $zite_id  AND `TemplateElementID` = 2;";
	$res = mysqli_query($mysqli_link, $getBGColorSQL);
	while($val = mysqli_fetch_assoc($res)) {
		$bgColor = $val['value'];
	}

	echo "
	<script>
		var svgContentArray = [];
		svgContentArray.push({index_zero:'zero'});
		";
		
		$getSvgSQL = "SELECT * FROM  `tblSvgs`";
		$res = mysqli_query($mysqli_link, $getSvgSQL);
		while($svg = mysqli_fetch_assoc($res)) {
			$name = $svg['Name'];
			$path1 = $svg['Path1'];
			$path2 = $svg['Path2'];
			$path3 = $svg['Path3'];
			$type = strtolower ($svg['Type']);
			echo "svgContentArray.push({ name : '$name', path1 : '$path1', path2 : '$path2', path3 : '$path3', type : '$type'});";
		}
	echo "</script>";

	echo "<div class='row'>"; //row start
	//left panel (editor)
	echo "
		<div class='col-md-7 col-xs-12'>
			<table class='table'>
				<thead>
					<tr>
						<th>Position</th>
						<th>Shape style</th>
						<th>Color</th>
						<th>Color</th>
						<th>Color</th>
					</tr>
				</thead>
				<tbody>";
	$svgPos = 1;
	while($svgPos <= 10){
		$posSpan = "";
		$type = "";
		if($svgPos <= 3){
			$type = "edge";
			$posSpan = "Edge ".$svgPos;
		}else {
			$type = "corner";
			$posSpan = "Corner ".($svgPos-3);
		}
		echo "
					<tr>
						<td><span>$posSpan</span></td>
						<td><select id='svgSelect$svgPos' class='form-control'></select></td>
						<td><input id='path1Color$svgPos' type='color' value='$bgColor' class='form-control' style='padding: 2px;'></td>
						<td><input id='path2Color$svgPos' type='color' value='$bgColor' class='form-control' style='padding: 2px;'></td>
						<td><input id='path3Color$svgPos' type='color' value='$bgColor' class='form-control' style='padding: 2px;'></td>
					</tr>
					<script>
						$(document).ready(function() {
							$.each(svgContentArray, function (i, item) {
								if(i != 0){
									if(item.type == '$type'){
										$('#svgSelect$svgPos').append($('<option>', { 
											value: i,
											text : item.name 
										}));
									}
								}
							});
						});

						$('select#svgSelect$svgPos').on('change', function(){
							$('#svg$svgPos').children('#path1').attr('d', svgContentArray[this.value].path1);
							$('#svg$svgPos').children('#path2').attr('d', svgContentArray[this.value].path2);
							$('#svg$svgPos').children('#path3').attr('d', svgContentArray[this.value].path3);
							if(svgContentArray[this.value].type == 'corner'){
								$('#svgR$svgPos').children('#path1').attr('d', svgContentArray[this.value].path1);
								$('#svgR$svgPos').children('#path2').attr('d', svgContentArray[this.value].path2);
								$('#svgR$svgPos').children('#path3').attr('d', svgContentArray[this.value].path3);
							}

							// disable/enable color inputs
							if(svgContentArray[this.value].path2 == ''){
								$('input#path2Color$svgPos').prop('disabled', true);
							}else{
								$('input#path2Color$svgPos').prop('disabled', false);
							}

							if(svgContentArray[this.value].path3 == ''){
								$('input#path3Color$svgPos').prop('disabled', true);
							}else{
								$('input#path3Color$svgPos').prop('disabled', false);
							}

							enableSave();
						});
						
						$('input#path1Color$svgPos').on('change', function(){
							$('#svg$svgPos').children('#path1').css('fill', this.value);
							if($svgPos > 3){
								$('#svgR$svgPos').children('#path1').css('fill', this.value);
							}
							enableSave();
						});

						$('input#path2Color$svgPos').on('change', function(){
							$('#svg$svgPos').children('#path2').css('fill', this.value);
							if($svgPos > 3){
								$('#svgR$svgPos').children('#path2').css('fill', this.value);
							}
							enableSave();
						});

						$('input#path3Color$svgPos').on('change', function(){
							$('#svg$svgPos').children('#path3').css('fill', this.value);
							if($svgPos > 3){
								$('#svgR$svgPos').children('#path3').css('fill', this.value);
							}
							enableSave();
						});
					</script>
		";
		$svgPos += 1;
	}
	echo "
				</tbody>
			</table>
		</div>
	";
	//right panel (preview)
	
	echo "<div class='col-md-5 col-xs-12'>";
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
	
			.previewWindow {
				width: 100%;
				text-align-last: center;
				color: white;
				font-family: Arial, Helvetica, sans-serif;
				display: inline-grid;
			}
	
			.section {
				position: relative;
			}

			.svgEdge {
				width: auto;
				height: auto;
				fill:  $bgColor;
			}

			.svgEdgeBottom {
				-ms-transform: rotate(180deg); /* IE 9 */
				-webkit-transform: rotate(180deg); /* Chrome, Safari, Opera */
				transform: rotate(180deg);
			}

			.svgCorner {
				fill:  #ff0000;
				width: 7%;
			}
			
			.svgCornerBL{
				position: absolute;
				bottom: 0;
				left: 0;
			}
			
			.svgCornerBR {
				position: absolute;
				bottom: 0;
				right: 0;
				-ms-transform: rotate(270deg); /* IE 9 */
				-webkit-transform: rotate(270deg); /* Chrome, Safari, Opera */
				transform: rotate(270deg);
			}
			
			.svgCornerTL{
				position: absolute;
				top: 0;
				right: 0;
				-ms-transform: rotate(180deg); /* IE 9 */
				-webkit-transform: rotate(180deg); /* Chrome, Safari, Opera */
				transform: rotate(180deg);
			}
			
			.svgCornerTR{
				position: absolute;
				top: 0;
				left: 0;
				-ms-transform: rotate(90deg); /* IE 9 */
				-webkit-transform: rotate(90deg); /* Chrome, Safari, Opera */
				transform: rotate(90deg);
			}

			.abtn {
				display: inline-block;
				padding: 6px 12px;
				margin-bottom: 0;
				font-size: 14px;
				font-weight: 400;
				line-height: 1.42857143;
				text-align: center;
				white-space: nowrap;
				vertical-align: middle;
				-ms-touch-action: manipulation;
				touch-action: manipulation;
				cursor: pointer;
				-webkit-user-select: none;
				-moz-user-select: none;
				-ms-user-select: none;
				user-select: none;
				background-image: none;
				border: 1px solid transparent;
				border-radius: 4px;
			}

			.abtn-warning {
				color: #fff;
				background-color: #f0ad4e;
				border-color: #eea236;
			}

			.abtn-success {
				color: #fff;
				background-color: #5cb85c;
				border-color: #4cae4c;
			}
		
			.abtn-danger {
				color: #fff;
				background-color: #d9534f;
				border-color: #d43f3a;
			}
		</style>
	
		<div style=' font-weight:bold;'>Preview</div>
		<div class='previwBox'>
			<div class='previewWindow'>

				<div class='section'>
					<h1>$FB_page_name</h1>
					<svg class='svgCorner svgCornerBL' id='svg4' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
					<svg class='svgCorner svgCornerBR' id='svgR4' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
				</div>
	
				<svg class='svgEdge' id='svg1' viewBox='0 0 2000 150'>				
					<path id='path1' d=''/>
					<path id='path2' d=''/>
					<path id='path3' d=''/>
				</svg>
	
				<div class='section' style='background-color:$bgColor;'>
					<h1>Photos</h1>
					<p>preview mode</p>

					<svg class='svgCorner svgCornerTR' id='svg5' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
					<svg class='svgCorner svgCornerTL' id='svgR5' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
					<svg class='svgCorner svgCornerBR' id='svg6' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
					<svg class='svgCorner svgCornerBL' id='svgR6' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
				</div>

				<svg class='svgEdge svgEdgeBottom' id='svg2' viewBox='0 0 2000 150'>				
					<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
				</svg>
	
				<div class='section'>
					<h1>About us</h1>
					<p>preview mode</p>

					<svg class='svgCorner svgCornerTR' id='svg7' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
					<svg class='svgCorner svgCornerTL' id='svgR7' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
					<svg class='svgCorner svgCornerBR' id='svg8' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
					<svg class='svgCorner svgCornerBL' id='svgR8' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
				</div>

				<svg class='svgEdge' id='svg3'viewBox='0 0 2000 150' >				
					<path id='path1' d=''/>
					<path id='path2' d=''/>
					<path id='path3' d=''/>
				</svg>

				<div class='section' style='background-color: $bgColor;'>
					<h1>upcoming events</h1>
					<p>preview</p>

					<svg class='svgCorner svgCornerTR' id='svg9' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
					<svg class='svgCorner svgCornerTL' id='svgR9' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
					<svg class='svgCorner svgCornerBR' id='svg10' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
					<svg class='svgCorner svgCornerBL' id='svgR10' viewBox='0 0 150 150'>				
						<path id='path1' d=''/><path id='path2' d=''/><path id='path3' d=''/>
					</svg>
				</div>

			</div>
		</div>
	";
	echo "</div>";
	
	echo "</div>";	//row end
	echo "<button class='abtn btn-primary' id='ajaxSave'><b id='ajaxStatus'>SAVE</b></button>";
	echo "</div>";	//blue box div end
	echo "</div>\n"; //show/hide div end
	
	
	//javascript ajax
	
		
		echo "
		<script>
		function enableSave(){
			$('button#ajaxSave')
			.addClass('btn-primary')
			.removeClass('abtn-warning')
			.removeClass('abtn-success')
			.removeClass('abtn-danger')
			.prop('disabled',false);
			$('b#ajaxStatus').text('SAVE');
		}

		$('button#ajaxSave').click(function(){
			$('b#ajaxStatus').text('Pending');
			$('button#ajaxSave').addClass('abtn-warning').removeClass('btn-primary').prop('disabled',true);
			var data = {};
			data.action = 'layout';
			data.zite_id = $zite_id;
			";
		for ($i = 1; $i <= 10; $i++) {
			echo "
			var id = $('#svgSelect$i').val();
			data.se$i = {
				'svg'		: id,
				'color1'	: $('#path1Color$i').val(),
				'color2'	: $('#path2Color$i').val(),
				'color3'	: $('#path3Color$i').val()
			}
			";
		} 
		echo "
			$.ajax({
				url: 'ajax_processor.php',
				type: 'post',
				data: data,
				success: function(data){
					$('b#ajaxStatus').text('Success');
					$('button#ajaxSave').addClass('abtn-success').removeClass('abtn-warning');
				},
				error: function (){
					$('b#ajaxStatus').text('Success');
					$('button#ajaxSave').addClass('abtn-success').removeClass('abtn-danger');
				}
			});
		});
		</script>
?>