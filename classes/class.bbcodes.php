<?php
/**
	* Advanced Miracle Content Management System - CMS Configurations
	*
	* Copyright (c) 2016 Naufal Hardiansyah (www.augoeides.world)
	* The program is distributed under the terms of the GNU General Public License 
	*
	* This file is part of Advanced Miracle Content Management System (AdvMiracleCMS).
	* 
	* AdvMiracleCMS is free software: you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* Naufal Hardiansyah, either version 3 of the License, or any later version.
	* 
	* AdvMiracleCMS is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	* GNU General Public License for more details.
	* 
	* You should have received a copy of the GNU General Public License
	* along with AdvMiracleCMS. If not, see <http://www.gnu.org/licenses/>.
**/

class BBCodes {   
    function Format($str) {
        $str = htmlentities($str);
        $simple_search = array( 
            /** ADDED LINE BREAK **/
            '/\[youtube\](.*?)\[\/youtube\]/is', 
            '/\[title\](.*?)\[\/title\]/is', 
            '/\[subtitle\](.*?)\[\/subtitle\]/is', 
            '/\[list\](.*?)\[\/list\]/is', 
            '/\[\*\](.*?)\[\/\*\]/is', 
            '/\[p\=(.*?)\](.*?)\[\/p\]/is', 
            '/\[br\]/is', 
            '/\[b\](.*?)\[\/b\]/is', 
            '/\[i\](.*?)\[\/i\]/is', 
            '/\[u\](.*?)\[\/u\]/is', 
            '/\[url\=(.*?)\](.*?)\[\/url\]/is', 
            '/\[url\](.*?)\[\/url\]/is', 
            '/\[align\=(left|center|right)\](.*?)\[\/align\]/is', 
            '/\[img\](.*?)\[\/img\]/is', 
            '/\[mail\=(.*?)\](.*?)\[\/mail\]/is', 
            '/\[mail\](.*?)\[\/mail\]/is', 
            '/\[font\=(.*?)\](.*?)\[\/font\]/is', 
            '/\[size\=(.*?)\](.*?)\[\/size\]/is', 
            '/\[color\=(.*?)\](.*?)\[\/color\]/is', 
            /** ADDED TEXTAREA FOR CODE PRESENTATION **/ 
            '/\[codearea\](.*?)\[\/codearea\]/is', 
            /** ADDED PRE CLASS FOR CODE PRESENTATION **/
            '/\[code\](.*?)\[\/code\]/is', 
            /** ADDED PARAGRAPH **/ 
            '/\[p\](.*?)\[\/p\]/is', 
            '/\[center\](.*?)\[\/center\]/is', 
        ); 
 
        $simple_replace = array( 
            /** ADDED LINE BREAK **/
            '<iframe width="420" height="315" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>', 
            '<h1 class="PageHeader">$1</h1>', 
            '<h3>$1</h3>', 
            '<ul>$1</ul>', 
            '<li>$1</li>', 
            '<h3>$1</h3><p>$2</p>', 
            '<br />', 
            '<strong>$1</strong>', 
            '<em>$1</em>', 
            '<u>$1</u>', 
            /** ADDED "NOFOLLOW" TO PREVENT SPAM **/ 
            '<a href="$1" rel="nofollow" title="$2 - $1">$2</a>', 
            '<a href="$1" rel="nofollow" title="$1">$1</a>', 
            '<div style="text-align: $1;">$2</div>', 
            /** ADDED ALT ATTRIBUTE FOR VALIDATION **/ 
            '<img src="$1" alt="" />', 
            '<a href="mailto:$1">$2</a>', 
            '<a href="mailto:$1">$1</a>', 
            '<span style="font-family: $1;">$2</span>', 
            '<span style="font-size: $1;">$2</span>', 
            '<span style="color: $1;">$2</span>', 
            /** ADDED TEXTAREA FOR CODE PRESENTATION **/
            '<textarea class="code_container" rows="30" cols="70">$1</textarea>', 
            /** ADDED PRE CLASS   FOR CODE PRESENTATION **/
            '<pre class="code">$1</pre>', 
            /** ADDED PARAGRAPH **/ 
            '<p>$1</p>', 
            '<center>$1</center>', 
        ); 
 
        /** DO SIMPLE BBCode's **/
        $str = preg_replace($simple_search, $simple_replace, $str); 
 
        /** DO <blockquote> BBCode **/ 
        $str = $this->Quote($str); 
 
        return $str; 
    } 
   
    function Quote($str) { 
        /** ADDED "DIV" AND "CLASS" FOR QUOTES **/ 
        $open = '<blockquote><div class="quote">'; 
        $close = '</div></blockquote>'; 
 
        /** HOW OFTEN IS THE OPEN TAG? **/ 
        preg_match_all('/\[quote\]/i', $str, $matches); 
        $opentags = count($matches['0']); 
 
        /** HOW OFTEN IS THE CLOSE TAG? **/ 
        preg_match_all('/\[\/quote\]/i', $str, $matches); 
        $closetags = count($matches['0']); 
 
        /** CHECK HOW MANY TAGS HAVE BEEN UNCLOSED 
          * AND ADD THE UNCLOSING TAG AT THE ENDOF MESSAGE **/
        $unclosed = $opentags - $closetags; 
        for ($i = 0; $i < $unclosed; $i++) { 
            $str .= '</div></blockquote>'; 
        } 
 
        /** DO REPLACEMENT **/ 
        $str = str_replace ('[' . 'quote]', $open, $str); 
        $str = str_replace ('[/' . 'quote]', $close, $str); 
 
        return $str; 
    }
}
?>