<?php
	// ------------- loading screen by Rasmus Puls

	echo "
	<div class='loadingDiv'>
		<div class='loadingInnerDiv'>
		<img src='http://zitecraft.com/images/home-V2-logo-color.png'>
		<p style='font-size: 200%;'>Content Management System</p>
		<div class='loader'></div>
		</div>
	</div>
	";
	
	// ------------- Pane 0.5: Section styling (svg editor)

	echo "<div style='display:none;' class='shTab' name='Section Styling' id='sh0.5'>";
	echo "<div style='max-width:770px; margin:4px 4px 4px 4px; padding:5px; z-index:90; position:relative;' id='blue' class='visible-xsX alert alert-info'>";	
	echo "<div style='border-bottom:solid; border-bottom-width:1px; font-weight:bold;'>Section Styling</div>";
	
	$bgColor = "#f9e7da";
	$getBGColorSQL = "SELECT value FROM `tblTemplateElementSettings` WHERE `PlaceID` = $zite_id  AND `TemplateElementID` = 2;";
	$res = mysqli_query($mysqli_link, $getBGColorSQL);
	while($val = mysqli_fetch_assoc($res)) {
		$bgColor = $val['value'];
	}

	echo "
	<script>
	var svgContentArray = [];
	var svgSettingsArray = [];
	svgContentArray.push({index_zero:'zero'}); //because we need to start from 1 since the mysql ID cant be 0.
	svgSettingsArray.push({index_zero:'zero'});
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

	$svgElementSQL = "SELECT * FROM `tblSvgElement` WHERE `PlaceID` = $zite_id;";
	$res = mysqli_query($mysqli_link, $svgElementSQL);
	while($prop = mysqli_fetch_assoc($res)) {
		$pos = $prop['SVGPosition'];
		$svg = $prop['SVG'];
		$p1c = $prop['Path1Color'];
		$p2c = $prop['Path2Color'];
		$p3c = $prop['Path3Color'];
		echo "svgSettingsArray.push({pos : '$pos', svg : '$svg', p1c : '$p1c', p2c : '$p2c', p3c : '$p3c'});";
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
						<th>Color 1</th>
						<th>Color 2</th>
						<th>Color 3</th>
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
						<td><div style='display : none;' id='div1c$svgPos'><input id='path1Color$svgPos' type=text value='$bgColor' class='SVGspectrumControl'></div></td>
						<td><div style='display : none;' id='div2c$svgPos'><input id='path2Color$svgPos' type=text value='$bgColor' class='SVGspectrumControl'></div></td>
						<td><div style='display : none;' id='div3c$svgPos'><input id='path3Color$svgPos' type=text value='$bgColor' class='SVGspectrumControl'></div></td>
					</tr>
					<script>
						$(document).ready(function() {
							$.each(svgContentArray, function (i, item) {
								if(i != 0){
									if(item.type == '$type'){
										$('select#svgSelect$svgPos').append($('<option>', { 
											value: i,
											text : item.name 
										}));
									}
								}
							});
							$.each(svgSettingsArray, function (i, val){
								if(i == val.pos){
									$('select#svgSelect'+i).val(val.svg).trigger('change');
									$('input#path1Color'+i).spectrum('set', val.p1c).trigger('change');
									$('input#path2Color'+i).spectrum('set', val.p2c).trigger('change');
									$('input#path3Color'+i).spectrum('set', val.p3c).trigger('change');
								}
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

								// show/hide color inputs
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
								$('#svg$svgPos').children('#path1').css('fill', $('input#path1Color$svgPos').spectrum('get').toRgbString());
								if($svgPos > 3){
									$('#svgR$svgPos').children('#path1').css('fill', $('input#path1Color$svgPos').spectrum('get').toRgbString());
								}
								enableSave();
							});

							$('input#path2Color$svgPos').on('change', function(){
								$('#svg$svgPos').children('#path2').css('fill', $('input#path2Color$svgPos').spectrum('get').toRgbString());
								if($svgPos > 3){
									$('#svgR$svgPos').children('#path2').css('fill', $('input#path2Color$svgPos').spectrum('get').toRgbString());
								}
								enableSave();
							});

							$('input#path3Color$svgPos').on('change', function(){
								$('#svg$svgPos').children('#path3').css('fill', $('input#path3Color$svgPos').spectrum('get').toRgbString());
								if($svgPos > 3){
									$('#svgR$svgPos').children('#path3').css('fill', $('input#path3Color$svgPos').spectrum('get').toRgbString());
								}
								enableSave();
							});
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
	
	
	echo "</div>";	//row end
	echo "<button class='abtn btn-primary' id='ajaxSave'><b id='ajaxStatus'>SAVE</b></button>";
	echo "</div>";	//blue box div end
	echo "</div>\n"; //show/hide div end
	
	
	//javascript ajax
		echo "
		<script>
		$('.SVGspectrumControl').spectrum({
			showPalette: true,
			showSelectionPalette: true,
			showInitial: true,
			showInput: true,
			showAlpha: true,
			preferredFormat: 'hex',
			localStorageKey: 'spectrum.zitecraft'
		});


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
				'color1'	: $('#path1Color$i').spectrum('get').toRgbString(),
				'color2'	: $('#path2Color$i').spectrum('get').toRgbString(),
				'color3'	: $('#path3Color$i').spectrum('get').toRgbString()
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