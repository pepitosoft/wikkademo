<?php

//interface strings
if (!defined('ERROR_EMPTY_COMMENT')) define('ERROR_EMPTY_COMMENT', "Sorry, empty comments cannot be saved.");
if (!defined('ERROR_NO_COMMENT_WRITE_ACCESS')) define('ERROR_NO_COMMENT_WRITE_ACCESS', "Sorry, you are not allowed to post comments to this page.");
if (!defined('ERROR_COMMENT_NO_KEY')) define('ERROR_COMMENT_NO_KEY', "Your comment cannot be saved. Please contact the wiki administrator(1).");
if (!defined('ERROR_COMMENT_INVALID_KEY')) define('ERROR_COMMENT_INVALID_KEY', "Your comment cannot be saved. Please contact the wiki administrator(2).");

$redirectmessage = '';

if (($this->HasAccess('comment') || $this->IsAdmin()) && $this->existsPage($this->tag))
{
	$body = trim($this->GetSafeVar('body', 'post'));

	if ('' == $body) #check if comment is non-empty
	{
		$redirectmessage = T_("Comment body was empty -- not saved!");
	}
	elseif (FALSE === ($aKey = $this->getSessionKey($this->GetSafeVar('form_id', 'post'))))	# check if page key was stored in session
	{
		$redirectmessage = T_("Your comment cannot be saved. Please contact the wiki administrator.");
	}
	elseif (TRUE !== ($rc = $this->hasValidSessionKey($aKey)))	# check if correct name,key pair was passed
	{
		$redirectmessage = T_("Your comment cannot be saved. Please contact the wiki administrator.");
	}
	// all is kosher: store new comment
	else
	{
		$body = nl2br($this->htmlspecialchars_ent($body));
		$this->SaveComment($this->tag, $body);
	}
	
	// redirect to parent page
	$this->Redirect($this->Href(), $redirectmessage);
}
else
{
	echo '<div id="content"><em class="error">'.T_("Sorry, you're not allowed to post comments to this page'").'</em></div>'."\n";
}
?>