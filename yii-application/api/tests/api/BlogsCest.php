<?php

namespace api\tests\api;

use \api\tests\ApiTester;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;
use common\fixtures\BlogFixture;

class BlogsCest
{
    public function _before(ApiTester $I)
    {
        $I->haveFixtures([
            /*'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
            'token' => [
                'class' => TokenFixture::className(),
                'dataFile' => codecept_data_dir() . 'token.php'
            ],*/
            'blog' => [
                'class' => BlogFixture::className(),
                'dataFile' => codecept_data_dir() . 'blog.php'
            ],
        ]);
    }

    public function index(ApiTester $I)
    {
        $I->sendGET('/blogs');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            ['title' => 'first title'],
            ['title' => 'second title'],
            ['title' => 'third title'],
        ]);
        $I->seeHttpHeader('X-Pagination-Total-Count', 3);
    }

    
}
