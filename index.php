<?php
/*
-Features-
Alpha: 
* Show error/success after form has been submitted.
* A basic HTML/CSS framework
* Sanitize input

Beta:
* Refactor code so it's elegant before anyone sees
	* Perhaps the output is built by a very simple templating engine
		* is parsing php variables going to work better?

Final:
* Simple front and back end validation
* Small Ajax library
* write so that it can easily be wrapped in a wordpress plugin?

Bonus:
* write in the storage methods so that they can be extended if someone wants to write a database driver? 
* a plugin api for integration with something like an email blacklist?
*/
class holding
{
	protected $config = array(
		'dataFile'		=> 'data/data.csv',
		'dateFormat'	=> 'Y-m-d h:i a',
		'template'		=> 'templates/main.tpl',
		'form'			=> 'templates/form.tpl',
		'success'		=> '<p>Thank you for submitting your information.</p>',
		'failure'		=> '<p>Oh no! Something went wrong!'
	);
	
	public function storeData($data)
	{
		$data['IP'] = $_SERVER['REMOTE_ADDR'];
		$data['When'] = date($this->config['dateFormat']);
		
		// create the directory structure if it doesn't exist yet
		if (!file_exists(dirname($this->config['dataFile']))) { mkdir(dirname($this->config['dataFile']), 0777, true); }
		
		$dataFile = fopen($this->config['dataFile'], 'a');
		// if the file is empty, write field names first
		if (0 == filesize($this->config['dataFile'])) { fputcsv($dataFile,array_keys($data)); }
		fputcsv($dataFile,$data);
		fclose($dataFile);
	}
}

$obj = new holding;

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
	// add ip and date to the post data
	$data = $_POST;
	
	$obj->storeData($data);
}
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
<title>Holding Page</title>
<link rel="stylesheet" href="http://zeitgeist.rnf.me/zeitgeist.min.css" />
</head>
<body>
	<h1>Gimme Dem Email</h1>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<label>Name<input type="text" name="Name" /></label>
		<label>Email<input type="text" name="Email" /></label>
		<input type="submit" />
	</form>
</body>
</html>