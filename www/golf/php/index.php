<?php

$max_chars	= 62;
$flag		= 'flag{perlgolfisbetter}';

function run_code($input, $test) {
	$path = '/var/golf/php/' . md5($input) . '/';
	$file = 'input.php';

	mkdir($path);
	file_put_contents($path . $file, $input);

	return exec('docker run -i --rm -m 8M -v ' . escapeshellarg($path) . ':/foo perlgolf /usr/bin/phpgolf ' . escapeshellarg($test));
}

function gen_string() {
	$file = '/usr/share/golf/secret_words.txt';
	$words = file($file);

	$output = '';
	$number = rand(8, 16);

	for ($i = 0; $i < $number; $i++) {
		$output .= trim($words[array_rand($words)]) . ' ';
	}

	return trim($output);
}

function filter_string($input) {
	return preg_filter("/\pL/e", '($0|" ")^chr($m^=32)', $input);
}

if (!isset($_POST['input']) || !is_string($_POST['input'])) {
	$_POST['input'] = '';
}

if (strlen($_POST['input']) > $max_chars) {
	$_POST['input'] = substr($_POST['input'], 0, $max_chars);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>PHP + Golf === NULL</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://bootswatch.com/yeti/bootstrap.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
 </head>
 <body>
  <div class="container">
   <h1>Goal</h1>
   <span>Write a PHP program that takes a parameter as input and outputs a filtered version with alternating upper/lower case letters of the (english) alphabet. Non-letter characters have to be printed but otherwise ignored.</span>
   <h1>Example</h1>
   <table class="table table-striped table-hover ">
    <tr>
     <td>Input</td>
     <td>Hello World! Hallo Welt!</td>
    </tr>
    <tr>
     <td>Output</td>
     <td>HeLlO wOrLd! HaLlO wElT!</td>
    </tr>
   </table>
   <h1>Rules</h1>
   <ul>
    <li>You have 1 second.</li>
    <li>You have <?= htmlspecialchars($max_chars) ?> (ASCII) chars.</li>
    <li>Do not flood the server.</li>
   </ul>
   <h1>IO</h1>
   <div class="row">
    <form method="post" class="form-horizontal">
     <div class="form-group">
      <div class="col-md-11">
       <input type="text" name="input" class="form-control" placeholder="Code" value="<?= htmlspecialchars($_POST['input']) ?>" maxlength="<?= htmlspecialchars($max_chars) ?>" size="<?= htmlspecialchars($max_chars) ?>">
      </div>
      <div class="col-md-1">
       <button type="submit" class="btn btn-primary">Submit</button>
      </div>
     </div>
    </form>
   </div>
<?php

if ($_POST['input']) {
	$test = gen_string();
	$output = run_code($_POST['input'], $test);
	$expected = filter_string($test);

?>
   <table class="table table-striped table-hover ">
    <tr>
     <td>Input</td>
     <td><?= htmlspecialchars($test) ?></td>
    </tr>
    <tr>
     <td>Output</td>
     <td><?= htmlspecialchars($output) ?></td>
    </tr>
    <tr>
     <td>Expected</td>
     <td><?= htmlspecialchars($expected) ?></td>
    </tr>
   </table>
<?php

	if ($output === $expected) {
		echo "Here's the flag: " . $flag . "\n";
	} else {
		echo "Wroooooooong.\n";
	}

}

?>
  </div>
 </body>
</html>
