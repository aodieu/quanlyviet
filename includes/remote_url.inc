<?php
/************************************************************************
	Bản quyền (C) 2021 thuộc về Trần Anh Phương <http://aodieu.com>
	Chương trình được phát hành theo các điều khoản của giấy phép phần 
	mềm tự do GNU GPL được xuất bản bởi Quỹ Phần Mềm Tự Do (Free 
	Software Foundation), phiên bản thứ 3 của giấy phép, hoặc bất kỳ 
	phiên bản nào mới hơn (theo tùy chọn của bạn).
	Chương trình này được phân phối với kỳ vọng rằng nó sẽ có ích, nhưng 
	KHÔNG CÓ BẤT KỲ BẢO HÀNH NÀO, thậm chí KHÔNG CÓ BẢO ĐẢM NGỤ Ý VỀ KHẢ 
	NĂNG KHAI THÁC THƯƠNG MẠI HAY PHÙ HỢP VỚI MỤC ĐÍCH SỬ DỤNG CỤ THỂ NÀO.
	Chi tiết về giấy phép <http://www.gnu.org/licenses/gpl-3.0.html>.
*************************************************************************/

/*
	Read content of remote url via http.
	Does not require curl php extension nor allow_url_fopen=1.
*/
function url_get_contents($url, $timeout=10) {
	// get the host name and url path
	$parsedUrl = parse_url($url);

	if (@$parsedUrl['scheme'] == 'file')
		return file_get_contents($parsedUrl['path']);

	$host = $parsedUrl['host'];

	if (isset($parsedUrl['path']))
		$path = $parsedUrl['path'];
	else
		// the url is pointing to the host like http://www.mysite.com
		$path = '/';

	if (isset($parsedUrl['query']))
		$path .= '?' . $parsedUrl['query'];

	if (isset($parsedUrl['port']))
		$port = $parsedUrl['port'];
	else
		// most sites use port 80
		$port = '80';

	$response = '';
	// connect to the remote server
	$fp = @fsockopen($host, $port, $errno, $errstr, $timeout );
	if( !$fp ) {
		return null;
	}
	else {
		// send the necessary headers to get the file
		fputs($fp, "GET $path HTTP/1.0\r\n" .
			"Host: $host\r\n".
			(isset($parsedUrl['pass']) ? "Authorization: Basic ".
				base64_encode($parsedUrl['user'].':'.$parsedUrl['pass'])."\r\n" : '').
			"User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n" .
			"Accept: */*\r\n" .
			"Accept-Language: en-us,en;q=0.5\r\n" .
			"Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" .
			"Connection: close\r\n" .
			"Referer: http://$host\r\n\r\n");

		// retrieve the response from the remote server

		$response = stream_get_contents($fp);

		if (!strpos($response, "200 OK\r\n"))
			return null;
		// strip the headers
		$pos = strpos($response, "\r\n\r\n");

		$response = substr($response, $pos + 4);
	}

	// return the file content
	return $response;
}

function url_copy($from, $to, $timeout=10) {
	$f = fopen($to, 'wb');
	if (!$f || !fwrite($f, url_get_contents($from, $timeout)))
		return false;
	fclose($f);
	return true;
}