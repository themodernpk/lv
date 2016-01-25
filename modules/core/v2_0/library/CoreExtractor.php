<?php

//source: http://blog.insicdesigns.com/2009/03/parsing-xml-file-using-codeigniters-simplexml-library/

class CoreExtractor{


    //to the data from the url
    function LoadCURLPage($url, $agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13', $cookie = '', $referer = '', $post_fields = '', $return_transfer = 1, $follow_location = 1, $ssl = '', $curlopt_header = 0)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        if($ssl)
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
        }

        curl_setopt ($ch, CURLOPT_HEADER, $curlopt_header);

        if($agent)
        {
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        }

        if($post_fields)
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        if($referer)
        {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }

        if($cookie)
        {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        }

        $result = curl_exec ($ch);

        curl_close ($ch);


        //print_r($result);

        return $result;
    }


//----------------------------------------------------------------------------------------

    function extract_unit($string, $start, $end)
    {
        $pos = stripos($string, $start);

        if($pos != false) {

        $str = substr($string, $pos);

        $str_two = substr($str, strlen($start));

        $second_pos = stripos($str_two, $end);

        $str_three = substr($str_two, 0, $second_pos);

        $unit = trim($str_three); // remove whitespaces

        return $unit;
        } else
        {
            return "";
        }



    }

//----------------------------------------------------------------------------------------
    function get_links($content)
    {

        //print_r($content);
        //$pattern = "/<a style=\"(.*)\" href=\"(.*)\">(.*)<\/a>/";
        //$pattern = "/<a href=\"(.*?)\">/";
        //$pattern = "/<a href=\"([^\"]*)\">(.*)<\/a>/iU";
        //$pattern = "/href\=\'(.*?)\'/";
        //$pattern = '/#<i(.*)>#ims/';

        $pattern = "/<a.*? href=\"(.*?)\".*?>(.*?)<\/a>/i";

        preg_match_all($pattern, $content, $match);



        return $match[1];
    }
//----------------------------------------------------------------------------------------

    function get_links_comp($content)
    {

        //print_r($content);
        //$pattern = "/<a style=\"(.*)\" href=\"(.*)\">(.*)<\/a>/";
        //$pattern = "/<a href=\"(.*?)\">/";
        //$pattern = "/<a href=\"([^\"]*)\">(.*)<\/a>/iU";
        $pattern = "/href\=\'(.*?)\'/";
        //$pattern = '/#<i(.*)>#ims/';



        preg_match_all($pattern, $content, $match);


        return $match[1];
    }

//----------------------------------------------------------------------------------------

    function strip_attributes($msg, $tag, $attr="", $suffix = "")
    {
     $lengthfirst = 0;
     while (strstr(substr($msg, $lengthfirst), "<$tag ") != "") {
          $tag_start = $lengthfirst + strpos(substr($msg, $lengthfirst), "<$tag ");

          $partafterwith = substr($msg, $tag_start);

          $img = substr($partafterwith, 0, strpos($partafterwith, ">") + 1);
          $img = str_replace(" =", "=", $img);

          $out = "<$tag";
          for($i = 0; $i < count($attr); $i++) {
               if (empty($attr[$i])) {
                    continue;
               }
               $long_val =
               (strpos($img, " ", strpos($img, $attr[$i] . "=")) === false) ?
               strpos($img, ">", strpos($img, $attr[$i] . "=")) - (strpos($img, $attr[$i] . "=") + strlen($attr[$i]) + 1) :
               strpos($img, " ", strpos($img, $attr[$i] . "=")) - (strpos($img, $attr[$i] . "=") + strlen($attr[$i]) + 1);
               $val = substr($img, strpos($img, $attr[$i] . "=") + strlen($attr[$i]) + 1, $long_val);
               if (!empty($val)) {
                    $out .= " " . $attr[$i] . "=" . $val;
               }
          }
          if (!empty($suffix)) {
               $out .= " " . $suffix;
          }

          $out .= ">";
          $partafter = substr($partafterwith, strpos($partafterwith, ">") + 1);
          $msg = substr($msg, 0, $tag_start) . $out . $partafter;
          $lengthfirst = $tag_start + 3;
     }
     return $msg;
     }


//----------------------------------------------------------------------------------------
public static function cleanText($str){

    $str = str_replace("Ñ" ,"&#209;", $str);
    //$str =  preg_replace('/Ñ/g',"|&#209;|", $str);
    //echo "Text BEGIN ".$str."  --- ".bin2hex ("Ñ")."\n<BR>";     // d1
    /*
    for($i = 0 ; $i < strlen($str) ; $i++){
    echo "".$str{$i}."  - ". bin2hex ( $str{$i})."<BR>";
    }
    */
    $str = str_replace("�" ,"", $str);
    $str = str_replace("ñ" ," ", $str);
    $str = str_replace("ñ" ," ", $str);
    $str = str_replace("Á"," ", $str);
    $str = str_replace("á"," ", $str);
    $str = str_replace("É"," ", $str);
    $str = str_replace("é"," ", $str);

    $str = str_replace("ú"," ", $str);

    $str = str_replace("ù"," ", $str);
    $str = str_replace("Í"," ", $str);
    $str = str_replace("í"," ", $str);
    $str = str_replace("Ó"," ", $str);
    $str = str_replace("ó"," ", $str);
    $str = str_replace("“"," ", $str);

    $str = str_replace("”"," ", $str);

    $str = str_replace("‘"," ", $str);
    $str = str_replace("’"," ", $str);
    $str = str_replace("—"," ", $str);

    $str = str_replace("–"," ", $str);
    $str = str_replace("™"," ", $str);
    $str = str_replace("ü"," ", $str);
    $str = str_replace("Ü"," ", $str);
    $str = str_replace("Ê"," ", $str);
    $str = str_replace("ê"," ", $str);
    $str = str_replace("Ç"," ", $str);
    $str = str_replace("ç"," ", $str);
    $str = str_replace("È"," ", $str);
    $str = str_replace("è"," ", $str);
    $str = str_replace("•"," " , $str);
    $str = str_replace("Ã¼"," " , $str);
    $str = str_replace("â€¦"," " , $str);
    $str = str_replace("ü"," " , $str);
    $str = str_replace("â€¢"," " , $str);
    $str = str_replace('â€™','\'' , $str);
    $str = str_replace('â€¦','...' , $str);
    $str = str_replace('â€“','-', $str);
    $str = str_replace('â€œ','"', $str);
    $str = str_replace('â€˜','\'', $str);
    $str = str_replace('â€¢','-', $str);
    $str = str_replace('â€¡','c', $str);
    $str = preg_replace('!\s+!', ' ', $str);
    $str = htmlspecialchars($str, ENT_QUOTES, "UTF-8");

    $str = iconv("UTF-8", "ISO-8859-1", $str);

    $str = utf8_decode($str);
    $str = str_replace("?", " ", $str);


    return $str;

    }
//----------------------------------------------------------------------------------------

function remove_line_breaks($output)
{
$output = str_replace(array("\r\n", "\r"), "\n", $output);
$lines = explode("\n", $output);
$new_lines = array();

foreach ($lines as $i => $line) {
    if(!empty($line))
        $new_lines[] = trim($line);
}
return implode($new_lines);
}

//----------------------------------------------------------------------------------------

function find_email($content)
{


    $pattern = '/[A-Za-z0-9._-]+@[A-Za-z0-9_-]+\.([A-Za-z0-9_-][A-Za-z0-9_]+)/';
    //$pattern = '~[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[A-Z]{2,4}~';


    preg_match_all($pattern,$content,$matches);

    if(empty($matches[0]))
    {
        return false;
    }

    $matches[0] = array_unique($matches[0]);

    return $matches[0];


}

//----------------------------------------------------------------------------------------

function find_phone($content)
{

    //$content = str_replace(".", "", $content);


preg_match_all('/[0-9]{3}[\-][0-9]{6}|[0-9]{3}[\s][0-9]{6}|[0-9]{3}[\s][0-9]{3}[\s][0-9]{4}|[0-9]{9}|[0-9]{3}[\-][0-9]{3}[\-][0-9]{4}/', $content, $matches);
$matches = $matches[0];

return  $matches;
}

//----------------------------------------------------------------------------------------

    //generate comma string from array
    function array_comma_string($array)
    {
        $count = count($array);

        $html = "";
        $i = 1;
        if(is_array($array))
        {
            foreach($array as $row)
            {
                if($i != $count)
                {
                    $row = trim($row);
                    $html .= $row.", ";
                } else if($i == $count)
                    {
                        $html .= $row;
                    }

                    $i++;
            }
        } else
        {
            $html = $array;
        }

        return $html;
    }


//----------------------------------------------------------------------------------------

    function find_domains($str)
    {
        $pattern = '#[a-zA-Z0-9]{2,254}\.[a-zA-Z0-9]{2,4}(\S*)#i';
        preg_match_all($pattern, $str, $matches, PREG_PATTERN_ORDER);

        $result = array();

        foreach($matches[0] as $domain)
        {

            $domain =str_replace('whois', '', $domain);
            $result[] = $domain;
        }

        return $result;


    }

//----------------------------------------------------------------------------------------

    function getwhois($domain, $tld)
    {
        $ci =& get_instance();

        $ci->load->library('whois');


        if( !$ci->whois->ValidDomain($domain.'.'.$tld) ){
            return 'Sorry, the domain is not valid or not supported.';
        }

        if( $ci->whois->Lookup($domain.'.'.$tld) )
        {
            return $ci->whois->GetData(1);
        }else{
            return 'Sorry, an error occurred.';
        }
    }

//----------------------------------------------------------------------------------------

    function find_country($whois)
    {


$countries = array(
    'Andorra',
'United Arab Emirates',
'Afghanistan',
'Antigua &amp; Barbuda',
'Anguilla',
'Albania',
'Armenia',
'Netherlands Antilles',
'Angola',
'Antarctica',
'Argentina',
'American Samoa',
'Austria',
'Australia',
'Aruba',
'Azerbaijan',
'Bosnia and Herzegovina',
'Barbados',
'Bangladesh',
'Belgium',
'Burkina Faso',
'Bulgaria',
'Bahrain',
'Burundi',
'Benin',
'Bermuda',
'Brunei Darussalam',
'Bolivia',
'Brazil',
'Bahama',
'Bhutan',
'Burma (no longer exists)',
'Bouvet Island',
'Botswana',
'Belarus',
'Belize',
'Canada',
'Cocos (Keeling) Islands',
'Central African Republic',
'Congo',
'Switzerland',
'Côte D\'ivoire (Ivory Coast)',
'Cook Iislands',
'Chile',
'Cameroon',
'China',
'Colombia',
'Costa Rica',
'Czechoslovakia (no longer exists)',
'Cuba',
'Cape Verde',
'Christmas Island',
'Cyprus',
'Czech Republic',
'German Democratic Republic (no longer exists)',
'Germany',
'Djibouti',
'Denmark',
'Dominica',
'Dominican Republic',
'Algeria',
'Ecuador',
'Estonia',
'Egypt',
'Western Sahara',
'Eritrea',
'Spain',
'Ethiopia',
'Finland',
'Fiji',
'Falkland Islands (Malvinas)',
'Micronesia',
'Faroe Islands',
'France',
'France, Metropolitan',
'Gabon',
'United Kingdom (Great Britain)',
'Grenada',
'Georgia',
'French Guiana',
'Ghana',
'Gibraltar',
'Greenland',
'Gambia',
'Guinea',
'Guadeloupe',
'Equatorial Guinea',
'Greece',
'South Georgia and the South Sandwich Islands',
'Guatemala',
'Guam',
'Guinea-Bissau',
'Guyana',
'Hong Kong',
'Heard &amp; McDonald Islands',
'Honduras',
'Croatia',
'Haiti',
'Hungary',
'Indonesia',
'Ireland',
'Israel',
'India',
'British Indian Ocean Territory',
'Iraq',
'Islamic Republic of Iran',
'Iceland',
'Italy',
'Jamaica',
'Jordan',
'Japan',
'Kenya',
'Kyrgyzstan',
'Cambodia',
'Kiribati',
'Comoros',
'St. Kitts and Nevis',
'Korea, Democratic People\'s Republic of',
'Korea, Republic of',
'Kuwait',
'Cayman Islands',
'Kazakhstan',
'Lao People\'s Democratic Republic',
'Lebanon',
'Saint Lucia',
'Liechtenstein',
'Sri Lanka',
'Liberia',
'Lesotho',
'Lithuania',
'Luxembourg',
'Latvia',
'Libyan Arab Jamahiriya',
'Morocco',
'Monaco',
'Moldova, Republic of',
'Madagascar',
'Marshall Islands',
'Mali',
'Mongolia',
'Myanmar',
'Macau',
'Northern Mariana Islands',
'Martinique',
'Mauritania',
'Monserrat',
'Malta',
'Mauritius',
'Maldives',
'Malawi',
'Mexico',
'Malaysia',
'Mozambique',
'Namibia',
'New Caledonia',
'Niger',
'Norfolk Island',
'Nigeria',
'Nicaragua',
'Netherlands',
'Norway',
'Nepal',
'Nauru',
'Neutral Zone (no longer exists)',
'Niue',
'New Zealand',
'Oman',
'Panama',
'Peru',
'French Polynesia',
'Papua New Guinea',
'Philippines',
'Pakistan',
'Poland',
'St. Pierre &amp; Miquelon',
'Pitcairn',
'Puerto Rico',
'Portugal',
'Palau',
'Paraguay',
'Qatar',
'Réunion',
'Romania',
'Russian Federation',
'Rwanda',
'Saudi Arabia',
'Solomon Islands',
'Seychelles',
'Sudan',
'Sweden',
'Singapore',
'St. Helena',
'Slovenia',
'Svalbard &amp; Jan Mayen Islands',
'Slovakia',
'Sierra Leone',
'San Marino',
'Senegal',
'Somalia',
'Suriname',
'Sao Tome &amp; Principe',
'Union of Soviet Socialist Republics (no longer exists)',
'El Salvador',
'Syrian Arab Republic',
'Swaziland',
'Turks &amp; Caicos Islands',
'Chad',
'French Southern Territories',
'Togo',
'Thailand',
'Tajikistan',
'Tokelau',
'Turkmenistan',
'Tunisia',
'Tonga',
'East Timor',
'Turkey',
'Trinidad &amp; Tobago',
'Tuvalu',
'Taiwan, Province of China',
'Tanzania, United Republic of',
'Ukraine',
'Uganda',
'United States Minor Outlying Islands',
'United States of America',
'United States',
'USA',
'UK',
'UAE',
'Uruguay',
'Uzbekistan',
'Vatican City State (Holy See)',
'St. Vincent &amp; the Grenadines',
'Venezuela',
'British Virgin Islands',
'United States Virgin Islands',
'Viet Nam',
'Vanuatu',
'Wallis &amp; Futuna Islands',
'Samoa',
'Democratic Yemen (no longer exists)',
'Yemen',
'Mayotte',
'Yugoslavia',
'South Africa',
'Zambia',
'Zaire',
'Zimbabwe',
'Korea',

);

        $found = "";

        foreach($countries as $key => $value)
        {

            if(strpos($whois, $value) !== false)
            {
                $found = $value;
            }

        }

        return $found;

    }

//----------------------------------------------------------------------------------------


}// end of class
