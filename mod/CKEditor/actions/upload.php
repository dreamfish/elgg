<?php

global $CONFIG;

$user = get_loggedin_user();
 //$user = $_SESSION['user'];

$funcNum2 = get_Input('CKEditorFuncNum', 'CKEditorFuncNum');

$file = new ElggFile();
$filestorename = strtolower(time().$_FILES['upload']['name']);
$file->setFilename($filestorename);
$file->setMimeType($_FILES['upload']['type']);

$file->owner_guid = $user->guid;
$file->owner_guid = $_SESSION['user']->getGUID();
$file->subtype = "file";
$file->originalfilename = $filestorename;

//$file->access_id = ACCESS_DEFAULT;
//Changed by fabio due to bug report at:
//http://community.elgg.org/mod/plugins/read.php?guid=385093
$file->access_id = ACCESS_PUBLIC;

$file->open("write");
$file->write(get_uploaded_file('upload'));
$file->close();

$result = $file->save();

if ($result)
{
	$master = get_resized_image_from_existing_file($file->getFilenameOnFilestore(),550,550);

	if ($master !== false)
	{
		$_SESSION['UPLOAD_DATA']['file_save'] = "started";
		$filehandler = new ElggFile();
		$filehandler->setFilename($filestorename);
		$filehandler->setMimeType($_FILES['upload']['type']);
		$filehandler->owner_guid = $_SESSION['user']->getGUID();
		$filehandler->subtype = "file";
		$filehandler->originalfilename =$filestorename;
		$filehandler->access_id = ACCESS_PUBLIC;
		$filehandler->open("write");
		$filehandler->write($master);
		$filehandler->close();
		$filehandler->save();
		
		$url = ''.$CONFIG->url.'mod/CKEditor/image_viewer.php?file_guid='.$filehandler->guid;
		
		echo '<script type="text/javascript">
		window.parent.CKEDITOR.tools.callFunction('.$funcNum2.', "'.$url.'","Success");
		</script>';
		exit();

	}
	else
	{

		echo '<script type="text/javascript">
		window.parent.CKEDITOR.tools.callFunction('.$funcNum2.', "","");
		</script>';
		exit();
	}
}
?>
