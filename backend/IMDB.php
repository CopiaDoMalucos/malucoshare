<?php
############################################################
#######                                             ########
#######                                             ########
#######           www.brshares.com 2.0              ########
#######                                             ########
#######                                             ########
############################################################
/*
* @package TorrentTrader IMDB API
* @version v0.1
*/

class IMDB
{
         public function getImage($image, $id)
         {
                 $path = sprintf('uploads/imdb/%d.jpg', $id);
                                                                                                
                 if (($_data = $this->_getImage($image)) && (file_put_contents($path, $_data)))
                 {
                         return $path;
                 }
                                                                
                 return 'images/default_avatar.png';
         }
        
         public function getRated($var)
         {
                 $path = 'images/imdb/rated/' . basename($var);
                
                 if ((is_readable($path)) || ($_data = $this->_getImage($var)) && (file_put_contents($path, $_data)))
                 {
                         return '<img src="'.$path.'" alt="" title="" />';
                 }
                
                 return $var;
         }
        
         public function getRating($var)
         {
                 if ( is_numeric($var) )
                 {
                         return '<img src="images/imdb/' . round($var * 10 / 5) * 5 . '.gif" alt="' . $var . '" title="' . $var . '" height="15" />';
                 }
                                
                 return null;
         }
        
         public function getReleased($var)
         {
                 if ( is_numeric($var) )
                 {
                         return date('D dS F Y', $var);
                 }
                
                 return $var;
         }
        
         public function getUpdated($var)
         {
                 return date('D dS F Y H:i a', $var);
         }
        
         private function _getImage($path)
         {
                 $ch = curl_init();
                
                 if ( is_resource($ch) )
                 {
                         curl_setopt($ch, CURLOPT_URL, $path);
                         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                         curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                         curl_setopt($ch, CURLOPT_HEADER, false);
                         curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                         $_data = curl_exec($ch);
                         curl_close($ch);
                 }
                
                 return ( ! empty( $_data ) ) ? $_data : false;
         }
}