<?php

define("BASE_URL", "http://drmj.eu/");

function _mysqli_query($sql) {

    $result = array();

    $conn = mysqli_connect('194.61.136.10', 'sunwireless', 'sun2wireless@DB', 'libmak');
    mysqli_set_charset( $conn, 'utf8');
    if(!$conn) {
        die("Connection failed" . mysqli_connect_error());
    }
    $res = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($res)) {
        $result[] = $row;
    }

    mysqli_close($conn);

    return $result;

}

function _mysqli_modify($sql) {

    $success = false;

    $conn = mysqli_connect('dev.scnct.io', 'sunwireless', 'sun2wireless@DB', 'libmak');
    mysqli_set_charset( $conn, 'utf8');
    if(!$conn) {
        die("Connection failed" . mysqli_connect_error());
    }
    $res = mysqli_query($conn, $sql);
    if ($res) {
        $success = true;
    }

    mysqli_close($conn);

    return $success;

}

function do_curl($url) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $html=curl_exec($ch);

    return $html;

}

function get_letters() {
    $html = do_curl(BASE_URL);

    $re = '/<a href="\/letter\/(.*?)" >(.*?)<\/a>/m';
    preg_match_all($re, $html, $matches, PREG_SET_ORDER, 0);

    $values_sql = "";
    $count = 0;
    foreach($matches as $url) {
        $dom = new DOMDocument;
        $dom->loadHTML($url[0]);
        foreach ($dom->getElementsByTagName('a') as $node) {
            $link = $node->getAttribute( 'href' );
        }
        $letter = $url[1];

        if ( $count > 0 ) {
            $values_sql .= ", ";
        }
        $values_sql .= "('$letter', '$link')";

        $count++;
    }

    $sql = "REPLACE INTO `scrape_letter` (`letter`, `url`) VALUES " . $values_sql;
    $res = _mysqli_modify($sql);
    if ($res) {
        echo "Processed $count letters\n";
    } else {
        echo "Failed to process sql:\n$sql\n";
    }
}

function get_ranges($letter) {
    $html = do_curl(BASE_URL . "letter/" . $letter);

    // word options from dropdown menu
    $wordOptions = array();
    $re = '/<option value="\/letter\/(.*?)\/(.*?)" >/m';
    preg_match_all($re, $html, $matches, PREG_SET_ORDER, 0);

    $values_sql = "";
    $count = 0;
    foreach($matches as $match){
        $url = BASE_URL . "letter/" . $letter . "/" . $match[2];

        if ( $count > 0 ) {
            $values_sql .= ", ";
        }
        $values_sql .= "('$letter', '$url')";

        $count++;
    }

    $sql = "REPLACE INTO `scrape_word_ranges` (`letter`, `range`) VALUES " . $values_sql;
    $res = _mysqli_modify($sql);
    if ($res) {
        echo "Processed: $count urls\n";
    } else {
        echo "Failed to process sql:\n$sql\n";
    }
}

function get_words_in_range($ranges_to_process) {

    $sql = "SELECT `letter`, `range` FROM `scrape_word_ranges` WHERE `processed` != 'Y' LIMIT " . $ranges_to_process;
    $res = _mysqli_query($sql);
    foreach($res as $item) {
        $letter = $item['letter'];
        $range_url = $item['range'];

        $html = do_curl($range_url);

        $re = '/show\/(.*?)\/(.*?)\/(.*?)\?/m';
        preg_match_all($re, $html, $matches, PREG_SET_ORDER, 0);

        $values_sql = "";
        $count = 0;
        foreach($matches as $match){
            $url = BASE_URL . $match[0];

            if ( $count > 0 ) {
                $values_sql .= ", ";
            }
            $values_sql .= "('$letter', '$range_url', '$url')";

            $count++;
        }

        $sql = "REPLACE INTO `scrape_words` (`letter`, `range`, `url`) VALUES " . $values_sql;
        $res = _mysqli_modify($sql);
        if ($res) {
            echo "Processed: $count urls\n";
            $sql = "UPDATE `scrape_word_ranges` SET `processed` = 'Y' WHERE `range` = '$range_url' ";
            $res = _mysqli_modify($sql);
        } else {
            echo "Failed to process sql:\n$sql\n";
        }
    }

}

function get_word_definitions($word, $words_to_process) {

    if ( $word ) {
        $sql = "SELECT `letter`, `url` FROM `scrape_words` WHERE `title` = '$word' ";
    } else {
        $sql = "SELECT `letter`, `url` FROM `scrape_words` WHERE `processed` != 'Y' LIMIT " . $words_to_process;
    }
    $res = _mysqli_query($sql);
    foreach($res as $item) {
        $letter = $item['letter'];
        $word_url = $item['url'];

        echo "Processing: $word_url\n";

        $result = do_curl($word_url);

        $content = addslashes($result);

        // content encoded and with slashes to add to DB
        /*
        $re = '/<div class="span-13" id="main_content" >(?s)(.*)<div class="span-6 last" id="right_bar" >/m';
        preg_match_all($re, $result, $matches, PREG_SET_ORDER, 0);
        $json = json_encode($matches);
        //$content = addslashes($json);
        */

        //title
        $re1 = '/<h2 class="lexem" name="(.*?)">/m';
        preg_match_all($re1, $result, $matches1, PREG_SET_ORDER, 0);
        foreach($matches1 as $match){
            $title = $match[1];
        }
        // print_r($title . "\n");

        // flexion
        $re11 = '/<div class="flexion" >([^;]*)<\/div>/m';
        preg_match_all($re11, $result, $matches11, PREG_SET_ORDER, 0);
        foreach ($matches11 as $match) {
            $flexion = trim($match[1]);
        }
        $flexion = ltrim($flexion, "<i>");
        $flexion = rtrim($flexion, "</i>");
        $flexion = trim($flexion);
        // print_r($flexion . "\n");

        // grammar
        $re12 = '/Вид збор: <i>(.*?)<\/i>/m';
        preg_match_all($re12, $result, $matches12, PREG_SET_ORDER, 0);
        foreach ($matches12 as $match) {
            $grammar = trim($match[1]);
        }
        // print_r($grammar . "\n");

        // rank
        $re4 = '/Ранг: (.*?)\n/m';
        preg_match_all($re4, $result, $matches4, PREG_SET_ORDER, 0);
        foreach ($matches4 as $match) {
            $rank = $match[1];
        }
        // print_r($rank . "\n");

        // meaning
        $mean = array();
        $re5 = '/<div class="meaning" >[\n\r\s]+(.*?)[\n\r\s]+(.*?)/mU';
        preg_match_all($re5, $result, $matches5, PREG_SET_ORDER, 0);
        foreach ($matches5 as $match) {
            $mean[] = trim(end($match));
        }
        $definition = implode(",", $mean);
        // print_r($meaning);

        // translate en
        $en = array();
        $re6 = '/<a href="\/ontology\/(.*?)\/(.*)/im';
        preg_match_all($re6, $result, $matches6, PREG_SET_ORDER, 0);
        foreach ($matches6 as $match) {
            $en[] = $match[1];
        }
        $ens = implode(",", $en);
        // print_r("en: " . $ens . "\n");

        // translate tr/al
        $tr = array();
        $re7 = '/<a >(.*?)<\/a>/m';
        preg_match_all($re7, $result, $matches7, PREG_SET_ORDER, 0);
        foreach ($matches7 as $match) {
            $tr[] = $match[1];
        }
        $trs = implode(",", $tr);
        // print_r("tr: " . $trs . "\n");

        // Употреба
        $upotreba = array();
        $re8 = '/<span class="tag" >[\n\r\s]+<a href="\/(.*?)\/(.*?)\/(.*?)" >(.*?)<\/a>/m';
        preg_match_all($re8, $result, $matches8, PREG_SET_ORDER, 0);
        foreach ($matches8 as $match) {
            $upotreba[] = $match[4];
        }
        $upotrebi = implode(",", $upotreba);
        // print_r($upotrebi . "\n");

        // Примери\
        $primer = array();
        $re9 = '/<div class="example" >[\n\r\s]+(.*?)[\n\r\s]+<\/div>/m';
        preg_match_all($re9, $result, $matches9, PREG_SET_ORDER, 0);
        foreach ($matches9 as $match) {
            $primer[] = $match[1];
        }
        $primeri = implode(",", $primer);
        // print_r($primeri . "\n");

        // Слично со / Спротивно од
        $sl = array();
        $re10 = '/<a href="\/show\/(.*?)\/(.*?)" >(.*?)<\/a>/m';
        preg_match_all($re10, $result, $matches10, PREG_SET_ORDER, 0);
        foreach ($matches10 as $match) {
            $sl[] = $match[3];
        }
        $sli = implode(",", $sl);
        // print_r($sli . "\n");

        // Изведенки
        $izved = array();
        $re14 = '/<span class="definer" >[\n\r\s]+(.*?)[\n\r\s]+(.*?)[\n\r\s]+(.*)<\/b>/m';
        preg_match_all($re14, $result, $matches14, PREG_SET_ORDER, 0);
        foreach ($matches14 as $match) {
            $iz = substr($match[2], 0, -7);
            $izved[] = $iz . " " . $match[3];
        }
        $izvedenki = implode(",", $izved);
        // print_r($izvedenki . "\n");
        // print_r($matches14);


        $sql  = "REPLACE INTO `scrape_word_data` (`title`, `letter`, `url`, `flexion`, `grammar`, `rank`, `definition`, `translationEn`, `translationTrAl`, `usages`, `examples`, `similar`, `derivatives`, `content`) ";
        $sql .= "VALUES ('$title', '$letter', '$word_url', '$flexion', '$grammar', '$rank', '$definition', '$ens', '$trs', '$upotrebi', '$primeri', '$sli', '$izvedenki', '$content') ";
        $res  = _mysqli_modify($sql);

        if ( $res ) {
            echo "Processed: $title\n";
            $sql = "UPDATE `scrape_words` SET `processed` = 'Y' WHERE `url` = '$word_url' ";
            $res = _mysqli_modify($sql);
        } else {
            echo "Failed to process: $title\n";
        }
    }

}

function get_word_definitions2($word, $words_to_process) {

    if ( $word ) {
        $sql = "SELECT `letter`, `url` FROM `scrape_words` WHERE `url` = '$word' ";
    } else {
        $sql = "SELECT `letter`, `url` FROM `scrape_words` WHERE `processed` != 'Y' LIMIT " . $words_to_process;
    }
    $res = _mysqli_query($sql);
    foreach($res as $item) {
        $letter = $item['letter'];
        $word_url = $item['url'];

        echo "Processing: $word_url\n";

        $result = do_curl($word_url);

        $content = addslashes($result);

        //title
        $re1 = '/<h2 class="lexem" name="(.*?)">/m';
        preg_match_all($re1, $result, $matches1, PREG_SET_ORDER, 0);
        foreach($matches1 as $match){
            $title = $match[1];
        }
        // print_r($title . "\n");

        // flexion
        $re11 = '/<div class="flexion" >([^;]*)<\/div>/m';
        preg_match_all($re11, $result, $matches11, PREG_SET_ORDER, 0);
        foreach ($matches11 as $match) {
            $flexion = trim($match[1]);
        }
        $flexion = ltrim($flexion, "<i>");
        $flexion = rtrim($flexion, "</i>");
        $flexion = trim($flexion);
        // print_r($flexion . "\n");

        // grammar
        $re12 = '/Вид збор: <i>(.*?)<\/i>/m';
        preg_match_all($re12, $result, $matches12, PREG_SET_ORDER, 0);
        foreach ($matches12 as $match) {
            $grammar = trim($match[1]);
        }
        // print_r($grammar . "\n");

        // rank
        $re4 = '/Ранг: (.*?)\n/m';
        preg_match_all($re4, $result, $matches4, PREG_SET_ORDER, 0);
        foreach ($matches4 as $match) {
            $rank = $match[1];
        }
        // print_r($rank . "\n");

        $doc = new DOMDocument();
        $doc->loadHTML($result);
        $finder = new DomXPath($doc);
        $classname="definition";
        $node = $finder->query("//*[contains(@class, '$classname')]");
        for($i=0; $i<count($node); $i++) {
            $nodeHTML = $doc->saveHTML($node->item($i));

            // meaning
            $mean_dom = new DOMDocument();
            $mean_dom->loadHTML($nodeHTML);
            $mean_find = new DomXPath($mean_dom);
            $classname="meaning";
            $res = $mean_find->query("//*[contains(@class, '$classname')]");
            $meaning = $mean_dom->saveHTML($res->item(0));
            $xml = simplexml_load_string($meaning);
            $meaning = trim($xml);
            echo "Meaning: $meaning\n";

            // translations
            $trans_dom = new DOMDocument();
            $trans_dom->loadHTML($nodeHTML);
            $trans_find = new DomXPath($trans_dom);
            $classname="semem-categories";
            $res = $trans_find->query("//*[contains(@class, '$classname')]");
            $translations = $trans_dom->saveHTML($res->item(0));
            $xml = simplexml_load_string($translations);
            //$meaning = trim($xml);
            //echo "Meaning: $meaning\n";
            print_r($xml);
        }
        exit;


        // translate en
        $en = array();
        $re6 = '/<a href="\/ontology\/(.*?)\/(.*)/im';
        preg_match_all($re6, $result, $matches6, PREG_SET_ORDER, 0);
        foreach ($matches6 as $match) {
            $en[] = $match[1];
        }
        $ens = implode(",", $en);
        // print_r("en: " . $ens . "\n");

        // translate tr/al
        $tr = array();
        $re7 = '/<a >(.*?)<\/a>/m';
        preg_match_all($re7, $result, $matches7, PREG_SET_ORDER, 0);
        foreach ($matches7 as $match) {
            $tr[] = $match[1];
        }
        $trs = implode(",", $tr);
        // print_r("tr: " . $trs . "\n");

        // Употреба
        $upotreba = array();
        $re8 = '/<span class="tag" >[\n\r\s]+<a href="\/(.*?)\/(.*?)\/(.*?)" >(.*?)<\/a>/m';
        preg_match_all($re8, $result, $matches8, PREG_SET_ORDER, 0);
        foreach ($matches8 as $match) {
            $upotreba[] = $match[4];
        }
        $upotrebi = implode(",", $upotreba);
        // print_r($upotrebi . "\n");

        // Примери\
        $primer = array();
        $re9 = '/<div class="example" >[\n\r\s]+(.*?)[\n\r\s]+<\/div>/m';
        preg_match_all($re9, $result, $matches9, PREG_SET_ORDER, 0);
        foreach ($matches9 as $match) {
            $primer[] = $match[1];
        }
        $primeri = implode(",", $primer);
        // print_r($primeri . "\n");

        // Слично со / Спротивно од
        $sl = array();
        $re10 = '/<a href="\/show\/(.*?)\/(.*?)" >(.*?)<\/a>/m';
        preg_match_all($re10, $result, $matches10, PREG_SET_ORDER, 0);
        foreach ($matches10 as $match) {
            $sl[] = $match[3];
        }
        $sli = implode(",", $sl);
        // print_r($sli . "\n");

        // Изведенки
        $izved = array();
        $re14 = '/<span class="definer" >[\n\r\s]+(.*?)[\n\r\s]+(.*?)[\n\r\s]+(.*)<\/b>/m';
        preg_match_all($re14, $result, $matches14, PREG_SET_ORDER, 0);
        foreach ($matches14 as $match) {
            $iz = substr($match[2], 0, -7);
            $izved[] = $iz . " " . $match[3];
        }
        $izvedenki = implode(",", $izved);
        // print_r($izvedenki . "\n");
        // print_r($matches14);


        $sql  = "REPLACE INTO `scrape_word_data` (`title`, `letter`, `url`, `flexion`, `grammar`, `rank`, `definition`, `translationEn`, `translationTrAl`, `usages`, `examples`, `similar`, `derivatives`, `content`) ";
        $sql .= "VALUES ('$title', '$letter', '$word_url', '$flexion', '$grammar', '$rank', '$definition', '$ens', '$trs', '$upotrebi', '$primeri', '$sli', '$izvedenki', '$content') ";
        $res  = _mysqli_modify($sql);

        if ( $res ) {
            echo "Processed: $title\n";
            $sql = "UPDATE `scrape_words` SET `processed` = 'Y' WHERE `url` = '$word_url' ";
            $res = _mysqli_modify($sql);
        } else {
            echo "Failed to process: $title\n";
        }
    }

}

function reparseWords($words_to_process) {

    //$sql = "SELECT `title`, `content` FROM `scrape_word_data` LIMIT " . $words_to_process;
    $sql = "SELECT `title`, `content` FROM `scrape_word_data` WHERE `title` = 'абиогенеза/ж' ";
    $res = _mysqli_query($sql);
    foreach($res as $item) {
        $content = $item['content'];

        $doc = new DOMDocument();
        $doc->loadHTML($content);
        $cnt = 0;
        foreach ($doc->childNodes as $item) {
            $cnt++;
            echo "================== $cnt =================\n";
            print_r($item);
        }
        //echo $doc->saveHTML();
    }
}
