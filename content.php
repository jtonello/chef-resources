<html>
<head>
  <base href="https://docs.chef.io/" target="_blank">
</head>
<body>
<?php
$myhref = $_GET['myhref'];
require_once dirname(__FILE__) . '/vendor/autoload.php';

$response = WpOrg\Requests\Requests::get($myhref);
var_dump($response->body);

?>
</body>
</html>
