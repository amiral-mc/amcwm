<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "comments".
 *
 * The followings are the available columns in table 'comments':
 * @property string $comment_id
 * @property string $comment_review
 * @property string $comment_header
 * @property string $comment
 * @property integer $published
 * @property string $comment_date
 * @property string $ip
 * @property integer $hide
 * @property string $user_id
 * @property string $bad_imp
 * @property string $good_imp
 * @property integer $force_display
 *
 * The followings are the available model relations:
 * @property ArticlesComment[] $articlesComments
 * @property Comments $commentReview
 * @property Comments[] $comments
 * @property Users $user
 * @property CommentsOwners $commentsOwners
 * @property ImagesComments[] $imagesComments
 * @property VideosComments[] $videosComments
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class RepliesComments extends Comments {

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'articlesComments' => array(self::HAS_ONE, 'ArticlesComments', 'article_comment_id'),
            'commentReview' => array(self::BELONGS_TO, 'Comments', 'comment_review'),
            'comments' => array(self::HAS_MANY, 'Comments', 'comment_review'),
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'commentsOwners' => array(self::HAS_ONE, 'RepliesCommentsOwners', 'comment_id'),
            'imagesComments' => array(self::HAS_ONE, 'ImagesComments', 'image_comment_id'),
            'videosComments' => array(self::HAS_ONE, 'VideosComments', 'video_comment_id'),
        );
    }

}