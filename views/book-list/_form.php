<?php

use app\entities\Book;
use kartik\select2\Select2;
use kartik\sortable\Sortable;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\forms\BookListForm $model
 */

$listItems = [];

foreach ($model->bookIds as $bookId) {
    $book = Book::findOne($bookId);

    $listItems[] = [
        'content' => $this->render('_list-item', [
            'model' => $book,
        ]),
    ];
}

$form = ActiveForm::begin([
    'method' => 'post',
]);

?>

<div class="row">
    <div class="col-12">
        <p class="fs-2">
            <?= $this->title ?>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?= $form->field($model, 'name')->textInput([
            'maxLength' => true,
            'class' => 'form-control form-control-lg',
        ]) ?>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <?= $form->field($model, 'searchInput')->widget(Select2::class, [
            'options' => [
                'placeholder' => 'Pesquisar...',
                'multiple' => true,
            ],
            'pluginOptions' => [
                'minimumInputLength' => 1,
                'ajax' => [
                    'url' => Url::to(['search-books']),
                    'data' => new JsExpression("({ term }) => ({ search: term })"),
                    'dataType' => 'json',
                    'cache' => true,
                    'processResults' => new JsExpression('results => ({ results })'),
                ],
                'escapeMarkup' => new JsExpression('markup => markup'),
                'templateResult' => new JsExpression('({ text }) => text'),
                'templateSelection' => new JsExpression('({ text }) => text'),
            ],
        ]) ?>
        <?= $form->field($model, 'bookIds')->hiddenInput(['value' => ''])->label(false) ?>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <?= Sortable::widget([
            'items' => $listItems,
        ]) ?>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <?= Html::a('Cancelar', Url::toRoute('index'), ['class' => 'btn btn-danger']) ?>
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php

ActiveForm::end();

$searchInputId = Html::getInputId($model, 'searchInput');
$bookIdsInputId = Html::getInputId($model, 'bookIds');

$this->registerJs(<<<JS

const form = jQuery('form');
const searchInput = jQuery('#{$searchInputId}');
const sortableMenu = jQuery('#w1');

/**
 * Returns an array with the IDs of the selected books without duplicates.
 * 
 * @returns {string[]}
 */
const getBookIds = () => {
    const ids = jQuery('[book-id]').toArray()
        .map((item) => item.getAttribute('book-id'));

    return [...new Set(ids)];
};

/**
 * Returns the list item of a book by the book ID.
 * 
 * @param {number} bookId Book ID.
 * 
 * @returns {jQuery}
 */
const getItemByBookId = (bookId) => jQuery(`[book-id="\${bookId}"]`).parent();

/**
 * Removes a list item of a book by the book ID.
 * 
 * @param {number} bookId Book ID.
 */
const removeItemByBookId = (bookId) => getItemByBookId(bookId).remove();

/**
 * Checks if the given template is already rendered and delete it if so.
 * 
 * @param {string} template The HTML template.
 */
const removeDuplicatedTemplate = (template) => {
    const element = jQuery(template);
    const bookId = element.attr('book-id');

    if (getBookIds().includes(bookId)) {
        removeItemByBookId(bookId);
    }
};

/**
 * Renders an item using the given template.
 * 
 * @param {string} template The HTML template.
 */
const renderItem = (template) => {
    removeDuplicatedTemplate(template);

    sortableMenu.prepend(`
        <li draggable="true" role="option" aria-grabbed="false">
            \${template}
        </li>
    `);
};

/**
 * Clears the search input.
 */
const clearInput = () => searchInput.val(null).trigger('change');

// events

searchInput.on('select2:select', (e) => {
    renderItem(e.params.data.template);
    clearInput();
});

form.on('submit', (e) => {
    jQuery('#{$bookIdsInputId}').val(getBookIds().join(','));
});

// window

window.removeItemByBookId = removeItemByBookId;

JS);
