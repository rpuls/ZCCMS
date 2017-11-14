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
			<table>
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
						<td><div id='div1c$svgPos'><input id='path1Color$svgPos' type=text value='$bgColor' class='spectrumControl'></div></td>
						<td><div id='div2c$svgPos'><input id='path2Color$svgPos' type=text value='$bgColor' class='spectrumControl'></div></td>
						<td><div id='div3c$svgPos'><input id='path3Color$svgPos' type=text value='$bgColor' class='spectrumControl'></div></td>
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
							if(svgContentArray[this.value].path1 == ''){
								$('div#div1c$svgPos').fadeOut(1000);
							}else{
								$('div#div1c$svgPos').fadeIn(1000);
							}
							
							if(svgContentArray[this.value].path2 == ''){
								$('div#div2c$svgPos').fadeOut(1000);
							}else{
								$('div#div2c$svgPos').fadeIn(1000);
							}

							if(svgContentArray[this.value].path3 == ''){
								$('div#div3c$svgPos').fadeOut(1000);
							}else{
								$('div#div3c$svgPos').fadeIn(1000);
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
	echo file_get_contents("../includes/preview.html");
	echo "</div>";
	
	echo "
	<style>
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
	";
	
	echo "</div>";	//row end
	echo "<button class='abtn btn-primary' id='ajaxSave'><b id='ajaxStatus'>SAVE</b></button>";
	echo "</div>";	//blue box div end
	echo "</div>\n"; //show/hide div end
	
	
	//javascript ajax
		echo "
		<script>

		$(document).ready(function() {
			setPreview();
		});

		function setPreview(){
			$('#pTitle').text('$FB_page_name');
			$('.svgCorner').css('fill', '$bgColor');
			$('.svgEdge').css('fill', '$bgColor');
			$('.sectionBg').css('background-color', '$bgColor');
		}

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
	";
?>