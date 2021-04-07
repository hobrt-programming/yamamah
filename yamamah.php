<?php

/**
* @author hobrt.me
*/
class Sms
{
    public $user;

    public $pass;

    public $userSender;

    public $base_url = "http://api.yamamah.com/";

    function __construct($user, $pass, $us)
    {
        $this->user=$user;
        $this->pass=$pass;
        $this->userSender = $us;
    }

    
    /**
    * @access public
    * @param array, String, String
    * @return true
    **/

    public function send($teles, $msg, $time = false)
    {

        $ar['username'] = $this->user;
        $ar['password'] = $this->pass;
        $ar['Tagname'] = $this->userSender;
        $ar['Message'] = $msg;

        $i = 0;

        $telc = count($teles);


        foreach ($teles as $k => $key) {

            if(count($teles) > 500) {

                $telesf[] = $key;

                unset($teles[$k]);

                $i++;

                if($i%500 == 0) {
                    $ar['RecepientNumber'] = implode(",", $telesf);

                    $w = http_build_query($ar);

                    $t = $this->base_url."SendSMS?".$w;

                    $this->send_request($t);
                    $telesf = array();
                }
            }else {
                $ar['RecepientNumber'] = implode(",", $teles);


                $w = http_build_query($ar);

                $t = $this->base_url."SendSMS?".$w;
                    
                $this->send_request($t);
                $telesf = array();

                break;
            }
        }

        

        return true;

    }

    /**
    * @access public
    * @param Void
    * @return Int
    **/

    public function getCredet()
    {
        $url = $this->base_url."GetCredit/".$this->user."/".$this->pass;
        return $this->send_request($url);
    }

    public function send_request($url)
    {
        echo $url;
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_USERAGENT      => "spider", // who am i
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $content;
    }
}
