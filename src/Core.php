<?php 
namespace Jnologi\RouterApi;

class Core{
    protected $debug = false;
    protected $connected = false;
    protected $timeout = 20;
    protected $attempts = 5;
    protected $socket;
    protected $error_no;
    protected $error_str;
    protected $ssl = false;
    protected $port = 8728;
    protected $certless = false;
    protected $delay = 3;
   
    public function __construct(
        protected $ip,
        protected $username,
        protected $password
    ){
      $this->connect($this->ip, $this->username, $this->password);
    }
    protected function isIsterable($var){
        return $var !== null
        && (is_array($var)
        || $var instanceof \Traversable
        || $var instanceof \Iterator
        || $var instanceof \IteratorAggregate
        );

    }

    protected function debug($text){
        if($this->debug){
            throw new \Exception($text);
        }
    }

    protected function encodedLength($length){
        if ($length < 0x80) {
            $length = chr($length);
        } elseif ($length < 0x4000) {
            $length |= 0x8000;
            $length = chr(($length >> 8) & 0xFF) . chr($length & 0xFF);
        } elseif ($length < 0x200000) {
            $length |= 0xC00000;
            $length = chr(($length >> 16) & 0xFF) . chr(($length >> 8) & 0xFF) . chr($length & 0xFF);
        } elseif ($length < 0x10000000) {
            $length |= 0xE0000000;
            $length = chr(($length >> 24) & 0xFF) . chr(($length >> 16) & 0xFF) . chr(($length >> 8) & 0xFF) . chr($length & 0xFF);
        } elseif ($length >= 0x10000000) {
            $length = chr(0xF0) . chr(($length >> 24) & 0xFF) . chr(($length >> 16) & 0xFF) . chr(($length >> 8) & 0xFF) . chr($length & 0xFF);
        }

        return $length ?? null;
    }

    protected function connect($ip, $username, $password)
    {
        for ($ATTEMPT = 1; $ATTEMPT <= $this->attempts; $ATTEMPT++) {
            $this->connected = false;
            $PROTOCOL = ($this->ssl ? 'ssl://' : '' );
            $CERTLESS = ($this->certless ? ':@SECLEVEL=0' : '' );
            $context = stream_context_create(array('ssl' => array('ciphers' => 'ADH:ALL' . $CERTLESS, 'verify_peer' => false, 'verify_peer_name' => false)));
            $this->debug('Connection attempt #' . $ATTEMPT . ' to ' . $PROTOCOL . $ip . ':' . $this->port . '...');
            $this->socket = @stream_socket_client($PROTOCOL . $ip.':'. $this->port, $this->error_no, $this->error_str, $this->timeout, STREAM_CLIENT_CONNECT,$context);
            if ($this->socket) {
                socket_set_timeout($this->socket, $this->timeout);
                $this->write('/login', false);
                $this->write('=name=' . $username, false);
                $this->write('=password=' . $password);
                $RESPONSE = $this->read(false);
                if (isset($RESPONSE[0])) {
                    if ($RESPONSE[0] == '!done') {
                        if (!isset($RESPONSE[1])) {
                            // Login method post-v6.43
                            $this->connected = true;
                            break;
                        } else {
                            // Login method pre-v6.43
                            $MATCHES = array();
                            if (preg_match_all('/[^=]+/i', $RESPONSE[1], $MATCHES)) {
                                if ($MATCHES[0][0] == 'ret' && strlen($MATCHES[0][1]) == 32) {
                                    $this->write('/login', false);
                                    $this->write('=name=' . $username, false);
                                    $this->write('=response=00' . md5(chr(0) . $password . pack('H*', $MATCHES[0][1])));
                                    $RESPONSE = $this->read(false);
                                    if (isset($RESPONSE[0]) && $RESPONSE[0] == '!done') {
                                        $this->connected = true;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                fclose($this->socket);
            }
            sleep($this->delay);
        }

        if ($this->connected) {
            $this->debug('Connected...');
        } else {
            $this->debug('Error...');
        }
        return $this->connected;
    }


    protected function disconnected(){
        if( is_resource($this->socket) ) {
            fclose($this->socket);
        }
        $this->connected = false;
        $this->debug('Disconnected...');
    }

    protected function parseResponse($response)
    {
        if (!is_array($response)) {
            return [];
        }
        
        $parsed = [];
        $current = [];
        $singleValue = null;
        
        foreach ($response as $item) {
            if (in_array($item, ['!fatal', '!re', '!trap'])) {
                if ($item === '!re') {
                    $parsed[] = []; // Inisialisasi array baru
                    $current = &$parsed[count($parsed) - 1]; // Referensi ke elemen terakhir
                } else {
                    if (!isset($parsed[$item])) {
                        $parsed[$item] = []; // Inisialisasi array baru jika belum ada
                    }
                    $current = &$parsed[$item]; // Referensi ke elemen terakhir dari sub-array
                }
            } elseif ($item !== '!done') {
                if (preg_match_all('/[^=]+/i', $item, $matches)) {
                    $key = $matches[0][0];
                    $value = $matches[0][1] ?? '';
        
                    if ($key === 'ret') {
                        $singleValue = $value;
                    }
        
                    $current[$key] = $value;
                }
            }
        }
        
        return empty($parsed) && $singleValue !== null ? $singleValue : $parsed;
    }

    protected function parseResponse4Smarty($response)
    {
        if (is_array($response)) {
            $PARSED      = [];
            $CURRENT     = null;
            $singlevalue = null;
            foreach ($response as $x) {
                if (in_array($x, ['!fatal','!re','!trap'])) {
                    if ($x == '!re') {
                        $CURRENT =& $PARSED[$x];
                    } else {
                        $CURRENT =& $PARSED[$x];
                    }
                } elseif ($x != '!done') {
                    $MATCHES = array();
                    if (preg_match_all('/[^=]+/i', $x, $MATCHES)) {
                        if ($MATCHES[0][0] == 'ret') {
                            $singlevalue = $MATCHES[0][1];
                        }
                        $CURRENT[$MATCHES[0][0]] = (isset($MATCHES[0][1]) ? $MATCHES[0][1] : '');
                    }
                }
            }
            foreach ($PARSED as $key => $value) {
                $PARSED[$key] = $this->arrayChangeKeyName($value);
            }
            return $PARSED;
            if (empty($PARSED) && !is_null($singlevalue)) {
                $PARSED = $singlevalue;
            }
        } else {
            return array();
        }
    }

    protected function arrayChangeKeyName(&$array)
    {
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                $tmp = str_replace("-", "_", $k);
                $tmp = str_replace("/", "_", $tmp);
                if ($tmp) {
                    $array_new[$tmp] = $v;
                } else {
                    $array_new[$k] = $v;
                }
            }
            return $array_new;
        } else {
            return $array;
        }
    }

    protected function read($parse = true)
    {
        $RESPONSE     = [];
        $receiveddone = false;
        while (true) {
            $BYTE   = ord(fread($this->socket, 1));
            $LENGTH = 0;
            if ($BYTE & 128) {
                switch ($BYTE & 240) {
                    case 128:
                        $LENGTH = (($BYTE & 63) << 8) + ord(fread($this->socket, 1));
                        break;
                    case 192:
                        $LENGTH = (($BYTE & 31) << 8) + ord(fread($this->socket, 1));
                        $LENGTH = ($LENGTH << 8) + ord(fread($this->socket, 1));
                        break;
                    case 224:
                        $LENGTH = (($BYTE & 15) << 8) + ord(fread($this->socket, 1));
                        $LENGTH = ($LENGTH << 8) + ord(fread($this->socket, 1));
                        $LENGTH = ($LENGTH << 8) + ord(fread($this->socket, 1));
                        break;
                    default:
                        $LENGTH = ord(fread($this->socket, 1));
                        $LENGTH = ($LENGTH << 8) + ord(fread($this->socket, 1));
                        $LENGTH = ($LENGTH << 8) + ord(fread($this->socket, 1));
                        $LENGTH = ($LENGTH << 8) + ord(fread($this->socket, 1));
                        break;
                }
            } else {
                $LENGTH = $BYTE;
            }

            $_ = "";

            // If we have got more characters to read, read them in.
            if ($LENGTH > 0) {
                $_      = "";
                $retlen = 0;
                while ($retlen < $LENGTH) {
                    $toread = $LENGTH - $retlen;
                    $_ .= fread($this->socket, $toread);
                    $retlen = strlen($_);
                }
                $RESPONSE[] = $_;
                $this->debug(">>> [{$retlen}/{$LENGTH}] bytes read.");
            }

            // If we get a !done, make a note of it.
            if ($_ == "!done") {
                $receiveddone = true;
            }

            $STATUS = socket_get_status($this->socket);
            if ($LENGTH > 0) {
                $this->debug('>>> [' . $LENGTH . ', ' . $STATUS['unread_bytes'] . ']' . $_);
            }

            if ((!$this->connected && !$STATUS['unread_bytes']) || ($this->connected && !$STATUS['unread_bytes'] && $receiveddone) || $STATUS['timed_out']) {
                break;
            }
        }

        if ($parse) {
            $RESPONSE = $this->parseResponse($RESPONSE);
        }

        return $RESPONSE;
    }

    protected function write($command, $param2 = true)
    {
        if ($command) {
            $data = explode("\n", $command);
            foreach ($data as $com) {
                $com = trim($com);
                fwrite($this->socket, $this->encodedLength(strlen($com)) . $com);
                $this->debug('<<< [' . strlen($com) . '] ' . $com);
            }

            switch (gettype($param2)) {
                case 'integer':
                    fwrite($this->socket, $this->encodedLength(strlen(".tag={$param2}")) . '.tag=' . $param2 . chr(0));
                    $this->debug('<<< [' . strlen("tag={$param2}") . '] .tag=' . $param2);
                    break;
                case 'boolean':
                    fwrite($this->socket, ($param2 ? chr(0) : ''));
                    break;
            }

            return true;
        } else {
            return false;
        }
    }
  

    public static function config($ip, $username, $password){
        return new static($ip, $username, $password);
    }

    protected function comm(string $com, array $arr = [])
    {
        $count = count($arr);
        $this->write($com, !$arr);
        $i = 0;
        if ($this->isIsterable($arr)) {
            foreach ($arr as $k => $v) {
                $el = match($k[0]) {
                    "?" => "$k=$v",
                    "~" => "$k~$v",
                    default => "=$k=$v"
                };
            
                $last = $i++ == $count - 1;
                $this->write($el, $last);
            }
        }

        return $this->read();
    }

    /**
     * Standard destructor
     *
     * @return void
     */
    public function __destruct()
    {
        $this->disconnected();
    }

    public function query(string $query){
        if($this->connected){
           return $this->comm($query);
        }
    }

    public function where(string $query, array $params){
        if($this->connected){
            return $this->comm($query, $params);
        }
    }

    public function getById(string $query, string $id){
        if($this->connected){
            return $this->comm($query, ["?.id" => $id]);
        }
    }
   
}