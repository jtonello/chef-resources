<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>Chef Resources</title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/black-tie/jquery-ui.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="./css/style.css" />
<script>
$(document).ready(function(){
    $('#resourceTable,#inspecTable').DataTable({
    "lengthMenu": [[5, 10, 20, 30, -1], [5, 10, 20, 30, "All"]],
    "bLengthChange":true,
    "bFilter": true
    });
    
    // Create the main tabs
    $("div#tabs").tabs();

});
</script>
</head>
<body>
<!-- Top tabs-->
<div id="tabs">
        <ul>
                <li><a href="#tabs-1">Chef Resources</a></li>
                <li><a href="#tabs-2">Inspec Resources</a></li>
        </ul>
        <div id="tabs-1">
        <table id='resourceTable' width='100%'>
            <thead>
            <tr>
                <th colspan='1'>Resource</th>
                <th colspan='1'>Usage</th>
		<th colspan='1'>Examples</th>
            </tr>
            </thead>
            <tbody>
<?php

	include 'Parsedown.php';
	$Parsedown = new Parsedown();
        $dir = './chef-resources';
        $lines = array_diff(scandir($dir), array('..', '.'));
        $url = "https://docs.chef.io/resources";

        echo "<tr>\n";
        for ($i=0; $i<sizeof($lines)+2; $i++) {
                $resource_name = str_replace(".yaml","",$lines[$i]);
                $resource_file = $lines[$i];
                $yamlData = file_get_contents($dir . '/' . $resource_file);
		if ($yamlData) {
			$parsed = yaml_parse($yamlData);
		}
                $string = $parsed['resource_description_list'][0]['markdown'];
		$new_in = $parsed['resource_new_in'];
		$example = $parsed['examples'];

                if (strpos($resource_file, '.yaml')) {
    
			echo "<td width='200px'><a href='$url/$resource_name#examples'>$resource_name</a></td>";
                        echo "<td width='400px' valign='top'>";
                                if(!$string ) {
                                        $string = $parsed['resource_description_list'][1]['markdown'];
                                        if(!$string) {
                                                $string = $parsed['resource_description_list'][2]['markdown'];
                                        }
                                }
                                echo preg_replace("/\*{2}(.*?)\*{2,}/", '<a href="https://docs.chef.io/resources/$1">$1</a>', $string);

				if($new_in) {
					echo " (New in: $new_in)";
				}
                        echo "</td>";
			echo "<td width='300px'>\n<span id='example$i'>Example</span>\n<div id='example$i' style='display:none;'>" . $Parsedown->text($example) . "</div>\n</td>";
                //      if(($i+1)%2==0 && $i!=sizeof($lines)-1) echo '</tr><tr>';
                echo "</tr>";
                }
        }
?>
        </td></tbody>
        </table>
        </div>
        <div id="tabs-2">
        <table id='inspecTable' width='100%'>
            <thead>
            <tr>
                <th colspan='1'>Resource</th>
                <th colspan='1'>Usage</th>
                <th colspan='1'>Examples</th>
            </tr>
            </thead>
            <tbody>
<?php

        $dir = './inspec-resources';
        $lines = array_diff(scandir($dir), array('..', '.'));
        $inspecurl = "https://docs.chef.io/inspec/resources";

        echo "<tr>\n";
        for ($i=0; $i<sizeof($lines)+2; $i++) {
                $resource_name = str_replace(".md","",$lines[$i]);
                $resource_file = $lines[$i];
                $file_content = file_get_contents($dir . '/' . $resource_file);

                if (strpos($resource_file, '.md') && $resource_name != '_index') {
                        $snippet = preg_match("/^\#\# Syntax(.*)#\#/msU",$file_content,$match2);
                        echo "<td width='200px'><a href='$inspecurl/$resource_name'>$resource_name</a></td>";
                        echo "<td width='400px' valign='top'>";
                                if($file_content) {
                                        $use = preg_match("/^(Use.*)#\#/msU",$file_content,$match);
                                        $snippet = $Parsedown->text($match[1]);
                                }
                                echo preg_replace("/<code>(.*?)<\/code>/", '<a href="' . $inspecurl . '/$1">$1</a>', $snippet);

                        echo "</td>";
                        echo "<td width='300px'>\n<span id='inspec$i'>Example</span>\n<div id='inspec$i' style='display:none;'>" . $Parsedown->text($match2[1])  . "</div>\n</td>";
                echo "</tr>";
                }
        }


?>
        </td></tbody>
        </table>
        </div>

</div>
<iframe id="resourceContent" width="100%" height="650" frameBorder="0"></iframe>

</body>
</html>
<script type="text/javascript">
        $('td a').on('click', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');
                $('#resourceContent').attr("src", href);
                table.draw(false);
        });

	$.fn.extend({
    	toggleText: function(a, b){
        	return this.text(this.text() == b ? a : b);
    	}
	});

    // Show example
    $('span').click(function() {
	    varid = $(this).attr('id');
	    $('div#' + varid).toggle('slow');
	    $('span#' + varid).toggleText('Example', 'Close');
    });
</script>

