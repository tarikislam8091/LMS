<?php

namespace App;
use Image;
use Illuminate\Database\Eloquent\Model;
use PHPMailer\PHPMailer;

class Admin extends Model
{
    

    /**
     * Upload user image.
     *
     * @param string $img_location, $slug, $img_ext
     * @return user profile image.
     */
    public static function UserImageUpload($img_location, $email, $img_ext)
    {
        $filename  = $email.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;
        if (!file_exists('images/user/admin/small/')) {
            mkdir('images/user/admin/small/', 0777, true);
        }
        $path2 = 'images/user/admin/small/' . $filename;
        Image::make($img_location)->resize(30, 30)->save($path2);
        return $path2;
    }


    /**
     * Upload common image.
     *
     * @param string $img_location, $slug, $img_ext
     * @return user profile image.
     */
    public static function CommonImageUpload($img_location, $img_ext_wide, $image_type, $name)
    {
        $filename  = $name.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext_wide;
        if (!file_exists('images/common/'.$image_type.'/small/')) {
            mkdir('images/common/'.$image_type.'/small/', 0777, true);
        }
        $path2 = 'images/common/'.$image_type.'/small/' . $filename;
        Image::make($img_location)->save($path2);
        return $path2;
    }



    /**
     * Upload color image.
     *
     * @param string $img_location, $slug, $img_ext
     * @return user profile image.
     */
    public static function ColorImageUpload($img_location, $img_ext_wide, $image_type, $name)
    {
        $filename  = $name.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext_wide;
        if (!file_exists('images/color/'.$image_type.'/small/')) {
            mkdir('images/color/'.$image_type.'/small/', 0777, true);
        }
        $path2 = 'images/color/'.$image_type.'/small/' . $filename;
        Image::make($img_location)->resize(150, 150)->save($path2);

        return $path2;
    }


    /**
     * Upload product image.
     *
     * @param string $img_location, $slug, $img_ext
     * @return user profile image.
     */
    public static function ProductImageUpload($img_location, $img_ext_wide, $image_type, $name)
    {
        $filename  = $name.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext_wide;
        if (!file_exists('images/product/'.$image_type.'/small/')) {
            mkdir('images/product/'.$image_type.'/small/', 0777, true);
        }
        $path2 = 'images/product/'.$image_type.'/small/' . $filename;
        Image::make($img_location)->resize(250, 250)->save($path2);

        return $path2;
    }


    /**
     * Upload stock image.
     *
     * @param string $img_location, $slug, $img_ext
     * @return user profile image.
     */
    public static function StockImageUpload($img_location, $img_ext_wide, $image_type, $name)
    {
        $filename  = $name.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext_wide;
        if (!file_exists('images/stock/'.$image_type.'/small/')) {
            mkdir('images/stock/'.$image_type.'/small/', 0777, true);
        }
        $path2 = 'images/stock/'.$image_type.'/small/' . $filename;
        Image::make($img_location)->resize(250, 250)->save($path2);

        return $path2;
    }
 


    public static function multiArraySerach($search,$search_key,$array){

        foreach ($array as $key => $value) {
            if ($value[$search_key] == $search) {
                return $search;
            }
        }
        return null;
    }



    /********************************************
    ## AppProfileImageUpload
    *********************************************/

    public static function AppProfileImageUpload($FILE,$name_slug){

       try{
            $file = $FILE["user_profile_image"]['tmp_name'];

            $ext = explode('.',$FILE['user_profile_image']['name']);
            $file_ext   = array('jpg','png','gif','bmp','JPG','jpeg');
            $post_ext   = end($ext);
            $photo_name = explode(' ', trim(strtolower($FILE['user_profile_image']['name'])));
            $photo_name = implode('_', $photo_name);
            $photo_type = $FILE['user_profile_image']['type'];
            $photo_size = $FILE['user_profile_image']['size'];
            $photo_tmp  = $FILE['user_profile_image']['tmp_name'];
            $photo_error= $FILE['user_profile_image']['error'];
        
            if( in_array($post_ext,$file_ext) && ($photo_error == 0 )){

                $filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$post_ext;

            /*directory create*/
            if (!file_exists('images/user/userprofile/'))
               mkdir('images/user/userprofile/', 0777, true);

            $path = public_path('images/user/userprofile/' . $filename);
            \Image::make($file)->resize(150, 150)->save($path);


            $user_profile_image=$filename;
            return $user_profile_image;

            }

        }catch(\Exception $e){

             $response["errors"]= [
                "statusCode"=> 501,
                "errorMessage"=> $e->getMessage(),
                "serverReferenceCode"=> date('Y-m-d H:i:s'),
            "file_system" =>$FILE,
            ];


            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
           
            return \Response::json($response);
        }

    }


}
