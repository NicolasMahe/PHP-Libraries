<?php

class Storage
{
    public static function write($filePath, $data, $jsonEncode=false)
    {
        if($jsonEncode) {
            $data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        
        try {
            $isInFolder = preg_match("/^(.*)\/([^\/]+)$/", $filePath, $filepathMatches);
            if($isInFolder) {
                $folderName = $filepathMatches[1];
                $fileName = $filepathMatches[2];
                if (!is_dir($folderName)) {
                    mkdir($folderName, 0777, true);
                }
            }
            file_put_contents($filePath, $data);
        } catch (Exception $e) {
            Error::add('Storage write to file "'.$filePath.'" failed');
            return false;
        }
        
        return true;
    }

    public static function read($filePath, $jsonDecode=false)
    {
        $fileExists = file_exists($filePath);

        if($fileExists) {
            $content = file_get_contents($filePath);

            if($content === FALSE) {
                Error::add('Storage failed to read file "'.$filePath.'"');
            } else {
                if($jsonDecode) {
                    $data = json_decode($content, true);
                    
                    if($data === false) {
                        Error::add('Error during decoding json from file "'.$filePath.'"');
                    } else {
                        return $data;
                    }
                } else {
                    return $content;
                }
            }
        } else {
            Error::add('Storage file "'.$filePath.'" do not exist');
        }

        return;
    }
}
