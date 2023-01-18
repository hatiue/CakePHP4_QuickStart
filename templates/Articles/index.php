<!-- File: templates/Articles/index.php（編集リンク・削除リンク付き） -->

<h1>記事一覧</h1>
<p><?= $this->Html->link('記事の追加', ['action' => 'add']) ?></p>
<table>
    <tr>
        <th><?= $this->Paginator->sort('title', 'タイトル(クリックで並び替え)') ?></th>
        <th><?= $this->Paginator->sort('created', '作成日時(クリックで並び替え)') ?></th>
        <th>操作</th>
    </tr>

<!-- ここで$articlesクエリオブジェクトを繰り返して、記事の情報を出力する -->

<?php foreach ($articles as $article): ?>
    <tr>
        <td>
            <?= $this->Html->link($article->title, ['action' => 'view', $article->slug]) ?>
        </td>
        <td>
            <?= $article->created->format(DATE_RFC850) ?>
        </td>
        <td>
            <?= $this->Html->link('編集', ['action' => 'edit', $article->slug]) ?>
            <?= $this->Form->postLink('削除',
                                      ['action' => 'delete', $article->slug],
                                      ['confirm' => 'よろしいですか？'])
            ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>
<div class="paginator">
    <ul class="paginate">
        <?= $this->Paginator->first('Top <<') ?>
        <?= $this->Paginator->prev('<') ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next('>') ?>
        <?= $this->Paginator->last('>> Last') ?>
    </ul>
</div>
