<?php

/**
 * service logic
 * 1. Request for public action/task/sub_task, then return it
 * 2. Request for private action/task/sub_task
 * 		2.1 Is user authenticated
 * 			2.1.1 Yes: Is user authorized?
 * 				2.1.1.1 Yes: return action/task/sub_task
 * 				2.1.1.2 No: Return code 401
 * 			2.1.2 No: Return code 401
 */

/**
 * 1xx Informational
 * 100 Continue
 * 101 Switching Protocols
 * 102 Processing (WebDAV)
 *
 * 2xx Success
 * 200 OK
 * 201 Created
 * 202 Accepted
 * 203 Non-Authoritative Information
 * 204 No Content
 * 205 Reset Content
 * 206 Partial Content
 * 207 Multi-Status (WebDAV)
 * 208 Already Reported (WebDAV)
 * 226 IM Used
 *
 * 3xx Redirection
 * 300 Multiple Choices
 * 301 Moved Permanently
 * 302 Found
 * 303 See Other
 * 304 Not Modified
 * 305 Use Proxy
 * 306 (Unused)
 * 307 Temporary Redirect
 * 308 Permanent Redirect (experiemental)
 *
 * 4xx Client Error
 * 400 Bad Request
 * 401 Unauthorized
 * 402 Payment Required
 * 403 Forbidden
 * 404 Not Found
 * 405 Method Not Allowed
 * 406 Not Acceptable
 * 407 Proxy Authentication Required
 * 408 Request Timeout
 * 409 Conflict
 * 410 Gone
 * 411 Length Required
 * 412 Precondition Failed
 * 413 Request Entity Too Large
 * 414 Request-URI Too Long
 * 415 Unsupported Media Type
 * 416 Requested Range Not Satisfiable
 * 417 Expectation Failed
 * 418 I'm a teapot (RFC 2324)
 * 420 Enhance Your Calm (Twitter)
 * 422 Unprocessable Entity (WebDAV)
 * 423 Locked (WebDAV)
 * 424 Failed Dependency (WebDAV)
 * 425 Reserved for WebDAV
 * 426 Upgrade Required
 * 428 Precondition Required
 * 429 Too Many Requests
 * 431 Request Header Fields Too Large
 * 444 No Response (Nginx)
 * 449 Retry With (Microsoft)
 * 450 Blocked by Windows Parental Controls (Microsoft)
 * 451 Unavailable For Legal Reasons
 * 499 Client Closed Request (Nginx)
 *
 * 5xx Server Error
 * 500 Internal Server Error
 * 501 Not Implemented
 * 502 Bad Gateway
 * 503 Service Unavailable
 * 504 Gateway Timeout
 * 505 HTTP Version Not Supported
 * 506 Variant Also Negotiates (Experimental)
 * 507 Insufficient Storage (WebDAV)
 * 508 Loop Detected (WebDAV)
 * 509 Bandwidth Limit Exceeded (Apache)
 * 510 Not Extended
 * 511 Network Authentication Required
 * 598 Network read timeout error
 * 599 Network connect timeout error
 */
$_SESSION['authorization_message'] = '';
$jpmContent = '';
$actionInstance = NULL;
if ($_SESSION['request_type'] == 'partial') {
	echo '';
	exit;
}
try {
	$actionInstance = new $_SESSION ['url_action'] ();
	if (method_exists ( $actionInstance, $_SESSION ['url_task'] )) {
		if (isAllowed ($_SESSION ['url_collection'], $_SESSION ['url_sub_task'])) {
			if ($_SESSION['debug']) {
				echo "Running " . get_class($actionInstance) . " -> " . $_SESSION ['url_task'];
			}
			$jpmContent .= $actionInstance->{$_SESSION ['url_task']} ( $urlArgsArray );
		} else {
			$jpmContent .= $_SESSION['authorization_message'];
		}
	} else {
		echo 'invalid task ' . $_SESSION ['url_task'];
	}
} catch ( Exception $e ) {
	echo 'invalid action ' . $_SESSION ['url_action'] . $e->getMessage ();
}
?>
