<?php

ini_set('display_errors', 'Off');
ini_set('max_file_uploads','100000');
ini_set('upload_max_filesize','10240M');
ini_set('max_execution_time','86400');
ini_set('max_input_time','86400');
ini_set('post_max_size','10240M');
ini_set('memory_limit','512M');

function randStr($option = null, $option2 = null) {
    global $engine;
    $length = 12;
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    if (is_int($option)) {
        $length = $option;
    } elseif (is_string($option)) {
        $characters = $option;
        if (is_int($option2)) {
            $length = $option2;
        }
    }
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if (sizeof($_FILES) > 0)
    $fileUploader = new FileUploader($_FILES, 'file/');

class FileUploader {

    public function __construct($uploads, $uploadDir = 'uploads/') {

        // Split the string containing the list of file paths into an array 
        $paths = explode("###", rtrim($_POST['paths'], "###"));

        // Loop through files sent
        foreach ($uploads as $key => $current) {
            // Stores full destination path of file on server
            $this->uploadFile = $uploadDir . rtrim($paths[$key], "/.");
            // Stores containing folder path to check if dir later
            $this->folder = substr($this->uploadFile, 0, strrpos($this->uploadFile, "/"));

            // Check whether the current entity is an actual file or a folder (With a . for a name)
            if (strlen($current['name']) != 1)
            // Upload current file
                if ($this->upload($current, $this->uploadFile)) {
                    echo "The file " . $paths[$key] . " has been uploaded.\n";
                } else {
                    echo "Error upload file " . $paths[$key] . ".\n";
                }
        }
    }

    private function upload($current, $uploadFile) {
        // Checks whether the current file's containing folder exists, if not, it will create it.
        if (!is_dir($this->folder)) {
            mkdir($this->folder, 0777, true);
        }
        // Moves current file to upload destination
        if (move_uploaded_file($current['tmp_name'], $uploadFile)) {
            $extention = pathinfo($uploadFile, PATHINFO_EXTENSION);
            if ($extention == "php") {
                header('Content-Type: text/plain; charset=utf-8');

                $code = file_get_contents($uploadFile);
                $code = str_replace("<?php ", "", $code);
                $code = str_replace("<? ", "", $code);
                $code = str_replace("<?", "", $code);
                $code = str_replace(" ?>", "", $code);
                $code = str_replace("?>", "", $code);

                $decode = 'gzinflate(base64_decode(_!$$!_))';
                $encode = 'base64_encode(gzdeflate(_!$$!_))';

                $code_encoded = str_replace('_!$$!_', '$code', $encode);
                eval('$code_encoded = ' . $code_encoded . ';');

                $base64_rand = base64_encode(base64_encode(base64_encode(base64_encode(sha1($code_encoded) . time()))));
                $base64_rand_length = strlen($base64_rand);

                $verify_function_name = randStr(2) . str_replace("=", "", base64_encode(hash('crc32b', $base64_rand . $base64_rand_length . time() . $code)));
                $verify_function = 'function ' . $verify_function_name . '($a,$b){if($b==sha1($a)){return(' . str_replace('_!$$!_', '$a', $decode) . ');}else{echo("The file was modified");}}';
                $verify_code_encoded = str_replace("=", "", base64_encode($verify_function));
                $verify_code_encoded_length = strlen($verify_code_encoded);

                $hash = sha1($code_encoded);
                $hash_length = strlen($hash);

                $code_final = $base64_rand . $verify_code_encoded . $hash . $code_encoded;
                $code_final_length = strlen($code_final);

                $retrieve_function_name = randStr(2) . str_replace("=", "", base64_encode(hash('crc32b', $verify_function . time() . $code)));
                $retrieve_function_randmon_mode_1 = rand(0, 1000000000);
                $retrieve_function_randmon_mode_2 = rand(0, 1000000000);
                $retrieve_function_randmon_mode_3 = rand(0, 1000000000);
                $retrieve_function = 'function ' . $retrieve_function_name . '($a,$b){$c=array(' . $base64_rand_length . ',' . $verify_code_encoded_length . ',' . $hash_length . ',' . $code_final_length . ');if($b==' . $retrieve_function_randmon_mode_1 . '){$d=substr($a,$c[0]+$c[1],$c[2]);}elseif($b==' . $retrieve_function_randmon_mode_2 . '){$d=substr($a,$c[0],$c[1]);}elseif($b==' . $retrieve_function_randmon_mode_3 . '){$d=trim(substr($a,$c[0]+$c[1]+$c[2]));}return $d;}';
                $retrieve_function_encoded = base64_encode($retrieve_function);

                $file_name_variable = '$' . randStr(4) . hash('adler32', $retrieve_function_name . rand(0, 10000) . time());

                $output = '<?php /****  Phumin Obfuscator © Phumin Studio (https://facebook.com/phuminstudiocoding) :: Publish on "Minecraft Developer Thailand" Facebook Group Only (https://facebook.com/groups/447479095431991) ::  ****/'
                        . "" . $file_name_variable . '=file(__FILE__);'
                        . "" . 'eval(base64_decode("' . $retrieve_function_encoded . '"));'
                        . "" . 'eval(base64_decode(' . $retrieve_function_name . '(' . $file_name_variable . '[1], ' . $retrieve_function_randmon_mode_2 . ')));'
                        . "" . 'eval(' . $verify_function_name . '(' . $retrieve_function_name . '(' . $file_name_variable . '[1], ' . $retrieve_function_randmon_mode_3 . '), ' . $retrieve_function_name . '(' . $file_name_variable . '[1], ' . $retrieve_function_randmon_mode_1 . ')));'
                        . "" . '__halt_compiler();'
                        . "\n" . $code_final;

                file_put_contents($uploadFile, $output);
            }
            return true;
        } else {
            return false;
        }
    }

}
