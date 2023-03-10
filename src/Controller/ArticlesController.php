<?php
// src/Controller/ArticlesController.php

namespace App\Controller;

use Cake\Controller\Component\PaginatorComponent;
use Cake\View\Helper\PaginatorHelper;

class ArticlesController extends AppController
{

    // ページネーション用
    public $paginate = [
        'limit' => 5,
    ];

    // 記事の追加の項で特に説明なく追加されていた
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Paginator');
        $this->loadComponent('Flash'); // FlashComponentをインクルード
    }

    public function index()
    {
        $this->loadComponent('Paginator');
        $this->set('articles', $this->paginate($this->Articles)); // ページネーション用
        // $articles = $this->Paginator->paginate($this->Articles->find());
        // $this->set(compact('articles'));
    }

    public function view($slug = null)
    {
        $article = $this->Articles
            ->findBySlug($slug)
            ->contain('Tags')
            ->firstOrFail();
        $this->set(compact('article'));
    }

    public function add()
    {
        $article = $this->Articles->newEmptyEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            // user_idを一時的に決め打ち、あとで認証を構築する際に削除
            $article->user_id = 1;

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        // タグのリストを取得
        $tags = $this->Articles->Tags->find('list')->all();

        // ビューコンテキストにtagsをセット
        $this->set('tags', $tags);

        $this->set('article', $article);
    }

    public function edit($slug)
    {
        $article = $this->Articles
            ->findBySlug($slug)
            ->contain('Tags')
            ->firstOrFail();

        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article.'));
        }

        // タグのリストを取得
        $tags = $this->Articles->Tags->find('list')->all();

        // ビューコンテキストにtagsをセット
        $this->set('tags', $tags);

        $this->set('article', $article);
    }

    public function delete($slug)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function tags()
    {
        // 'pass'キーはCakePHPによって提供され、リクエストに渡された全てのURLパスセグメントを含む
        $tags = $this->request->getParam('pass');

        // ArticlesTableを使用してタグ付きの記事を検索する
        $articles = $this->Articles->find('tagged', ['tags' => $tags])->all();

        // 変数をビューテンプレートのコンテキストに渡す
        $this->set(['articles' => $articles, 'tags' => $tags]);
    }


}