<?php

namespace gallery\components;

use SocialLinks\Page;

class SocialLinks
{

    public static function shareLinks($url, $title, $text, $image, $twitterUser)
    {

        $page = new Page([
            'url' => $url,
            'title' => $title,
            'text' => $text,
            'image' => $image,
            'twitterUser' => $twitterUser
        ]);


        //Use the properties to get the providers info, for example:
//        $facebookProvider = $page->facebook;

        //Each provider has the following info:
//        $page->twitter->shareUrl; //The url to share this page  (returns null if is not available)
//        $page->twitter->shareCount; //The number of the current shares (returns null if is not available)

        //Example
        $link = '<a href="%s">%s (%s)</a>';

        printf($link, $page->vk->shareUrl, 'Share in Vk', $page->vk->shareCount);
        printf($link, $page->facebook->shareUrl, 'Share in Facebook', $page->facebook->shareCount);
        printf($link, $page->twitter->shareUrl, 'Share in Twitter', $page->twitter->shareCount);
        printf($link, $page->plus->shareUrl, 'Share in Google Plus', $page->plus->shareCount);
        printf($link, $page->pinterest->shareUrl, 'Share in Pinterest', $page->pinterest->shareCount);
        printf($link, $page->linkedin->shareUrl, 'Share in Linkedin', $page->linkedin->shareCount);
        printf($link, $page->stumbleupon->shareUrl, 'Share StumbleUpon', $page->stumbleupon->shareCount);


    }


}