<?php

//https://en.wikipedia.org/wiki/Block_cipher_mode_of_operation

function Encrpt($txt,$initial_vetor) {
    $MSG = "";
    $size = strlen($txt);
    $block_Size = 5;
    $shift = 1;
    $A = 0;
    $number_of_blocks = floor($size/$block_Size);
    $rem_of_blocks = $size%$block_Size;

//    echo "$size \n";
//    echo  "\nNumber_of_blocks : $number_of_blocks \n";
//    echo  "Rem :+$rem_of_blocks \n\n";

//    padding -------------------------------------------------
    for ($x = 0; $x < ($block_Size - $rem_of_blocks); $x++) {
        $txt = $txt . 'x';
    }

//    echo "$txt\n";
//    $size = strlen($txt);
//    echo "$size \n";

//    devide into blocks--------------------------------------
    $block_plain_text = str_split($txt, $block_Size);
    if ($rem_of_blocks > 0) $A = 1;

//    for ($x = 0; $x < $number_of_blocks+$A; $x++) {
//        echo  "$block_plain_text[$x]\n";
//    }

//    XOR with intial vector---------------------------------
    $Temp_vector = $initial_vetor;
    $cipher = "";

//    CBC----------------------------------------------------
    for ($x = 0; $x < $number_of_blocks + $A; $x++) {
//        echo "$Temp_vector --- $block_plain_text[$x] \n";
        $cbc = base64_encode(xorIt($block_plain_text[$x],$Temp_vector));  // xor with each blocks.
//        echo "$cbc \n";
        $cipher = shift($cbc,$shift);
        $Temp_vector = $cipher ;
//        echo "$cipher\n";
//        echo "$cipher";
        $MSG .= $cipher;
        $cbc ="";
    }
    return $MSG;
}

function Decrypt($txt,$initial_vetor) {
    $MSG = "";
    $size = strlen($txt);
    $block_Size = 8;
    $shift = 1;
    $A = 0;
    $number_of_blocks = floor($size/$block_Size);
    $block_plain_text = str_split($txt, $block_Size);
    $Temp_vector = $initial_vetor;

    for ($x = 0; $x < $number_of_blocks; $x++) {
        $dcrypt_txt = shift($block_plain_text[$x],-$shift);
//        echo "$dcrypt_txt\n";
//        $cbc = base64_encode(xorIt($dcrypt_txt,$Temp_vector));
        $cbc = xorIt(base64_decode($dcrypt_txt), $Temp_vector, 1);
        $Temp_vector = $block_plain_text[$x];
        $cbc = str_replace(array('x'), '',$cbc);
        $MSG .= $cbc;
//        echo "$cbc";
    }
    return $MSG;
}


function xorIt($string, $key, $type = 0)
{
    $sLength = strlen($string);
    $xLength = strlen($key);
    for($i = 0; $i < $sLength; $i++) {
        for($j = 0; $j < $xLength; $j++) {
            if ($type == 1) {
                $string[$i] = $key[$j]^$string[$i];
            } else {
                $string[$i] = $string[$i]^$key[$j];
            }
        }
    }
    return $string;
}

function shift($fname,$shift) {
    $size = strlen($fname);
    for ($x = 0; $x < $size; $x++) {
        $t = ord($fname[$x]) + $shift;
        $fname[$x] = chr($t);
    }
    return $fname;
}

$txt = "Amila indrajith ukwattage";
$initial_vetor  = "Amila";

$XX = Encrpt($txt,$initial_vetor); // call the function
echo "$XX\n";
$YY =  Decrypt($XX,$initial_vetor);
echo "$YY\n";

//------------------------------------------------------------
//$xor_key = '11111';
//$string = 'amila';
//$signal = base64_encode(xorIt($string, $xor_key));
//echo $signal . PHP_EOL;
//
//$string = xorIt(base64_decode($signal), $xor_key, 1);
//echo $string . PHP_EOL;
//------------------------------------------------------------

?>
