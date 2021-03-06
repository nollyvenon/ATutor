<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2010                                      */
/* Inclusive Design Institute                                   */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$
define('AT_INCLUDE_PATH', '../../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');

authenticate(AT_PRIV_FAQ);


if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: index_instructor.php');
	exit;
} 

if (isset($_GET['id'])) {
	$id = intval($_GET['id']);
} else {
	$id = intval($_POST['id']);
}

if (isset($_POST['submit'])) {
	if (trim($_POST['name']) == '') {
		$msg->addError('NAME_EMPTY');
	}

	if (!$msg->containsErrors()) {
		$_POST['name'] = $addslashes($_POST['name']);
		//This will truncate the content of the length to 240 as defined in the db.
		$_POST['name'] = validate_length($_POST['name'], 250);

		$sql	= "UPDATE %sfaq_topics SET name='%s' WHERE topic_id=%d AND course_id=%d";
		$result = queryDB($sql, array(TABLE_PREFIX, $_POST['name'], $id, $_SESSION['course_id']));
		
		$msg->addFeedback('ACTION_COMPLETED_SUCCESSFULLY');
		header('Location: index_instructor.php');
		exit;
	}
}
$onload = 'document.form.name.focus();';

require(AT_INCLUDE_PATH.'header.inc.php');

if ($id == 0) {
	$msg->printErrors('ITEM_NOT_FOUND');
	require (AT_INCLUDE_PATH.'footer.inc.php');
	exit;
}

$sql	= "SELECT name FROM %sfaq_topics WHERE course_id=%d AND topic_id=%d ORDER BY name";
$row_topic = queryDB($sql, array(TABLE_PREFIX, $_SESSION['course_id'], $id), TRUE);

if(count($row_topic) == 0){
	$msg->printErrorS('ITEM_NOT_FOUND');
	require(AT_INCLUDE_PATH.'footer.inc.php');
	exit;
} else if (!isset($_POST['name'])) {
	$_POST['name'] = $row_topic['name'];
}

$savant->assign('id', $id);
$savant->display('instructor/faq/edit_topic.tmpl.php');
require (AT_INCLUDE_PATH.'footer.inc.php'); ?>