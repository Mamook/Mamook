<?php
/**
 * Copyright (c) 2008, David R. Nadeau, NadeauSoftware.com.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *	* Redistributions of source code must retain the above copyright
 *	  notice, this list of conditions and the following disclaimer.
 *
 *	* Redistributions in binary form must reproduce the above
 *	  copyright notice, this list of conditions and the following
 *	  disclaimer in the documentation and/or other materials provided
 *	  with the distribution.
 *
 *	* Neither the names of David R. Nadeau or NadeauSoftware.com, nor
 *	  the names of its contributors may be used to endorse or promote
 *	  products derived from this software without specific prior
 *	  written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY
 * WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY
 * OF SUCH DAMAGE.
 */

/*
 * This is a BSD License approved by the Open Source Initiative (OSI).
 * See:  http://www.opensource.org/licenses/bsd-license.php
 */


/**
 * Get a file on the web.  The file may be an (X)HTML page, an image, etc.
 * Return an associative array containing the page header, contents,
 * and HTTP status code.
 *
 * Values in the returned array are as defined by the CURL curl_getinfo()
 * function, and include:
 *
 * 	"url"		the last effective URL after redirects
 * 	"http_code"	the last error/status code
 * 	"content_type"	the content type from the header
 *
 * This function adds a few more:
 *
 * 	"content"	the page content (text, image, etc.)
 * 	"errno"		the CURL error code
 * 	"errmsg"	the CURL error message
 *
 * On success, "errno" is 0, "http_code" is 200, and "content" has the
 * web page.
 *
 * On an error with the URL, such as a redirect limit, or timeout,
 * "errno" will be non-zero and "errmsg" will contain an error message.
 * There other fields will be missing.
 *
 * On an error with the web site, such as a missing page, no permissions,
 * or no service, "errno" will be 0, "http_code" will be the HTTP error
 * code, and "content" will be missing.
 *
 * Parameters:
 * 	url		the URL of the page to get
 *
 * Return values:
 * 	An associative array containing the page text and error codes,
 * 	as described above.
 *
 * See also:
 *	http://nadeausoftware.com/articles/2007/06/php_tip_how_get_web_page_using_curl
 */

class GetExternalContent
{

	public function __construct()
	{
		return;
	}

	public function getWebPage($url)
	{
		$options = array(
			CURLOPT_RETURNTRANSFER => TRUE,     	# return web page
			CURLOPT_HEADER         => FALSE,    	# don't return headers
			CURLOPT_FOLLOWLOCATION => TRUE,     	# follow redirects
			CURLOPT_ENCODING       => "",       	# handle compressed
			CURLOPT_USERAGENT      => "spider", 	# who am i
			CURLOPT_AUTOREFERER    => TRUE,     	# set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,      	# timeout on connect
			CURLOPT_TIMEOUT        => 120,      	# timeout on response
			CURLOPT_MAXREDIRS      => 10,       	# stop after 10 redirects
		);

		$ch      = curl_init($url);
		curl_setopt_array($ch,$options);
		$content = curl_exec($ch);
		$err     = curl_errno($ch);
		$errmsg  = curl_error($ch);
		$header  = curl_getinfo($ch);
		curl_close($ch);

		$header['errno']   = $err;
		$header['errmsg']  = $errmsg;
		$header['content'] = $content;
		return $header;
	}

	public function fileGetContentsCurl($url)
	{
		$ch = curl_init();

		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); # Set curl to return the data instead of printing it to the browser.
		curl_setopt($ch,CURLOPT_URL,$url);

		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
	}

}