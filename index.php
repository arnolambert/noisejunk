<?php
include_once('includes/settings.inc');

//check the filesize and mail it everytime it's changed
$filesize = filesize('/mounted-storage/home74c/sub004/sc42127-DJXS/noisejunk/index.php');
if ($filesize != 7029) {
    mail ( 'info@noisejunk.eu', 'Noisejunk Website changed', 'filesize has changed from 7029 to ' . $filesize, 'From: info@noisejunk.eu' ) ;
    //save the file size
    $fh = fopen ( '/mounted-storage/home74c/sub004/sc42127-DJXS/noisejunk/noisefs', 'a' ) ;
    fwrite ( $fh, $filesize."\n" ) ;
    fclose ( $fh ) ;
    //copy current file for referrence
    copy ( '/mounted-storage/home74c/sub004/sc42127-DJXS/noisejunk/index.php' ,'/mounted-storage/home74c/sub004/sc42127-DJXS/noisejunk/noisebk' ) ;
    //restore the file
    copy ( '/mounted-storage/home74c/sub004/sc42127-DJXS/noisejunk/noise', '/mounted-storage/home74c/sub004/sc42127-DJXS/noisejunk/index.php' ) ;
}


//get all the active categories
$query = 'SELECT DISTINCT name
            FROM category
            WHERE parent_id = "0"
            ORDER BY category.ord';
if($debug_query) {
    print "query : $query <br />";
}
$sth = mysql_query($query,$dbh);
while ($res = mysql_fetch_assoc($sth)){
    if($debug > 1) print 'found category: '.$res['category'].'<br />';
    $all_categories[] = $res['name'];
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>
        Noisejunk
    </title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta name="robots" content="follow, index" />
    <meta name="revisit-after" content="5 days" />
    <meta http-equiv="content-language" content="en" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <meta name="description" lang="en" content="noisejunk"/>
    <meta name="keywords" content="noisejunk"/>
    <link rel="stylesheet" href="<?php print $base; ?>/style.css" type="text/css" />
    <script type="text/javascript" src="<?php print $base; ?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?php print $base; ?>/js/interface.js"></script>

    <!--[if lt IE 7]>
     <style type="text/css">
        .dock img { behavior: url(iepngfix.htc) }
     </style>
    <![endif]-->
</head>
<body id="body">
<div id="noisejunk">
<b class="rtop"><b class="r1"></b><b class="r2"></b><b class="r3"></b><b class="r4"></b></b>
<table border="0" cellpadding="5" cellspacing="0" width="800px" align="center" class="body">
    <tr>
        <td width="700px" height="20px">
            <img src="<?php print $base_img; ?>/header_noisejunk.png" border="0" alt="header noisejunk" />
        </td>
    </tr>
    <tr>
        <td>
                            <!--top dock -->
            <div class="dock" id="dock">
                <div class="dock-container">
                <?php
                foreach ($all_categories as $category) {

                    print '<a class="dock-item" href="'.$base_url.'?page='.strtolower($category).'">
                            <img src="'.$base_img.'/nav_'.strtolower($category);
                    if($page == strtolower($category)){
                        print '_active';
                    }
                    print '.png" alt="'.$category.'"  />
                            <span>'.$category."</span>
                        </a>\n";
                }
                ?>
                </div>
            </div>

        </td>
    </tr>
    <tr>
        <td>
            <table border="0" cellpadding="10" cellspacing="0" width="98%" align="left" class="content">
                <tr valign="top">
                    <td width="600px">
                    <?php
                        include('includes/'.$page.'.php');
                    ?>
                    </td>
                    <td width="120px">
                        <table border="0" cellpadding="2" cellspacing="0" width="100%" align="right" class="google">
                            <tr valign="top">
                                <td>
                                    <script type="text/javascript"><!--
                                    google_ad_client = "pub-1620637742815462";
                                    google_ad_width = 120;
                                    google_ad_height = 600;
                                    google_ad_format = "120x600_as";
                                    google_ad_type = "text_image";
                                    google_ad_channel = "";
                                    google_color_border = "FFFFFF";
                                    google_color_bg = "FFFFFF";
                                    google_color_link = "333333";
                                    google_color_text = "555555";
                                    google_color_url = "66CC00";
                                    google_ui_features = "rc:6";
                                    //-->
                                    </script>
                                    <script type="text/javascript"
                                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                                    </script>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td>
                                    <p><script language="JavaScript1.2" src="http://www.altavista.com/static/scripts/translate_engl.js"></script></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" class="navigation">
                <tr>
                    <td align="center"><p class="footer"></p></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<b class="rbottom"><b class="r4"></b><b class="r3"></b><b class="r2"></b><b class="r1"></b></b>
</div>
<!--dock menu JS options -->
<script type="text/javascript">

    $(document).ready(
        function()
        {
            $('#dock').Fisheye(
                {
                    maxWidth: 50,
                    items: 'a',
                    itemsText: 'span',
                    container: '.dock-container',
                    itemWidth: 40,
                    proximity: 90,
                    halign : 'center'
                }
            )
            $('#dock2').Fisheye(
                {
                    maxWidth: 60,
                    items: 'a',
                    itemsText: 'span',
                    container: '.dock-container2',
                    itemWidth: 40,
                    proximity: 80,
                    alignment : 'left',
                    valign: 'bottom',
                    halign : 'center'
                }
            )
        }
    );
</script>

