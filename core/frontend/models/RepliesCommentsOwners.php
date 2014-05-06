<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "comments_owners".
 *
 * The followings are the available columns in table 'comments_owners':
 * @property string $name
 * @property string $email
 * @property string $comment_id
 *
 * The followings are the available model relations:
 * @property Comments $comment
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class RepliesCommentsOwners extends CommentsOwners {
/**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'comment' => array(self::BELONGS_TO, 'RepliesComments', 'comment_id'),
        );
    }
}