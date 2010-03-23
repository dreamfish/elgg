<?php
/*
Extension Name: DiscussionSearch
Extension Url: http://www.consil.co.uk
Description: Adds a standard search box above the discussions.
This extension was built using DiscussionHeading as an example.
Version: 0.5
Author: Jason Judge
Author Url: http://www.consil.co.uk/blog/author/judgej
*/

function DiscussionSearch_SearchForm($DiscussionGrid)
{
	global $Context;

	$SessionPostBackKey = $Context->Session->GetVariable('SessionPostBackKey', 'string');
	
	// Get the search form handler.
	$SearchForm = $Context->ObjectFactory->CreateControl($Context, "SearchForm");

	// Make sure all postbacks go to search.php and not the current context (e.g. index.php).
	// Being referenced, there is a danger this will leak out to other plugin contexts, so we
	// will set it back after rendering the form, just in case.
	$SelfUrl = $SearchForm->Context->SelfUrl;

    // If we do not change this SelfURL context, then search results will appear between the search
    // form and the main discussions list.
	$SearchForm->Context->SelfUrl = 'search.php';

	// Render the search form.
	$SearchForm->Render_NoPostBack();

	// Set the context back, in case it leaks out..
	$SearchForm->Context->SelfUrl = $SelfUrl;
}

if ($Context->SelfUrl == 'index.php' /*&& !empty($Context->Session->User->UserID)*/) {
	$Context->AddToDelegate('DiscussionGrid', 'PreRender', 'DiscussionSearch_SearchForm');
}

?>