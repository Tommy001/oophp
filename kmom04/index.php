<?php
// Enable to display sourceode below this directory
include('CSource.php');
$source = new CSource();
?><!doctype html>
<html lang='en'>
<meta charset='utf-8'>
<title>View sourceode</title>
<meta name="robots" content="nofollow" />
<link rel='stylesheet' type='text/css' href='source.css'/>
<style>
.wrap {max-width: 750px;}
</style>
<body>
<div class='wrap'>
<h1>Example on usage of CSource and source.php</h1>
<p>The script source.php was first introduced to display sourcecode to aid in debugging of websites, it was purely for educational purpose and its not intended to be used for a live website. From the start the script was available in a <a href='https://github.com/mosbth/Utility/blob/master/source.php'>github repository named "Utility" as source.php</a>.</p>

<p>The script is now rewritten and code is separated in a class (CSource.php), a stylesheet (source.css), an example file displaying how to use it (example.php) and resides in its own <a href='https://github.com/mosbth/csource'>github repository CSource</a>.</p>

<p>The new repository also includes a file source.php which does it all, it is a single script which can be copied to a directory and it show the files and directory in the current directory and below.<p>

<p>Try out <a href='source.php'>source.php</a> to see how it works and study the <a href='source.php?path=example.php'>source for example.php</a> to learn how to use it with separate files.</p>

<p>Here is the sourcecode for this current directory, check out <a href='?path=index.php'>index.php</a> which is this file.</p>

</div>

<?=$source->View()?>

<hr>
<?php include('../template/footer_mos.php') ?>
