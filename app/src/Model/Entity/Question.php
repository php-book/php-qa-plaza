<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Question Entity
 */
class Question extends Entity
{
    /**
     * @var array 各プロパティが一括代入できるかどうかの情報
     */
    protected $_accessible = [
        'user_id' => true,
        'body' => true,
        'created' => true,
        'modified' => true
    ];
}
