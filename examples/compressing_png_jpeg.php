<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
function debugHere($data)
{
    echo "<pre>" . print_r($data, 1) . "</pre>";
}
function resize_photo($filesize, $type, $tmp_name)
{
    $size = 648576;
    if ($filesize > $size) {
        debugHere($filesize);
        debugHere($tmp_name);
        switch ($type) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($tmp_name);
                imagejpeg($source, $tmp_name, 50);
                echo "\$source " . $source;
                imagedestroy($source);
                break;
            case 'image/png':
                $source = imagecreatefrompng($tmp_name);
                imagepng($source, $tmp_name, 9);
                echo "\$source " . $source;
                imagedestroy($source);
                break;
            default:
                return false;
        }
        return true;
    }
}
// $fileId = 252682;
// $arFile = CFile::GetFileArray(intval($fileId));
// debugHere($arFile);

// $resFile = file_get_contents(__DIR__ . "/upload/iblock/28a/l48zhvxcjueqvxf7730q11ydt02iwtoo.png");
// debugHere($resFile);
// die();
$i = 0;
if (false) {
    //найдем самые большие файлы ядра
    debugHere(__DIR__);
    $res = CFile::GetList(array("FILE_SIZE" => "desc"), array("MODULE_ID" => "iblock", 'ID' => 252960));
    while ($res_arr = $res->GetNext()) {
        if ($i > 0) {
            break;
        }
        debugHere($res_arr);
        $result =  resize_photo(intval($res_arr["FILE_SIZE"]), $res_arr["CONTENT_TYPE"], __DIR__ .  "/upload/" . $res_arr["SUBDIR"] . "/" . $res_arr["FILE_NAME"]);
        debugHere("\$result:" . $result);
        $i++;
    }
}
if (false) {
    // Image
    $dir = 'upload/iblock/28a/';
    $name = 'l48zhvxcjueqvxf7730q11ydt02iwtoo.png';
    $newName = 'new.webp';

    // Create and save
    $img = imagecreatefrompng($dir . $name);
    imagepalettetotruecolor($img);
    imagealphablending($img, true);
    imagesavealpha($img, true);
    imagewebp($img, $dir . $newName, 100);
    imagedestroy($img);
}

debugHere("total :" . $i);