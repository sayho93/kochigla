<?
//    $template_file_name = 'template.docx';
//
//    $rand_no = rand(111111, 999999);
//    $fileName = "results" . $rand_no . ".docx";
//
//    $folder   = "results";
//    $full_path = $folder . '/' . $fileName;
//
//    try
//    {
//        if (!file_exists($folder))
//        {
//            mkdir($folder);
//        }
//
//        //Copy the Template file to the Result Directory
//        copy($template_file_name, $full_path);
//
//        // add calss Zip Archive
//        $zip_val = new ZipArchive;
//
//        //Docx file is nothing but a zip file. Open this Zip File
//        if($zip_val->open($full_path) == true)
//        {
//            // In the Open XML Wordprocessing format content is stored.
//            // In the document.xml file located in the word directory.
//
//            $key_file_name = 'word/document.xml';
//            $message = $zip_val->getFromName($key_file_name);
//
//            $timestamp = date('d-M-Y H:i:s');
//
//            $message = str_replace("#{date}", $timestamp, $message);
//            echo $message . "<br/>";
//            $message = str_replace("#{client}", "(주) 앱플러", $message);
//            echo $message . "<br/>";
//            $message = str_replace("#{partner}", "피클코드", $message);
//            echo $message . "<br/>";
//            $message = str_replace("#{price}", "30,000,000", $message);
//            echo $message . "<br/>";
//
//            // this data Replace the placeholders with actual values
////            $message = str_replace("no_1", "2014112102", $message);
////            echo $message . "<br/>";
////            $message = str_replace("no_2", "2014112021", $message);
////            echo $message . "<br/>";
////            $message = str_replace("no_3", "2014111111", $message);
////            echo $message . "<br/>";
////
////            $message = str_replace("name_1", "전세호", $message);
////            echo $message . "<br/>";
////            $message = str_replace("name_2", "함의진", $message);
////            echo $message . "<br/>";
////            $message = str_replace("name_3", "이도현", $message);
////            echo $message . "<br/>";
////
////            $message = str_replace("date", $timestamp, $message);
////            echo $message . "<br/>";
////            $message = str_replace("time", $timestamp, $message);
////            echo $message . "<br/>";
//
//            //Replace the content with the new content created above.
//            $zip_val->addFromString($key_file_name, $message);
//            $zip_val->close();
//        }
//    }
//    catch (Exception $exc)
//    {
//        $error_message =  "Error creating the Word Document";
//        var_dump($exc);
//    }
//














    $file="template.docx";

    // check valid .docx file
    if(pathinfo($file,PATHINFO_EXTENSION) !== "docx"){
        die("File is not a valid docx file");
    }

    $rand_no = rand(111111, 999999);
    $fileName = "results" . $rand_no . ".docx";

    $folder   = "results";
    $full_path = $folder . '/' . $fileName;

    if (!file_exists($folder)){
        mkdir($folder);
    }

    //Copy the Template file to the Result Directory
    copy($file, $full_path);

    // Create reference variable/object of php zip class
    $zip=new ZipArchive();

    //opening docx file
    if($zip->open($full_path) == FALSE){
        die("Unable to open file");
    }
    // accessing the document.xml file and its content from subdirectory in the archived file
    $xml_content = $zip->getFromName('word/document.xml');

    // modifying the keyword(s) in docx file which could be split in internal xml tags
    preg_match_all("/\[(.*?)\]/",$xml_content,$match);

    // here we can change opening/closing [ ] with {},() or other unique symbol which could be well paired
    if(isset($match[0])){

        foreach($match[0] as $keyword) {
            $new_word[] = strip_tags($keyword);
        }

        $xml_content = str_replace($match[0],$new_word,$xml_content);
    }else {
        die("Unable to modify keyword");
    }

    //file could have multiple words,so grouping keywords in array form

    $timestamp = date('d-M-Y H:i:s');
    // our key word and [] could be replaced with other symbol but should be same as search symbol
    $keywords=array(
        "[price]",
        "[client]",
        "[partner]",
        "[date]"
    );
    // replacement data
    $replacement=array(
        "30,000,000",
        "(주)앱플러",
        "피클코드",
        $timestamp
    );

    // replacing data with keywords
    $xml_content = str_replace($keywords,$replacement,$xml_content);

    // write data back to main docx file
    if($zip->addFromString('word/document.xml', $xml_content) !== FALSE){
        echo "File written successfully";
    }else {
        echo "Unable to write file";
    }
    $zip->close();
