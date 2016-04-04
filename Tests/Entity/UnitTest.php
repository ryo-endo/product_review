<?php

namespace Plugin\ProductReview\Tests\Entity;

use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;
use Plugin\ProductReview\Entity\ProductReview;

class UnitTest extends AbstractAdminWebTestCase
{
    /**
     * レビュー検索画面のルーティング
     */
    public function test_routing_review_search()
    {
        $this->client->request(
            'GET',
            $this->app->url('admin_product_review')
        );
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     * レビュー編集画面のルーティング
     */
    public function test_routing_review_edit()
    {
        $test_review_id = $this->get_test_review_id();
        $crawler = $this->client->request('GET',
            $this->app->url('admin_product_review_edit', array('id' => $test_review_id))
        );
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }


    /**
     * レビュー編集画面のデータ編集
     */
    public function test_review_edit()
    {
        $test_review_id = $this->get_test_review_id();
        $formData = $this->createReviewFormData(1);
        $crawler = $this->client->request(
            'POST',
            $this->app->url('admin_product_review_edit', array('id' => $test_review_id)),
            array('product_review' => $formData)
        );
        $Review = $this->app['eccube.plugin.product_review.repository.product_review']->find($test_review_id);
        $this->expected = $this->createReviewFormData(1)['reviewer_name'];
        $this->actual = $Review->getReviewerName();
        $this->verify();
    }

    /**
     * レビュー公開チェック
     */
    public function test_review_public()
    {

    }

    /**
     * レビュー非公開チェック
     */
    public function test_review_not_public()
    {

    }

    /**
     * 削除チェック
     */
    public function test_review_delete()
    {

    }

    /**
     * 商品レビュー入力画面
     */
    public function test_review_display()
    {

    }

    /**
     * 商品レビュー確認画面
     */
    public function test_review_confirm()
    {

    }

    public function get_test_review_id()
    {
        $TestReview = $this->createReview(0, 'lamlam123', 1);
        $test_review_id = $this->app['eccube.plugin.product_review.repository.product_review']
            ->findOneBy(array(
                'reviewer_name' => $TestReview->getReviewerName('lamlam123')
            ))
            ->getId();
        return $test_review_id;
    }
    /**
     * Create new review data
     */
    public function createReviewFormData($product_id)
    {
        $faker = $this->getFaker();
        $form = array(
            'comment' => $faker->word,
            'reviewer_name' => $faker->word,
            'title' => $faker->word,
            'recommend_level' => 5,
            'product_id' => $product_id,
            '_token' => 'dummy',
        );
        return $form;
    }

    /**
     * レビュー作成
     */
    public function createReview($delFlg, $reviewer, $disp)
    {
        $faker = $this->getFaker();
        $Review = new ProductReview();
        $Product = $this->app['eccube.repository.product']->find(1);
        $Disp = $this->app['eccube.repository.master.disp']->find($disp);
        $Review
            ->setComment($faker->word)
            ->setDelFlg($delFlg)
            ->setReviewerName($reviewer)
            ->setRecommendLevel(5)
            ->setTitle($faker->word)
            ->setProduct($Product)
            ->setStatus($Disp);
        $this->app['orm.em']->persist($Review);
        $this->app['orm.em']->flush($Review);
        return $Review;
    }

}
