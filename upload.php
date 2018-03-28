<?php

class FileUpload
{
    const UPLOAD_DIR = __DIR__ . '';
    protected $disableExtensions = array("php", "exe");

    protected $relativeDir = "/uploads/files/";

    public function __construct()
    {

    }

    public function uploadFile($phpUploadFile)
    {
        if (in_array(pathinfo($phpUploadFile["name"], PATHINFO_EXTENSION), $this->disableExtensions)){
            throw new Exception($phpUploadFile["name"] . " is disable extension");
        }
        if ($phpUploadFile["error"] > 0) {
            throw new Exception("Return Code: " . $phpUploadFile["error"]);
        }

        $newPath = self::UPLOAD_DIR . $this->relativeDir . $phpUploadFile["name"];

        if (!is_dir(self::UPLOAD_DIR . $this->relativeDir)){
            if (!mkdir(self::UPLOAD_DIR . $this->relativeDir, 0777, true)){
                throw new Exception(self::UPLOAD_DIR . $this->relativeDir . " mkdir failure. ");
            }
        }
        if (!is_writeable(self::UPLOAD_DIR . $this->relativeDir)) {
            throw new Exception(self::UPLOAD_DIR . $this->relativeDir . " can't writeable. ");
        }

        if (file_exists($newPath)) {
            throw new Exception($newPath . " already exists. ");
        }

        if (!move_uploaded_file($phpUploadFile["tmp_name"], $newPath)) {
            throw new Exception($newPath . " move_uploaded_file failure. ");
        }

        $phpUploadFile['new_path'] = $this->relativeDir . $phpUploadFile["name"];

        return $phpUploadFile;
    }
}

$uploader = new FileUpload();
if (strpos($_SERVER['HTTP_USER_AGENT'],'curl') !== false || strpos($_SERVER['HTTP_USER_AGENT'],'wget') !== false){
    define('BR_CHAR', "\n");
}else{
    define('BR_CHAR', "<br />");
}


foreach ($_FILES as $_file) {
    $file = null;
    try {
        $file = $uploader->uploadFile($_file);
    } catch (Exception $e) {
        echo "Stored Exception: " . $e->getMessage() . BR_CHAR;
    }
    if (!empty($file)) {
        echo "Upload: " . $file["name"] . BR_CHAR;
        echo "Type: " . $file["type"] . BR_CHAR;
        echo "Size: " . ($file["size"] / 1024) . " Kb" . BR_CHAR;
        echo "Stored success !" . BR_CHAR;
        echo "Access url: " . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $file['new_path'] . BR_CHAR.BR_CHAR;
    }
}